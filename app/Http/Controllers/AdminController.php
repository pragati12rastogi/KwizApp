<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\File;
use App\Models\Roles;
use Auth;
use DB;
use Hash;
class AdminController extends Controller
{
    public function profile_update()
    {
        $user = User::where('id',Auth::id())->first();
        return view('profile_update',['user'=>$user]);
    }

    public function profile_update_db(Request $request){
        try {

            $user_id = Auth::id();
            $this->validate($request,[
                'name'=>'required',
                'email'=>'required|unique:users,email,'.$user_id.',id',
            ],[
                'name.required'=> 'This is required.',
                'email.required'=> 'This is required.'   
            ]);

            DB::beginTransaction();
            $name=$request->input('name');
            $email=$request->input('email');
            
            $update = User::where('id',$user_id)->update([
                'name'=>$name,
                'email'=>$email
            ]);
            
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/profile/update')->with('error','some error occurred'.$ex->getMessage());
        }

        if($update){
            DB::commit();
            return redirect('/profile/update')->with('success','Profile Details Updated');
        }
    }
    public function profile_pass_update_db(Request $request){
        try {
            $user_id = Auth::id();
            $this->validate($request,[
                'current_password'=>'required',
                'new_password'=>'required|min:8',
                'password_confirmation'=>'required|same:new_password'
            ],[
                'current_password.required'=> 'This is required.',
                'new_password.required'=> 'This is required.' ,  
                'new_password.min'=> 'Minimum length is 8 character.' ,  
                'password_confirmation.required'=> 'This is required.',
                'password_confirmation.same'=> 'Password not matched.'   
            ]);

            DB::beginTransaction();
            
            $current_pass = $request->input('current_password');
            $new_pass = $request->input('new_password');
            $confirm_pass = $request->input('password_confirmation');

            $user = User::where('id',$user_id)->first();
            if(!Hash::check($current_pass,$user['password'])){
                DB::rollback();
                return back()->with('error','The specified password does not match your old password');
            }else{
                
                $update = User::where('id',$user_id)->update([
                    'password'=>Hash::make($confirm_pass),
                ]);
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/profile/update')->with('error','some error occurred'.$ex->getMessage());
        }
        if($update){
            DB::commit();
            return redirect('/profile/update')->with('success','Password Updated Successfully');
        }
    }

    public function admin_create(){
        $role = Roles::get()->toArray();
        return view('admin/admin_create',['roles'=>$role]);
    }

    public function admin_create_db(Request $request){
        try {

            $this->validate($request,[
                'name'=>'required',
                'email'=>'required|email|unique:users',
                'phone'=>'required|unique:users',
                'role'=>'required',
                /*'password'=>'required|min:8',
                'confirm_pass'=>'required|same:password',*/
                'profile_picture' => 'mimes:jpeg,png,jpg,gif,pdf,svg'
            ],[
                'name.required'=> 'This is required.',
                'email.required'=> 'This is required.',
                'phone.required'=> 'This is required.',
                'role.required'=>'This is required',
                /*'password'=>'This is required',
                'password.min'=> 'Minimum length is 8 character.' ,  
                'confirm_pass.required'=> 'This is required.',
                'confirm_pass.same'=> 'Password not matched.',*/
                'profile_picture.mimes'=>'Field accept only jpeg,png,jpg,pdf format'
            ]);
            DB::beginTransaction();

            if(!preg_match("/^[0-9]{3}[0-9]{3}[0-9]{4}$/", $request->input('phone'))) {
                DB::rollback();
                return back()->with('error','Please Enter Correct Phone Number')->withInput();
            }

            $profile = '';
            $file = $request->file('profile_picture');
            if(isset($file) || $file != null){
                $destinationPath = public_path().'/upload/admin_profile/';
                $filenameWithExt = $request->file('profile_picture')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('profile_picture')->getClientOriginalExtension();
                $profile = $filename.'_'.time().'.'.$extension;
                $path = $file->move($destinationPath, $profile);
            }else{
                $profile = '';
            }

            $timestamp = date('Y-m-d G:i:s');
            $password = Hash::make(12345678);
            $ins = ['name'=> $request->input('name'),
                'email'=> $request->input('email'),
                'phone'=> $request->input('phone'),
                'role_id'=> $request->input('role'),
                // 'password'=>$password,
                'profile_picture'=>$profile,
                'created_at' => $timestamp
            ];
            
            $insert = User::insertGetId($ins);
            
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/admin/user/create')->with('error','some error occurred'.$ex->getMessage());
        }

        if($insert){
            DB::commit();
            return redirect('/admin/user/create')->with('success','Admin User Created Successfully');
        }
    }

    public function admin_users_listing(){
        return view('admin/admin_user_listing');
    }

    public function admin_users_listing_api(Request $request){
        $search = $request->input('search');
        $search_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $user_detail = User::where('users.status_id','<>',4)
        ->leftjoin('roles','roles.role_id','users.role_id')
        ->select('users.id',
            'users.name',
            'users.email',
            'users.phone',
            'roles.role_name',
            'users.profile_picture'
        );

        if(!empty($search_value))
        {
            $user_detail = $user_detail->where('users.name','LIKE',"%".$search_value."%")
                        ->orwhere('users.email','LIKE',"%".$search_value."%")
                        ->orwhere('users.phone','LIKE',"%".$search_value."%")
                        ->orwhere('roles.role_name','LIKE',"%".$search_value."%")
                        ;
        }

        $count = $user_detail->count();
        $user_detail = $user_detail->offset($offset)->limit($limit);

        if(isset($request->input('order')[0]['column'])){
            $data = ['users.id','users.name','users.email','users.phone','roles.role_name'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $user_detail->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else
        {
            $user_detail->orderBy('users.id','desc');
        }
        $user_detaildata = $user_detail->get()->toArray();
        
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $user_detaildata; 
        return json_encode($array);
  
    }

    public function admin_user_update($id){
        $role = Roles::get()->toArray();
        $user = User::where('id',$id)->first();
        return view('admin/admin_update',['roles'=>$role,'user'=>$user]);
    }

    public function admin_user_update_db(Request $request,$id){
        try {

            $this->validate($request,[
                'name'=>'required',
                'email'=>'required|email|unique:users,email,'.$id.',id',
                'phone'=>'required|unique:users,phone,'.$id.',id',
                'role'=>'required',
                'upd_user_photo' => 'mimes:jpeg,png,jpg,gif,pdf,svg'
            ],[
                'name.required'=> 'This is required.',
                'email.required'=> 'This is required.',
                'phone.required'=> 'This is required.',
                'role.required'=>'This is required',
                'upd_user_photo.mimes'=>'Field accept only jpeg,png,jpg,pdf format'
                ]);

            DB::beginTransaction();

            if(!preg_match("/^[0-9]{3}[0-9]{3}[0-9]{4}$/", $request->input('phone'))) {
                DB::rollback();
                return back()->with('error','Please Enter Correct Phone Number')->withInput();
            }

            $profile = '';
            $file = $request->file('upd_user_photo');
            if(isset($file) || $file != null){
                $destinationPath = public_path().'/upload/admin_profile/';
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
            $ins = ['name'=> $request->input('name'),
                'email'=> $request->input('email'),
                'phone'=> $request->input('phone'),
                'role_id'=> $request->input('role'),
                'profile_picture' =>$profile
            ];
            
            // if(!empty($request->input('password'))){
            //     $hash_pass = Hash::make($request->input('password'));
            //     $ins = array_merge($ins,array('password'=> $hash_pass));
            // }
            
            $insert = User::where('id',$id)->update($ins);
            
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/admin/user/update/'.$id)->with('error','some error occurred'.$ex->getMessage());
        }

        if($insert){
            DB::commit();
            return redirect('/admin/user/update/'.$id)->with('success','Admin User Updated Successfully');
        }   
    }

    public function admin_user_delete(Request $request, $id){
        try {

            DB::beginTransaction();
            if($id){
                $del = User::where('id',$id)->update([
                    'status_id'=>4
                ]);

                if($del){
                    DB::commit();
                    return redirect('/admin/user/list')->with('success','Admin User Deleted Successfully');
                }

            }else{
                DB::rollback();
                return redirect('/admin/user/list')->with('error','Some Error Occurred, No Data found.');
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/admin/user/list')->with('error','some error occurred'.$ex->getMessage());
        }
    }

    public function admin_user_view($id){
        $admin_user = User::where('users.id',$id)->leftjoin('roles','roles.role_id','users.role_id')
        ->leftjoin('status','status.id','users.status_id')
        ->select('users.*','roles.role_name','status.status')->get()->first();
        return view('admin/admin_user_view',['users'=>$admin_user]);
    }
}
