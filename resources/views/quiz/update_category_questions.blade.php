@extends('layouts.main')
@section('title', 'Edit Quiz Category Questions')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/quiz/category/question/list">Manage Quiz Creation</a></li>
<li class="breadcrumb-item active">Edit Quiz Category Questions</li>
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
    .appended-content{
        border-top: 1px solid #e5e2e2;
        padding-top: 35px;
        margin-top: 35px;
    }
    .all_err{
        color: red;
    }
</style>
@endsection
@section('js')
<script>
  $(function() {
    
    

    $('#quiz_create_form').on('submit',function(e){
        
        var names = []
        $('input:radio').each(function () {
            var rclass = $(this).attr('class');
            var rpickclass = rclass.split(' ');
            var rname = rpickclass[1];
            if ($.inArray(rname, names) === -1) names.push(rname);
        });

        //do validation for each group
        $.each(names, function (i, name) {
            if ($('.' + name + ':checked').length === 0) {
                $('#'+name+'-error').text('This field is required');
                e.preventDefault();
                return false;
            }else{
                $('#'+name+'-error').text('');
                return true;
            }
        });
    });

    $(document).on('click','.rm-btn',function(e){
      $(this).parents(".appended-content").remove();
      perQuizTime();
    });

    $('#quiz_create_form').validate({ // initialize the plugin
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

    

    $('.time_p_ques').datetimepicker({
      showSecond: true,
      format: 'mm:ss'
    });
    $('#quiz_total_time').datetimepicker({
      showSecond: true,
      format: 'mm:ss'
    });
    
    $(".ques_addOther").click(function(){
        var count = $('.ques_count').length;
        var show_count = $('.ques_count').length+1;
        $(".replicate_div").append(
            '<div class="row div_tocopy ques_count appended-content">'+
                    
                    '<div class="col-md-12">'+
                        '<a href="javascript:void(0)" class="rm-btn"><i class="fa fa-trash-alt"></i></a>'+
                    '</div>'+
                    '<div class="col-md-12">'+
                        '<label>{{__("Quiz Question")}} '+show_count+' <sup>*</sup></label>'+
                        '<textarea class="cat_ques form-control input-css " id="cat_ques_'+count+'" name="cat_ques[]" value="" autocomplete="off" placeholder="Enter Quiz Question here..."></textarea><br>'+
                    '</div>'+
                    '<div class="col-md-3 ">'+
                        '<div class="form-group">'+
                            '<label for="cat_icon">{{__("Option 1")}}<sup>*</sup></label><br>'+
                            '<input type="text" class="cat_option1 form-control input-css " autocomplete="off" id="cat_option1_'+count+'"  name="cat_option1[]" placeholder="Option 1">'+
                        '</div>'+               
                    '</div>'+
                    '<div class="col-md-3 ">'+
                        '<div class="form-group">'+
                            '<label for="cat_option2">{{__("Option 2")}}<sup>*</sup></label><br>'+
                            '<input type="text" class="cat_option2 form-control input-css " autocomplete="off" id="cat_option2_'+count+'" placeholder="Option 2" name="cat_option2[]">'+
                        '</div>'+ 
                    '</div>'+
                    '<div class="col-md-3 ">'+
                        '<div class="form-group">'+
                            '<label for="cat_option3">{{__("Option 3")}}<sup>*</sup></label><br>'+
                            '<input type="text" class="cat_option3 form-control input-css" autocomplete="off" id="cat_option3_'+count+'"  name="cat_option3[]" placeholder="Option 3">'+
                        '</div>'+              
                    '</div>'+
                    '<div class="col-md-3 ">'+
                        '<div class="form-group">'+
                            '<label for="cat_option4">{{__("Option 4")}}<sup>*</sup></label><br>'+
                            '<input type="text" autocomplete="off" class="cat_option4 form-control input-css @error("cat_option4") is-invalid @enderror" id="cat_option4_'+count+'"  name="cat_option4[]" placeholder="Option 4">'+
                        '</div> '+
                    '</div>'+
                    '<div class="col-md-4 ">'+
                        '<label for="cat_option4">{{__("Quiz Answer")}}  '+show_count+'<sup>*</sup></label><br>'+
                        '<div class="form-group">'+
                            '<div class="form-check-inline">'+
                              '<label class="form-check-label">'+
                                '<input type="radio" class="ques_ans ques_ans_'+count+' form-check-input" value="option1" name="answer['+count+']">Option 1'+
                              '</label>'+
                            '</div>'+
                            '<div class="form-check-inline">'+
                              '<label class="form-check-label">'+
                                '<input type="radio" class="ques_ans ques_ans_'+count+' form-check-input" value="option2" name="answer['+count+']">Option 2'+
                              '</label>'+
                            '</div>'+
                            '<div class="form-check-inline ">'+
                              '<label class="form-check-label">'+
                                '<input type="radio" class="ques_ans ques_ans_'+count+' form-check-input" value="option3" name="answer['+count+']" >Option 3'+
                              '</label>'+
                            '</div>'+
                            '<div class="form-check-inline ">'+
                              '<label class="form-check-label">'+
                                '<input type="radio" class="ques_ans ques_ans_'+count+' form-check-input" value="option4" name="answer['+count+']" >Option 4'+
                              '</label>'+
                            '</div>'+
                        '</div>'+
                        '<span id="ques_ans_'+count+'-error" class="all_err"></span>'+
                    '</div>'+
                    '<div class="col-md-4 ">'+
                        '<label for="cat_option4">{{__("Question Coins")}}<sup>*</sup></label><br>'+
                        '<input type="number" class="point_question form-control input-css " name="point_question[]" id="point_question_'+count+'" min="0" value="" autocomplete="off">'+              
                    '</div>'+
                    '<div class="col-md-4 ">'+
                        '<label for="cat_option4">{{__("Question Time")}} (Minute:Seconds)<sup>*</sup></label><br>'+
                        '<input type="text" class="time_p_ques datetimepicker-input form-control input-css " name="time_p_ques[]" id="time_p_ques_'+count+'" autocomplete="off" data-target="#time_p_ques_'+count+'" data-toggle="datetimepicker" onblur="ques_time_change()">'+         
                    '</div>'+
                '</div>');
                $('.time_p_ques').datetimepicker({
                  showSecond: true,
                  format: 'mm:ss'
                });
                perQuizTime();
    });


});
$(function() {
    
   
    $('#reward_form').validate({ // initialize the plugin
        rules: {

            winner_no: {
                required: true
            }
        }
    });

});

function perQuizTime(){
    var total_time = $("#quiz_total_time").val();
    if(total_time == ""){

    }else{
        var tsplit = total_time.split(':');
        var minute =  tsplit[0];
        var sec = tsplit[1];

        var mtms = (minute*60000);
        var stms = (sec * 1000);
        var totalmsec = parseInt(mtms) + parseInt(stms);

        var count = $('.ques_count').length;
        var div = Math.floor(totalmsec / count);

        var after_divide = millisToMinutesAndSeconds(div);

        for(var i = 0;i<count;i++){
            $("#time_p_ques_"+i).val(after_divide);
        }
    }
    
}

function millisToMinutesAndSeconds(millis) {
  var minutes = Math.floor(millis / 60000);
  var seconds = ((millis % 60000) / 1000).toFixed(0);
  return (minutes < 10 ? '0' : '') + minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
}

function ques_time_change(){
    var count = $('.ques_count').length;
    var minute =0;
    var sec =0;
    for(var i = 0;i<count;i++){
        var each_time = $("#time_p_ques_"+i).val();

        var tsplit = each_time.split(':');
        minute=  minute+parseInt(tsplit[0]);
        sec= sec+parseInt(tsplit[1]);
    }
    var mtms = (minute*60000);
    var stms = (sec * 1000);
    var totalmsec = parseInt(mtms) + parseInt(stms);
    var quiz_time = millisToMinutesAndSeconds(totalmsec);
    $("#quiz_total_time").val(quiz_time);
}

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
                            '<label for="">Winner No. '+i+'<sup>*</sup></label><br>'+
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
</script>
@endsection
@section('content')
<div class="container-fluid">
    @include('flash-message')
</div>
<div class="container-fluid">
    <!-- <div class="card">
        <div class="card-header">
           <div class="row">
                
                <div class="col-md-8">
                  <h5 class="m-0">Quiz Reward Distribution Edit</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/quiz/reward/update/')}}/{{$cat_ques[0]['quiz_qroup_id']}}" enctype="multipart/form-data" method="POST" id="reward_form" files="true">
            
        <div class="card-body">
               @csrf
               <div class="row">
                    <div class="col-md-12" style="color: indianred">
                        <label><b>Coin Currency :</b></label>
                        <span> INR 1 = {{isset($coin['coin_currency_value'])? $coin['coin_currency_value']." Coins" : 'Coin Currency Not Set' }}</span>
                    </div>
                    
                </div>
               <div class="row">

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="quiz_name">{{__('Enter Winners for Quiz')}} <sup>*</sup></label><br>
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
                                    <label for="">Winner No. {{$reward[$i]['position']}}<sup>*</sup></label><br>
                                    <input type="number" class="winner-input form-control input-css" name="position[]" autocomplete="off" id="winner_input_{{$reward[$i]['position']}}" value="{{$reward[$i]['position_amount']}}" required>
                                </div>              
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <div class="form-group row mb-0">
                    <div class="col-md-1 offset-md-11">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div> -->
    <div class="card">
        <div class="card-header">
           <div class="row">
                <div class="col-md-12">
                    <div class="col-md-11 card-head-left">
                      <h5 class="m-0">Edit Quiz Category Questions</h5>
                    </div>
                </div>
            </div>
        </div>  
        <form  action="{{url('/quiz/category/questions/update')}}/{{$cat_ques[0]['quiz_qroup_id']}}" enctype="multipart/form-data" method="POST" id="quiz_create_form" files="true">
            
            <div class="card-body">
               @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label>{{__('Quiz Category')}} <sup>*</sup></label>
                        <input type="hidden" name="cat_name" value='{{$cat_ques[0]["quiz_category_id"]}}'>
                        <select class="select2bs4 form-control @error('cat_name') is-invalid @enderror" id="cat_name"  disabled="disabled">
                            <option value="">Select Quiz Category</option>
                            @foreach($category as $cat)
                                <option value="{{$cat['quiz_category_id']}}" {{ ($cat_ques[0]["quiz_category_id"]== $cat['quiz_category_id'])?'selected':''}}>{{$cat['quiz_category_name']}}</option>
                            @endforeach
                            
                        </select>
                            @error('cat_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <div class="col-md-6">
                        <label>{{__('Quiz Title')}} <sup>*</sup></label>
                        <input type="text" class="capital form-control input-css @error('quiz_title') is-invalid @enderror" name="quiz_title"
                            value="{{ $cat_ques[0]['quiz_title'] }}" autocomplete="off">

                            @error('quiz_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    
                </div><br>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="quiz_total_time">{{__('Quiz Total Time')}} <sup>*</sup></label><br>
                            <input type="text" class="quiz_total_time datetimepicker-input form-control input-css @error('quiz_total_time') is-invalid @enderror" name="quiz_total_time" id="quiz_total_time" value="{{$cat_ques[0]['quiz_time']}}" autocomplete="off" data-target="#quiz_total_time" data-toggle="datetimepicker" onchange="perQuizTime()">
                        </div> 
                        <span class="invalid-feedback" role="alert" id="number-err" style="display: none;"></span>
                        @error('quiz_total_time')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                </div>
                <br><hr>
                
                <div class="row div_tocopy ques_count">
                    <input type="hidden" value="{{$cat_ques[0]['ques_id']}}" name="ques_id[]">
                    <div class="col-md-12">
                        <label>{{__('Quiz Question')}} 1<sup>*</sup></label>
                        <textarea class="cat_ques form-control input-css @error('cat_ques.0') is-invalid @enderror" name="cat_ques[]" autocomplete="off" placeholder="Enter Quiz Question here...">{{$cat_ques[0]['question']}}</textarea>
                            @error('cat_ques.0')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror<br>
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label for="cat_icon">{{__('Option 1')}}<sup>*</sup></label><br>
                            <input type="text" class="cat_option1 form-control input-css @error('cat_option1.*') is-invalid @enderror" id="cat_option1_0" autocomplete="off" value="{{$cat_ques[0]['option1']}}"  name="cat_option1[]" placeholder="Option 1">
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
                            <input type="text" class="cat_option2 form-control input-css @error('cat_option2.0') is-invalid @enderror" id="cat_option2_0" autocomplete="off" value="{{$cat_ques[0]['option2']}}" placeholder="Option 2" name="cat_option2[]">
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
                            <input type="text" class="cat_option3 form-control input-css @error('cat_option3.0') is-invalid @enderror" id="cat_option3_0" autocomplete="off" value="{{$cat_ques[0]['option3']}}"  name="cat_option3[]" placeholder="Option 3">
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
                            <input type="text" class=" cat_option4 form-control input-css @error('cat_option4.0') is-invalid @enderror" id="cat_option4_0" autocomplete="off" value="{{$cat_ques[0]['option4']}}" name="cat_option4[]" placeholder="Option 4">
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
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option1" name="answer[0]" {{($cat_ques[0]['answer']=='option1')?'checked=checked':""}} >Option 1
                              </label>
                            </div>
                            <div class="form-check-inline">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option2" name="answer[0]" {{($cat_ques[0]['answer']=='option2')?'checked=checked':""}}>Option 2
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option3" name="answer[0]" {{($cat_ques[0]['answer']=='option3')?'checked=checked':""}} >Option 3
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option4" name="answer[0]" {{($cat_ques[0]['answer']=='option4')?'checked=checked':""}} >Option 4
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
                        <input type="number" class="point_question form-control input-css @error('point_question') is-invalid @enderror" name="point_question[]" id="point_question" min="0" value="{{$cat_ques[0]['question_point']}}" autocomplete="off">
                        @error('point_question.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-4 ">
                        <label for="cat_option4">{{__('Question Time')}} (Minute:Seconds)<sup>*</sup></label><br>
                        <input type="text" class="time_p_ques datetimepicker-input form-control input-css @error('time_p_ques') is-invalid @enderror" name="time_p_ques[]" id="time_p_ques_0" value="{{$cat_ques[0]['question_time']}}" autocomplete="off" data-target="#time_p_ques_0" data-toggle="datetimepicker" onblur="ques_time_change()">
                        @error('time_p_ques.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                </div>
                <!-- div to replicate data -->
                <div class="replicate_div">
                    @if($cat_ques)
                        @if(count($cat_ques) > 1)
                            @for($i=1;$i < count($cat_ques);$i++)
                                <div class="row div_tocopy ques_count appended-content">
                                    <div class="col-md-12">
                                        <a href="javascript:void(0)" class="rm-btn"><i class="fa fa-trash-alt"></i></a>
                                    </div>
                                    <input type="hidden" value="{{$cat_ques[$i]['ques_id']}}" name="ques_id[]">
                                    <div class="col-md-12">
                                        <label>{{__('Quiz Question')}} {{$i+1}}<sup>*</sup></label>
                                        <textarea class="cat_ques form-control input-css @error('cat_ques.*') is-invalid @enderror" name="cat_ques[]" autocomplete="off" placeholder="Enter Quiz Question here...">{{$cat_ques[$i]['question']}}</textarea>
                                            @error('cat_ques.*')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror<br>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label for="cat_icon">{{__('Option 1')}}<sup>*</sup></label><br>
                                            <input type="text" class="cat_option1 form-control input-css @error('cat_option1.*') is-invalid @enderror" id="cat_option1_{{$i}}" autocomplete="off" value="{{$cat_ques[$i]['option1']}}"  name="cat_option1[]" placeholder="Option 1">
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
                                            <input type="text" class="cat_option2 form-control input-css @error('cat_option2.*') is-invalid @enderror" id="cat_option2_{{$i}}" autocomplete="off" value="{{$cat_ques[$i]['option2']}}" placeholder="Option 2" name="cat_option2[]">
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
                                            <input type="text" class="cat_option3 form-control input-css @error('cat_option3.*') is-invalid @enderror" id="cat_option3_{{$i}}" autocomplete="off" value="{{$cat_ques[$i]['option3']}}"  name="cat_option3[]" placeholder="Option 3">
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
                                            <input type="text" class=" cat_option4 form-control input-css @error('cat_option4.*') is-invalid @enderror" id="cat_option4_{{$i}}" autocomplete="off" value="{{$cat_ques[$i]['option4']}}" name="cat_option4[]" placeholder="Option 4">
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
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option1" name="answer[{{$i}}]" {{($cat_ques[$i]['answer']=='option1')?'checked="checked"':""}}>Option 1
                                              </label>
                                            </div>
                                            <div class="form-check-inline">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option2" name="answer[{{$i}}]" {{($cat_ques[$i]['answer']=='option2')?'checked="checked"':""}}>Option 2
                                              </label>
                                            </div>
                                            <div class="form-check-inline ">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option3" name="answer[{{$i}}]" {{($cat_ques[$i]['answer']=='option3')?'checked="checked"':""}}>Option 3
                                              </label>
                                            </div>
                                            <div class="form-check-inline ">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option4" name="answer[{{$i}}]" {{($cat_ques[$i]['answer']=='option4')?'checked="checked"':""}} >Option 4
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
                                        <input type="number" class="point_question form-control input-css @error('point_question') is-invalid @enderror" name="point_question[]" id="point_question_{{$i}}" min="0" value="{{$cat_ques[$i]['question_point']}}" autocomplete="off">
                                        @error('point_question.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="cat_option4">{{__('Question Time')}} (Minute:Seconds)<sup>*</sup></label><br>
                                        <input type="text" class="time_p_ques datetimepicker-input form-control input-css @error('time_p_ques') is-invalid @enderror" name="time_p_ques[]" id="time_p_ques_{{$i}}" value="{{$cat_ques[$i]['question_time']}}" autocomplete="off" data-target="#time_p_ques_{{$i}}" data-toggle="datetimepicker" onblur="ques_time_change()">
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
                <div class="row">
                    <div class="col-md-11"></div>
                    <div class="col-md-1"><button type="button" class="ques_addOther btn btn-outline-danger btn-xs"><i class="fa fa-plus " style="cursor:pointer;">Add More</i></button></div>
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
        
    </div><br>
</div>
@endsection
