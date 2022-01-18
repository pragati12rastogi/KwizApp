<?php

namespace App\Http\Controllers\Bonus;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppUsers;
use App\Models\Status;
use App\Models\CoinWalletTransaction;
use App\Models\CoinWallet;
use App\Models\CoinCurrency;
use App\Models\DailyBonus;
use App\Models\WatchAddBonus;
use App\Models\ReferAndEarn;
use App\Models\RedeemMoney;
use Auth;
use Illuminate\Support\Facades\File;
use DB;
use Hash;
use PDF;
use App\Custom\CustomHelpers;

class BonusController extends Controller
{
    
    public function bonus_setting(){
        $get_bonus = DailyBonus::leftjoin('users','users.id','daily_bonus.created_by')
                ->select('daily_bonus.*','users.name as created_by_name')
                ->get()->first();
        $get_coin_currency = CoinCurrency::get()->first();
        $data = ['coin_currency'=>$get_coin_currency,'get_bonus'=>$get_bonus];
        return view('bonus/daily_bonus',$data);
    }

    public function bonus_setting_db(Request $request){
        try {
            
            $id = $request->input('bonus_id');
            $monday = $request->input('monday');
            $tuesday = $request->input('tuesday');
            $wednesday = $request->input('wednesday');
            $thursday = $request->input('thursday');
            $friday = $request->input('friday');
            $saturday = $request->input('saturday');
            $sunday = $request->input('sunday');

            DB::beginTransaction();
            $timestamp = date('Y-m-d H:i:s');

            if($id != null && $id != ''){
                $upd = DailyBonus::where('bonus_id',$id)->update([
                    'bonus_id'=>$id,
                    'monday'=>($monday=='')?0:$monday,
                    'tuesday'=>($tuesday=='')?0:$tuesday,
                    'wednesday'=>($wednesday==0)?0:$wednesday,
                    'thursday'=>($thursday==0)?0:$thursday,
                    'friday'=>($friday==0)?0:$friday,
                    'saturday'=>($saturday==0)?0:$saturday,
                    'sunday'=>($sunday==0)?0:$sunday,
                    'created_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp

                ]);

                if(!$upd){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');

                }
            }else{
                $ins = DailyBonus::insertGetId([
                    'monday'=>($monday=='')?0:$monday,
                    'tuesday'=>($tuesday=='')?0:$tuesday,
                    'wednesday'=>($wednesday==0)?0:$wednesday,
                    'thursday'=>($thursday==0)?0:$thursday,
                    'friday'=>($friday==0)?0:$friday,
                    'saturday'=>($saturday==0)?0:$saturday,
                    'sunday'=>($sunday==0)?0:$sunday,
                    'created_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if($ins ==0){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');
                    
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
           return redirect('/daily/bonus/setting')->with('error','some error occurred'.$ex->getMessage());  
        }

        DB::commit();
        return redirect('/daily/bonus/setting')->with('success','Daily Bonus Updated Successfully');  
        
    }

    public function watch_ad_bonus_setting(){
        $get_bonus = WatchAddBonus::leftjoin('users','users.id','watch_ad_bonus.updated_by')
                ->select('watch_ad_bonus.*','users.name as created_by_name')
                ->get()->first();
        $get_coin_currency = CoinCurrency::get()->first();
        $data = ['coin_currency'=>$get_coin_currency,'bonus'=>$get_bonus];
        return view('bonus/watch_ad_bonus',$data);
    }

    public function watch_ad_bonus_setting_db(Request $request){
        try {
            $this->validate($request,[
                'coin_value'=>'required'
            ],[
                'coin_value.required'=> 'This is required.'
            ]);

            $id = $request->input('watch_ad_id');
            $value = $request->input('coin_value');

            DB::beginTransaction();
            
            $timestamp = date('Y-m-d H:i:s');
            if($id != null && $id != ''){
                $upd = WatchAddBonus::where('watch_ad_bonus_id',$id)->update([
                    'bonus_amount'=>$value,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if(!$upd){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');

                }
            }else{
                $ins = WatchAddBonus::insertGetId([
                    'bonus_amount'=>$value,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if($ins ==0){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');
                    
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
           return redirect('/setting/watch/add/bonus')->with('error','some error occurred'.$ex->getMessage());  
        }

        DB::commit();
        return redirect('/setting/watch/add/bonus')->with('success','Watch Ad Bonus Updated Successfully');  
    }

    public function refer_earn(){
    	$get_bonus = ReferAndEarn::leftjoin('users','users.id','refer_and_earn.updated_by')
                ->select('refer_and_earn.*','users.name as updated_by_name')
                ->get()->first();
        $get_coin_currency = CoinCurrency::get()->first();
        $data = ['coin_currency'=>$get_coin_currency,'bonus'=>$get_bonus];
        return view('bonus/refer_and_earn',$data);

    }
    public function refer_earn_db(Request $request){
        try {
            $this->validate($request,[
                'coin_value_ref'=>'required',
                'coin_value_join'=>'required'
            ],[
                'coin_value_ref.required'=> 'This is required.',
                'coin_value_join.required'=> 'This is required.'
            ]);

            $id = $request->input('refer_earn_id');
            $value_ref = (empty($request->input('coin_value_ref'))?0:$request->input('coin_value_ref'));
            $value_join = (empty($request->input('coin_value_join'))?0:$request->input('coin_value_join'));

            DB::beginTransaction();

            $timestamp = date('Y-m-d H:i:s');
            if($id != null && $id != ''){
                $upd = ReferAndEarn::where('refer_and_earn_id',$id)->update([
                    'join_bonus_amount'=>$value_join,
                    'refer_bonus_amount'=>$value_ref,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if(!$upd){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');

                }
            }else{
                $ins = ReferAndEarn::insertGetId([
                    'join_bonus_amount'=>$value_join,
                    'refer_bonus_amount'=>$value_ref,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if($ins ==0){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');
                    
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
           return redirect('/refer/and/earn/bonus')->with('error','some error occurred'.$ex->getMessage());  
        }

        DB::commit();
        return redirect('/refer/and/earn/bonus')->with('success','Refer And Earn Bonus Updated Successfully');  
    }

    public function redeem_money(){
        $rm = RedeemMoney::leftjoin('users','users.id','redeem_money.updated_by')
                ->select('redeem_money.*','users.name as updated_by_name')
                ->get()->first();
        $get_coin_currency = CoinCurrency::get()->first();
        $data = array('redeem'=>$rm,'coin'=>$get_coin_currency);
        return view('bonus/redeem_money',$data);
    }

    public function redeem_money_db(Request $request){
            try {
            $this->validate($request,[
                'redeem_cash_min'=>'required',
                'redeem_cash_max'=>'required',
                'redeem_coin_min'=>'required',
                'redeem_coin_max'=>'required',
            ],[
                'redeem_cash_min.required'=> 'This is required.',
                'redeem_cash_max.required'=> 'This is required.',
                'redeem_coin_min.required'=> 'This is required.',
                'redeem_coin_max.required'=> 'This is required.'
            ]);

            $id = $request->input('redeem_money_id');
            $redeem_cash_min = (empty($request->input('redeem_cash_min'))?0:$request->input('redeem_cash_min'));
            $redeem_cash_max = (empty($request->input('redeem_cash_max'))?0:$request->input('redeem_cash_max'));
            $redeem_coin_min = (empty($request->input('redeem_coin_min'))?0:$request->input('redeem_coin_min'));
            $redeem_coin_max = (empty($request->input('redeem_coin_max'))?0:$request->input('redeem_coin_max'));

            DB::beginTransaction();

            $timestamp = date('Y-m-d H:i:s');
            if($id != null && $id != ''){
                $upd = RedeemMoney::where('redeem_money_id',$id)->update([
                    'redeem_coin_amt_min'=>$redeem_coin_min,
                    'redeem_coin_amt_max'=>$redeem_coin_max,
                    'redeem_cash_amt_min'=>$redeem_cash_min,
                    'redeem_cash_amt_max'=>$redeem_cash_max,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if(!$upd){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');

                }
            }else{
                $ins = RedeemMoney::insertGetId([
                    'redeem_coin_amt_min'=>$redeem_coin_min,
                    'redeem_coin_amt_max'=>$redeem_coin_max,
                    'redeem_cash_amt_min'=>$redeem_cash_min,
                    'redeem_cash_amt_max'=>$redeem_cash_max,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if($ins ==0){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');
                    
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
           return redirect('/redeem/money')->with('error','some error occurred'.$ex->getMessage());  
        }

        DB::commit();
        return redirect('/redeem/money')->with('success','Redeem Money Updated Successfully');  
    
    }
}