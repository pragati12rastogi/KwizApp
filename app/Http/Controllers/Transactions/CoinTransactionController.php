<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppUsers;
use App\Models\Status;
use App\Models\CoinWalletTransaction;
use App\Models\CoinWallet;
use App\Models\CoinCurrency;
use App\Models\DailyBonus;
use App\Models\WatchAddBonus;
use Auth;
use Illuminate\Support\Facades\File;
use DB;
use Hash;
use PDF; 

use App\Custom\CustomHelpers;

class CoinTransactionController extends Controller
{
    public function coinTransactionList(){
        $app_user = AppUsers::where('status_id','<>',4)->get()->toArray();
        return view('transactions/coinTransactionList',['app_user'=>$app_user]);
    }

    public function coinTransactionListApi(Request $request){
        $search = $request->input('search');
        $search_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        $transfer_type = $request->input('transfer_type');

        $coin_trans_detail = CoinWalletTransaction::leftjoin('app_users','app_users.app_user_id','coin_wallet_transaction.app_user_id')
        ->select('coin_wallet_trans_id',
            DB::raw('Concat("Kwiz","-",coin_wallet_transaction.app_user_id,"-",coin_wallet_trans_id) as formed_trans_id'),
            'app_users.full_name',
            'app_users.phone',
            'app_users.email',
            DB::raw('(Case When (coin_wallet_type = 1) then "Debit"
                 When (coin_wallet_type = 2) then "Credit" 
                 End ) as trans_type'),
            DB::raw('(Case When (coin_wallet_trans_status = 1) then "Pending"
                    When (coin_wallet_trans_status = 2) then "Approved"
                    Else "" End ) as trans_status'),
            'coin_wallet_amount',
            'coin_wallet_remark',
            DB::raw('DATE_FORMAT(coin_wallet_trans_at,"%r %d-%m-%Y") as trans_date')
        );

        if(!empty($search_value))
        {
            $coin_trans_detail = $coin_trans_detail->where(function($query) use ($search_value){
                        $query->where('app_users.full_name','LIKE',"%".$search_value."%")
                        ->orwhere('app_users.phone','LIKE',"%".$search_value."%")
                        ->orwhere('app_users.email','LIKE',"%".$search_value."%")
                        ->orwhere('coin_wallet_amount','LIKE',"%".$search_value."%")
                        ->orwhere('coin_wallet_remark','LIKE',"%".$search_value."%")
                        ->orwhere(DB::raw('(Case When (coin_wallet_type = 1) then "Debit"
                             When (coin_wallet_type = 2) then "Credit" 
                             End )'),'LIKE',"%".$search_value."%")
                        ;
            });
        }

        if(!empty($transfer_type))
        {
            $coin_trans_detail->where(function($query) use ($transfer_type){
                $query->where('coin_wallet_transaction.coin_wallet_type',$transfer_type);
            });
        }
        
        $count = $coin_trans_detail->count();
        $coin_trans_detail = $coin_trans_detail->offset($offset)->limit($limit);

        if(isset($request->input('order')[0]['column'])){
            $data = ['coin_wallet_trans_id','formed_trans_id','full_name','trans_type','coin_wallet_amount','trans_status','coin_wallet_remark','trans_date','app_users.phone','app_users.email'
            ];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $coin_trans_detail->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else
        {
            $coin_trans_detail->orderBy('coin_wallet_trans_id','desc');
        }
        $coin_trans_detaildata = $coin_trans_detail->get()->toArray();
        
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $coin_trans_detaildata; 
        return json_encode($array);
    }

    public function coinTransactionView($trans_id){
        $coin_trans_view = CoinWalletTransaction::leftjoin('app_users','app_users.app_user_id','coin_wallet_transaction.app_user_id')
        ->select('coin_wallet_trans_id',
            DB::raw('Concat("Kwiz","-",coin_wallet_transaction.app_user_id,"-",coin_wallet_trans_id) as formed_trans_id'),
            'app_users.full_name',
            'app_users.phone',
            'app_users.email',
            DB::raw('(Case When (coin_wallet_type = 1) then "Debit"
                 When (coin_wallet_type = 2) then "Credit" 
                 End ) as trans_type'),
            DB::raw('(Case When (coin_wallet_trans_status = 1) then "Pending"
                    When (coin_wallet_trans_status = 2) then "Approved"
                    Else "" End ) as trans_status'),
            'coin_wallet_amount',
            'coin_wallet_remark',
            DB::raw('DATE_FORMAT(coin_wallet_trans_at,"%r %d-%m-%Y") as trans_date')
        )->where('coin_wallet_trans_id',$trans_id)->get()->first();
        return view('transactions/coin_trans_view',['coin_trans_view'=>$coin_trans_view]);
    }

    public function gen_pdf_coin_trans($trans_id){
        $coin_trans_view = CoinWalletTransaction::leftjoin('app_users','app_users.app_user_id','coin_wallet_transaction.app_user_id')
        ->select('coin_wallet_trans_id',
            DB::raw('Concat("Kwiz","-",coin_wallet_transaction.app_user_id,"-",coin_wallet_trans_id) as formed_trans_id'),
            'app_users.full_name',
            DB::raw('(Case When (coin_wallet_type = 1) then "Debit"
                 When (coin_wallet_type = 2) then "Credit" 
                 End ) as trans_type'),
            DB::raw('(Case When (coin_wallet_trans_status = 1) then "Pending"
                    When (coin_wallet_trans_status = 2) then "Approved"
                    Else "" End ) as trans_status'),
            'coin_wallet_amount',
            'coin_wallet_remark',
            DB::raw('DATE_FORMAT(coin_wallet_trans_at,"%r %d-%m-%Y") as trans_date')
        )->where('coin_wallet_trans_id',$trans_id)->get()->first();
        
        $timestamp = date('d-m-Y H:i:s');
        if($coin_trans_view){
            $data = [
                'foo' => 'bar',
                'coin_trans_view'=>$coin_trans_view,
                'created'=>$timestamp
                ];
               
            $pdfFilePath = "coin_transaction.pdf";
            $pdf = PDF::loadView('template.coin_trans_invoice', $data);
            return $pdf->stream($pdfFilePath);
            // return view('template.coin_trans_invoice',$data);
        }
        else{
            $message="No Transaction found!!";
            return redirect('/coin/transaction/view/'.$trans_id)->with('error',$message);
        }   
    }

    public function credit_coin(){
        $app_users = AppUsers::where('app_users.status_id',2)->where('app_users.is_verified',1)->pluck('full_name','app_user_id');
        return view('transactions/credit_coin',compact('app_users'));

    }
    public function credit_coin_db(Request $request){
        try {
            $this->validate($request,[
                'app_user'=>'required',
                'credit_amt'=>'required',
                'credit_remark'=>'required'
            ],[
                'app_user.required'=> 'This is required.',
                'credit_amt.required'=> 'This is required.',
                'credit_remark'=>'required'
            ]);

            $app_user = $request->input('app_user');
            $credit_amt = $request->input('credit_amt');
            $remark = $request->input('credit_remark');
            $coin_wallet = 0;

            DB::beginTransaction();
            


            $get_coinwallet = CoinWallet::where('app_user_id',$app_user)->get()->toArray();
            if(count($get_coinwallet)>0){
                $coin_wallet_upd = CoinWallet::where('app_user_id',$app_user)->increment('coin_wallet_balance',$credit_amt);

                if($coin_wallet_upd==0){
                    DB::rollback();
                    return back()->with('error','Some Error Occurred');
                }
                $coin_wallet =$get_coinwallet[0]['coin_wallet_id'];

            }else{
                $coin_wallet_ins = coinWallet::insertGetId([
                    'app_user_id'=>$app_user,
                    'coin_wallet_balance'=>$credit_amt
                ]);

                if($coin_wallet_ins==0){
                    DB::rollback();
                    return back()->with('error','Some Error Occurred');
                }
                $coin_wallet =$coin_wallet_ins;
            }

            $coin_wallet_trans = coinWalletTransaction::insertGetId([
                'coin_wallet_id'=>$coin_wallet,
                'app_user_id'=>$app_user,
                'coin_wallet_type' =>2,
                'coin_wallet_trans_status'=>2,
                'coin_wallet_amount'=>$credit_amt,
                'coin_wallet_remark'=>$remark
            ]);

            if($coin_wallet_trans == 0){
                DB::rollback();
                return back()->with('error','Some Error Occurred');
            }
            
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/credit/coin/user')->with('error','some error occurred'.$ex->getMessage());
        }
        DB::commit();
        return redirect('/credit/coin/user')->with('success','Coin Amount Credit to User Wallet Successfully');       
    
    }

    public function coin_currency(){
        $get_coin_currency = CoinCurrency::get()->first();
        return view('transactions/coin_currency',['coin_currency'=>$get_coin_currency]);
    }

    public function coin_currency_db(Request $request){
        try {
            $this->validate($request,[
                'coin_value'=>'required'
            ],[
                'coin_value.required'=> 'This is required.'
            ]);

            $id = $request->input('coin_currency_id');
            $value = $request->input('coin_value');

            DB::beginTransaction();

            if($id != null && $id != ''){
                $upd = CoinCurrency::where('coin_currency_id',$id)->update([
                    'coin_currency_value'=>$value
                ]);

                if(!$upd){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');

                }
            }else{
                $ins = CoinCurrency::insertGetId([
                    'coin_currency_value'=>$value
                ]);

                if($ins ==0){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');
                    
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
           return redirect('/coin/currency')->with('error','some error occurred'.$ex->getMessage());  
        }

        DB::commit();
        return redirect('/coin/currency')->with('success','Coin Currency Updated Successfully');  
        
    }

}