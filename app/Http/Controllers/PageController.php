<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\File;
use App\Models\PageMaster;
use Auth;
use DB;
use Hash;
class PageController extends Controller
{
    public function create_page($title)
    {
        if($title == ''){
            return view('404');
        }
        $get_data = PageMaster::where('page_name',$title)->get()->first();
        
        return view('pages/create_page',['title'=>$title,'page_detail'=>$get_data]);
    }
    public function create_page_db(Request $request)
    {
        
        try {
            $this->validate($request,[
                'page_name'=>'required',
                'editor1'=>'required',
            ],[
                'page_name.required'=> 'This is required.',
                'editor1.required'=> 'This is required.'
            ]);
            DB::beginTransaction();

            $title = $request->input('page_name');
            $get_data = PageMaster::where('page_name',$title)->get()->first();
        
            $timestamp = date('Y-m-d H:i:s');
            if($get_data){
                $page_upd = PageMaster::where('page_name',$title)->update([
                    'content'=>$request->input('editor1'),
                    'updated_at'=>$timestamp
                ]);
            }else{
                $page = PageMaster::insertGetId([
                    'page_name'=>$title,
                    'content'=>$request->input('editor1'),
                    'created_at'=>$timestamp
                ]);
                if($page == 0){
                    DB::rollback();
                    return back()->with('error','Some error Occurred');
                }
            }
            
            
        } catch (Illuminate\Database\QueryException $ex) {
            return redirect('/create/required/page/'.$title)->with('error','some error occurred'.$ex->getMessage());
        }
        DB::commit();
        return redirect('/create/required/page/'.$title)->with('success','Page Content Uploaded Successfully');
    }

    /*public function page_summary()
    {
        return view('pages/page_summary');
    }

    public function page_summary_api(Request $request)
    {
        $search = $request->input('search');
        $search_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $user_detail = PageMaster::select('page_master.*');

        if(!empty($search_value))
        {
            $user_detail = $user_detail->where('page_name','LIKE',"%".$search_value."%")
                        ;
        }

        $count = $user_detail->count();
        $user_detail = $user_detail->offset($offset)->limit($limit);

        if(isset($request->input('order')[0]['column'])){
            $data = ['page_id','page_name','content'
            ];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $user_detail->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else
        {
            $user_detail->orderBy('page_id','desc');
        }
        $user_detaildata = $user_detail->get()->toArray();
        
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $user_detaildata; 
        return json_encode($array);
    }*/
}
