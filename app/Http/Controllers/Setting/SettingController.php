<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppUsers;
use App\Models\Status;
use App\Models\Banner;
use App\Models\Popup;
use Auth;
use Illuminate\Support\Facades\File;
use DB;
use PDF;
use Hash;

class SettingController extends Controller
{
	public function banner_and_popup(){
		$banner = Banner::leftjoin('users','users.id','banner.updated_by')
			->select('banner.*','users.name as updated_by_name')->get()->first();
		$popup = Popup::leftjoin('users','users.id','popup_notification.updated_by')
			->select('popup_notification.*','users.name as updated_by_name')->get()->first();
		return view('setting/banner',['banner'=>$banner,'popup'=>$popup]);
	}

	public function banner_db(Request $request){
		try {
            $this->validate($request,[
                'display'=>'required',
                'upd_banner' => "required_if:old_banner,==,''|mimes:jpeg,png,jpg,gif,svg",

            ],[
                'display.required'=> 'This is required.',
                'upd_banner.required_if'=> 'This is required.',
                'upd_banner.mimes'=> 'Field accept only jpeg,png,jpg,gif,svg format.',
            ]);

            $id = $request->input('banner_id');
            $display = $request->input('display');

            DB::beginTransaction();
            
            $timestamp = date('Y-m-d H:i:s');

            $banner = '';
            $file = $request->file('upd_banner');
            if(isset($file) || $file != null){
                $destinationPath = public_path().'/upload/admin_profile/';
                $filenameWithExt = $request->file('upd_banner')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('upd_banner')->getClientOriginalExtension();
                $banner = $filename.'_'.time().'.'.$extension;
                $path = $file->move($destinationPath, $banner);
                File::delete($destinationPath.$request->input('old_banner'));
            }else{
                $banner = $request->input('old_banner');
            }

            if($id != null && $id != ''){
                $upd = Banner::where('id',$id)->update([
                    'banner_img'=>$banner,
                    'display'=>$display,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if(!$upd){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');

                }
            }else{
                $ins = Banner::insertGetId([
                    'banner_img'=>$banner,
                    'display'=>$display,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if($ins ==0){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');
                    
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
           return redirect('/setting/banner/popup')->with('error','some error occurred'.$ex->getMessage());  
        }

        DB::commit();
        return redirect('/setting/banner/popup')->with('success','Banner Setting Updated Successfully');  
    
	}

	public function popup_db(Request $request){
		try {
            $this->validate($request,[
                'display'=>'required'
            ],[
                'display.required'=> 'This is required.'
            ]);

            $id = $request->input('popup_id');
            $display = $request->input('display');

            DB::beginTransaction();
            
            $timestamp = date('Y-m-d H:i:s');

            
            if($id != null && $id != ''){
                $upd = Popup::where('id',$id)->update([
                    'display'=>$display,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if(!$upd){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');

                }
            }else{
                $ins = Popup::insertGetId([
                    'display'=>$display,
                    'updated_by'=>Auth::id(),
                    'last_updated_at'=>$timestamp
                ]);

                if($ins ==0){
                    DB::rollback();
                    return back()->with('error','Something Went Wrong!!');
                    
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
           return redirect('/setting/banner/popup')->with('error','some error occurred'.$ex->getMessage());  
        }

        DB::commit();
        return redirect('/setting/banner/popup')->with('success','Popup Setting Updated Successfully');  
    
	}
}