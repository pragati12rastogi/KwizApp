<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Route;
use DB;
use Response;
class RoleRights
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        $user_id = Auth::id();
        $userAlloweds = [];
        
        $user = Auth::user();
        $user_type = $user['role_id'];

        $current_url = '/'.preg_replace('/{[a-zA-Z0-9_]*}/','*',Route::getFacadeRoot()->current()->uri());
        $userSections = \App\Models\SectionRights::leftjoin('role_section_rights',function($join){ 
                $join->on('section_rights.id','=','role_section_rights.section_id');
                    })
        ->where('role_section_rights.role_id', $user_type)
        ->where(function ($query) use ($current_url) {
            $query->where('show_menu','=',1)
            ->orwhere('section_rights.link','=',$current_url);
            });

        $userSections = $userSections->select('section_rights.*','role_section_rights.section_id')
        ->orderBy('section_rights.show_order')
        ->orderBy('section_rights.id')
        ->get()->toarray();

        foreach($userSections as $userSection){
            
            // if($userSection['link']==$current_url){    
            //     $userAlloweds['section'] =  explode(',',$userSection['allowed']);
            // }        
            // $key = $userSection['name'].$userSection['pid'];
            if($userSection['pid']==0)
                $layout[$userSection['id']] = $userSection;
            else
                $layout[$userSection['pid']]['child'][] = $userSection;
        }
        if($current_url=="//")
        {
            return redirect()->intended('/home');
        }
    
        if(empty($userAlloweds['section']) && $user_type!=1)
        {   
            return abort(403,'You are not authorised to access this page.');
        }

        $layout = $this->buildTree($userSections);
        $userAlloweds['layout'] = $layout;
        $request->merge(compact('userAlloweds'));
        return $next($request);
    }
    

    
    public function buildTree(array &$elements, $parentId = 0) {

        $branch = array();    
        foreach ($elements as $element) {
            if($element['show_menu']==1)
            {
                if ($element['pid'] == $parentId) {
                    $children = $this->buildTree($elements, $element['id']);
                    if ($children) {
                        $element['child'] = $children;
                    }
                    $branch[$element['id']] = $element;
                }
            }
        }
        return $branch;
    }
}
