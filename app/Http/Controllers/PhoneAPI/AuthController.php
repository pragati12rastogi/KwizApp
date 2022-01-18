<?php

namespace App\Http\Controllers\PhoneAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use DB;
use Mail;
use File;
use App\Models\AppUsers;
use App\Models\CoinCurrency;
use App\Models\CashWallet;
use App\Models\CashWalletTransaction;
use App\Models\CoinWallet;
use App\Models\CoinWalletTransaction;
use App\Models\QuizCategory;
use App\Models\RedeemMoney;
use App\Models\ReferAndEarn;
use App\Models\DailyBonus;
use App\Models\Contest;
use App\Models\ContestQuestion;
use App\Models\ContestReward;
use App\Models\QuizGroup;
use App\Models\QuizReward;
use App\Models\QuizGroupQues;
use App\Models\WatchAddBonus;
use App\Models\SubmitAnswer;
use App\Models\UserReferences;
use App\Models\Winnings;
use App\Models\PageMaster;
use App\Models\Popup;
use App\Models\Banner;

use App\Custom\CustomHelpers;

use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use AuthenticatesUsers;
    public $successStatus = 200;
    public $errorStatus = 401;
    public $errorValidation = 400;


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'full_name'=>['required'],
            'dob'=>['required'],
            'email'=>['required_without:phone','email','unique:app_users'],
            'phone'=>['required_without:email','unique:app_users'],
            'profile_picture' => ['mimes:jpeg,png,jpg,gif,pdf,svg'],
            'password' => ['required', 'string', 'min:8']
        ],[
            'full_name.required'=>'Full Name field is required. ',
            'dob.required'=>'DOB field is required. ',
            'email.required_without'=>'Email field is required. ',
            'phone.required_without'=>'Phone field is required. ',
            'profile_picture.mimes'=>'Profile Picture accept only jpeg,png,jpg,gif,svg. ',
            'password.required'=>'Password is required. ',
            'password.min'=>'Password should be atleast 8 characters. '
        ]);
    }

    public function register(Request $request)
    {
        $validation = $this->validator($request->all());
        if ($validation->fails()) {
            $error['status']="error";

            $validation_arr = $validation->errors();
            $message = '';
            foreach ($validation_arr->all() as $key => $value) {
                $message = $message.$value;
                
            }
            $error['message'] = $message;


          return response()->json($error,$this->errorValidation);
        }
        DB::beginTransaction();
        if(!empty($request->input('phone'))){
            if(!preg_match("/^[0-9]{3}[0-9]{3}[0-9]{4}$/", $request->input('phone'))) {
                DB::rollback();
                $send_data['status'] = 'error';
                $send_data['message'] = 'No Data found';
                
                return response()->json($send_data, $this->errorValidation);
                }
        }
        $profile = '';
        $file = $request->file('profile_picture');
        if(isset($file) || $file != null){
            $destinationPath = public_path().'/upload/user_image/';
            $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('profile_picture')->getClientOriginalExtension();
            $profile = $filename.'_'.time().'.'.$extension;
            $path = $file->move($destinationPath, $profile);
        }else{
            $profile = '';
        }
        
        $random = CustomHelpers::quickAlphaRandom(4);
        $check_user = AppUsers::get()->toArray();
        $unique_no = count($check_user)+1;
        $refer_code = "Kwiz-".$random.$unique_no;
        $timestamp = date('Y-m-d G:i:s');
        
        
        $otp = mt_rand(100000,999999);
          
        $ins = ['full_name'=> $request->input('full_name'),
                'dob'=> date('Y-m-d',strtotime($request->input('dob'))),
                'email'=> $request->input('email'),
                'phone'=> $request->input('phone'),
                'refer_code'=>$refer_code,
                'password'=>$request->input('password'),
                'profile_pic' => $profile,
                'created_at' => $timestamp,
                'otp_code'=>$otp
        ];
        
        $user_insert = AppUsers::insertGetId($ins);
        if($user_insert){
            if(!empty($ins['email'])){
                $sender_name = "Kwiz App";
                $from = "kwizApp@gmail.com";  
                $reciver_name = $ins['full_name'] ;
                $to = $ins['email'];
                // Mail::send('email.register_app_user', ['data' => $ins], function($message) use ($from,$sender_name,$to,$reciver_name)
                // {
                //     $message->from($from, $sender_name)->to($to, $reciver_name)->subject('Kwiz App Email Verify');
                // });
            }
              
            DB::commit();
            $dp = $ins['profile_pic'];
            if(!empty($dp)){
                $destPath = asset('/upload/user_image/');
                $ins['profile_pic'] = $destPath."/".$dp;
            }
            $ins['app_user_id']=$user_insert;
            return $this->successResponse($request, $ins);    
        }else{
            DB::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = 'Some Error Occurred During Registration Please Try Again!!';
            
            return response()->json($send_data, $this->errorValidation);            
        }
        
    }

    protected function successResponse(Request $request, $user)
    {
        return response()->json(['status'=>'success','data' => $user], $this->successStatus);
    }


    public function login(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'email_phone' => 'required',
            'password' => 'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
       
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $email_or_phone = $request->input('email_phone'); 
        $password = $request->input('password'); 

        $userdata = AppUsers::whereRaw("(email ='".$email_or_phone."' OR phone ='".$email_or_phone."')")
                ->where('password',$password)
                    ->leftjoin('status','status.id','app_users.status_id')
                    ->select('app_users.*','status.status')
                    ->first();
                    
        if(isset($userdata->app_user_id))
        {
            if($userdata->status_id != 2){
                $this->incrementLoginAttempts($request);
                $send_data['status'] = 'error';
                $send_data['message'] = 'Login Failed, User is not active';
                
                return response()->json($send_data,$this->errorStatus);    
            }
            if($userdata->is_verified != 1){
                $this->incrementLoginAttempts($request);
                $send_data['status'] = 'error';
                $send_data['message'] = 'Login Failed, User is not verified';
                
                return response()->json($send_data,$this->errorStatus);    
            }
                $timestamp = date('Y-m-d H:i:s');
                
                $upd_login_time= AppUsers::where('app_user_id',$userdata->app_user_id)->update([
                    'login_timestamp'=> $timestamp
                ]);

                $dp = $userdata['profile_pic'];
                if(!empty($dp)){
                    $destPath = asset('/upload/user_image/');
                    $userdata['profile_pic'] = $destPath."/".$dp;
                }
                $send_data['status'] = 'success';
                $send_data['data'] = $userdata;
                return response()->json($send_data,$this->successStatus);
            
        }else {
            $this->incrementLoginAttempts($request);
            $send_data['status'] = 'error';
            $send_data['message'] = 'Login Failed, User credentials not match';
            
            return response()->json($send_data,$this->errorStatus);
        }

    }

    public function daily_bonus(Request $request){

        $validation = Validator::make($request->all(),[
            "app_user_id"=>'required',
            "ad_status"=>"required"
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        DB::beginTransaction();
       
        $daily_bonus = DailyBonus::get()->first();
        $date = strtolower(date('l'));

        $app_user_id = $request->input('app_user_id');
        $ad_status = $request->input('ad_status');

        // present day bonus
        $day_reward = $daily_bonus[$date];
        
        if($ad_status == 1){
            if($day_reward > 0){

                $coin = CoinWallet::where('app_user_id',$app_user_id);
            
                $coin_id = $coin->get()->first();
                if($coin_id){
                    $upd = $coin->increment('coin_wallet_balance',$day_reward);
                    $coin_wallet_id = $coin_id['coin_wallet_id'];
                }else{
                    $ins = CoinWallet::insertGetId([
                        'app_user_id' =>$app_user_id,
                        'coin_wallet_balance' =>$day_reward
                    ]);
                    $coin_wallet_id = $ins;
                }

                $coin_wt = CoinWalletTransaction::insertGetId([
                    'coin_wallet_id'=> $coin_wallet_id,
                    'app_user_id'=> $app_user_id,
                    'coin_wallet_type'=> 2,
                    'coin_wallet_trans_status'=> 2,
                    'coin_wallet_amount'=> $day_reward,
                    'coin_wallet_remark'=> 'Daily Joining Bonus.'
                ]);

            }
        }
             
        $send_data['status'] = 'success';
        $send_data['message'] = "Daily Bonus Rewarded";
        $send_data['data'] = $day_reward;
        DB::commit();
        return response()->json($send_data,$this->successStatus);
            

    }

    public function old_daily_bonus(Request $request){
        die('not in use');
        $request->validate([
            "app_user_id"=>'required',
            "ad_status"=>"required"
        ]);

        DB::beginTransaction();
        $timestamp = date('Y-m-d H:i:s');
        $daily_bonus = DailyBonus::get()->first();
        $date = strtolower(date('l'));

        $app_user_id = $request->input('app_user_id');
        $ad_status = $request->input('ad_status');

        $already_login = AppUsers::where('app_user_id',$app_user_id)
                    ->whereRaw('DATE_FORMAT(login_timestamp,"%Y-%m-%d") = CURDATE()')
                    ->get()->first();

        $day_reward = 0;   

        $message = "";        
        //user first login of present day 
        if(empty($already_login) && $ad_status==1){
            
            $message = "Day First Login and Ad Watched"; 
            // present day bonus
            $day_reward = $daily_bonus[$date];
            
            if($day_reward > 0){

                $coin = CoinWallet::where('app_user_id',$app_user_id);
            
                $coin_id = $coin->get()->first();
                if($coin_id){
                    $upd = $coin->increment('coin_wallet_balance',$day_reward);
                    $coin_wallet_id = $coin_id['coin_wallet_id'];
                }else{
                    $ins = CoinWallet::insertGetId([
                        'app_user_id' =>$app_user_id,
                        'coin_wallet_balance' =>$day_reward
                    ]);
                    $coin_wallet_id = $ins;
                }

                $coin_wt = CoinWalletTransaction::insertGetId([
                    'coin_wallet_id'=> $coin_wallet_id,
                    'app_user_id'=> $app_user_id,
                    'coin_wallet_type'=> 2,
                    'coin_wallet_trans_status'=> 2,
                    'coin_wallet_amount'=> $day_reward,
                    'coin_wallet_remark'=> 'Daily Bonus for'.date('l')
                ]);

            }
            
        }else{
            $message = "Day Login and Ad not Watched"; 
        }

        $upd_login_time= AppUsers::where('app_user_id',$app_user_id)->update([
            'login_timestamp'=> $timestamp
        ]);

        $send_data['status'] = 'success';
        $send_data['message'] = $message;
        $send_data['data'] = ['day_reward'=> "Amount Rewarded is ".$day_reward];
        DB::commit();
        return response()->json($send_data,$this->successStatus);
            
    }

    public function forget_password(Request $request){
        $validation = Validator::make($request->all(),[
            'email_phone' => 'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $email_or_phone= $request->input('email_phone');

        DB::beginTransaction();
        $otp = mt_rand(100000,999999);

        $check_appUser = AppUsers::where('email',$email_or_phone)->orwhere('phone',$email_or_phone)->first();

        if(!empty($check_appUser)){

            if (filter_var($email_or_phone, FILTER_VALIDATE_EMAIL)) {

                $upd_otp = AppUsers::where('app_user_id',$check_appUser['app_user_id'])->update([
                    'otp_code'=>$otp
                ]);

                $sender_name = "Kwiz App";
                $from = "kwizApp@gmail.com";  
                $reciver_name = $check_appUser['full_name'] ;
                $to = $check_appUser['email'];
                Mail::send('email.forget_password', ['data' => $check_appUser,'otp'=>$otp], function($message) use ($from,$sender_name,$to,$reciver_name)
                {
                    $message->from($from, $sender_name)->to($to, $reciver_name)->subject('Reset Password');
                });

                if($upd_otp){
                    DB::commit();
                    return $this->successResponse($request, ['app_user_id'=>$check_appUser['app_user_id'],'full_name'=> $reciver_name,'email'=>$to,'otp'=>$otp]);  
                }

            } else {

                if(preg_match("/^[0-9]{3}[0-9]{3}[0-9]{4}$/", $email_or_phone)) {

                    $send_data['message'] = 'Phone Api Not Impemented!!';
                    return response()->json($send_data, $this->successStatus);
                    //send otp in number 
                }else{
                    $send_data['status'] = 'error';
                    $send_data['message'] = 'Wrong Input Inserted,phone or email both are invalid.';
                    
                    return response()->json($send_data, 404);
                }
                
            }
            
        }else{
            $send_data['status'] = 'error';
            $send_data['message'] = 'User Not Found,Email or Number Dont Exist';
            
            return response()->json($send_data, 404);
        }

    }

    public function reset_password(Request $request){
        $validation = Validator::make($request->all(),[
            'app_user_id'=>'required',
            'otp' => 'required',
            'new_password'=>'required|min:8',
            'password_confirmation'=>'required|same:new_password'
        
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        

        $otp = $request->input('otp');
        $user = AppUsers::where('app_user_id',$request->input('app_user_id'))->first();
        if($user['app_user_id']){
            if($otp == $user['otp_code']){

                $upd_pass = AppUsers::where('app_user_id',$user['app_user_id'])->update([
                    'password'=>$request->input('password_confirmation')
                ]);

                if($upd_pass){
                    return $this->successResponse($request, ['message'=>'Password updated Successfully']);  
                }else{
                    $send_data['status'] = 'error';
                    $send_data['message'] = 'Same Password Inserted';
                    
                    return response()->json($send_data, $this->errorStatus);
                }

            }else{
                $send_data['status'] = 'error';
                $send_data['message'] = 'Wrong OTP';
                
                return response()->json($send_data, $this->errorStatus);
            }
        }else{
            $send_data['status'] = 'error';
            $send_data['message'] = 'User Not Found';
            
            return response()->json($send_data, $this->errorStatus);
        }

    }

    public function edit_profile($id){

        if($id){
            $user = AppUsers::where('app_users.app_user_id',$id)->leftjoin('status','status.id','app_users.status_id')
            ->select('app_users.*','status.status')->first();

            $dp = $user['profile_pic'];
            if(!empty($dp)){
                $destPath = asset('/upload/user_image/');
                $user['profile_pic'] = $destPath."/".$dp;
            }
            $user['otp_code'] =0;
            $send_data['status']= 'success';
            $send_data['data']= $user;
            return response()->json($send_data, $this->successStatus);    
        }else{
            $send_data['status'] = 'error';
            $send_data['message'] = 'User Not Found';
            
            return response()->json($send_data, $this->errorStatus);
        }
        
    }

    public function update_profile(Request $request,$id){
        $validation = Validator::make($request->all(),[
            'full_name'=>'required',
            'dob'=>'required',
            'upd_user_photo' => 'mimes:jpeg,png,jpg,gif,pdf,svg'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        
        DB::beginTransaction();

        $profile = '';
        $file = $request->file('upd_user_photo');
        if(isset($file) || $file != null){
            $destinationPath = public_path().'/upload/user_image/';
            $filenameWithExt = $request->file('upd_user_photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('upd_user_photo')->getClientOriginalExtension();
            $profile = $filename.'_'.time().'.'.$extension;
            $path = $file->move($destinationPath, $profile);
            File::delete($destinationPath.$request->input('old_profile_pic'));
        }else{
            $profile = $request->input('old_profile_pic');
        }
        $timestamp = date('Y-m-d G:i:s');
        $upd = ['full_name'=> $request->input('full_name'),
            'dob'=> date('Y-m-d',strtotime($request->input('dob'))),
            'profile_pic' => $profile
        ];
        $app_user_update = AppUsers::where('app_user_id',$id)->update($upd);
        if($app_user_update){
            DB::commit();
            $send_data['status']= 'success';
            $send_data['data']= $upd;
            $send_data['message']= 'Update Successfully';
            return response()->json($send_data, $this->successStatus);
        }else{

            $send_data['data']= $upd;
            $send_data['message']= 'Nothing updated';
            return response()->json($send_data, $this->errorStatus);
        }

    }
    
    public function coin_currency_api(){
        $cc = CoinCurrency::all();
        return response()->json($cc,$this->successStatus);
    }

    public function coin_wallet_balance_api($app_user_id){
        if($app_user_id){
            $cw = CoinWallet::where('app_user_id',$app_user_id)->orderBy('coin_wallet_id','desc')->get();
            return response()->json($cw,$this->successStatus);
        }else{

            $send_data['message']= 'user id not found';
            return response()->json($send_data, $this->errorStatus);
        }
        
    }
    public function cash_wallet_balance_api($app_user_id){
        if($app_user_id){
            $cw = CashWallet::where('app_user_id',$app_user_id)->orderBy('cash_wallet_id','desc')->get();
            return response()->json($cw,$this->successStatus);
        }else{
            $send_data['message']= 'user id not found';
            return response()->json($send_data, $this->errorStatus);
        }
    }

    public function cash_wallet_transaction_history($app_user_id, $cash_wallet_id){
        
        $cwt = CashWalletTransaction::where('app_user_id',$app_user_id)
            ->where('cash_wallet_id',$cash_wallet_id)
            ->orderBy('cash_wallet_trans_id','desc')->limit(10)->get();
        return response()->json($cwt,$this->successStatus);
    }

    public function coin_wallet_transaction_history($app_user_id, $cash_wallet_id){
        
        $cwt = CoinWalletTransaction::where('app_user_id',$app_user_id)
            ->where('coin_wallet_id',$cash_wallet_id)
            ->orderBy('coin_wallet_trans_id','desc')->limit(10)->get();
        return response()->json($cwt,$this->successStatus);
    }

    public function credit_coin_wallet(Request $request){
        $validation = Validator::make($request->all(),[
            'app_user_id'=>'required',
            'coin_wallet_id'=>'required',
            'coin_wallet_amount'=>'required',
            'coin_wallet_remark'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        

        DB::beginTransaction();

        $credit_coin = CoinWalletTransaction::insertGetId([
            'coin_wallet_id'=>$request->input('coin_wallet_id'),
            'app_user_id'=>$request->input('app_user_id'),
            'coin_wallet_type'=>2,
            'coin_wallet_trans_status'=>2,
            'coin_wallet_amount'=>$request->input('coin_wallet_amount'),
            'coin_wallet_remark'=>$request->input('coin_wallet_remark')
        ]);

        if($credit_coin!=0){

            $increment_cw = CoinWallet::where('coin_wallet_id',$request->input('coin_wallet_id'))->increment('coin_wallet_balance',$request->input('coin_wallet_amount'));

            if($increment_cw!=0){
                DB::commit();
                $send_data['status']= 'success';
                return response()->json($send_data,$this->successStatus);
            }else{
                $send_data['status']= 'error';
                DB::rollback();
                return response()->json($send_data,$this->errorStatus);    
            }
            
        }else{
            $send_data['status']= 'error';
            DB::rollback();
            return response()->json($send_data,$this->errorStatus);
        }
    }

    public function credit_cash_wallet(Request $request){
        $validation = Validator::make($request->all(),[
            'app_user_id'=>'required',
            'cash_wallet_id'=>'required',
            'cash_wallet_amount'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        
        DB::beginTransaction();

        $credit_cash = CashWalletTransaction::insertGetId([
            'cash_wallet_id'=>$request->input('cash_wallet_id'),
            'app_user_id'=>$request->input('app_user_id'),
            'cash_wallet_type'=>2,
            'cash_wallet_trans_status'=>2,
            'cash_wallet_amount'=>$request->input('cash_wallet_amount'),
            'cash_wallet_remark'=>$request->input('cash_wallet_remark')
        ]);

        if($credit_cash!=0){
            $increment_cw = CashWallet::where('cash_wallet_id',$request->input('cash_wallet_id'))->increment('cash_wallet_balance',$request->input('cash_wallet_amount'));

            if($increment_cw!=0){
                DB::commit();
                $send_data['status']= 'success';
                return response()->json($send_data,$this->successStatus);
            }else{
                $send_data['status']= 'error';
                DB::rollback();
                return response()->json($send_data,$this->errorStatus);    
            }
        }else{
            $send_data['status']= 'error';
            DB::rollback();
            return response()->json($send_data,$this->errorStatus);
        }
    }

    public function verify_user(Request $request){
        $validation = Validator::make($request->all(),[
            'app_user_id'=>'required',
            'otp' => 'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $id = $request->input('app_user_id');
        $otp = $request->input('otp');

        $app_user = AppUsers::where('app_user_id',$id)->first();
        if(!empty($app_user)){
            // if($otp == $app_user['otp_code']){
            if($otp == $app_user['otp_code']){
                $verify = AppUsers::where('app_user_id',$app_user['app_user_id'])->update([
                                    'is_verified'=>1,
                                    'status_id'=>2
                                ]);

                $after_upd = AppUsers::where('app_user_id',$id)->first();
                $dp = $after_upd['profile_pic'];
                if(!empty($dp)){
                    $dest = asset('/upload/user_image/');
                    $after_upd['profile_pic'] = $dest.'/'.$dp;
                }
                if($verify){ 
                    return $this->successResponse($request, ['user_data'=>$after_upd,'message'=>'User verified']);  
                }else{
                    $send_data['status'] = 'error';
                    $send_data['message'] = 'User Is Already Verified';
                    
                    return response()->json($send_data, $this->errorStatus);
                }
            }else{
                $send_data['status'] = 'error';
                $send_data['message'] = 'Wrong OTP';
                
                return response()->json($send_data, $this->errorStatus);
          
            }
        }else{
            $send_data['status'] = 'error';
            $send_data['message'] = 'User Not found';
            
            return response()->json($send_data, $this->errorStatus);
        }
    }

    public function getQuizCategory(){
        $quiz_c = QuizCategory::where('is_delete',0)->get();
        return response()->json($quiz_c,$this->successStatus);   
    }

    public function coin_converter(Request $request){
        $validation = Validator::make($request->all(),[
            'coin_amount'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        
        $c_am = $request->input('coin_amount');
        $coin_master = CoinCurrency::get()->first();
        
        $converted_amt = round((int)$c_am / $coin_master['coin_currency_value'],2);

        $data = array(
            "status"=>'success',
            "message"=>"Coin Converter",
            "data"=> array("in_rupees"=>$converted_amt)
        );
        return response()->json($data,$this->successStatus);
    }

    public function redeem_money_master(){
        $redeem = RedeemMoney::get()->first();
        $data = array(
            "status"=>'success',
            "message"=>"Redeem Master for Cash And Coin",
            "data"=> $redeem
        );
        return response()->json($redeem,$this->successStatus);    
    }

    public function coinRedeemToCashWallet(Request $request){
        $validation = Validator::make($request->all(),[
            'coin_amount' => 'required',
            'app_user_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        

        DB::beginTransaction();
        $c_am = $request->input('coin_amount');
        $redeem_money = RedeemMoney::get()->first();
        $coin_master = CoinCurrency::get()->first();
        $app_user_id = $request->input('app_user_id');

        $check_all_trans_day = CoinWalletTransaction::whereraw('DATE_FORMAT(coin_wallet_trans_at,"%Y-%m-%d") = CURDATE()')->where('app_user_id',$app_user_id)
            ->where('coin_wallet_type',1)
            ->select(DB::raw('sum(coin_wallet_amount) as day_trans'))
            ->first();
        if($check_all_trans_day['day_trans'] < $redeem_money['redeem_coin_amt_max']){
            $curr_amount = $check_all_trans_day['day_trans'] + $c_am;
            if($curr_amount > $redeem_money['redeem_coin_amt_max']){
                DB::rollback();
                $data = array(
                    "status"=>'error',
                    "message"=>"Exceeding coin redeem amount.",
                    "data"=> $redeem_money
                );
                return response()->json($data,$this->errorStatus);
            }
        }else{
                DB::rollback();
                $data = array(
                    "status"=>'error',
                    "message"=>"Day limit to redeem reached.",
                    "data"=> $redeem_money
                );
                return response()->json($data,$this->errorStatus);
        }

        if($redeem_money['redeem_coin_amt_min'] <= $c_am && $redeem_money['redeem_coin_amt_max']>= $c_am){
            
            $converted_amt = round((int)$c_am / $coin_master['coin_currency_value'],2);
            
            $cash_wallet = CashWallet::where('app_user_id',$app_user_id)->get()->first();
            $coin_wallet = CoinWallet::where('app_user_id',$app_user_id)->get()->first();

            $check_cwt = CashWalletTransaction::where('app_user_id',$app_user_id)->whereRaw('cash_wallet_trans_at = CURDATE()')->where('cash_wallet_type',3)->get();

            if(count($check_cwt)>0){
                $data = array(
                    "status"=>'error',
                    "message"=>"Coin converted once come next day.",
                    "data"=> $redeem_money
                );
                return response()->json($data,$this->errorStatus);
            }else{

                if($cash_wallet != null){
                    $increment_cw = CashWallet::where('cash_wallet_id',$cash_wallet['cash_wallet_id'])
                    ->increment('cash_wallet_balance', $converted_amt);
                    $cash_wallet_id = $cash_wallet['cash_wallet_id'];

                }else{
                    $ins = CashWallet::insertGetId([
                        'app_user_id' => $app_user_id,
                        'cash_wallet_balance' => $converted_amt
                    ]);
                    $cash_wallet_id = $ins;
                }

                $cash_wt = CashWalletTransaction::insertGetId([
                    'cash_wallet_id'=> $cash_wallet_id,
                    'app_user_id'=> $app_user_id,
                    'cash_wallet_type'=> 3,
                    'cash_wallet_trans_status'=> 2,
                    'cash_wallet_amount'=> $converted_amt,
                    'cash_wallet_remark'=> 'Coin Coverted.'
                ]);

                if($cash_wt == 0){
                    $send_data['status']= 'error';
                    DB::rollback();
                    return response()->json($send_data,$this->errorStatus);
                }

                if($coin_wallet != null){
                    if($coin_wallet['$coin_wallet']>=$c_am){
                        $increment_cw = CoinWallet::where('coin_wallet_id',$coin_wallet['coin_wallet_id'])
                        ->decrement('coin_wallet_balance', $c_am);
                        $coin_wallet_id = $coin_wallet['coin_wallet_id'];

                    }else{
                        $send_data['status']= 'error';
                        $send_data['message']= 'Insufficient Coin to redeem';
                        DB::rollback();
                        return response()->json($send_data,$this->errorStatus);
                    }
                    
                }else{
                    $send_data['status']= 'error';
                    $send_data['message']= 'Insufficient Coin to redeem';
                    DB::rollback();
                    return response()->json($send_data,$this->errorStatus);
                }

                $coin_wt = CoinWalletTransaction::insertGetId([
                    'coin_wallet_id'=> $coin_wallet_id,
                    'app_user_id'=> $app_user_id,
                    'coin_wallet_type'=> 1,
                    'coin_wallet_trans_status'=> 2,
                    'coin_wallet_amount'=> $c_am,
                    'coin_wallet_remark'=> 'Coin Coverted To Cash.'
                ]);

                if($coin_wt == 0){
                    $send_data['status']= 'error';
                    DB::rollback();
                    return response()->json($send_data,$this->errorStatus);
                }

            }
            
        }else{
            DB::rollback();
            $data = array(
                "status"=>'error',
                "message"=>"Coin amount is not correct as per redeem setting.",
                "data"=> $redeem_money
            );
            return response()->json($data,$this->errorStatus);
        }

        DB::commit();
        $data = array('status'=>'success','message'=>'Coin Converted to wallet successfully');
        return response()->json($data,$this->successStatus);
    }

    public function enter_refer_code(Request $request){
        $validation = Validator::make($request->all(),[
            'refer_code' => 'required',
            'joinuser_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        DB::beginTransaction();

        $refer_code = $request->input('refer_code');
        $user_id = $request->input('joinuser_id');

        $joining_user =AppUsers::where('app_user_id',$user_id)->where('refer_code','<>',$refer_code);

        $app_user = $joining_user->get()->first();

        $checkUserRewarded = $joining_user->where('refer_code_used',1)->get()->toArray();
 
        if($checkUserRewarded){
            DB::rollback();
             $data = array(
                "status"=>'error',
                "message"=>"User Already used reference code"
            );
            return response()->json($data,$this->errorStatus);
        }
        $rnf = ReferAndEarn::get()->first();

        // amount
        $refer = $rnf['refer_bonus_amount'];
        $join = $rnf['join_bonus_amount'];

        $refer_code_user = AppUsers::where('refer_code',$refer_code)->where('status_id',2)->get()->first();
        if($refer_code_user){

            $refer_coin = CoinWallet::where('app_user_id',$refer_code_user['app_user_id']);
            $refer_coin_id = $refer_coin->get()->first();
            if($refer_coin_id){
                $upd = $refer_coin->increment('coin_wallet_balance',$refer);
                $refer_coin_wallet_id = $refer_coin_id['coin_wallet_id'];
            }else{
                $ins = CoinWallet::insertGetId([
                    'app_user_id' =>$refer_code_user['app_user_id'],
                    'coin_wallet_balance' =>$refer
                ]);
                $refer_coin_wallet_id = $ins;
            }

            $coin_wt = CoinWalletTransaction::insertGetId([
                'coin_wallet_id'=> $refer_coin_wallet_id,
                'app_user_id'=> $refer_code_user['app_user_id'],
                'coin_wallet_type'=> 2,
                'coin_wallet_trans_status'=> 2,
                'coin_wallet_amount'=> $refer,
                'coin_wallet_remark'=> 'Reference Bonus.'
            ]);
        }else{
            DB::rollback();
             $data = array(
                "status"=>'error',
                "message"=>"Reference code is not valid Please check again"
            );
            return response()->json($data,$this->errorStatus);
        }

        if($app_user){
            $coin = CoinWallet::where('app_user_id',$user_id);
            $coin_id = $coin->get()->first();
            if($coin_id){
                $upd = $coin->increment('coin_wallet_balance',$join);
                $coin_wallet_id = $coin_id['coin_wallet_id'];
            }else{
                $ins = CoinWallet::insertGetId([
                    'app_user_id' =>$user_id,
                    'coin_wallet_balance' =>$join
                ]);
                $coin_wallet_id = $ins;
            }

            $coin_wt = CoinWalletTransaction::insertGetId([
                'coin_wallet_id'=> $coin_wallet_id,
                'app_user_id'=> $user_id,
                'coin_wallet_type'=> 2,
                'coin_wallet_trans_status'=> 2,
                'coin_wallet_amount'=> $join,
                'coin_wallet_remark'=> 'Joining Bonus With Reference Code-'.$refer_code
            ]);

            
        }else{
            DB::rollback();
             $data = array(
                "status"=>'error',
                "message"=>"User not found or using own Reference."
            );
            return response()->json($data,$this->errorStatus);
        }

        $updateReferUsed = AppUsers::where('app_user_id',$user_id)->update(['refer_code_used'=>1]);
        
        $refer_ins = UserReferences::insertGetId([
            'app_user_id'=> $refer_code_user['app_user_id'],
            'joinee_id'=> $user_id,
            'bonus_amount'=> $refer
        ]);

        if($refer_ins == 0){
            DB::rollback();
            $data = array(
                "status"=>'error',
                "message"=>"Some Error Occured While Rewarding."
            );
            return response()->json($data,$this->errorStatus);
        }

        DB::commit();
        $data = array('status'=>'success','message'=>'Congrats You Earned Joining Bonus');
        return response()->json($data,$this->successStatus);
    }

    public function get_contest(){
        $contest = Contest::where('status_id',2)->get()->toArray();
            // whereRaw('contest.start_time <= Now()')->whereRaw('contest.end_time >= Now()')->
                
        foreach ($contest as $key => $contest_data) {
            $dest = asset('/upload/quiz_cat_icon/');
            $contest[$key]['contest_icon'] = $dest.'/'.$contest_data['contest_icon'];
        }

        $data = array(
            "status"=>'success',
            "message"=>"Contest list",
            "data"=> $contest
        );
        return response()->json($data,$this->successStatus);
        
    }

    public function get_quiz_detail(){
        $quiz_cat = QuizCategory::where('is_delete',0)->get();
        $quiz_arr=[];
        foreach ($quiz_cat as $key => $cat_data) {
            
            $quiz_group = QuizGroup::where('quiz_category_id',$cat_data['quiz_category_id'])
            ->where('status_id',2)->get()->toArray();

            $quiz_arr[$cat_data['quiz_category_name']] = $quiz_group;

            $dest = asset('/upload/quiz_cat_icon/');
            $quiz_cat[$key]['quiz_category_icon'] = $dest.'/'.$cat_data['quiz_category_icon'];
        
        }

        $data = array(
            "status"=>'success',
            "message"=>"Quiz Data include Quiz Category And Quiz Group",
            "data"=> array("quiz_category"=>$quiz_cat,"quiz_group"=>$quiz_arr)
        );
        return response()->json($data,$this->successStatus);
        
    }

    public function get_quiz_question(Request $request){
        $validation = Validator::make($request->all(),[
            'quiz_group_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $group_id = $request->input('quiz_group_id');

        $get_ques = QuizGroupQues::where('quiz_qroup_id',$group_id)->where('status_id',2)->get()->toArray();
        
        $ques_count = count($get_ques);

        $data = array(
            "status"=>'success',
            "message"=>"Quiz Group Questions And Answers",
            "data"=> array("ques_count"=>$ques_count,"quiz_ques"=>$get_ques)
        );
        return response()->json($data,$this->successStatus);
        
    }

    public function get_contest_question(Request $request){
        $validation = Validator::make($request->all(),[
            'contest_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $contest_id = $request->input('contest_id');

        $get_ques = ContestQuestion::where('contest_id',$contest_id)->where('status_id',2)->get()->toArray();
        
        $ques_count = count($get_ques);

        $data = array(
            "status"=>'success',
            "message"=>"Contest Questions And Answers",
            "data"=> array("ques_count"=>$ques_count,"contest_ques"=>$get_ques)
        );
        return response()->json($data,$this->successStatus);
        
    }

    public function watch_ad_bonus(Request $request){
        $validation = Validator::make($request->all(),[
            'app_user_id'=>'required',
            'ad_status'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        
        DB::beginTransaction();
        $app_user_id = $request->input('app_user_id');
        $ad_status = $request->input('ad_status');

        $bonus_setting = WatchAddBonus::get()->first();
        if($bonus_setting){

            $day_reward = $bonus_setting['bonus_amount'];

            if($ad_status == 1){

                if($day_reward > 0){

                    $coin = CoinWallet::where('app_user_id',$app_user_id);
                
                    $coin_id = $coin->get()->first();
                    if($coin_id){
                        $upd = $coin->increment('coin_wallet_balance',$day_reward);
                        $coin_wallet_id = $coin_id['coin_wallet_id'];
                    }else{
                        $ins = CoinWallet::insertGetId([
                            'app_user_id' =>$app_user_id,
                            'coin_wallet_balance' =>$day_reward
                        ]);
                        $coin_wallet_id = $ins;
                    }

                    $coin_wt = CoinWalletTransaction::insertGetId([
                        'coin_wallet_id'=> $coin_wallet_id,
                        'app_user_id'=> $app_user_id,
                        'coin_wallet_type'=> 2,
                        'coin_wallet_trans_status'=> 2,
                        'coin_wallet_amount'=> $day_reward,
                        'coin_wallet_remark'=> 'Watch Ad Bonus'
                    ]);

                }else{
                    DB::rollback();
                    $send_data['status'] = 'error';
                    $send_data['message'] = "No Bonus Present";
                    return response()->json($send_data,$this->errorStatus);
                }
            }else{
                DB::rollback();
                $send_data['status'] = 'error';
                $send_data['message'] = "Ad Not Watched,No Bonus Given";
                return response()->json($send_data,$this->errorStatus);
            }
            
        }else{
            DB::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = "No Watch Ad Bonus Found";
            return response()->json($send_data,$this->errorStatus);

        }
        
            
        $send_data['status'] = 'success';
        $send_data['message'] = "Watch Ad Bonus Rewarded";
        $send_data['data'] = $day_reward;
        DB::commit();
        return response()->json($send_data,$this->successStatus);
    }

    public function user_leaderboard(){
        $coin_wallet = CoinWallet::leftjoin('app_users','coin_wallet.app_user_id','app_users.app_user_id')
            ->select('coin_wallet.coin_wallet_id','coin_wallet.coin_wallet_balance','app_users.full_name','app_users.profile_pic')
            ->orderBy('coin_wallet_balance','desc')->limit(10)->get()->toArray();

        foreach ($coin_wallet as $key => $value) {
            $path = asset('/upload/user_image/');
            $coin_wallet[$key]['profile_pic']= $path.'/'.$value['profile_pic'];
        }
        
        $send_data['status'] = 'success';
        $send_data['message'] = "Leader Board Of Top 10 Users";
        $send_data['data'] = array('list'=>$coin_wallet);
        
        return response()->json($send_data,$this->successStatus);

    }

    public function refernce_list(Request $request){
        $validation = Validator::make($request->all(),[
            'app_user_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }


        $user_id = $request->input('app_user_id');
        $refer_users = UserReferences::where('user_references.app_user_id',$user_id)
            ->leftjoin('app_users','app_users.app_user_id','user_references.joinee_id')
            ->select('app_users.full_name as joinee','user_references.app_user_id','user_references.bonus_amount')
            ->get()->toArray();

        $total_ref_amt = UserReferences::where('user_references.app_user_id',$user_id)
            ->select(DB::raw('sum(user_references.bonus_amount) as totalbonus'))
            ->get()->first();

        $send_data['status'] = 'success';
        $send_data['message'] = "Reference Users";
        $send_data['data'] = array('refer_user_list'=>$refer_users,'total_refer_reward'=>$total_ref_amt['totalbonus']);
        
        return response()->json($send_data,$this->successStatus);

    }

    public function totalBonusEarned(Request $request){
        $validation = Validator::make($request->all(),[
            'app_user_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }


        $user_id = $request->input('app_user_id');
        $total = CoinWalletTransaction::where('coin_wallet_transaction.app_user_id',$user_id)
            ->where('coin_wallet_transaction.coin_wallet_remark','LIKE',"%Bonus%")
            ->where('coin_wallet_transaction.coin_wallet_type',2)
            ->select(DB::raw('sum(coin_wallet_amount) as total_bonus'))->get()->toArray();

        $send_data['status'] = 'success';
        $send_data['message'] = "Total Bonus Earned";
        $send_data['data'] = $total;
        
        return response()->json($send_data,$this->successStatus);
   
    }

    public function submit_quiz_ans(Request $request){
        $validation = Validator::make($request->all(),[
            // 'quiz_group_id'=>'required',
            'quiz_data.*'=>'required',
            'app_user_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }


        DB::beginTransaction();
        // $quiz_group_id = $request->input('quiz_group_id');
        $quiz_data = $request->input('quiz_data');
        $app_user_id = $request->input('app_user_id');

        $check = SubmitAnswer::whereIn('quiz_ques_id',array_keys($quiz_data))
            ->where('app_user_id',$app_user_id)->get()->toArray();

        if(count($check)>0){
            DB::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = "User Already Played This Quiz";
            
            return response()->json($send_data,$this->errorStatus);
        }

        $timestamp = date('Y-m-d H:i:s');

        foreach ($quiz_data as $ques_id => $ans) {
            $quiz_group_ques = QuizGroupQues::where('ques_id',$ques_id)->get()->first();
            $result = 0;
            if($quiz_group_ques['answer'] == $ans){
                $result = 1;
            }

            $ins = SubmitAnswer::insertGetId([
                'app_user_id'=>$app_user_id,
                'quiz_ques_id'=>$ques_id,
                'result'=>$result,
                'created_at'=>$timestamp
            ]);

            if($ins == 0){
                DB::rollback();
                $send_data['status'] = 'error';
                $send_data['message'] = "Some error Occurred";
                
                return response()->json($send_data,$this->errorStatus);
           
            }

        }

        DB::commit();

        $send_data['status'] = 'success';
        $send_data['message'] = "All Answers Submitted successfully.";
        
        return response()->json($send_data,$this->errorStatus);

    }

    public function submit_contest_ans(Request $request){
        $validation = Validator::make($request->all(),[
            // 'contest_id'=>'required',
            'contest_ans.*'=>'required',
            'contest_time.*'=>'required',
            'app_user_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        DB::beginTransaction();

        $contest_ans = $request->input('contest_ans');
        $contest_time = $request->input('contest_time');
        $app_user_id = $request->input('app_user_id');

        $check = SubmitAnswer::whereIn('contest_ques_id',array_keys($contest_ans))
            ->where('app_user_id',$app_user_id)->get()->toArray();

        if(count($check)>0){
            DB::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = "User Already Played This Contest";
            
            return response()->json($send_data,$this->errorStatus);
        }

        $timestamp = date('Y-m-d H:i:s');

        foreach ($contest_ans as $ques_id => $ans) {
            $contest_ques = ContestQuestion::where('question_id',$ques_id)->get()->first();
            $result = 0;
            $points = 0;
            if($contest_ques['answer'] == $ans){
                $result = 1;
                $points = $contest_ques['question_point'];
            }else{
                $marks = $contest_ques['question_point']/2;
                $points = -$marks;
            }
            
            $ins = SubmitAnswer::insertGetId([
                'app_user_id'=>$app_user_id,
                'contest_ques_id'=>$ques_id,
                'result'=>$result,
                'answering_time'=>$contest_time[$ques_id],
                'points'=> $points,
                'created_at'=>$timestamp
            ]);

            if($ins == 0){
                DB::rollback();
                $send_data['status'] = 'error';
                $send_data['message'] = "Some error Occurred";
                
                return response()->json($send_data,$this->errorStatus);
           
            }

        }

        DB::commit();

        $send_data['status'] = 'success';
        $send_data['message'] = "Contest All Answers Submitted successfully.";
        
        return response()->json($send_data,$this->errorStatus);

    }

    public function get_quiz_rewards_api(Request $request){
        $validation = Validator::make($request->all(),[
            'quiz_group_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $quiz_group_id = $request->input('quiz_group_id');

        $get_reward = QuizReward::where('quiz_id',$quiz_group_id)->get()->toArray();

        $send_data['status'] = 'success';
        $send_data['message'] = "Quiz Reward List. (Amount In Coins)";
        $send_data['data'] = $get_reward;
        return response()->json($send_data,$this->errorStatus);

    }

    public function get_contest_rewards_api(Request $request){
        $validation = Validator::make($request->all(),[
            'contest_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $contest_id = $request->input('contest_id');

        $get_reward = ContestReward::where('contest_id',$contest_id)->get()->toArray();

        $send_data['status'] = 'success';
        $send_data['message'] = "Contest Reward List. (Amount In Cash)";
        $send_data['data'] = $get_reward;
        return response()->json($send_data,$this->errorStatus);
    }

    public function get_leaderboard_quiz(Request $request){
        $validation = Validator::make($request->all(),[
            'quiz_group_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $group_id = $request->input('quiz_group_id');
        
        $leader_board_result = SubmitAnswer::leftjoin('quiz_group_ques','submit_answer.quiz_ques_id','quiz_group_ques.ques_id')
            ->leftjoin('app_users','app_users.app_user_id','submit_answer.app_user_id')
            ->where('quiz_group_ques.quiz_qroup_id',$group_id)
            ->where('submit_answer.result',1)
            ->select('submit_answer.app_user_id','app_users.full_name','quiz_group_ques.quiz_qroup_id',DB::raw('sum(quiz_group_ques.question_point) as total_points'),'submit_answer.result',DB::raw('Group_Concat(quiz_group_ques.ques_id) as correct_ques_ids'))
            ->groupBy('submit_answer.app_user_id','app_users.full_name','quiz_group_ques.quiz_qroup_id','submit_answer.result')
            ->orderBy('total_points','desc')
            ->limit(10)
            ->get()->toArray();

        $send_data['status'] = 'success';
        $send_data['message'] = "Quiz Leasderboard List";
        $send_data['data'] = $leader_board_result;
        return response()->json($send_data,$this->errorStatus);
    }

    public function get_leaderboard_contest(Request $request){
        $validation = Validator::make($request->all(),[
            'contest_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $contest_id = $request->input('contest_id');
        
        $leader_board_result = SubmitAnswer::leftjoin('contest_question','submit_answer.contest_ques_id','contest_question.question_id')
            ->leftjoin('app_users','app_users.app_user_id','submit_answer.app_user_id')
            ->leftjoin('contest','contest.contest_id','contest_question.contest_id')
            ->where('contest_question.contest_id',$contest_id)
            ->select('submit_answer.app_user_id',
                'app_users.full_name',
                'contest_question.contest_id',
                DB::raw('sum(submit_answer.points) as total_points'),
                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(answering_time))) AS total_time'),
                DB::raw('Group_Concat(contest_question.question_id) as ques_ids'))
            ->groupBy('submit_answer.app_user_id','app_users.full_name','contest_question.contest_id','contest.contest_id')
            ->orderBy('total_points','desc')
            ->orderBy('total_time','ASC')
            ->orderBy('submit_answer.created_at','ASC')
            ->limit(10)
            ->get()->toArray();
        
        $send_data['status'] = 'success';
        $send_data['message'] = "Contest Leasderboard List";
        $send_data['data'] = $leader_board_result;
        return response()->json($send_data,$this->errorStatus);
    }

    public function quiz_ques_report(Request $request){
        $validation = Validator::make($request->all(),[
            'quiz_group_id'=>'required',
            'app_user_id'=>'required'
            
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }


        $group_id = $request->input('quiz_group_id');
        $app_user_id = $request->input('app_user_id');
        
        $get_ques_count = QuizGroupQues::where('quiz_qroup_id',$group_id)->where('status_id',2)->select(DB::raw('count(ques_id) as total_ques'))->get()->first();

        $total_correct = SubmitAnswer::leftjoin('quiz_group_ques','submit_answer.quiz_ques_id','quiz_group_ques.ques_id')->where('quiz_group_ques.quiz_qroup_id',$group_id)
            ->where('submit_answer.result',1)
            ->where('submit_answer.app_user_id',$app_user_id)
            ->select('submit_answer.app_user_id',DB::raw('Count(submit_answer.result) as total_correct'))
            ->groupBy('submit_answer.app_user_id','submit_answer.result')
            ->get()->first();

        $total_wrong = SubmitAnswer::leftjoin('quiz_group_ques','submit_answer.quiz_ques_id','quiz_group_ques.ques_id')->where('quiz_group_ques.quiz_qroup_id',$group_id)
            ->where('submit_answer.result',0)
            ->where('submit_answer.app_user_id',$app_user_id)
            ->select('submit_answer.app_user_id',DB::raw('Count(submit_answer.result) as total_incorrect'))
            ->groupBy('submit_answer.app_user_id','submit_answer.result')
            ->get()->first();

        $send_data['status'] = 'success';
        $send_data['message'] = "Total ques, Total correct ques, Total wrong ques";
        $send_data['data'] = array('total_ques'=>$get_ques_count['total_ques'],'total_wrong'=>$total_wrong['total_incorrect'],'total_correct'=>$total_correct['total_correct']);

        return response()->json($send_data,$this->errorStatus);

    }

    public function contest_ques_report(Request $request){
        $validation = Validator::make($request->all(),[
            'contest_id'=>'required',
            'app_user_id'=>'required'
            
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }


        $contest_id = $request->input('contest_id');
        $app_user_id = $request->input('app_user_id');

        $total_ques = ContestQuestion::where('contest_id',$contest_id)->where('status_id',2)->select(DB::raw('count(question_id) as total_ques'))->get()->first();

        $total_right = SubmitAnswer::leftjoin('contest_question','submit_answer.contest_ques_id','contest_question.question_id')
            ->where('contest_question.contest_id',$contest_id)
            ->where('submit_answer.result',1)
            ->where('submit_answer.app_user_id',$app_user_id)
            ->select('submit_answer.app_user_id',DB::raw('count(submit_answer.result) as total_correct'))
            ->groupBy('submit_answer.app_user_id','submit_answer.result')
            ->get()->first();

        $total_wrong = SubmitAnswer::leftjoin('contest_question','submit_answer.contest_ques_id','contest_question.question_id')
            ->where('contest_question.contest_id',$contest_id)
            ->where('submit_answer.app_user_id',$app_user_id)
            ->where('submit_answer.result',0)
            ->select('submit_answer.app_user_id',DB::raw('count(submit_answer.result) as total_incorrect'))
            ->groupBy('submit_answer.app_user_id','submit_answer.result')
            ->get()->first();

        $send_data['status'] = 'success';
        $send_data['message'] = "Total ques, Total correct ques, Total wrong ques";
        $send_data['data'] = array('total_ques'=>$total_ques['total_ques'],'total_wrong'=>$total_wrong['total_incorrect'],'total_correct'=>$total_right['total_correct']);

        return response()->json($send_data,$this->errorStatus);

    }

    public function check_user_played_quiz(Request $request){
        $validation = Validator::make($request->all(),[
            'quiz_group_id'=>'required',
            'app_user_id'=>'required'
            
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        
        $quiz_group_id = $request->input('quiz_group_id');
        $app_user_id = $request->input('app_user_id');

        $check = SubmitAnswer::leftjoin('quiz_group_ques','submit_answer.quiz_ques_id','quiz_group_ques.ques_id')
            ->where('quiz_group_ques.quiz_qroup_id',$quiz_group_id)
            ->where('submit_answer.app_user_id',$app_user_id)->get()->toArray();

        if(count($check)>0){
            $send_data['status'] = 'success';
            $send_data['message'] = "User Already Played This Quiz";
            $send_data['data'] = 1;
            
            return response()->json($send_data,$this->errorStatus);
        }else{

            $send_data['status'] = 'success';
            $send_data['message'] = "User Not Played This Quiz";
            $send_data['data'] = 0;
            
            return response()->json($send_data,$this->errorStatus);
        
        }
    }

    public function quiz_leaderboard_rewarding(Request $request){
        $validation = Validator::make($request->all(),[
            'quiz_group_id'=>'required',
            'app_user_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }


        $group_id = $request->input('quiz_group_id');
        $app_user_id = $request->input('app_user_id');
        
        $already_exit = Winnings::where('quiz_group_id',$group_id)->where('app_user_id',$app_user_id)->get()->toArray();
        if(count($already_exit)>0){
            DB::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = "Rewardings for this quiz is already distributed to User";
            $send_data['data'] = $already_exit;
            return response()->json($send_data,$this->errorStatus);

        }

        DB::beginTransaction();

        $leader_board_result = SubmitAnswer::leftjoin('quiz_group_ques','submit_answer.quiz_ques_id','quiz_group_ques.ques_id')
            ->leftjoin('quiz_group','quiz_group.group_id','quiz_group_ques.quiz_qroup_id')
            ->leftjoin('app_users','app_users.app_user_id','submit_answer.app_user_id')
            ->where('quiz_group_ques.quiz_qroup_id',$group_id)
            ->where('submit_answer.app_user_id',$app_user_id)
            ->where('submit_answer.result',1)
            ->select('submit_answer.app_user_id','quiz_group.quiz_title','app_users.full_name','quiz_group_ques.quiz_qroup_id',DB::raw('sum(quiz_group_ques.question_point) as total_points'),'submit_answer.result',DB::raw('Group_Concat(quiz_group_ques.ques_id) as correct_ques_ids'))
            ->groupBy('submit_answer.app_user_id','quiz_group.quiz_title','app_users.full_name','quiz_group_ques.quiz_qroup_id','submit_answer.result')
            ->get()->first();
           
            $timestamp = date('Y-m-d H:i:s');

        if(!empty($leader_board_result)){


            $pos = 0;
            $pos_amt = $leader_board_result['total_points'];
            $app_user_id =$leader_board_result['app_user_id'];

            $win = Winnings::insertGetId([
                'quiz_group_id'=> $leader_board_result['quiz_qroup_id'],
                'app_user_id'=>$app_user_id,
                'position'=> $pos,
                'rewarding_type'=>'coin',
                'amount_rewarded' => $pos_amt,
                'created_at'=>$timestamp
            ]);

            $coin = CoinWallet::where('app_user_id',$app_user_id);
            
            $coin_id = $coin->get()->first();
            if($coin_id){
                $upd = $coin->increment('coin_wallet_balance',$pos_amt);
                $coin_wallet_id = $coin_id['coin_wallet_id'];
            }else{
                $ins = CoinWallet::insertGetId([
                    'app_user_id' =>$app_user_id,
                    'coin_wallet_balance' =>$pos_amt
                ]);
                $coin_wallet_id = $ins;
            }

            $coin_wt = CoinWalletTransaction::insertGetId([
                'coin_wallet_id'=> $coin_wallet_id,
                'app_user_id'=> $app_user_id,
                'coin_wallet_type'=> 2,
                'coin_wallet_trans_status'=> 2,
                'coin_wallet_amount'=> $pos_amt,
                'coin_wallet_remark'=> 'Winnings Coins for Playing Quiz -'.$leader_board_result['quiz_title']
            ]);
            
        }else{
            db::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = "No data found for user and quiz";
            return response()->json($send_data,$this->errorStatus);

        }
        
        $get_win = Winnings::where('quiz_group_id',$group_id)->get()->toArray();
        DB::commit(); 
        $send_data['status'] = 'success';
        $send_data['message'] = "Winnings Distributed Successfully";
        $send_data['data'] = $get_win;
        
        return response()->json($send_data,$this->errorStatus);
        
    }

    public function contest_leaderboard_rewarding(Request $request){
        $validation = Validator::make($request->all(),[
            'contest_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        $contest_id = $request->input('contest_id');
        
        $already_exit = Winnings::where('contest_id',$contest_id)->get()->toArray();
        if(count($already_exit)>0){
            DB::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = "Rewardings for this contest is already distributed";
            $send_data['data'] = $already_exit;
            return response()->json($send_data,$this->errorStatus);

        }
        $get_reward = ContestReward::where('contest_id',$contest_id)->orderBy('position','asc')->get()->toArray();

        DB::beginTransaction();
        if(count($get_reward)>0){

            $leader_board_result = SubmitAnswer::leftjoin('contest_question','submit_answer.contest_ques_id','contest_question.question_id')
            ->leftjoin('app_users','app_users.app_user_id','submit_answer.app_user_id')
            ->leftjoin('contest','contest.contest_id','contest_question.contest_id')
            ->where('contest_question.contest_id',$contest_id)
            ->select('submit_answer.app_user_id',
                'app_users.full_name',
                'contest_question.contest_id',
                'contest.contest_name',
                DB::raw('sum(submit_answer.points) as total_points'),
                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(answering_time))) AS total_time'),
                DB::raw('Group_Concat(contest_question.question_id) as ques_ids'))
            ->groupBy('submit_answer.app_user_id','app_users.full_name','contest_question.contest_id','contest.contest_id','contest.contest_name')
            ->orderBy('total_points','desc')
            ->orderBy('total_time','ASC')
            ->orderBy('submit_answer.created_at','ASC')
            ->limit(count($get_reward))
            ->get()->toArray();

            $timestamp = date('Y-m-d H:i:s');

           
            foreach ($leader_board_result as $index => $detail) {
                $pos = $get_reward[$index]['position'];
                $pos_amt = $get_reward[$index]['position_amount'];
                $app_user_id =$detail['app_user_id'];

                $win = Winnings::insertGetId([
                    'contest_id'=> $detail['contest_id'],
                    'app_user_id'=>$app_user_id,
                    'position'=> $pos,
                    'rewarding_type'=>'cash',
                    'amount_rewarded' => $pos_amt,
                    'created_at'=>$timestamp
                ]);

                $cash = CashWallet::where('app_user_id',$app_user_id);
                
                $cash_id = $cash->get()->first();
                if($cash_id){
                    $upd = $cash->increment('cash_wallet_balance',$pos_amt);
                    $cash_wallet_id = $cash_id['cash_wallet_id'];
                }else{
                    $ins = CashWallet::insertGetId([
                        'app_user_id' =>$app_user_id,
                        'cash_wallet_balance' =>$pos_amt
                    ]);
                    $cash_wallet_id = $ins;
                }

                $cash_wt = CashWalletTransaction::insertGetId([
                    'cash_wallet_id'=> $cash_wallet_id,
                    'app_user_id'=> $app_user_id,
                    'cash_wallet_type'=> 2,
                    'cash_wallet_trans_status'=> 2,
                    'cash_wallet_amount'=> $pos_amt,
                    'cash_wallet_remark'=> 'Winnings of Playing Contest -'.$detail['contest_name'].' and obtaining position '.$pos
                ]);


            }
        }else{
            DB::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = "No Rewardings found";
            return response()->json($send_data,$this->errorStatus);

        }
        
        $get_win = Winnings::where('contest_id',$contest_id)->get()->toArray();
        DB::commit(); 
        $send_data['status'] = 'success';
        $send_data['message'] = "Winnings Distributed Successfully";
        $send_data['data'] = $get_win;
        
        return response()->json($send_data,$this->errorStatus);
        
    }

    public function debit_cash_wallet(Request $request){
        $validation = Validator::make($request->all(),[
            'cash_amount'=>'required',
            'app_user_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }

        DB::beginTransaction();
        $cash_amount = $request->input('cash_amount');
        $app_user_id = $request->input('app_user_id');
        $redeem_money = RedeemMoney::get()->first();
        // DB::enableQueryLog();
        $check_all_trans_day = CashWalletTransaction::whereraw('DATE_FORMAT(cash_wallet_trans_at,"%Y-%m-%d") = CURDATE()')->where('app_user_id',$app_user_id)
            ->where('cash_wallet_type',1)
            ->select(DB::raw('sum(cash_wallet_amount) as day_trans'))
            ->first();
            
        if($check_all_trans_day['day_trans'] < $redeem_money['redeem_cash_amt_max']){
            $curr_amount = $check_all_trans_day['day_trans'] + $cash_amount;
            if($curr_amount > $redeem_money['redeem_cash_amt_max']){
                DB::rollback();
                $data = array(
                    "status"=>'error',
                    "message"=>"Exceeding cash redeem amount.",
                    "data"=> $redeem_money
                );
                return response()->json($data,$this->errorStatus);
            }
        }else{
                DB::rollback();
                $data = array(
                    "status"=>'error',
                    "message"=>"Day limit to redeem reached.",
                    "data"=> $redeem_money
                );
                return response()->json($data,$this->errorStatus);
        }

        if($redeem_money['redeem_cash_amt_min'] <= $cash_amount && $redeem_money['redeem_cash_amt_max']>= $cash_amount){

                $cash = CashWallet::where('app_user_id',$app_user_id)->get()->first();
                $cwt_check = CashWalletTransaction::where('app_user_id',$app_user_id)
                    ->where('cash_wallet_type',1)
                    ->select(DB::raw('sum(cash_wallet_amount) as total_trans'))
                    ->get()->first();

                if($cwt_check['total_trans'] > $cash['cash_wallet_balance']){
                    DB::rollback();
                    $data = array(
                        "status"=>'error',
                        "message"=>"Exceeding limit.",
                        "data"=> $redeem_money
                    );
                    return response()->json($data,$this->errorStatus);
                }else{
                    $new_amt = $cwt_check['total_trans']+$cash_amount;
                    if($new_amt > $cash['cash_wallet_balance']){
                        DB::rollback();
                        $data = array(
                            "status"=>'error',
                            "message"=>"Exceeding limit.",
                            "data"=> $redeem_money
                        );
                        return response()->json($data,$this->errorStatus);
                    }
                }

                if($cash['cash_wallet_balance']>= $cash_amount){
                    $cash_wt = CashWalletTransaction::insertGetId([
                        'cash_wallet_id'=> $cash['cash_wallet_id'],
                        'app_user_id'=> $app_user_id,
                        'cash_wallet_type'=> 1,
                        'cash_wallet_trans_status'=> 1,
                        'cash_wallet_amount'=> $cash_amount,
                        'cash_wallet_remark'=> 'Withdraw Cash Amount Request'
                    ]);
                }
                

        }else{
            DB::rollback();
            $data = array(
                "status"=>'error',
                "message"=>"Cash amount is not correct as per redeem setting.",
                "data"=> $redeem_money
            );
            return response()->json($data,$this->errorStatus);
        }

        DB::commit();
        $data = array('status'=>'success','message'=>'Cash Withdrawal Request generated');
        return response()->json($data,$this->successStatus);
    }

    public function static_page_master(){
        $get_page = PageMaster::get()->toArray();
        $data = array('status'=>'success','message'=>'Static Page Master','data'=>$get_page);
        return response()->json($data,$this->successStatus);
    }

    public function deduct_joining_contest_fee(Request $request){
        $validation = Validator::make($request->all(),[
            'app_user_id'=>'required',
            'contest_id'=>'required'
        ]);
        if ($validation->fails()) {
            $error['status']="error";
            $error["data"] = $validation->errors();

          return response()->json($error,$this->errorValidation);
        }
        DB::beginTransaction();
        $app_user_id = $request->input('app_user_id');
        $contest_id = $request->input('contest_id');

        $check = SubmitAnswer::where('app_user_id',$app_user_id)
            ->leftjoin('contest_question','contest_question.question_id','submit_answer.contest_ques_id')
            ->where('contest_question.contest_id',$contest_id)
            ->get()->toArray();

        if(count($check)>0){
            DB::rollback();
            $send_data['status'] = 'error';
            $send_data['message'] = "User Already Played This Contest";
            
            return response()->json($send_data,$this->errorStatus);
        }

        $coin_wallet = CoinWallet::where('app_user_id',$app_user_id)->get()->first();
        $contest = Contest::where('contest_id',$contest_id)->where('status_id',2)->get()->first();
        if(!empty($coin_wallet) && !empty($contest)){
            if($coin_wallet['coin_wallet_balance']>= $contest['contest_fee']){

                $dec_cw = CoinWallet::where('coin_wallet_id',$coin_wallet['coin_wallet_id'])
                        ->decrement('coin_wallet_balance', $contest['contest_fee']);
                        

                $coin_wt = CoinWalletTransaction::insertGetId([
                    'coin_wallet_id'=> $coin_wallet['coin_wallet_id'],
                    'app_user_id'=> $app_user_id,
                    'coin_wallet_type'=> 1,
                    'coin_wallet_trans_status'=> 2,
                    'coin_wallet_amount'=> $c_am,
                    'coin_wallet_remark'=> 'Contest joining fee of '.$contest['contest_name']
                ]);

            }else{
                DB::rollback();
                $data = array(
                    "status"=>'error',
                    "message"=>"Don't Have enough coins to join contest",
                    "data"=> 0
                );
                return response()->json($data,$this->errorStatus);
            }
        }else{
            DB::rollback();
            $data = array(
                "status"=>'error',
                "message"=>"Don't Have enough coins to join contest or Contest Not Present",
                "data"=> 0
            );
            return response()->json($data,$this->errorStatus);
        }

        DB::commit();
        $data = array('status'=>'success','message'=>'Cash Deducted For Contest Entry Fee');
        return response()->json($data,$this->successStatus);
    }

    public function banner_setting(){
        $banner = Banner::get()->toArray();
        $data = array('status'=>'success','message'=>'Banner setting','data'=>$banner);
        return response()->json($data,$this->successStatus);
    
    }

    public function pop_up_setting(){
        $popup = Popup::get()->toArray();
        $data = array('status'=>'success','message'=>'Popup setting','data'=>$popup);
        return response()->json($data,$this->successStatus);
    
    }
}
