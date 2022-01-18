<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppUsers;
use App\Models\Winnings;
use App\Models\Contest;
use App\Models\Status;
use Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Hash;

use App\Custom\CustomHelpers;
use App\Exports\DataExportSheet;
use App\Exports\DataExport;

class UserManagementController extends Controller
{
    public function user_management_listing(){
        return view('appUserManagement/userManagementListing');
    }

    public function user_management_listing_api(Request $request){
        $search = $request->input('search');
        $search_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $user_detail = AppUsers::where('app_users.status_id','<>',4)
        ->leftjoin('status','status.id','app_users.status_id')
        ->leftjoin('cash_wallet','cash_wallet.app_user_id','app_users.app_user_id')
        ->leftjoin('coin_wallet','coin_wallet.app_user_id','app_users.app_user_id')
        ->select('app_users.app_user_id',
            'app_users.email',
            'app_users.phone',
            'app_users.dob',
            'app_users.profile_pic',
            DB::raw('(Case When (app_users.register_type = 1) then "Traditional"
                 When (app_users.register_type = 2) then "Facebook" 
                 When (app_users.register_type = 3) then "Google" End ) as register_type_name'),
            DB::raw('(Case When (app_users.is_verified = 1) then "Yes" Else "No" End ) as verified'),
            'app_users.full_name',
            'cash_wallet.cash_wallet_balance',
            'coin_wallet.coin_wallet_balance',
            'status.status',
            'app_users.refer_code'
        );

        if(!empty($search_value))
        {
            $user_detail = $user_detail->where('app_users.full_name','LIKE',"%".$search_value."%")
                        ->orwhere('app_users.phone','LIKE',"%".$search_value."%")
                        ->orwhere('app_users.email','LIKE',"%".$search_value."%")
                        ;
        }

        $count = $user_detail->count();
        $user_detail = $user_detail->offset($offset)->limit($limit);

        if(isset($request->input('order')[0]['column'])){
            $data = ['app_users.app_user_id','app_users.full_name','app_users.dob','app_users.email','app_users.phone','profile_pic','refer_code','verified','register_type_name'
            ];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $user_detail->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else
        {
            $user_detail->orderBy('app_users.app_user_id','desc');
        }
        $user_detaildata = $user_detail->get()->toArray();
        
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $user_detaildata; 
        return json_encode($array);
  
    }

    public function user_management_create(){
        $status = Status::get()->toArray();
        return view('appUserManagement/user_create',['status'=>$status]);
    }

    public function user_management_create_db(Request $request){
        try {

            $this->validate($request,[
                'full_name'=>'required',
                'dob'=>'required',
                'email'=>'required_without:phone|email|unique:app_users',
                'phone'=>'required_without:email|unique:app_users',
                'profile_picture' => 'mimes:jpeg,png,jpg,gif,pdf,svg',
                'status'=>'required'
            ],[
                'full_name.required'=> 'This is required.',
                'last_name.required'=> 'This is required.',
                'dob.required'=> 'This is required.',
                'email.required_without'=> 'Please Enter Phone or Email.',
                'phone.required_without'=> 'Please Enter Phone or Email.',
                'phone.regex'=> 'Phone Number contains digits only.',
                'profile_picture.mimes'=>'Field accept only jpeg,png,jpg,pdf format',
                'status.required'=>'This is required'
            ]);

            DB::beginTransaction();

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

            if(!empty($request->input('phone'))){
                if(!preg_match("/^[0-9]{3}[0-9]{3}[0-9]{4}$/", $request->input('phone'))) {
                  DB::rollback();
                  return back()->with('error','Please Enter Correct Phone Number')->withInput();
                }
            }
            
            $random = CustomHelpers::quickAlphaRandom(4);
            $check_user = AppUsers::get()->toArray();
            $unique_no = count($check_user)+1;
            $refer_code = "Kwiz-".$random.$unique_no;
            $timestamp = date('Y-m-d G:i:s');
             
            $password = CustomHelpers::quickRandom(8);
            $ins = ['full_name'=> $request->input('full_name'),
                'dob'=> date("Y-m-d",strtotime($request->input('dob'))),
                'email'=> $request->input('email'),
                'phone'=> $request->input('phone'),
                'refer_code'=>$refer_code,
                'password'=>$password,
                'profile_pic' => $profile,
                'status_id'=> $request->input('status'),
                'created_at' => $timestamp
            ];
            
            $employee_insert = AppUsers::insertGetId($ins);
            
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/app/user/management/create')->with('error','some error occurred'.$ex->getMessage());
        }

        if($employee_insert){
            DB::commit();
            return redirect('/app/user/management/create')->with('success','App User Created Successfully');
        }
    }
    

    public function user_management_delete(Request $request, $id){
        try {
            
            DB::beginTransaction();
            if($id){
                $del = AppUsers::where('app_user_id',$id)->update([
                    'status_id'=>4
                ]);

                if($del){
                    DB::commit();
                    return redirect('/app/user/management')->with('success','App User Deleted Successfully');
                }

            }else{
                DB::rollback();
                return redirect('/app/user/management')->with('error','Some Error Occurred, No Data found.');
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/app/user/management')->with('error','some error occurred'.$ex->getMessage());
        }
    }

    public function user_management_Update(Request $request, $id){
        $user = AppUsers::where('app_user_id',$id)->first();
        $status = Status::get()->toArray();
        return view('appUserManagement/user_update',['user'=>$user,'status'=>$status]);
    }

    public function user_update_db(Request $request,$id){
        try {

            $this->validate($request,[
                'full_name'=>'required',
                'dob'=>'required',
                'email'=>'required_without:phone|email|unique:app_users,email,'.$id.',app_user_id',
                'phone'=>'required_without:email|unique:app_users,phone,'.$id.',app_user_id',
                'upd_user_photo' => 'mimes:jpeg,png,jpg,gif,pdf,svg',
                'status'=>'required'
            ],[
                'full_name.required'=> 'This is required.',
                'dob.required'=> 'This is required.',
                'email.required_without'=> 'Please Enter Phone Or Email.',
                'phone.required_without'=> 'Please Enter Phone Or Email',
                'upd_user_photo.mimes'=>'Field accept only jpeg,png,jpg,pdf format',
                'status.required'=>'This is required'
            ]);


            DB::beginTransaction();

            if(!empty($request->input('phone'))){
                if(!preg_match("/^[0-9]{3}[0-9]{3}[0-9]{4}$/", $request->input('phone'))) {
                  DB::rollback();
                  return back()->with('error','Please Enter Correct Phone Number')->withInput();
                }
            }

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
                'dob'=> date("Y-m-d",strtotime($request->input('dob'))),
                'email'=> $request->input('email'),
                'phone'=> $request->input('phone'),
                'profile_pic' => $profile,
                'status_id'=> $request->input('status')
            ];
            $employee_update = AppUsers::where('app_user_id',$id)->update($upd);
            
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/app/user/management/update/'.$id)->with('error','some error occurred'.$ex->getMessage());
        }

        if($employee_update){
            DB::commit();
            return redirect('/app/user/management/update/'.$id)->with('success','App User Details Updated Successfully');
        }
    }

    public function app_user_view($id){
        $app_user = AppUsers::where('app_users.app_user_id',$id)->leftjoin('status','status.id','app_users.status_id')
        ->leftjoin('cash_wallet','cash_wallet.app_user_id','app_users.app_user_id')
        ->leftjoin('coin_wallet','coin_wallet.app_user_id','app_users.app_user_id')
        ->select('app_users.app_user_id',
            'app_users.email',
            'app_users.phone',
            'app_users.dob',
            'app_users.profile_pic',
            DB::raw('(Case When (app_users.register_type = 1) then "Traditional"
                 When (app_users.register_type = 2) then "Facebook" 
                 When (app_users.register_type = 3) then "Google" End ) as register_type_name'),
            DB::raw('(Case When (app_users.is_verified = 1) then "Yes" Else "No" End ) as verified'),
            'app_users.full_name',
            'status.status',
            'cash_wallet.cash_wallet_balance',
            'coin_wallet.coin_wallet_balance',
            'app_users.refer_code'
        )->first();

        return view('appUserManagement/app_user_view',['users'=>$app_user]);
    }
    
    public function export_appuser(){
        $column = ['app_user_id'=>'Id','full_name'=>'Name','email'=>'Email','phone'=>'Phone','created_at'=>'Date Of Joining','cash_wallet_balance'=>'Total Cash Earned','coin_wallet_balance'=>'Total Coin Earned','redeem_cash' => 'Total Cash Redeem','redeem_coin'=> 'Total Coin Redeem','referral_earning'=>'Referral Earning','daily_bonus_sum'=>'Daily Bonus','contest_wise'=>'Contest Wise Earning','quiz_wise'=>'Quiz Wise Earning'
        ];
        return view('export/app_user_export',['columns'=>$column]);
    }

    public function export_appuser_db(Request $request,$column=[]){
        $this->validate($request,[
            'user_type'=>'required',
            'time_report'=>'required'
        ],[
            'user_type.required'=>'This is required',
            'time_report.required'=>'This is required'
        ]);

        $user_type = $request->input('user_type');
        $time_report = $request->input('time_report');
        // print_r($request->input('columns_in_excel'));die();
        
        $outcolumn =[];
        $outcolumn1 = [
            'app_user_id'=>'Id','full_name'=>'Name','email'=>'Email','phone'=>'Phone','created_at'=>'Date Of Joining','cash_wallet_balance'=>'Total Cash Earned','coin_wallet_balance'=>'Total Coin Earned','redeem_cash' => 'Total Cash Redeem','redeem_coin'=> 'Total Coin Redeem','referral_earning'=>'Referral Earning','daily_bonus_sum'=>'Daily Bonus','contest_wise'=>'Contest Wise Earning','quiz_wise'=>'Quiz Wise Earning'
        ];

        
        $column =['app_users.app_user_id','app_users.full_name','app_users.email','app_users.phone','app_users.created_at','cash_wallet.cash_wallet_balance','coin_wallet.coin_wallet_balance',DB::raw('sum(debit_cash.cash_wallet_amount) as redeem_cash'),DB::raw('sum(debit_coin.coin_wallet_amount) as redeem_coin'),DB::raw('sum(user_references.bonus_amount) as referral_earning'),DB::raw('sum(daily_bonus.coin_wallet_amount) as daily_bonus_sum')];

        $app_user = AppUsers::where('status_id','<>',4)
            ->leftjoin('cash_wallet','cash_wallet.app_user_id','app_users.app_user_id')
            ->leftjoin('coin_wallet','coin_wallet.app_user_id','app_users.app_user_id')
            ->leftjoin('cash_wallet_transaction as debit_cash',function($join){
                $join->on('debit_cash.app_user_id','app_users.app_user_id')->where('debit_cash.cash_wallet_type',1)
                ;
            })
            ->leftjoin('user_references','user_references.app_user_id','app_users.app_user_id')
            ->leftjoin('coin_wallet_transaction as daily_bonus',function($join){
                $join->on('daily_bonus.app_user_id','app_users.app_user_id')->where('daily_bonus.coin_wallet_type',2)->where('daily_bonus.coin_wallet_remark','LIKE','%Daily Joining Bonus%')
                ;
            })
            ->leftjoin('coin_wallet_transaction as debit_coin',function($join){
                $join->on('debit_coin.app_user_id','app_users.app_user_id')->where('debit_coin.coin_wallet_type',1)
                ;
            })
            ->groupBy('app_users.app_user_id','app_users.status_id','app_users.refer_code','app_users.created_at','app_users.updated_at','cash_wallet.cash_wallet_id','cash_wallet.app_user_id','cash_wallet.created_at','cash_wallet.updated_at','coin_wallet.coin_wallet_id','coin_wallet.app_user_id'
                ,'coin_wallet.updated_at','coin_wallet.created_at','app_users.full_name','app_users.login_timestamp','app_users.email','app_users.phone','cash_wallet.cash_wallet_balance','coin_wallet.coin_wallet_balance');


        if($user_type == 'total_user'){
            if($time_report == 'today'){

                $app_user = $app_user->whereRaw('app_users.created_at = CURDATE()');

            }elseif($time_report == 'yesterday'){

                $app_user = $app_user->whereRaw('app_users.created_at = DATE_SUB(CURDATE(), INTERVAL 1 DAY)');

            }elseif($time_report == 'last7day'){

                $app_user = $app_user->whereRaw('app_users.created_at BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) and CURDATE()');

            }elseif($time_report == 'last30day'){

                $app_user = $app_user->whereRaw('app_users.created_at BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) and CURDATE()');

            }elseif($time_report == 'currentweek'){

                $app_user = $app_user->whereRaw('WEEKOFYEAR(app_users.created_at) = WEEKOFYEAR(NOW())');

            }elseif($time_report == 'currentmonth'){
                
                $app_user = $app_user->whereRaw('YEAR(app_users.created_at) = YEAR(NOW()) and MONTH(app_users.created_at) = MONTH(NOW())');

            }elseif($time_report == 'lastmonth'){

                $app_user = $app_user->whereRaw('YEAR(app_users.created_at) = YEAR(DATE_SUB(CURDATE(), INTERVAL 30 DAY)) and MONTH(app_users.created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL 30 DAY))');
            }

        }elseif($user_type == 'online_user'){
            $app_user = $app_user->where('app_users.login_timestamp','<>',null);
            if($time_report == 'today'){

                $app_user = $app_user->whereRaw('app_users.login_timestamp = CURDATE()');

            }elseif($time_report == 'yesterday'){

                $app_user = $app_user->whereRaw('app_users.login_timestamp = DATE_SUB(CURDATE(), INTERVAL 1 DAY)');

            }elseif($time_report == 'last7day'){

                $app_user = $app_user->whereRaw('app_users.login_timestamp BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) and CURDATE()');

            }elseif($time_report == 'last30day'){

                $app_user = $app_user->whereRaw('app_users.login_timestamp BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) and CURDATE()');

            }elseif($time_report == 'currentweek'){

                $app_user = $app_user->whereRaw('WEEKOFYEAR(app_users.login_timestamp) = WEEKOFYEAR(NOW())');

            }elseif($time_report == 'currentmonth'){
                
                $app_user = $app_user->whereRaw('YEAR(app_users.login_timestamp) = YEAR(NOW()) and MONTH(app_users.login_timestamp) = MONTH(NOW())');

            }elseif($time_report == 'lastmonth'){

                $app_user = $app_user->whereRaw('YEAR(app_users.login_timestamp) = YEAR(DATE_SUB(CURDATE(), INTERVAL 30 DAY)) and MONTH(app_users.login_timestamp) = MONTH(DATE_SUB(CURDATE(), INTERVAL 30 DAY))');
            }
        }

        
        $app_user=$app_user->select($column)->get()->toArray();

        // return Excel::download(new DataExport($app_user,$outcolumn,'App Users'), $user_type.'.xlsx');
        foreach ($app_user as $key => &$value) {
            $contest_winnings = Contest::leftjoin('winnings','contest.contest_id','winnings.contest_id')
                ->where('winnings.contest_id','<>',null)->select(DB::raw('(Concat(contest.contest_name,"=",sum(winnings.amount_rewarded),","))as contest_wise'))
                ->where('winnings.app_user_id',$value['app_user_id'])
                ->groupBy('contest.contest_id','contest.contest_name')
                ->get()->first();

            if(isset($contest_winnings['contest_wise'])){
                $value['contest_wise']=$contest_winnings['contest_wise'];
            }else{
                $value['contest_wise']=0;
            }
            
            
           $quiz_winnings = Winnings::leftjoin('quiz_group','quiz_group.group_id','winnings.quiz_group_id')
                ->where('winnings.quiz_group_id','<>',null)->select(DB::raw('Concat(IfNUll(quiz_group.quiz_title,""),"=",(sum(winnings.amount_rewarded)),",") as quiz_wise'),'quiz_group.quiz_title',DB::raw('(sum(winnings.amount_rewarded)) as total'))
                ->where('winnings.app_user_id',$value['app_user_id'])
                ->groupBy('quiz_group.group_id','quiz_group.quiz_title')
                ->get()->first();
            
            if(isset($quiz_winnings['quiz_wise'])){
                $value['quiz_wise']=$quiz_winnings['quiz_wise'];
            }else{
                $value['quiz_wise']=0;
            }
            // $value['quiz_wise']=$quiz_winnings['quiz_wise'];
            // if(count($quiz_winnings)>0){
            //     foreach ($quiz_winnings as $index => $quiz_detail) {
            //         $value[$quiz_detail['quiz_title']]=$quiz_detail['total'];
            //     }
            // } 
        }

        if(!empty($request->input('columns_in_excel'))){
            $column = $request->input('columns_in_excel');
            foreach($column as $k){
                $outcolumn =array_merge($outcolumn,array($outcolumn1[$k]));

            }
        }else{
            $outcolumn = ['Id','Name','Email','Phone','Total Cash Earned','Total Coin Earned','Total Cash Redeem','Total Coin Redeem','Referral Earning','Daily Bonus','Contest Wise Earning','Quiz Wise Earning'];

        }
        
        $file_name = $user_type.'_'.strtotime('now').'.csv';
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=".$file_name);
        $fp = fopen('php://output', 'w');
       
        if ($fp) {
            
            $tmpArray = $outcolumn;
            fputcsv($fp, $tmpArray);
            
            
            foreach($app_user as $key => $data) {
                
                $tmpArray = [];
                foreach ($column as $ind => $col_name) {
                       $tmpArray[]=$data[$col_name];
                   }    
                                   
                fputcsv($fp, $tmpArray);
            }
            
        }   
        fclose($fp);
        

    }

}