<?php

namespace App\Http\Controllers\Transactions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppUsers;
use App\Models\Status;
use App\Models\CashWalletTransaction;
use App\Models\CashWallet;
use Auth;
use Illuminate\Support\Facades\File;
use DB;
use PDF;
use Hash;
use App\Custom\CustomHelpers;

class CashTransactionController extends Controller
{
    public function cashTransactionList(){
        $app_user = AppUsers::where('status_id','<>',4)->get()->toArray();
        return view('transactions/cashTransactionList',['app_user'=>$app_user]);
    }

    public function cashTransactionListApi(Request $request){
        $search = $request->input('search');
        $search_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        $transfer_type = $request->input('transfer_type');

        $cash_trans_detail = CashWalletTransaction::leftjoin('app_users','app_users.app_user_id','cash_wallet_transaction.app_user_id')
        ->select('cash_wallet_trans_id',
            DB::raw('Concat("Kwiz","-",cash_wallet_transaction.app_user_id,"-",cash_wallet_trans_id) as formed_trans_id'),
            'app_users.full_name',
            'app_users.phone',
            'app_users.email',
            DB::raw('(Case When (cash_wallet_type = 1) then "Debit"
                 When (cash_wallet_type = 2) then "Credit" 
                 When (cash_wallet_type = 3) then "Transfer From Coin" End ) as trans_type'),
            DB::raw('(Case When (cash_wallet_trans_status = 1) then "Pending"
                    When (cash_wallet_trans_status = 2) then "Approved"
                    Else "" End ) as trans_status'),
            'cash_wallet_amount',
            'cash_wallet_remark',
            DB::raw('DATE_FORMAT(cash_wallet_trans_at,"%r %d-%m-%Y") as trans_date')
        );

        if(!empty($search_value))
        {
            $cash_trans_detail = $cash_trans_detail->where(function($query) use ($search_value){
                    $query->where('app_users.full_name','LIKE',"%".$search_value."%")
                        ->orwhere('app_users.phone','LIKE',"%".$search_value."%")
                        ->orwhere('app_users.email','LIKE',"%".$search_value."%")
                        ->orwhere('cash_wallet_amount','LIKE',"%".$search_value."%")
                        ->orwhere('cash_wallet_remark','LIKE',"%".$search_value."%")
                        ->orwhere(DB::raw('(Case When (cash_wallet_type = 1) then "Debit"
                 When (cash_wallet_type = 2) then "Credit" 
                 When (cash_wallet_type = 3) then "Transfer From Coin" End )'),'LIKE',"%".$search_value."%")
                        ;
            });
        }

        if(!empty($transfer_type))
        {
            $cash_trans_detail->where(function($query) use ($transfer_type){
                $query->where('cash_wallet_transaction.cash_wallet_type',$transfer_type);
            });
        }

        $count = $cash_trans_detail->count();
        $cash_trans_detail = $cash_trans_detail->offset($offset)->limit($limit);

        if(isset($request->input('order')[0]['column'])){
            $data = ['cash_wallet_trans_id','formed_trans_id','app_users.full_name','trans_type','trans_status','cash_wallet_amount','cash_wallet_remark','trans_date','app_users.phone','app_users.email'
            ];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $cash_trans_detail->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else
        {
            $cash_trans_detail->orderBy('cash_wallet_trans_id','desc');
        }
        $cash_trans_detaildata = $cash_trans_detail->get()->toArray();
        
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $cash_trans_detaildata; 
        return json_encode($array);
        
    }

    public function cashTransactionView($trans_id){
        $cash_trans_view = CashWalletTransaction::leftjoin('app_users','app_users.app_user_id','cash_wallet_transaction.app_user_id')
        ->select('cash_wallet_trans_id',
            DB::raw('Concat("Kwiz","-",cash_wallet_transaction.app_user_id,"-",cash_wallet_trans_id) as formed_trans_id'),
            'app_users.full_name',
            'app_users.phone',
            'app_users.email',
            DB::raw('(Case When (cash_wallet_type = 1) then "Debit"
                 When (cash_wallet_type = 2) then "Credit" 
                 When (cash_wallet_type = 3) then "Transfer From Coin" End ) as trans_type'),
            DB::raw('(Case When (cash_wallet_trans_status = 1) then "Pending"
                    When (cash_wallet_trans_status = 2) then "Approved"
                    Else "" End ) as trans_status'),
            'cash_wallet_amount',
            'cash_wallet_remark',
            DB::raw('DATE_FORMAT(cash_wallet_trans_at,"%r %d-%m-%Y") as trans_date')
        )->where('cash_wallet_trans_id',$trans_id)->get()->first();
        return view('transactions/cash_trans_view',['cash_trans_view'=>$cash_trans_view]);
    }

    public function gen_pdf_cash_trans($trans_id){
        $cash_trans_view = CashWalletTransaction::leftjoin('app_users','app_users.app_user_id','cash_wallet_transaction.app_user_id')
        ->select('cash_wallet_trans_id',
            DB::raw('Concat("Kwiz","-",cash_wallet_transaction.app_user_id,"-",cash_wallet_trans_id) as formed_trans_id'),
            'app_users.full_name',
            DB::raw('(Case When (cash_wallet_type = 1) then "Debit"
                 When (cash_wallet_type = 2) then "Credit" 
                 When (cash_wallet_type = 3) then "Transfer From Coin" End ) as trans_type'),
            DB::raw('(Case When (cash_wallet_trans_status = 1) then "Pending"
                    When (cash_wallet_trans_status = 2) then "Approved"
                    Else "" End ) as trans_status'),
            'cash_wallet_amount',
            'cash_wallet_remark',
            DB::raw('DATE_FORMAT(cash_wallet_trans_at,"%r %d-%m-%Y") as trans_date')
        )->where('cash_wallet_trans_id',$trans_id)->get()->first();
        $timestamp = date('d-m-Y H:i:s');
        if($cash_trans_view){
            $data = [
                'foo' => 'bar',
                'cash_trans_view'=>$cash_trans_view,
                'created'=>$timestamp
                ];
               
            $pdfFilePath = "cash_transaction.pdf";
            $pdf = PDF::loadView('template.cash_trans_invoice', $data);
            return $pdf->stream($pdfFilePath);
            // return view('template.cash_trans_invoice',$data);
        }
        else{
            $message="No Transaction found!!";
            return redirect('/cash/transaction/view/'.$trans_id)->with('error',$message);
        }   
    }

    public function credit_cash(){
        $app_users = AppUsers::where('app_users.status_id',2)->where('app_users.is_verified',1)->pluck('full_name','app_user_id');
        return view('transactions/credit_cash',compact('app_users'));

    }
    public function credit_cash_db(Request $request){
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
            $cash_wallet = 0;

            DB::beginTransaction();
            


            $get_cashwallet = CashWallet::where('app_user_id',$app_user)->get()->toArray();
            if(count($get_cashwallet)>0){
                $cash_wallet_upd = CashWallet::where('app_user_id',$app_user)->increment('cash_wallet_balance',$credit_amt);

                if($cash_wallet_upd==0){
                    DB::rollback();
                    return back()->with('error','Some Error Occurred');
                }
                $cash_wallet =$get_cashwallet[0]['cash_wallet_id'];

            }else{
                $cash_wallet_ins = CashWallet::insertGetId([
                    'app_user_id'=>$app_user,
                    'cash_wallet_balance'=>$credit_amt
                ]);

                if($cash_wallet_ins==0){
                    DB::rollback();
                    return back()->with('error','Some Error Occurred');
                }
                $cash_wallet =$cash_wallet_ins;
            }

            $cash_wallet_trans = CashWalletTransaction::insertGetId([
                'cash_wallet_id'=>$cash_wallet,
                'app_user_id'=>$app_user,
                'cash_wallet_type' =>2,
                'cash_wallet_trans_status'=>2,
                'cash_wallet_amount'=>$credit_amt,
                'cash_wallet_remark'=>$remark
            ]);

            if($cash_wallet_trans == 0){
                DB::rollback();
                return back()->with('error','Some Error Occurred');
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/credit/cash/user')->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/credit/cash/user')->with('success','Cash Amount Credit to User Wallet Successfully');       
    }

    public function cash_transaction_approve(Request $request, $transaction_id){
        try {
            if($transaction_id){
                $get_transaction = CashWalletTransaction::where('cash_wallet_trans_id',$transaction_id)->get()->first();
                if($get_transaction['cash_wallet_type'] == 1){

                    $check = CashWallet::where('cash_wallet_id',$get_transaction['cash_wallet_id'])->get()->first();
                    if($check['cash_wallet_balance'] >= $get_transaction['cash_wallet_amount'])
                    {
                        $upd = CashWallet::where('cash_wallet_id',$get_transaction['cash_wallet_id'])->decrement('cash_wallet_balance',$get_transaction['cash_wallet_amount']);
                                            
                        if($upd==0){
                            db::rollback();
                            return redirect('/cash/transaction/list')->with('error','Some Error Occurred');
                        }

                        $upd_cwt = CashWalletTransaction::where('cash_wallet_trans_id',$transaction_id)->update([
                            'cash_wallet_trans_status'=>2
                        ]);
                    }else{
                        db::rollback();
                        return redirect('/cash/transaction/list')->with('error','Debit amount is greater than wallet amount');
                    
                    }                    
                }elseif($get_transaction['cash_wallet_type'] == 2){

                    $upd = CashWallet::where('cash_wallet_id',$get_transaction['cash_wallet_id'])->increment('cash_wallet_balance',$get_transaction['cash_wallet_amount']);

                    if($upd==0){
                        db::rollback();
                        return redirect('/cash/transaction/list')->with('error','Some Error Occurred');
                    }

                    $upd_cwt = CashWalletTransaction::where('cash_wallet_trans_id',$transaction_id)->update([
                        'cash_wallet_trans_status'=>2
                    ]);
                }else{

                    $upd_cwt = CashWalletTransaction::where('cash_wallet_trans_id',$transaction_id)->update([
                        'cash_wallet_trans_status'=>2
                    ]);
                }

            }else{
                db::rollback();
                return redirect('/cash/transaction/list')->with('error','Transaction data not found');
        
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/cash/transaction/list')->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/cash/transaction/list')->with('success','Transaction Approved Successfully');
    }

}