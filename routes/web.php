<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();


Route::group(['middleware' => ['auth','rights']], function () {
	Route::get('/', function () {
	    return redirect()->intended('/home');
	});

	// ------------------------------Dashboard------------------------------------------
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

	// -----------------------------Profile Update--------------------------------------
	Route::get('/profile/update',[App\Http\Controllers\AdminController::class,'profile_update']);
	Route::post('/profile/update',[App\Http\Controllers\AdminController::class,'profile_update_db']);
	Route::post('/profile/password/update',[App\Http\Controllers\AdminController::class,'profile_pass_update_db']);

	// ----------------------------User Management----------------------------------------
	Route::get('/app/user/management', [App\Http\Controllers\UserManagementController::class, 'user_management_listing']);
	Route::get('/app/user/management/list/api', [App\Http\Controllers\UserManagementController::class, 'user_management_listing_api']);
	Route::get('/app/user/management/create', [App\Http\Controllers\UserManagementController::class, 'user_management_create']);
	Route::post('/app/user/management/create', [App\Http\Controllers\UserManagementController::class, 'user_management_create_db']);

	Route::get('/app/user/management/update/{id}', [App\Http\Controllers\UserManagementController::class, 'user_management_update']);
	Route::post('/app/user/management/update/{id}',[App\Http\Controllers\UserManagementController::class,'user_update_db']);
	Route::get('/app/user/management/delete/{id}', [App\Http\Controllers\UserManagementController::class, 'user_management_delete']);

	Route::get('/admin/user/create', [App\Http\Controllers\AdminController::class, 'admin_create']);
	Route::post('/admin/user/create', [App\Http\Controllers\AdminController::class, 'admin_create_db']);

	Route::get('/admin/user/list', [App\Http\Controllers\AdminController::class, 'admin_users_listing']);
	Route::get('/admin/user/list/api', [App\Http\Controllers\AdminController::class, 'admin_users_listing_api']);

	Route::get('/admin/user/update/{id}', [App\Http\Controllers\AdminController::class, 'admin_user_update']);
	Route::post('/admin/user/update/{id}',[App\Http\Controllers\AdminController::class,'admin_user_update_db']);
	Route::get('/admin/user/delete/{id}', [App\Http\Controllers\AdminController::class, 'admin_user_delete']);

	Route::get('/admin/user/view/{id}',[App\Http\Controllers\AdminController::class, 'admin_user_view']);
	Route::get('/app/user/view/{id}',[App\Http\Controllers\UserManagementController::class, 'app_user_view']);

	// ------------------------------------cash transaction---------------------------------------------

	Route::get('/cash/transaction/list', [App\Http\Controllers\Transactions\CashTransactionController::class, 'cashTransactionList']);
	Route::get('/cash/transaction/list/api', [App\Http\Controllers\Transactions\CashTransactionController::class, 'cashTransactionListApi']);

	Route::get('/cash/transaction/view/{trans_id}',[App\Http\Controllers\Transactions\CashTransactionController::class, 'cashTransactionView']);

	Route::get('/cash/transaction/pdf/{trans_id}',[App\Http\Controllers\Transactions\CashTransactionController::class, 'gen_pdf_cash_trans']);

	// ------------------------------------coin transaction---------------------------------------------


	Route::get('/coin/transaction/list', [App\Http\Controllers\Transactions\CoinTransactionController::class, 'coinTransactionList']);
	Route::get('/coin/transaction/list/api', [App\Http\Controllers\Transactions\CoinTransactionController::class, 'coinTransactionListApi']);

	Route::get('/coin/transaction/view/{trans_id}',[App\Http\Controllers\Transactions\CoinTransactionController::class, 'coinTransactionView']);

	Route::get('/coin/transaction/pdf/{trans_id}',[App\Http\Controllers\Transactions\CoinTransactionController::class, 'gen_pdf_coin_trans']);

	// --------------------------------------credit coin--------------------------------------------------

	Route::get('/credit/coin/user', [App\Http\Controllers\Transactions\CoinTransactionController::class, 'credit_coin']);
	Route::post('/credit/coin/user', [App\Http\Controllers\Transactions\CoinTransactionController::class, 'credit_coin_db']);

	Route::get('/coin/currency', [App\Http\Controllers\Transactions\CoinTransactionController::class, 'coin_currency']);

	Route::post('/coin/currency', [App\Http\Controllers\Transactions\CoinTransactionController::class, 'coin_currency_db']);
	// --------------------------------------credit cash--------------------------------------------------
	Route::get('/credit/cash/user', [App\Http\Controllers\Transactions\CashTransactionController::class, 'credit_cash']);
	Route::post('/credit/cash/user', [App\Http\Controllers\Transactions\CashTransactionController::class, 'credit_cash_db']);

	// -----------------------------------------quiz-------------------------------------------------------

	Route::get('/quiz/category', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_category_create']);
	Route::post('/quiz/category', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_category_create_db']);
	Route::get('/quiz/category/list', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_category_list']);
	Route::get('/quiz/category/list/api', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_category_list_api']);

	Route::get('/quiz/category/edit/{id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_category_edit']);
	Route::post('/quiz/category/edit/{id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_category_edit_db']);

	Route::get('/quiz/category/delete/{id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_category_delete']);
	Route::get('/quiz/category/view/{cat_id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_category_view']);

	Route::get('/quiz/reward/{id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'create_reward']);
	Route::post('/quiz/reward/{id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'create_reward_db']);
	
	Route::get('/quiz/reward/update/{id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'reward_update']);
	Route::post('/quiz/reward/update/{id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'reward_update_db']);

	Route::get('/quiz/group/delete/{id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'quiz_group_delete']);
	// -----------------------------------------questions---------------------------------------------------
	Route::get('/quiz/category/questions/create', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'category_question']);
	Route::post('/quiz/category/questions/create', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'category_question_db']);

	Route::get('/quiz/category/questions/update/{cat_id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'category_question_upd']);
	Route::post('/quiz/category/questions/update/{cat_id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'category_question_upd_db']);

	Route::get('/quiz/category/question/list',[App\Http\Controllers\Quiz\QuizCategoryController::class, 'category_question_list']);
	Route::get('/quiz/category/question/list/api',[App\Http\Controllers\Quiz\QuizCategoryController::class, 'category_question_list_api']);
	Route::get('/quiz/category/question/view/{cat_id}', [App\Http\Controllers\Quiz\QuizCategoryController::class, 'category_view']);

	// -----------------------------------------bonus------------------------------------------------------------

	Route::get('/daily/bonus/setting', [App\Http\Controllers\Bonus\BonusController::class, 'bonus_setting']);
	Route::post('/daily/bonus/setting', [App\Http\Controllers\Bonus\BonusController::class, 'bonus_setting_db']);

	Route::get('/setting/watch/add/bonus', [App\Http\Controllers\Bonus\BonusController::class, 'watch_ad_bonus_setting']);
	Route::post('/setting/watch/add/bonus', [App\Http\Controllers\Bonus\BonusController::class, 'watch_ad_bonus_setting_db']);

	Route::get('/refer/and/earn/bonus', [App\Http\Controllers\Bonus\BonusController::class, 'refer_earn']);
	Route::post('/refer/and/earn/bonus', [App\Http\Controllers\Bonus\BonusController::class, 'refer_earn_db']);

	Route::get('/redeem/money', [App\Http\Controllers\Bonus\BonusController::class, 'redeem_money']);
	Route::post('/redeem/money', [App\Http\Controllers\Bonus\BonusController::class, 'redeem_money_db']);


	// ---------------------------------------------Contest------------------------------------------------------

    Route::get('/contest/create', [App\Http\Controllers\Contest\ContestController::class, 'create_contest']);
	Route::post('/contest/create', [App\Http\Controllers\Contest\ContestController::class, 'create_contest_db']);

    Route::get('/contest/summary', [App\Http\Controllers\Contest\ContestController::class, 'contest_list']);
    Route::get('/contest/summary/api', [App\Http\Controllers\Contest\ContestController::class, 'contest_list_api']);


    Route::get('/contest/edit/{id}', [App\Http\Controllers\Contest\ContestController::class, 'contest_edit']);
	Route::post('/contest/edit/{id}', [App\Http\Controllers\Contest\ContestController::class, 'contest_edit_db']);
	Route::post('/contest/question/edit/{id}', [App\Http\Controllers\Contest\ContestController::class, 'edit_contest_question_db']);

	Route::get('/contest/question/create/{id}', [App\Http\Controllers\Contest\ContestController::class, 'create_contest_question']);
	Route::post('/contest/question/create/{id}', [App\Http\Controllers\Contest\ContestController::class, 'create_contest_question_db']);

	Route::get('/contest/delete/{id}', [App\Http\Controllers\Contest\ContestController::class, 'contest_delete']);
	Route::get('/contest/view/{id}', [App\Http\Controllers\Contest\ContestController::class, 'contest_view']);
	
	Route::get('/contest/reward/{id}', [App\Http\Controllers\Contest\ContestController::class, 'reward_contest']);
	Route::post('/contest/reward/{id}', [App\Http\Controllers\Contest\ContestController::class, 'reward_contest_db']);
	
	Route::get('/contest/reward/update/{id}', [App\Http\Controllers\Contest\ContestController::class, 'reward_update']);
	Route::post('/contest/reward/update/{id}', [App\Http\Controllers\Contest\ContestController::class, 'reward_update_db']);
	
	// ---------------------------------------------Page Create -----------------------------------------------

	Route::get('/create/required/page/{title}', [App\Http\Controllers\PageController::class, 'create_page']);
	Route::post('/create/required/page', [App\Http\Controllers\PageController::class, 'create_page_db']);

	Route::get('/page/summary', [App\Http\Controllers\PageController::class, 'page_summary']);
	Route::get('/page/summary/api', [App\Http\Controllers\PageController::class, 'page_summary_api']);

	Route::get('/app/user/export', [App\Http\Controllers\UserManagementController::class, 'export_appuser']);
	Route::post('/app/user/export', [App\Http\Controllers\UserManagementController::class, 'export_appuser_db']);

	Route::get('/cash/transaction/approve/{transaction_id}', [App\Http\Controllers\Transactions\CashTransactionController::class, 'cash_transaction_approve']);
	
	// ----------------------------------------Banner and popup ----------------------------------------------

	Route::get('/setting/banner/popup', [App\Http\Controllers\Setting\SettingController::class, 'banner_and_popup']);
	Route::post('/setting/banner', [App\Http\Controllers\Setting\SettingController::class, 'banner_db']);
	Route::post('/setting/popup', [App\Http\Controllers\Setting\SettingController::class, 'popup_db']);

	// -----------------------------------------Section Rights--------------------------------------------------

	Route::get('/admin/role/management', [App\Http\Controllers\RolePermissionController::class, 'role_permission']);
	Route::get('/admin/get/section/list', [App\Http\Controllers\RolePermissionController::class, 'get_section_name']);
	Route::post('/admin/role/management', [App\Http\Controllers\RolePermissionController::class, 'role_permission_db']);

});
// dont write below this

Route::get('logout', [App\Http\Controllers\Auth\LoginController::class,'logout']);
Route::get('/send/otp/login', [App\Http\Controllers\Auth\LoginController::class,'send_otp']);
