<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
        Auth::logout();
        return redirect('/login');
    }

    public function send_otp(Request $request){
        $otp = 999999;
        $phone = $request->input('phone');

        $check_valid = User::where('phone',$phone)->where('status_id',2)->get()->first();
        if($check_valid == null || $check_valid == ''){
            $data = array('status'=>'error',
                'message'=>'Sorry, You are not a user.'
            );
            return response()->json($data, 401);
        }else{

            $update = User::where('phone',$phone)->where('status_id',2)->update([
                'otp_code'=>$otp,
                'updated_at'=>date('Y-m-d H:i:s')
            ]);

            if($update != 0){
                $data = array('status'=>'success','message'=>'OTP Send Successfully');
                return response()->json($data, 200);
            }else{
                $data = array('status'=>'error','message'=>'Something Went Wrong');
                return response()->json($data, 200);
            }
        }
        
    }

    public function username()
    {
        return 'phone';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'otp' => 'required|string',
        ]);
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'otp_code');
    }

    
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->credentials($request);
        $userdata = User::where('phone',$credentials['phone'])
            ->where('otp_code',$request->input('otp'))
            ->where('status_id',2)
            ->first();
        
        
        if(isset($userdata->id) )
        {
            Auth::login($userdata,true);
            $update = User::where('phone',$credentials['phone'])->update([
                'otp_code'=>null
            ]);
            return redirect('/home');
        } 
        else {
            $this->incrementLoginAttempts($request);
            throw ValidationException::withMessages([
                $this->username() => ['Permission denied, Wrong Credentials Inserted']
            ]);
        }
    }
    protected function authenticated()
    {
        if(Auth::user()->status_id != 2){
            Auth::logout();
            return redirect('/login')->with('error','Your Account is Deleted');
        }
        
    }
    
}
