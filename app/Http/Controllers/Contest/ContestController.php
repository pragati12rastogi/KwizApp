<?php

namespace App\Http\Controllers\Contest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppUsers;
use App\Models\Status;
use App\Models\Contest;
use App\Models\ContestQuestion;
use App\Models\CoinCurrency;
use App\Models\ContestReward;
use Auth;
use Illuminate\Support\Facades\File;
use DB;
use PDF;
use Hash;
use App\Custom\CustomHelpers;

class ContestController extends Controller
{
    public function create_contest(){
        $get_coin_currency = CoinCurrency::get()->first();
        return view('contest/create_contest',['coin'=>$get_coin_currency]);
    }

    public function create_contest_db(Request $request){
        try {
            $this->validate($request,[
                'contest_name'=>'required',
                'start_time'=>'required',
                'end_time'=>'required',
                'join_user'=>'required',
                'contest_icon'=>'required|mimes:jpeg,png,jpg,gif,svg',
                'winner_no'=>'required',
                'contest_fee'=>'required'
            ],[
                'contest_name.required'=> 'This is required.',
                'start_time.required'=> 'This is required.',
                'end_time.required'=> 'This is required.',
                'join_user.required'=> 'This is required.',
                'contest_icon.required'=> 'This is required.',
                'contest_icon.mimes'=>'Field accept only jpeg,png,jpg,svg format',
                'winner_no.required'=> 'This is required.',
                'contest_fee.required'=> 'This is required.'
            ]);

            $position = $request->input('position');
            if (in_array("", $position))
            {
                DB::rollback();
              return back()->with('error','Please fill blank Inputs')->withInput();
            }

            $contest_name = $request->input('contest_name');
            $start_time = date('Y-m-d H:i:s',strtotime($request->input('start_time')));
            $end_time = date('Y-m-d H:i:s',strtotime($request->input('end_time')));
            $join_user = $request->input('join_user');
            $contest_fee = $request->input('contest_fee');
            $file = $request->file('contest_icon');
            $timestamp = date('Y-m-d H:i:s');
            
            DB::beginTransaction();
            
            $contest_icon = '';
            
            if(isset($file) || $file != null){
                $destinationPath = public_path().'/upload/quiz_cat_icon/';
                $filenameWithExt = $request->file('contest_icon')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('contest_icon')->getClientOriginalExtension();
                $contest_icon = $filename.'_'.time().'.'.$extension;
                $path = $file->move($destinationPath, $contest_icon);
            }else{
                $contest_icon = '';
            }


            $contest_insert = Contest::insertGetId([
                'contest_name'=>$contest_name,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'user_can_join'=>$join_user,
                'contest_icon'=>$contest_icon,
                'contest_fee'=>$contest_fee,
                'status_id'=>2,
                'created_at'=>$timestamp
            ]);

            
            if($contest_insert == 0){
                DB::rollback();
                return back()->with('error','Some Error Occurred')->withInput();
            }

            foreach ($position as $position => $position_amount) {
                $ins = ContestReward::insertGetId([
                    'contest_id'=>$contest_insert,
                    'position'=>($position+1),
                    'position_amount'=>$position_amount,
                    'created_at'=>$timestamp
                ]);

                if($ins == 0){
                    db::rollback();
                    return back()->with('error','Some Error Occoured in contest reward')->withInput();
                }
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/contest/create')->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/contest/create')->with('success','Contest Created Successfully');       
    
    }

    public function contest_list(){
        return view('contest/contest_summary');
    }

    public function contest_list_api(Request $request){
        $search = $request->input('search');
        $search_value = $search['value'];
        $start = $request->input('start');
        $limit = $request->input('length');
        $offset = empty($start) ? 0 : $start ;
        $limit =  empty($limit) ? 10 : $limit ;
        
        $contest_list = Contest::where('contest.status_id',2)
            ->leftjoin('contest_question',function($join){
                $join->on('contest_question.contest_id','contest.contest_id')
                ->where('contest_question.status_id',2);
            })
            ->leftjoin('contest_reward','contest_reward.contest_id','contest.contest_id')
            ->select('contest.contest_id','contest.contest_name',
            'contest.contest_icon', 'contest.contest_fee',
            DB::raw('Date_Format(contest.start_time,"%d-%m-%Y %r") as start_time'),
            DB::raw('Date_Format(contest.end_time,"%d-%m-%Y %r") as end_time'),
            DB::raw('Concat(Date_Format(contest.start_time,"%d-%m-%Y %h:%i %p")," to ",Date_Format(contest.end_time,"%d-%m-%Y %h:%i %p")) as bothtime'),
            'contest.user_can_join',
            DB::raw('count(contest_question.question_id) as total_question'),
            DB::raw('count(contest_reward.contest_reward_id) as winner_count')
        )->groupby('contest.contest_id','contest.contest_name','contest.start_time','contest.end_time','contest.user_can_join','contest.contest_icon','contest.contest_fee');

        if(!empty($search_value))
        {
            $contest_list = $contest_list->where('contest.contest_name','LIKE',"%".$search_value."%")
                        ;
        }

        $count = $contest_list->count();
        $contest_list = $contest_list->offset($offset)->limit($limit);

        if(isset($request->input('order')[0]['column'])){
            $data = ['contest_id','contest_name','start_time','end_time','bothtime','user_can_join','total_question'];
            $by = ($request->input('order')[0]['dir'] == 'desc')? 'desc': 'asc';
            $contest_list->orderBy($data[$request->input('order')[0]['column']], $by);
        }
        else
        {
            $contest_list->orderBy('contest.contest_id','desc');
        }
        $quiz_listdata = $contest_list->get()->toArray();
        
        $array['recordsTotal'] = $count;
        $array['recordsFiltered'] = $count ;
        $array['data'] = $quiz_listdata; 
        return json_encode($array);
  
    }

    public function contest_edit($id){
        
        $contest_list = Contest::where('contest.status_id',2)
            ->where('contest.contest_id',$id)
            ->select('contest.contest_id','contest.contest_name',
            'contest.contest_icon', 
            DB::raw('Date_Format(contest.start_time,"%d-%m-%Y %r") as start_time'),
            DB::raw('Date_Format(contest.end_time,"%d-%m-%Y %r") as end_time'),
            'contest.user_can_join',
            'contest.contest_fee'
        )->first();

        $contest_question = ContestQuestion::where('contest_question.contest_id',$id)
            ->where('contest_question.status_id',2)->select('contest_question.*')->get()->toArray();

        $get_coin_currency = CoinCurrency::get()->first();

        $reward = ContestReward::where('contest_id',$id)->get();

        $data = array( 'master'=>$contest_list,
            'question'=>$contest_question,
            'coin_currency'=>$get_coin_currency,
            'reward'=>$reward
        );
        return view('contest/update_contest',$data);
    }

    public function contest_edit_db(Request $request,$id){
        try {
            $this->validate($request,[
                'contest_name'=>'required',
                'start_time'=>'required',
                'end_time'=>'required',
                'join_user'=>'required',
                'contest_icon'=>'mimes:jpeg,png,jpg,gif,svg',
                'contest_fee'=>'required'
            ],[
                'contest_name.required'=> 'This is required.',
                'start_time.required'=> 'This is required.',
                'end_time.required'=> 'This is required.',
                'join_user.required'=> 'This is required.',
                'contest_fee.required'=> 'This is required.',
                'contest_icon.mimes'=>'Field accept only jpeg,png,jpg,svg format'
            ]);

            $contest_name = $request->input('contest_name');
            $start_time = date('Y-m-d H:i:s',strtotime($request->input('start_time')));
            $end_time = date('Y-m-d H:i:s',strtotime($request->input('end_time')));
            $join_user = $request->input('join_user');
            $contest_fee = $request->input('contest_fee');
            $file = $request->file('contest_icon');
            $timestamp = date('Y-m-d H:i:s');
            
            DB::beginTransaction();
            
            $contest_icon = '';
            
            if(isset($file) || $file != null){
                $destinationPath = public_path().'/upload/quiz_cat_icon/';
                $filenameWithExt = $request->file('contest_icon')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('contest_icon')->getClientOriginalExtension();
                $contest_icon = $filename.'_'.time().'.'.$extension;
                $path = $file->move($destinationPath, $contest_icon);
                File::delete($destinationPath.$request->input('old_contest_icon'));
            }else{
                $contest_icon = $request->input('old_contest_icon');
            }


            $contest_update = Contest::where('contest_id',$id)->update([
                'contest_name'=>$contest_name,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'user_can_join'=>$join_user,
                'contest_icon'=>$contest_icon,
                'contest_fee'=>$contest_fee,
                'updated_at'=>$timestamp
            ]);

            if($contest_update == 0){
                DB::rollback();
                return back()->with('error','Some Error Occurred')->withInput();
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('/contest/edit/'.$id)->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/contest/edit/'.$id)->with('success','Contest Updated Successfully');       
    
    }

    public function create_contest_question($contest_id){
        $get_coin_currency = CoinCurrency::get()->first();
        return View('contest/create_contest_questions',['coin_currency'=>$get_coin_currency,'contest_id'=>$contest_id]);
    }
    public function create_contest_question_db(Request $request,$contest_id){
        try {

            $this->validate($request,[
                
                'cat_ques.*'=>'required',
                'cat_option1.*'=>'required',
                'cat_option2.*'=>'required',
                'cat_option3.*'=>'required',
                'cat_option4.*'=>'required',
                'answer.*'=>'required',
                'point_question.*'=>'required',
                'time_p_ques.*'=>'required'
            ],[
                'cat_ques.*.required'=> 'This is required',
                'cat_option1.*.required' => 'This is required',
                'cat_option2.*.required'=> 'This is required',
                'cat_option3.*.required'=> 'This is required',
                'cat_option4.*.required'=> 'This is required',
                'answer.*.required'=>'This is required',
                'point_question.*.required'=>'This is required',
                'time_p_ques.*.required'=>'This is required'
            ]);
            
            DB::beginTransaction();

            $contest_name = $contest_id;
            $cat_ques = $request->input('cat_ques');
            $cat_option1 = $request->input('cat_option1');
            $cat_option2 = $request->input('cat_option2');
            $cat_option3 = $request->input('cat_option3');
            $cat_option4 = $request->input('cat_option4');
            $answer = $request->input('answer');
            $point_question = $request->input('point_question');
            $time_p_ques = $request->input('time_p_ques');
            
            
            if(count($cat_ques) != count($answer)){
                db::rollback();
                return back()->with('error','Please Select answer for all Questions Inserted')->withInput();
            }

            $timestamp = date('Y-m-d H:i:s');


            foreach ($cat_ques as $key => $value) {

                $cat_ques_insert_id = ContestQuestion::insertGetId([
                    'contest_id'=> $contest_name,
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

        } catch (Illuminate\Database\QueryException $ex) {
            return redirect('/contest/question/create/'.$contest_id)->with('error','Something Went Wrong '.$ex ); 
        }
        DB::commit();
        return redirect('/contest/question/create/'.$contest_id)->with('success','Contest Questions Created Successfully'); 

    }
    public function edit_contest_question_db(Request $request,$contest_id){
        try {
            $this->validate($request,[
                'cat_ques.*'=>'required',
                'cat_option1.*'=>'required',
                'cat_option2.*'=>'required',
                'cat_option3.*'=>'required',
                'cat_option4.*'=>'required',
                'answer.*'=>'required',
                'point_question.*'=>'required',
                'time_p_ques.*'=>'required'
            ],[
                'cat_ques.*.required'=> 'This is required',
                'cat_option1.*.required' => 'This is required',
                'cat_option2.*.required'=> 'This is required',
                'cat_option3.*.required'=> 'This is required',
                'cat_option4.*.required'=> 'This is required',
                'answer.*.required'=>'This is required',
                'point_question.*.required'=>'This is required',
                'time_p_ques.*.required'=>'This is required',
            ]);
            
            $cat_name = $contest_id;
            $cat_ques = $request->input('cat_ques');
            $cat_option1 = $request->input('cat_option1');
            $cat_option2 = $request->input('cat_option2');
            $cat_option3 = $request->input('cat_option3');
            $cat_option4 = $request->input('cat_option4');
            $answer = $request->input('answer');
            $point_question = $request->input('point_question');
            $time_p_ques = $request->input('time_p_ques');
            
            $ques_id = $request->input('ques_id');
            $categoryQues = ContestQuestion::where('contest_question.contest_id',$contest_id)->get()->toArray();
            
            $get_ques_id = array_column($categoryQues, 'question_id');
            
            $id_deleted = array_diff($get_ques_id,$ques_id);
           
            
            DB::beginTransaction();

            if(count($cat_ques) != count($answer)){
                db::rollback();
                return back()->with('error','Please Select answer for all Questions Inserted')->withInput();
            }

            $timestamp = date('Y-m-d H:i:s');


            foreach ($ques_id as $key => $value) {

                $cat_ques_update_id = ContestQuestion::where('question_id',$value)->update([
                    'contest_id'=> $cat_name,
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
                    return back()->with('error','Something Went Wrong')->withInput();
                }
                
            }
            if(count($id_deleted)>0){
                $cat_delete = ContestQuestion::whereIn('question_id',$id_deleted)->update([
                    'status_id'=> 4
                ]);
                if($cat_delete==0){
                    db::rollback();
                    return back()->with('error','Something Went Wrong')->withInput();
                }
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            return redirect('/contest/edit/'.$contest_id)->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/contest/edit/'.$contest_id)->with('success','Contest Question Updated Successfully');
    }

    public function contest_delete($contest_id){
        if($contest_id){
            DB::beginTransaction();
            $del = Contest::where('contest_id',$contest_id)->update([
                'status_id'=>4,
                'updated_at'=>date('Y-m-d H:i:s')
            ]);

            if($del){
                DB::commit();
                return redirect('/contest/summary')->with('success','Contest Deleted Successfully');
            }else{
                DB::rollback();
                return redirect('/contest/summary')->with('error','Something Went Wrong');
            }
        }else{
            return redirect('/contest/summary')->with('error','Contest not found');
        }
    }

    public function contest_view($contest_id){

        $contest_list = Contest::where('contest.status_id',2)
            ->where('contest.contest_id',$contest_id)
            ->select('contest.contest_id','contest.contest_name',
            'contest.contest_icon', 
            DB::raw('Date_Format(contest.start_time,"%d-%m-%Y %r") as start_time'),
            DB::raw('Date_Format(contest.end_time,"%d-%m-%Y %r") as end_time'),
            'contest.user_can_join',
            'contest.contest_fee'
        )->first();

        $contest_question = ContestQuestion::where('contest_question.contest_id',$contest_id)
            ->where('contest_question.status_id',2)->select('contest_question.*')->get()->toArray();

        $contest_reward = ContestReward::where('contest_id',$contest_id)->get()->toArray();
        $data = array( 'master'=>$contest_list,
            'question'=>$contest_question,
            'reward'=>$contest_reward
        );
        return view('contest/contest_view',$data);  
    }

    /*public function reward_contest($id){
        $get_coin_currency = CoinCurrency::get()->first();

        return view('contest/reward_contest',['contest_id'=>$id,'coin'=>$get_coin_currency]);
    }*/
    /*public function reward_contest_db(Request $request, $id){
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
                $ins = ContestReward::insertGetId([
                    'contest_id'=>$id,
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
            return redirect('/contest/reward/'.$id)->with('error','some error occurred'.$ex->getMessage());
        }

        DB::commit();
        return redirect('/contest/summary')->with('success','Rewards Updated Successfully');
    }*/

    public function reward_update($id){
        $get_coin_currency = CoinCurrency::get()->first();

        $reward = ContestReward::where('contest_id',$id)->get();
        return view('contest/reward_contest_update',['contest_id'=>$id,'reward'=>$reward,'coin'=>$get_coin_currency]);
    }

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

            $contest = ContestReward::where('contest_id',$id)->delete();

            $timestamp = date('Y-m-d H:i:s');
            foreach ($position as $position => $position_amount) {
                $upd = ContestReward::insertGetId([
                    'contest_id'=>$id,
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
            return redirect('/contest/edit/'.$id)->with('error','some error occurred'.$ex->getMessage());
        }
        DB::commit();
        return redirect('/contest/edit/'.$id)->with('success','Contest Updated Successfully');
    }

}