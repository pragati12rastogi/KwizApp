@extends('layouts.main')
@section('title', 'Edit Contest')

@section('user',Auth::user()->name)

@section('breadcrumb')

<li class="breadcrumb-item"><a href="/contest/summary">Manage Contest</a></li>
<li class="breadcrumb-item active">Edit Contest</li>
@endsection

@section('css')
<style type="text/css">
    label{
      font-weight: 500!important;
    }
    sup{
      color: red;
    }
    .capital{
      text-transform: capitalize;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
    .rm-btn{
        position: relative;
        right: 35px;
        float: right;
        color: crimson;
    }

</style>

@endsection
@section('js')

<script>

  $(function() {
    
    
    $(document).on('click','.rm-btn',function(e){
      $(this).parents(".appended-content").remove();
    });

    $('#contest_ques_form').validate({ // initialize the plugin
        rules: {

            cat_name: {
                required: true
            }
        }
    });


    $(".cat_ques,.cat_option1,.cat_option2,.cat_option3,.cat_option4,.ques_ans").each(function (item) {
        $(this).rules("add", {
            required: true,
        });
    });

    $('#contest_form').validate({ // initialize the plugin
        rules: {

            start_time: {
                required: true
            },
            end_time:{
                required:true
            },
            contest_name:{
                required:true
            },
            time_p_ques:{
                required:true
            },
            no_of_ques:{
                required:true
            },
            contest_icon:{
                required:'#old_contest_icon:blank'
            }

        }
    });

    $('#contest_reward').validate({ // initialize the plugin
        rules: {

            winner_no: {
                required: true
            }
        }
    });

    function create_input(){
        var winner = $('#winner_no').val();
        if(isNaN(winner)){
            $('#number-err').text('Please Enter only number').show();
        }else if(winner <= 0){
            $('#number-err').text('Please Enter number greater than 0').show();
        }else{
            $('#number-err').text('').hide();
            $('.winner_position').empty();
            var str = '';

            for(var i=1;i<=winner;i++){
                str +='<div class="col-md-2">'+
                        '<div class="form-group">'+
                            '<label for="">Winner No. '+i+' <b>(INR)</b><sup>*</sup></label><br>'+
                            '<input type="number" class="winner-input form-control input-css" name="position[]" autocomplete="off" id="winner_input_'+i+'" required>'+
                        '</div>'+              
                    '</div>';
            }
            $('.winner_position').append(str);
            $(".winner-input").each(function (item) {
                $(this).rules("add", {
                    required: true
                });
            });
        }
    }
    var startTime = $('#start_time').datetimepicker({
        format: 'DD-MM-YYYY hh:mm A',
    });
    var endTime = $('#end_time').datetimepicker({
        format: 'DD-MM-YYYY hh:mm A',
    });

    $("#time_p_ques").datetimepicker({
        format: 'mm:ss'
    });
});

$('#start_time,#end_time').change(function(){
    TimePickerCtrl();
});

$('#end_time').click(function(){
    var start_time = $('#start_time').val();
    var newDate 
    if(start_time == ''){
        $('#start_time').focus();
        $("#end_time-err").text('Please Select Start Time').show();
        return false;
    }else{
        $("#end_time-err").text('').hide();
        $('#end_time').datetimepicker('destroy');
        $('#end_time').datetimepicker({
            format: 'DD-MM-YYYY hh:mm A',
            minDate: new Date(moment(start_time, 'DD-MM-YYYY h:m:s A').format('YYYY-MM-DD HH:mm:ss'))
        });
    }
    
    
});

// $(document).ready(TimePickerCtrl);
    
    function TimePickerCtrl(){

        var start_time = $('#start_time').val();
        var convStartTime = moment(start_time, 'DD-MM-YYYY h:m:s A').format('YYYY-MM-DD HH:mm:ss');
        var end_time = $('#end_time').val();
        var convEndTime = moment(end_time, 'DD-MM-YYYY h:m:s A').format('YYYY-MM-DD HH:mm:ss');

        if (Date.parse(convStartTime) > Date.parse(convEndTime)) {
            $("#end_time-err").text('Start time should be smaller').show();
            return false;
        }else{
            $("#end_time-err").text('').hide();
            return true;
        }
    }
    
    
</script>
@endsection
@section('content')
<div class="container-fluid">
    @include('flash-message')
</div>
<div class="container-fluid">
    
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Edit Contest</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
        </div>
        <div class="card-body">
            <form  action="{{url('/contest/edit')}}/{{$master['contest_id']}}" enctype="multipart/form-data" method="POST" id="contest_form" files="true">
            
               @csrf
               <div class="row">
                    
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="contest_name">{{__('Contest Name')}} <sup>*</sup></label><br>
                            <input type="text" class="capital form-control input-css @error('contest_name') is-invalid @enderror" name="contest_name" id="contest_name" value="{{$master['contest_name']}}" autocomplete="off">
                        </div> 
                        @error('contest_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="join_user">{{__('Total User Can Join')}} <sup>*</sup></label><br>
                            <input type="number" class="form-control input-css @error('join_user') is-invalid @enderror" name="join_user" id="join_user" min="0" value="{{$master['user_can_join']}}" autocomplete="off">
                        </div> 
                        @error('join_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror 
                    </div>
                    
                </div>
                <div class="row">

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="start_time">{{__('Start Time')}} <sup>*</sup></label><br>
                            <input type="text" class="datetimepicker-input form-control input-css @error('start_time') is-invalid @enderror" name="start_time" id="start_time" value="{{date('d-m-Y h:i A',strtotime($master['start_time']))}}" autocomplete="off" data-target="#start_time" data-toggle="datetimepicker">
                        </div> 
                        @error('start_time')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="end_time">{{__('End Time')}} <sup>*</sup></label><br>
                            <input type="text" class="datetimepicker-input form-control input-css @error('end_time') is-invalid @enderror" name="end_time" id="end_time" value="{{date('d-m-Y h:i A',strtotime($master['end_time']))}}" autocomplete="off" data-target="#end_time" data-toggle="datetimepicker" >
                            <span class="invalid-feedback" role="alert" id="end_time-err"><strong></strong></span>

                        </div>
                        @error('end_time')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror 
                    </div>
                    
                </div>
                
                <div class="row">
                    
                    <div class="col-md-6 {{ $errors->has('contest_icon') ? ' has-error' : ''}}">
                        <label for="">{{__('Contest Icon')}} <sup>*</sup></label>
                        <br>
                        @if($master['contest_icon'] != "" || $master['contest_icon'] != null)
                            @if (file_exists(public_path().'/upload/quiz_cat_icon/'.$master['contest_icon'] ))
                                <img src="{{asset('/upload/quiz_cat_icon/')}}/{{$master['contest_icon']}}" height="50" width="100"><br><br>
                            @endif
                        @endif
                        
                        <input type="file" accept="image/*" name="contest_icon" value="{{$master['contest_icon']}}" id="" class="contest_icon ">
                        {!! $errors->first('contest_icon', '<p class="help-block">:message</p>') !!} 
                        <input type="text" name="old_contest_icon" id="old_contest_icon" value="{{$master['contest_icon']}}" hidden>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="contest_fee">{{__('Enter Contest Joining Fee')}}(In Coins) <sup>*</sup></label><br>
                            <input type="number" class="form-control input-css @error('contest_fee') is-invalid @enderror" name="contest_fee" id="contest_fee" value="{{$master['contest_fee']}}" autocomplete="off">
                        </div> 
                        @error('contest_fee')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                
                </div><br>
            </div>
            <div class="card-footer">
                <div class="form-group row mb-0">
                    <div class="col-md-1 offset-md-11">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Edit Contest Reward Distribution</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
        </div>  
           
        <div class="card-body">
            <form  action="{{url('/contest/reward/update/')}}/{{$master['contest_id']}}" enctype="multipart/form-data" method="POST" id="contest_reward" files="true">
         
               @csrf
               
               <div class="row">

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="contest_name">{{__('Enter Winners for Contest')}} <sup>*</sup></label><br>
                            <input type="number" class="form-control input-css @error('winner_no') is-invalid @enderror" name="winner_no" id="winner_no" value="{{count($reward)}}" autocomplete="off" placeholder="eg: 10 (prize distribution for top 10)" onchange="create_input()">
                        </div> 
                        <span class="invalid-feedback" role="alert" id="number-err" style="display: none;"></span>
                        @error('winner_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                </div>
                <div class="row winner_position">
                    @if(count($reward)>0)
                        @for($i =0;$i < count($reward);$i++)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Winner No. {{$reward[$i]['position']}} <b>(INR)</b><sup>*</sup></label><br>
                                    <input type="number" class="winner-input form-control input-css" name="position[]" autocomplete="off" id="winner_input_{{$reward[$i]['position']}}" value="{{$reward[$i]['position_amount']}}" required>
                                </div>              
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group row mb-0">
                    <div class="col-md-2 offset-md-10 pr-3" style="text-align: end">
                        <button type="submit" class="btn btn-primary">Update Rewards</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@if(count($question)>0)
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">Edit Contest Questions</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
        </div>
        <div class="card-body">
            <form  action="{{url('/contest/question/edit')}}/{{$master['contest_id']}}" enctype="multipart/form-data" method="POST" id="contest_ques_form" files="true">
            
               @csrf
               
                <div class="row div_tocopy ques_count">
                    <input type="hidden" value="{{$question[0]['question_id']}}" name="ques_id[]">
                    <div class="col-md-12">
                        <label>{{__('Quiz Question')}} 1<sup>*</sup></label>
                        <textarea class="cat_ques form-control input-css @error('cat_ques.0') is-invalid @enderror" name="cat_ques[]" autocomplete="off" placeholder="Enter Quiz Question here...">{{$question[0]['question']}}</textarea>
                            @error('cat_ques.0')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror<br>
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label for="cat_icon">{{__('Option 1')}}<sup>*</sup></label><br>
                            <input type="text" class="cat_option1 form-control input-css @error('cat_option1.*') is-invalid @enderror" id="cat_option1_0" autocomplete="off" value="{{$question[0]['option1']}}"  name="cat_option1[]" placeholder="Option 1">
                        </div> 
                        @error('cat_option1.*')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label for="cat_option2">{{__('Option 2')}}<sup>*</sup></label><br>
                            <input type="text" class="cat_option2 form-control input-css @error('cat_option2.0') is-invalid @enderror" id="cat_option2_0" autocomplete="off" value="{{$question[0]['option2']}}" placeholder="Option 2" name="cat_option2[]">
                        </div> 
                        @error('cat_option2.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label for="cat_option3">{{__('Option 3')}}<sup>*</sup></label><br>
                            <input type="text" class="cat_option3 form-control input-css @error('cat_option3.0') is-invalid @enderror" id="cat_option3_0" autocomplete="off" value="{{$question[0]['option3']}}"  name="cat_option3[]" placeholder="Option 3">
                        </div> 
                        @error('cat_option3.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label for="cat_option4">{{__('Option 4')}}<sup>*</sup></label><br>
                            <input type="text" class=" cat_option4 form-control input-css @error('cat_option4.0') is-invalid @enderror" id="cat_option4_0" autocomplete="off" value="{{$question[0]['option4']}}" name="cat_option4[]" placeholder="Option 4">
                        </div> 
                        @error('cat_option4.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror            
                    </div>
                    <div class="col-md-4 ">
                        <label for="cat_option4">{{__('Quiz Answer')}}  1<sup>*</sup></label><br>
                        <div class="form-group @error('answer.*') is-invalid @enderror">
                            <div class="form-check-inline">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option1" name="answer[0]" {{($question[0]['answer']=='option1')?'checked=checked':""}} >Option 1
                              </label>
                            </div>
                            <div class="form-check-inline">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option2" name="answer[0]" {{($question[0]['answer']=='option2')?'checked=checked':""}}>Option 2
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option3" name="answer[0]" {{($question[0]['answer']=='option3')?'checked=checked':""}} >Option 3
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option4" name="answer[0]" {{($question[0]['answer']=='option4')?'checked=checked':""}} >Option 4
                              </label>
                            </div>
                        </div> 
                        <span id="ques_ans_0-error" class="all_err"></span>
                        @error('answer.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-4 ">
                        <label for="cat_option4">{{__('Question Point')}}<sup>*</sup></label><br>
                        <input type="number" class="point_question form-control input-css @error('point_question.0') is-invalid @enderror" name="point_question[]" id="point_question" min="0" value="{{$question[0]['question_point']}}" autocomplete="off">
                        @error('point_question.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-4 ">
                        <label for="cat_option4">{{__('Question Time')}} (Minute:Seconds)<sup>*</sup></label><br>
                        <input type="text" class="time_p_ques datetimepicker-input form-control input-css @error('time_p_ques.0') is-invalid @enderror" name="time_p_ques[]" id="time_p_ques" value="{{$question[0]['question_time']}}" autocomplete="off" data-target="#time_p_ques" data-toggle="datetimepicker">
                        @error('time_p_ques.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                </div>
                <!-- div to replicate data -->
                <div class="replicate_div">
                    @if($question)
                        @if(count($question) > 1)
                            @for($i=1;$i < count($question);$i++)
                            <hr>
                                <div class="row div_tocopy ques_count appended-content">
                                    <div class="col-md-12">
                                        <a href="javascript:void(0)" class="rm-btn"><i class="fa fa-trash-alt"></i></a>
                                    </div>
                                    <input type="hidden" value="{{$question[$i]['question_id']}}" name="ques_id[]">
                                    <div class="col-md-12">
                                        <label>{{__('Quiz Question')}} {{$i+1}}<sup>*</sup></label>
                                        <textarea class="cat_ques form-control input-css @error('cat_ques.*') is-invalid @enderror" name="cat_ques[]" autocomplete="off" placeholder="Enter Quiz Question here...">{{$question[$i]['question']}}</textarea>
                                            @error('cat_ques.*')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror<br>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label for="cat_icon">{{__('Option 1')}}<sup>*</sup></label><br>
                                            <input type="text" class="cat_option1 form-control input-css @error('cat_option1.*') is-invalid @enderror" id="cat_option1_{{$i}}" autocomplete="off" value="{{$question[$i]['option1']}}"  name="cat_option1[]" placeholder="Option 1">
                                        </div> 
                                        @error('cat_option1.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label for="cat_option2">{{__('Option 2')}}<sup>*</sup></label><br>
                                            <input type="text" class="cat_option2 form-control input-css @error('cat_option2.*') is-invalid @enderror" id="cat_option2_{{$i}}" autocomplete="off" value="{{$question[$i]['option2']}}" placeholder="Option 2" name="cat_option2[]">
                                        </div> 
                                        @error('cat_option2.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label for="cat_option3">{{__('Option 3')}}<sup>*</sup></label><br>
                                            <input type="text" class="cat_option3 form-control input-css @error('cat_option3.*') is-invalid @enderror" id="cat_option3_{{$i}}" autocomplete="off" value="{{$question[$i]['option3']}}"  name="cat_option3[]" placeholder="Option 3">
                                        </div> 
                                        @error('cat_option3.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label for="cat_option4">{{__('Option 4')}}<sup>*</sup></label><br>
                                            <input type="text" class=" cat_option4 form-control input-css @error('cat_option4.*') is-invalid @enderror" id="cat_option4_{{$i}}" autocomplete="off" value="{{$question[$i]['option4']}}" name="cat_option4[]" placeholder="Option 4">
                                        </div> 
                                        @error('cat_option4.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="cat_option4">{{__('Quiz Answer')}} {{$i+1}}<sup>*</sup></label><br>
                                        <div class="form-group @error('answer.*') is-invalid @enderror">
                                            <div class="form-check-inline">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option1" name="answer[{{$i}}]" {{($question[$i]['answer']=='option1')?'checked="checked"':""}}>Option 1
                                              </label>
                                            </div>
                                            <div class="form-check-inline">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option2" name="answer[{{$i}}]" {{($question[$i]['answer']=='option2')?'checked="checked"':""}}>Option 2
                                              </label>
                                            </div>
                                            <div class="form-check-inline ">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option3" name="answer[{{$i}}]" {{($question[$i]['answer']=='option3')?'checked="checked"':""}}>Option 3
                                              </label>
                                            </div>
                                            <div class="form-check-inline ">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option4" name="answer[{{$i}}]" {{($question[$i]['answer']=='option4')?'checked="checked"':""}} >Option 4
                                              </label>
                                            </div>
                                        </div> 
                                        <span id="ques_ans_{{$i}}-error" class="all_err"></span>
                                        @error('answer.0')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="cat_option4">{{__('Question Point')}}<sup>*</sup></label><br>
                                        <input type="number" class="form-control input-css @error('point_question.*') is-invalid @enderror" name="point_question[]" id="point_question" min="0" value="{{$question[$i]['question_point']}}" autocomplete="off">
                                        @error('point_question.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-4 ">
                                       / <label for="cat_option4">{{__('Question Time')}} (Minute:Seconds)<sup>*</sup></label><br>
                                        <input type="text" class="datetimepicker-input form-control input-css @error('time_p_ques.*') is-invalid @enderror" name="time_p_ques[]" id="time_p_ques" value="{{$question[$i]['question_time']}}" autocomplete="off" data-target="#time_p_ques" data-toggle="datetimepicker">
                                        @error('time_p_ques.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                </div>
                            @endfor
                        @endif   
                    @endif
                </div><br>
            </div>
            <div class="card-footer">
                <div class="form-group row mb-0">
                    <div class="col-md-1 offset-md-11" >
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endif
<br>
</div>
@endsection
