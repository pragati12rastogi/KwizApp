<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register_new/app/user', [App\Http\Controllers\PhoneAPI\AuthController::class, 'register']);
Route::post('/login/app-user', [App\Http\Controllers\PhoneAPI\AuthController::class, 'login']);
Route::post('/forget/password/app/user', [App\Http\Controllers\PhoneAPI\AuthController::class, 'forget_password']);
Route::post('/reset/password/app/user', [App\Http\Controllers\PhoneAPI\AuthController::class, 'reset_password']);
Route::get('/app/user/profile/{id}', [App\Http\Controllers\PhoneAPI\AuthController::class, 'edit_profile']);
Route::post('/update/app/user/profile/{id}', [App\Http\Controllers\PhoneAPI\AuthController::class, 'update_profile']);

// ---------------------------wallet api------------------------------------------------------------------
Route::get('/coin/currency', [App\Http\Controllers\PhoneAPI\AuthController::class, 'coin_currency_api']);
Route::get('/coin/wallet/balance/{app_user_id}', [App\Http\Controllers\PhoneAPI\AuthController::class, 'coin_wallet_balance_api']);
Route::get('/cash/wallet/balance/{app_user_id}', [App\Http\Controllers\PhoneAPI\AuthController::class, 'cash_wallet_balance_api']);
Route::get('/cash/wallet/transaction/{app_user_id}/{cash_wallet_id}', [App\Http\Controllers\PhoneAPI\AuthController::class, 'cash_wallet_transaction_history']);
Route::get('/coin/wallet/transaction/{app_user_id}/{cash_wallet_id}', [App\Http\Controllers\PhoneAPI\AuthController::class, 'coin_wallet_transaction_history']);
Route::post('/credit/cash/wallet', [App\Http\Controllers\PhoneAPI\AuthController::class, 'credit_cash_wallet']);
Route::post('/credit/coin/wallet', [App\Http\Controllers\PhoneAPI\AuthController::class, 'credit_coin_wallet']);

Route::post('/verify/user', [App\Http\Controllers\PhoneAPI\AuthController::class, 'verify_user']);
Route::post('/coin/to/money/converter', [App\Http\Controllers\PhoneAPI\AuthController::class, 'coin_converter']);
Route::get('/redeem/cash/coin/master', [App\Http\Controllers\PhoneAPI\AuthController::class, 'redeem_money_master']);
Route::post('/transfer/cointocashwallet', [App\Http\Controllers\PhoneAPI\AuthController::class, 'coinRedeemToCashWallet']);

Route::post('/refernce/code',[App\Http\Controllers\PhoneAPI\AuthController::class,'enter_refer_code']);
Route::post('/daily/login/bonus',[App\Http\Controllers\PhoneAPI\AuthController::class,'daily_bonus']);

Route::get('/get/quiz/category', [App\Http\Controllers\PhoneAPI\AuthController::class, 'getQuizCategory']);
Route::get('/get/contest/list',[App\Http\Controllers\PhoneAPI\AuthController::class,'get_contest']);

Route::get('/get/quiz/detail',[App\Http\Controllers\PhoneAPI\AuthController::class,'get_quiz_detail']);

Route::get('/get/quiz/questions',[App\Http\Controllers\PhoneAPI\AuthController::class,'get_quiz_question']);
Route::get('/get/contest/questions',[App\Http\Controllers\PhoneAPI\AuthController::class,'get_contest_question']);
Route::post('/watch/ad/bonus',[App\Http\Controllers\PhoneAPI\AuthController::class,'watch_ad_bonus']);

Route::get('/leaderboard/top/ten/users',[App\Http\Controllers\PhoneAPI\AuthController::class,'user_leaderboard']);
Route::get('/get/all/reference/users',[App\Http\Controllers\PhoneAPI\AuthController::class,'refernce_list']);
Route::get('/total/bonus/earned',[App\Http\Controllers\PhoneAPI\AuthController::class,'totalBonusEarned']);
Route::get('/get/quiz/reward/list',[App\Http\Controllers\PhoneAPI\AuthController::class,'get_quiz_rewards_api']);
Route::get('/get/contest/reward/list',[App\Http\Controllers\PhoneAPI\AuthController::class,'get_contest_rewards_api']);
Route::post('/submit/quiz/answer',[App\Http\Controllers\PhoneAPI\AuthController::class,'submit_quiz_ans']);
Route::post('/submit/contest/answer',[App\Http\Controllers\PhoneAPI\AuthController::class,'submit_contest_ans']);

Route::get('/get/quiz/leaderboard',[App\Http\Controllers\PhoneAPI\AuthController::class,'get_leaderboard_quiz']);
Route::get('/get/contest/leaderboard',[App\Http\Controllers\PhoneAPI\AuthController::class,'get_leaderboard_contest']);
Route::get('/get/quiz/ques/attempt/info',[App\Http\Controllers\PhoneAPI\AuthController::class,'quiz_ques_report']);
Route::get('/get/contest/ques/attempt/info',[App\Http\Controllers\PhoneAPI\AuthController::class,'contest_ques_report']);

Route::get('/check/user/played/quiz',[App\Http\Controllers\PhoneAPI\AuthController::class,'check_user_played_quiz']);
Route::post('/reward/quiz/leaderboard/users',[App\Http\Controllers\PhoneAPI\AuthController::class,'quiz_leaderboard_rewarding']);
Route::post('/reward/contest/leaderboard/users',[App\Http\Controllers\PhoneAPI\AuthController::class,'contest_leaderboard_rewarding']);

Route::post('/debit/cash/amount',[App\Http\Controllers\PhoneAPI\AuthController::class,'debit_cash_wallet']);

Route::get('/get/static/page/master',[App\Http\Controllers\PhoneAPI\AuthController::class,'static_page_master']);

Route::get('/get/banner/setting',[App\Http\Controllers\PhoneAPI\AuthController::class,'banner_setting']);
Route::get('/get/popup/setting',[App\Http\Controllers\PhoneAPI\AuthController::class,'pop_up_setting']);

Route::post('/debit/joining/contest/fee',[App\Http\Controllers\PhoneAPI\AuthController::class,'deduct_joining_contest_fee']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
