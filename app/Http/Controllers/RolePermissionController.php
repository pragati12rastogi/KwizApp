<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
		$section= SectionRights::where('pid',0)->where('show_menu',1)->get()->toArray();

		$selected = (count($role)>0)?$role[0]:null;
		return view('pages/role_permission',compact('role','section','selected'));
	}

	public function get_section_name(Request $request){
		$role = $request->input('role');
		
		$menudata = SectionRights::leftJoin('role_section_rights',function($join) use ($role){
                $join->on(DB::raw('role_section_rights.role_id = '.$role.' and section_rights.id'),'=','role_section_rights.section_id');
            })
            ->where('show_permission','=',1)
            ->select(['section_rights.*','role_section_rights.role_id'])
            ->orderBy('show_order')
            ->get()->toarray();

        $menudata = CustomHelpers::menuTree($menudata);
        
		echo json_encode($menudata);
	}

	public function role_permission_db(Request $request){
		try {
			$role = $request->input('role');
			$section = $request->input('menu');
		
			DB::beginTransaction();
			$section_rights = SectionRights::whereIn('section_rights.id',$section)
				->orwhereIn('section_rights.permission_pid',$section)->get()->toArray();
			
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