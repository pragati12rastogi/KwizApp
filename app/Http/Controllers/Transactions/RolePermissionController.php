<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Status;
use App\Models\Roles;
use App\Models\SectionRights;
use App\Models\RoleSectionRights;
use Auth;
use Illuminate\Support\Facades\File;
use DB;
use Hash;
use App\Custom\CustomHelpers;
class RolePermissionController extends Controller
{
	public function role_permission(){
		$role = Roles::get()->toArray();
		$section= SectionRights::where('pid',0)->get()->toArray();
		$selected = (count($role)>0)?$role[0]:null;
		return view('layouts/role_permission',compact('role','section','selected'));
	}

	public function get_section_name(Request $request){
		$role = $request->input('role');
		$section = SectionRights::where('section_rights.pid',0)->get()->toArray();
			
		foreach ($section as $key => &$value2) {
			$id = $value2['id'];
			$role_permission = RoleSectionRights::where('section_id',$id)
			->where('role_id',$role)->get()->first();
			
			if(!empty($role_permission)){
				$value2['check'] = 1;
			}else{
				$value2['check'] = 0;
			}
		};

		echo json_encode($section);
	}

	public function role_permission_db(Request $request){
		try {
			$role = $request->input('role');
			$section = $request->input('section');
			
			DB::beginTransaction();
			$section_rights = SectionRights::whereIn('section_rights.id',$section)
				->orwhereIn('section_rights.pid',$section)->get()->toArray();
			
			$menudata = array_column($section_rights, 'id');

			RoleSectionRights::where('role_id','=',$role)
                    ->whereNotIn('section_id',$menudata)
                    ->delete();

            $added_section_rights = RoleSectionRights::where('role_id','=',$role)->get('section_id')->toArray();
            $array = array_column($added_section_rights, 'section_id');
            
			foreach ($section_rights as $index => $data) {
				$insert_array=[];
				if(!in_array($data['id'],$array))
                {
                    $insert_array['section_id'] = $data['id'];
                    $insert_array['role_id'] = $role;
                    $insert_data[] = $insert_array;
                }
			}
			if(!empty($insert_data))
                $userlayout = RoleSectionRights::insert($insert_data);
		}
		catch (\Illuminate\Database\QueryException $ex) {
	        return redirect('/admin/role/management')->with('error','some error occurred'.$ex->getMessage());
	    }
	    DB::commit();
        return redirect('/admin/role/management')->with('success','Permission has been set successfully.'); 
        
	}
}