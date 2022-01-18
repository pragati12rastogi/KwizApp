<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppUsers;
use App\Models\CashWalletTransaction;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $app_user = AppUsers::select();
        $total_app_user = count($app_user->where('status_id','<>',4)->get()->toArray());

        $total_online_app_user = count($app_user->where('status_id',2)->where('login_timestamp','<>',null)->get()->toArray());


        $user_register_daily = count($app_user->whereRaw('created_at = CURDATE()')->where('status_id','<>',4)->get()->toArray());
        $reg_user_weekly = count($app_user->whereRaw('WEEKOFYEAR(created_at) = WEEKOFYEAR(NOW())')->where('status_id','<>',4)->get()->toArray());
        $reg_user_monthly = count($app_user->whereRaw('YEAR(created_at) = YEAR(NOW()) and MONTH(created_at) = MONTH(NOW())')->where('status_id','<>',4)->get()->toArray());


        $cash_wallet = CashWalletTransaction::where('cash_wallet_type',2)->where('cash_wallet_trans_status',2)
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id)) as avg'))->get()->first();

        $cash_credit_daily = CashWalletTransaction::where('cash_wallet_type',2)->where('cash_wallet_trans_status',2)
            ->whereRaw('cash_wallet_trans_at = CURDATE()')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),
                DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id)) as avg'))->get()->first();

        $cash_credit_weekly = CashWalletTransaction::where('cash_wallet_type',2)->where('cash_wallet_trans_status',2)
            ->whereRaw('WEEKOFYEAR(cash_wallet_trans_at) = WEEKOFYEAR(NOW())')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id)) as avg'))->get()->first();

        $cash_credit_monthly = CashWalletTransaction::where('cash_wallet_type',2)->where('cash_wallet_trans_status',2)
            ->whereRaw('YEAR(cash_wallet_trans_at) = YEAR(NOW()) and MONTH(cash_wallet_trans_at) = MONTH(NOW())')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id)) as avg'))->get()->first();


        $cash_debit = CashWalletTransaction::where('cash_wallet_type',1)->where('cash_wallet_trans_status',2)
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id)) as avg'))->get()->first();

        $cash_debit_daily = CashWalletTransaction::where('cash_wallet_type',1)->where('cash_wallet_trans_status',2)
            ->whereRaw('cash_wallet_trans_at = CURDATE()')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id)) as avg'))->get()->first();

        $cash_debit_weekly = CashWalletTransaction::where('cash_wallet_type',1)->where('cash_wallet_trans_status',2)
            ->whereRaw('WEEKOFYEAR(cash_wallet_trans_at) = WEEKOFYEAR(NOW())')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id))as avg'))->get()->first();

        $cash_debit_monthly = CashWalletTransaction::where('cash_wallet_type',1)->where('cash_wallet_trans_status',2)
            ->whereRaw('YEAR(cash_wallet_trans_at) = YEAR(NOW()) and MONTH(cash_wallet_trans_at) = MONTH(NOW())')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id))as avg'))->get()->first();


        $cash_convert = CashWalletTransaction::where('cash_wallet_type',3)->where('cash_wallet_trans_status',2)
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id)) as avg'))->get()->first();

        $cash_convert_daily = CashWalletTransaction::where('cash_wallet_type',3)->where('cash_wallet_trans_status',2)
            ->whereRaw('cash_wallet_trans_at = CURDATE()')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id)) as avg'))->get()->first();

        $cash_convert_weekly = CashWalletTransaction::where('cash_wallet_type',3)->where('cash_wallet_trans_status',2)
            ->whereRaw('WEEKOFYEAR(cash_wallet_trans_at) = WEEKOFYEAR(NOW())')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id))as avg'))->get()->first();

        $cash_convert_monthly = CashWalletTransaction::where('cash_wallet_type',3)->where('cash_wallet_trans_status',2)
            ->whereRaw('YEAR(cash_wallet_trans_at) = YEAR(NOW()) and MONTH(cash_wallet_trans_at) = MONTH(NOW())')
            ->select(DB::raw('sum(cash_wallet_amount) as given'),DB::raw('(sum(cash_wallet_amount)/count(cash_wallet_trans_id))as avg'))->get()->first();

        $data= array('totalAppUser'=>$total_app_user,
            'totalActiveAppUser'=>$total_online_app_user,
            'daily_register'=>$user_register_daily,
            'reg_weekly'=>$reg_user_weekly,
            'reg_monthly'=>$reg_user_monthly,
            'cash_credit'=>$cash_wallet,
            'daily_credit'=>$cash_credit_daily,
            'weekly_credit'=>$cash_credit_weekly,
            'monthly_credit'=>$cash_credit_monthly,
            'cash_debit'=>$cash_debit,
            'daily_debit'=>$cash_debit_daily,
            'weekly_debit'=>$cash_debit_weekly,
            'monthly_debit'=>$cash_debit_monthly,
            'cash_convert'=>$cash_convert,
            'daily_convert'=>$cash_convert_daily,
            'weekly_convert'=>$cash_convert_weekly,
            'monthly_convert'=>$cash_convert_monthly,
        );
        return view('home',$data);
    }
}
