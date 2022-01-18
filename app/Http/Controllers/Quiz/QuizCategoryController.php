<?php

namespace App\Http\Controllers\Quiz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppUsers;
use App\Models\Status;
use App\Models\QuizCategory;
use App\Models\QuizGroupQues;
use App\Models\QuizQuesOptions;
use App\Models\CoinCurrency;
use App\Models\CashWallet;
use App\Models\QuizReward;
use App\Models\QuizGroup;
use Auth;
use Illuminate\Support\Facades\File;
use DB;
use PDF;
use Hash;
use App\Custom\CustomHelpers;

class QuizCategoryController extends Controller
{
    public function quiz_category_create(){
        return view('quiz/quiz_category_create');
    }

    public function quiz_category_create_db(Request $request){
        try {
            $this->validate($request,[
                'cat_name'=>'required',
                // 'cat_time'=>'required',
                'cat_icon'=>'required|mimes:jpeg,png,jpg,gif,svg'
            ],[
                'cat_name.required'=> 'This is required.',
                'cat_time.required'=> 'This is required.',
                'cat_icon.required'=> 'This is required.',
                'cat_icon.mimes'=>'Field accept only jpeg,png,jpg,svg format'
            ]);

            $cat_name = $request->input('cat_name');
            

            DB::beginTransaction();
            
            $cat_icon = '';
            $file = $request->file('cat_icon');
            if(isset($file) || $file != null){
                $destinationPath = public_path().'/upload/quiz_cat_icon/';
                $filenameWithExt = $request->file('cat_icon')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('cat_icon')->getClientOriginalExtension();
                $cat_icon = $filename.'_'.time().'.'.$extension;
                $path = $file->move($destinationPath, $cat_icon);
            }else{
                $cat_icon = '';
            }


            $quiz_insert = QuizCategory::insertGetId([
                'quiz_category_name'=>$cat_name,
                'quiz_category_icon'=>$cat_icon,
                // 'category_time'=>$request->input('cat_time'),
                'created_at'=>date('Y-m-d H:i:s')
            ]);

            if($quiz_insert == 0){
                DB::rollback();
                return back()->with('error','Some Error Occurred')->withInput();
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/quiz/category')->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/quiz/category')->with('success','Quiz Category Created Successfully');       
    }

    public function quiz_category_list(){
        return view('quiz/quiz_category_list'); 
    }

    public function quiz_category_list_api(Request $request){
        $search = $request->input('search');
        $search_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $quiz_list = QuizCategory::where('is_delete',0)
            ->leftjoin('quiz_group','quiz_group.quiz_category_id','quiz_category.quiz_category_id')
            ->select('quiz_category.quiz_category_id','quiz_category.quiz_category_name',
            'quiz_category.quiz_category_icon', 
            // DB::raw('Concat(quiz_category.category_time," ","min") as category_time'),
            DB::raw('Count(quiz_group.group_id) as quiz_count')
        )->groupBy('quiz_category.quiz_category_id','quiz_category.quiz_category_name','quiz_category.quiz_category_icon','quiz_category.category_time');

        if(!empty($search_value))
        {
            $quiz_list = $quiz_list->where('quiz_category.quiz_category_name','LIKE',"%".$search_value."%")
                        ;
        }

        $count = $quiz_list->count();
        $quiz_list = $quiz_list->offset($offset)->limit($limit);

        if(isset($request->input('order')[0]['column'])){
            $data = ['quiz_category_id','quiz_category_name','quiz_category_icon'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $quiz_list->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else
        {
            $quiz_list->orderBy('quiz_category.quiz_category_id','desc');
        }
        $quiz_listdata = $quiz_list->get()->toArray();
        
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $quiz_listdata; 
        return json_encode($array);
  
    }

    public function quiz_category_edit($id){
        $quiz_list = QuizCategory::where('quiz_category_id',$id)->select('quiz_category_id','quiz_category_name',
        'quiz_category_icon')->first();
        return View('quiz/quiz_category_edit',['cat'=>$quiz_list]);

    }

    public function quiz_category_edit_db(Request $request,$id){
        try {
            $this->validate($request,[
                'cat_name'=>'required',
                // 'cat_time'=>'required',
                'cat_icon'=>'mimes:jpeg,png,jpg,gif,svg'
            ],[
                'cat_name.required'=> 'This is required.',
                'cat_time.required'=> 'This is required.',
                'cat_icon.mimes'=>'Field accept only jpeg,png,jpg,svg format'
            ]);

            $cat_name = $request->input('cat_name');
            

            DB::beginTransaction();
            
            $cat_icon = '';
            $file = $request->file('cat_icon');
            if(isset($file) || $file != null){
                $destinationPath = public_path().'/upload/quiz_cat_icon/';
                $filenameWithExt = $request->file('cat_icon')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('cat_icon')->getClientOriginalExtension();
                $cat_icon = $filename.'_'.time().'.'.$extension;
                $path = $file->move($destinationPath, $cat_icon);
                File::delete($destinationPath.$request->input('old_cat_icon'));
            }else{
                $cat_icon = $request->input('old_cat_icon');
            }


            $quiz_update = QuizCategory::where('quiz_category_id',$id)->update([
                'quiz_category_name'=>$cat_name,
                'quiz_category_icon'=>$cat_icon,
                // 'category_time'=>$request->input('cat_time'),
                'updated_at'=>date('Y-m-d H:i:s')
            ]);

            if($quiz_update == 0){
                DB::rollback();
                return back()->with('error','Some Error Occurred')->withInput();
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/quiz/category/edit/'.$id)->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/quiz/category/edit/'.$id)->with('success','Quiz Category Updated Successfully');       
    }

    public function quiz_category_delete(Request $request,$id){
        if($id){
            DB::beginTransaction();
            $del = QuizCategory::where('quiz_category_id',$id)->update([
                'is_delete'=>1
            ]);

            if($del){
                DB::commit();
                return redirect('/quiz/category/list')->with('success','Category Deleted Successfully');
            }else{
                DB::rollback();
                return redirect('/quiz/category/list')->with('error','Something Went Wrong');
            }
        }else{
            return redirect('/quiz/category/list')->with('error','Category not found');
        }
        
    }
    
    public function quiz_category_view($id){
        $quiz_list = QuizCategory::where('quiz_category_id',$id)->select('quiz_category_id','quiz_category_name',
        'quiz_category_icon','category_time')->first();
        $quiz_group = QuizGroup::where('quiz_category_id',$id)->get()->toArray();

        return View('quiz/quiz_category_view',['cat'=>$quiz_list,'group'=>$quiz_group]);

    }

    public function category_question(){
        $get_coin_currency = CoinCurrency::get()->first();

        $get_category = QuizCategory::where('is_delete',0)->get()->toArray();
        return View('quiz/create_category_questions',['category'=>$get_category,'coin'=>$get_coin_currency]);
    }

    public function category_question_db(Request $request){
       
        try {
            $this->validate($request,[
                'cat_name'=>'required',
                'quiz_title'=>'required',
                'cat_ques.*'=>'required',
                'cat_option1.*'=>'required',
                'cat_option2.*'=>'required',
                'cat_option3.*'=>'required',
                'cat_option4.*'=>'required',
                'answer.*'=>'required',
                'point_question.*'=>'required',
                'time_p_ques.*'=>'required',
                // 'winner_no'=>'required',
                'quiz_total_time'=>'required'
            ],[
                'cat_name.required' => 'This is required',
                'quiz_title.required' => 'This is required',
                'cat_ques.*.required'=> 'This is required',
                'cat_option1.*.required' => 'This is required',
                'cat_option2.*.required'=> 'This is required',
                'cat_option3.*.required'=> 'This is required',
                'cat_option4.*.required'=> 'This is required',
                'answer.*.required'=>'This is required',
                'point_question.*.required'=>'This is required',
                'time_p_ques.*.required'=>'This is required',
                'winner_no.required'=>'This is required',
                'quiz_total_time.required'=>'This is required',

            ]);
            
            DB::beginTransaction();

            $quiz_title= $request->input('quiz_title');

            $cat_name = $request->input('cat_name');
            $cat_ques = $request->input('cat_ques');
            $cat_option1 = $request->input('cat_option1');
            $cat_option2 = $request->input('cat_option2');
            $cat_option3 = $request->input('cat_option3');
            $cat_option4 = $request->input('cat_option4');
            $answer = $request->input('answer');
            $point_question = $request->input('point_question');
            $time_p_ques = $request->input('time_p_ques');

            $quiz_total_time = $request->input('quiz_total_time');
            
            // $position = $request->input('position');
            // if (in_array("", $position))
            // {
            //     DB::rollback();
            //   return back()->with('error','Please fill blank Inputs')->withInput();
            // }

            // if(count($cat_ques) != count($answer)){
            //     db::rollback();
            //     return back()->with('error','Please Select answer for all Questions Inserted')->withInput();
            // }


            $timestamp = date('Y-m-d H:i:s');

            $group = QuizGroup::insertGetId([
                'quiz_category_id'=> $cat_name,
                'quiz_title'=> $quiz_title,
                'quiz_time'=> $quiz_total_time,
                'status_id'=> 2,
                'created_at'=>$timestamp
            ]);

            // foreach ($position as $position => $position_amount) {
            //     $ins = QuizReward::insertGetId([
            //         'quiz_id'=>$group,
            //         'position'=>($position+1),
            //         'position_amount'=>$position_amount,
            //         'created_at'=>$timestamp
            //     ]);

            //     if($ins == 0){
            //         db::rollback();
            //         return back()->with('error','Some Error Occoured')->withInput();
            //     }
            // }

            foreach ($cat_ques as $key => $value) {

                $cat_ques_insert_id = QuizGroupQues::insertGetId([
                    'quiz_qroup_id'=> $group,
                    'question'=> $value,
                    'option1'=>$cat_option1[$key],
                    'option2'=>$cat_option2[$key],
                    'option3'=>$cat_option3[$key],
                    'option4'=>$cat_option4[$key],
                    'answer'=> $answer[$key],
                    'question_point'=> $point_question[$key],
                    'question_time'=> $time_p_ques[$key],
                    'status_id'=> 2,
                    'created_at'=> $timestamp
                ]);

                if($cat_ques_insert_id==0){
                    db::rollback();
                    return back()->with('error','Something Went Wrong')->withInput();
                }
                
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return redirect('/quiz/category/questions/create')->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/quiz/category/questions/create')->with('success','Quiz Category Question Inserted Successfully');
    }
    public function category_question_upd($grp_id){
        $get_category = QuizCategory::where('is_delete',0)->get()->toArray();
        $categoryQues = QuizGroupQues::where('quiz_group_ques.quiz_qroup_id',$grp_id)
            ->leftjoin('quiz_group','quiz_group.group_id','quiz_group_ques.quiz_qroup_id')
            ->leftjoin('quiz_category','quiz_category.quiz_category_id','quiz_group.quiz_category_id')
            ->where('quiz_category.is_delete',0)
            ->where('quiz_group.status_id',2)
            ->where('quiz_group_ques.status_id',2)
            ->select('quiz_group_ques.*','quiz_category.quiz_category_name','quiz_category.quiz_category_id','quiz_group.quiz_title','quiz_group.quiz_time')->get()->toArray();

        $reward = QuizReward::where('quiz_id',$grp_id)->get();

        if(count($categoryQues)==0){
            return redirect('/quiz/category/question/list')->with('error','Category Questions not found');
        }

        return view('quiz/update_category_questions',['cat_ques'=>$categoryQues,'category'=>$get_category,
            'reward'=>$reward]);
    }
    public function category_question_upd_db(Request $request,$cat_id){
        try {
            $this->validate($request,[
                'cat_name'=>'required',
                'quiz_title'=>'required',
                'cat_ques.*'=>'required',
                'cat_option1.*'=>'required',
                'cat_option2.*'=>'required',
                'cat_option3.*'=>'required',
                'cat_option4.*'=>'required',
                'answer.*'=>'required',
                'point_question.*'=>'required',
                'time_p_ques.*'=>'required',
                'quiz_total_time'=>'required'
            ],[
                'cat_name.required' => 'This is required',
                'quiz_title.required' => 'This is required',
                'cat_ques.*.required'=> 'This is required',
                'cat_option1.*.required' => 'This is required',
                'cat_option2.*.required'=> 'This is required',
                'cat_option3.*.required'=> 'This is required',
                'cat_option4.*.required'=> 'This is required',
                'answer.*.required'=>'This is required',
                'point_question.*'=>'required',
                'time_p_ques.*'=>'required',
                'quiz_total_time'=>'required'

            ]);
            

            $quiz_title = $request->input('quiz_title');
            $cat_name = $request->input('cat_name');
            $cat_ques = $request->input('cat_ques');
            $cat_option1 = $request->input('cat_option1');
            $cat_option2 = $request->input('cat_option2');
            $cat_option3 = $request->input('cat_option3');
            $cat_option4 = $request->input('cat_option4');
            $answer = $request->input('answer');
            $point_question = $request->input('point_question');
            $time_p_ques = $request->input('time_p_ques');
            $quiz_total_time = $request->input('quiz_total_time');
            
            $ques_id = $request->input('ques_id');

            $categoryQues = QuizGroupQues::where('quiz_group_ques.quiz_qroup_id',$cat_id)->get()->toArray();
            
            $get_ques_id = array_column($categoryQues, 'ques_id');
            
            $id_deleted = array_diff($get_ques_id,$ques_id);
            
            
            DB::beginTransaction();

            if(count($cat_ques) != count($answer)){
                db::rollback();
                return back()->with('error','Please Select answer for all Questions Inserted');
            }

            $timestamp = date('Y-m-d H:i:s');

            $upd_group= QuizGroup::where('group_id',$cat_id)->update([
                'quiz_title'=>$quiz_title,
                'quiz_time'=>$quiz_total_time,
                'updated_at'=>$timestamp
            ]);

            foreach ($cat_ques as $key => $value) {

                if(isset($ques_id[$key])){
                    $cat_ques_update_id = QuizGroupQues::where('ques_id',$ques_id[$key])->update([
                        'quiz_qroup_id'=> $cat_id,
                        'question'=> $cat_ques[$key],
                        'option1'=> $cat_option1[$key],
                        'option2'=> $cat_option2[$key],
                        'option3'=> $cat_option3[$key],
                        'option4'=> $cat_option4[$key],
                        'answer'=>  $answer[$key],
                        'question_point'=> $point_question[$key],
                        'question_time'=> $time_p_ques[$key],
                        'updated_at'=> $timestamp
                    ]);

                    if($cat_ques_update_id==0){
                        db::rollback();
                        return back()->with('error','Something Went Wrong');
                    }  
                }else{
                    $cat_ques_insert_id = QuizGroupQues::insertGetId([
                        'quiz_qroup_id'=> $cat_id,
                        'question'=> $cat_ques[$key],
                        'option1'=> $cat_option1[$key],
                        'option2'=> $cat_option2[$key],
                        'option3'=> $cat_option3[$key],
                        'option4'=> $cat_option4[$key],
                        'answer'=>  $answer[$key],
                        'status_id'=>2,
                        'question_point'=> $point_question[$key],
                        'question_time'=> $time_p_ques[$key],
                        'created_at'=> $timestamp
                    ]);

                    if($cat_ques_insert_id==0){
                        db::rollback();
                        return back()->with('error','Something Went Wrong');
                    }  

                }
                
                
            }

            if(count($id_deleted)>0){
                $cat_delete = QuizGroupQues::whereIn('ques_id',$id_deleted)->update([
                    'status_id'=> 4,
                    'updated_at'=>$timestamp
                ]);
                if($cat_delete==0){
                    db::rollback();
                    return back()->with('error','Something Went Wrong');
                }
            }


        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return redirect('/quiz/category/questions/update/'.$cat_id)->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/quiz/category/questions/update/'.$cat_id)->with('success','Category Question Updated Successfully');
     
    }
    public function category_view($grp_id){
        $quiz_list = QuizGroupQues::where('quiz_group_ques.quiz_qroup_id',$grp_id)
            ->leftjoin('quiz_group','quiz_group.group_id','quiz_group_ques.quiz_qroup_id')
            ->leftjoin('quiz_category','quiz_category.quiz_category_id','quiz_group.quiz_category_id')
            ->where('quiz_category.is_delete',0)
            ->where('quiz_group.status_id',2)
            ->where('quiz_group_ques.status_id',2)
            ->select('quiz_group_ques.*','quiz_category.quiz_category_name','quiz_category.quiz_category_id','quiz_group.quiz_title','quiz_group.quiz_time')->get()->toArray();

        $reward = QuizReward::where('quiz_id',$grp_id)->get();

        return view('quiz/quiz_ques_view',['quiz'=>$quiz_list,'reward'=>$reward]);
    }

    public function category_question_list(){
        
        return view('quiz/quiz_question_list');
    }
    public function category_question_list_api(Request $request){
        $search = $request->input('search');
        $search_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $quiz_list = QuizGroup::where('quiz_group.status_id',2)
            ->leftjoin('quiz_category','quiz_category.quiz_category_id','quiz_group.quiz_category_id')
            ->where('quiz_category.is_delete',0)
            ->where('quiz_group_ques.status_id',2)
            ->leftjoin('quiz_group_ques','quiz_group_ques.quiz_qroup_id','quiz_group.group_id')
            ->select('quiz_group.group_id',
                'quiz_group.quiz_category_id',
                'quiz_group.quiz_title',
                'quiz_category.quiz_category_name',
                'quiz_category.quiz_category_icon', 
                DB::raw('count(quiz_group_ques.ques_id) as ques_count'),
                'quiz_group.quiz_time'
            )->groupBy('quiz_group.group_id','quiz_group.quiz_category_id','quiz_group.quiz_title','quiz_category.quiz_category_name','quiz_category.quiz_category_icon','quiz_category.category_time','quiz_group.quiz_time');

        if(!empty($search_value))
        {
            $quiz_list = $quiz_list->where('quiz_category.quiz_category_name','LIKE',"%".$search_value."%")
                            ->orwhere('quiz_group.quiz_title','LIKE',"%".$search_value."%")
                        ;
        }

        $count = $quiz_list->count();
        $quiz_list = $quiz_list->offset($offset)->limit($limit);

        if(isset($request->input('order')[0]['column'])){
            $data = ['quiz_group.group_id','quiz_group.quiz_title','quiz_category.quiz_category_name','ques_count','quiz_category_icon','quiz_time'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $quiz_list->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else
        {
            $quiz_list->orderBy('quiz_group.group_id','desc');
        }
        $quiz_listdata = $quiz_list->get()->toArray();
        
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $quiz_listdata; 
        return json_encode($array);
  
    }
    /*public function create_reward($id){
        $get_coin_currency = CoinCurrency::get()->first();

        return view('quiz/create_reward',['quiz_id'=>$id,'coin'=>$get_coin_currency]);
    }
    public function create_reward_db(Request $request, $id){
        try {
            $this->validate($request,[
                'winner_no'=>'required',
                
            ],[
                'winner_no.required'=> 'This is required.',
            ]);
            DB::beginTransaction();
            $position = $request->input('position');
            if (in_array("", $position))
            {
                DB::rollback();
              return back()->with('error','Please fill blank Inputs')->withInput();
            }
            $timestamp = date('Y-m-d H:i:s');
            foreach ($position as $position => $position_amount) {
                $ins = QuizReward::insertGetId([
                    'quiz_id'=>$id,
                    'position'=>($position+1),
                    'position_amount'=>$position_amount,
                    'created_at'=>$timestamp
                ]);

                if($ins == 0){
                    db::rollback();
                    return back()->with('error','Some Error Occoured')->withInput();
                }
            }
            
            
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return redirect('/quiz/reward/'.$id)->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/quiz/category/list')->with('success','Rewards Updated Successfully');
    }*/

    /*public function reward_update($id){
        $get_coin_currency = CoinCurrency::get()->first();

        $reward = QuizReward::where('quiz_id',$id)->get();
        return view('quiz/update_reward',['quiz_id'=>$id,'reward'=>$reward,'coin'=>$get_coin_currency]);
    }*/

    public function reward_update_db(Request $request,$id){
        try {
            $this->validate($request,[
                'winner_no'=>'required',
                
            ],[
                'winner_no.required'=> 'This is required.',
            ]);
            DB::beginTransaction();
            $position = $request->input('position');
            if (in_array("", $position))
            {
                DB::rollback();
              return back()->with('error','Please fill blank Inputs')->withInput();
            }

            $contest = QuizReward::where('quiz_id',$id)->delete();

            $timestamp = date('Y-m-d H:i:s');
            foreach ($position as $position => $position_amount) {
                $upd = QuizReward::insertGetId([
                    'quiz_id'=>$id,
                    'position'=>($position+1),
                    'position_amount'=>$position_amount,
                    'created_at'=>$timestamp
                ]);

                if($upd == 0){
                    db::rollback();
                    return back()->with('error','Some Error Occoured')->withInput();
                }
            }
            
            
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return redirect('/quiz/category/questions/update/'.$id)->with('error','some error occurred'.$ex->getMessage());
        }
        DB::commit();
        return redirect('/quiz/category/questions/update/'.$id)->with('success','Rewards Updated Successfully');
    }

    public function quiz_group_delete(Request $request,$id){
        if($id){
            DB::beginTransaction();
            $del = QuizGroup::where('group_id',$id)->update([
                'status_id'=>4
            ]);

            if($del){
                DB::commit();
                return redirect('/quiz/category/question/list')->with('success','Quiz Group Deleted Successfully');
            }else{
                DB::rollback();
                return redirect('/quiz/category/question/list')->with('error','Something Went Wrong');
            }
        }else{
            return redirect('/quiz/category/question/list')->with('error','Quiz Group not found');
        }
        
    }
}