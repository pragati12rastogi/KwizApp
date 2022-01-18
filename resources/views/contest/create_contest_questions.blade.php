@extends('layouts.main')
@section('title', 'Add Contest Questions')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/contest/summary">Manage Contest</a></li>
<li class="breadcrumb-item active">Add Contest Questions</li>
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

    $(function(){

        $('#contest_create_form').validate({ // initialize the plugin
            rules: {

                cat_name: {
                    required: true
                }
            },errorPlacement: function(error,element)
            {
                if ($(element).hasClass('select2bs4')) {
                    var place = $(element).siblings(".select2bs4-container");
                    error.insertAfter(place);
                }
                else if($(element).attr('type') == 'radio')
                {
                    error.insertAfter(element.parent());
                }
                else
                error.insertAfter(element);
            }
        });
        $(".cat_ques,.cat_option1,.cat_option2,.cat_option3,.cat_option4,.time_p_ques,.point_question").each(function (item) {
            $(this).rules("add", {
                required: true
            });
        });
    }) 
  $(function() {
    
    $('#contest_create_form').on('submit',function(e){
        
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
    });

    $('.time_p_ques').datetimepicker({
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
                        '<label>{{__("Contest Question")}} '+show_count+' <sup>*</sup></label>'+
                        '<textarea class="cat_ques form-control input-css " id="cat_ques_'+count+'" name="cat_ques[]" value="" autocomplete="off" placeholder="Enter Contest Question here..."></textarea><br>'+
                    '</div>'+
                    '<div class="col-md-3 ">'+
                        '<div class="form-group">'+
                            '<label for="cat_icon">{{__("Option 1")}}<sup>*</sup></label><br>'+
                            '<input type="text" autocomplete="off" class="cat_option1 form-control input-css " id="cat_option1_'+count+'"  name="cat_option1[]" placeholder="Option 1">'+
                        '</div>'+               
                    '</div>'+
                    '<div class="col-md-3 ">'+
                        '<div class="form-group">'+
                            '<label for="cat_option2">{{__("Option 2")}}<sup>*</sup></label><br>'+
                            '<input type="text" autocomplete="off" class="cat_option2 form-control input-css " id="cat_option2_'+count+'" placeholder="Option 2" name="cat_option2[]">'+
                        '</div>'+ 
                    '</div>'+
                    '<div class="col-md-3 ">'+
                        '<div class="form-group">'+
                            '<label for="cat_option3">{{__("Option 3")}}<sup>*</sup></label><br>'+
                            '<input type="text" autocomplete="off" class="cat_option3 form-control input-css" id="cat_option3_'+count+'"  name="cat_option3[]" placeholder="Option 3">'+
                        '</div>'+              
                    '</div>'+
                    '<div class="col-md-3 ">'+
                        '<div class="form-group">'+
                            '<label for="cat_option4">{{__("Option 4")}}<sup>*</sup></label><br>'+
                            '<input type="text" autocomplete="off" class="cat_option4 form-control input-css @error("cat_option4") is-invalid @enderror" id="cat_option4_'+count+'"  name="cat_option4[]" placeholder="Option 4">'+
                        '</div> '+
                    '</div>'+
                    '<div class="col-md-4 ">'+
                        '<label for="cat_option4">{{__("Contest Answer")}}  '+show_count+'<sup>*</sup></label><br>'+
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
                        '<label for="cat_option4">{{__("Question Point")}}<sup>*</sup></label><br>'+
                        '<input type="number" class="point_question form-control input-css " name="point_question[]" id="point_question" min="0" value="" autocomplete="off">'+              
                    '</div>'+
                    '<div class="col-md-4 ">'+
                        '<label for="cat_option4">{{__("Question Time")}} (Minute:Seconds)<sup>*</sup></label><br>'+
                        '<input type="text" class="time_p_ques datetimepicker-input form-control input-css " name="time_p_ques[]" id="time_p_ques_'+count+'" autocomplete="off" data-target="#time_p_ques'+count+'" data-toggle="datetimepicker" value="01:00">'+         
                    '</div>'+
                '</div>');
                $('.time_p_ques').datetimepicker({
                  showSecond: true,
                  format: 'mm:ss'
                });
    });


});
</script>
@endsection
@section('content')
<div class="container-fluid">
    @include('flash-message')
</div>
<div class="container-fluid">
    
    <div class="card">
        <div class="card-header">
           <div class="row">
                <div class="col-md-12">
                    <div class="col-md-11 card-head-left">
                      <h5 class="m-0">Add Contest Questions</h5>
                    </div>
                </div>
            </div>
        </div>  
        <form  action="{{url('/contest/question/create/')}}/{{$contest_id}}" enctype="multipart/form-data" method="POST" id="contest_create_form" files="true">
            
        <div class="card-body">
               @csrf

                <!-- <div class="row">
                    <div class="col-md-12" style="color: indianred">
                        <label><b>Coin Currency :</b></label>
                        <span> INR 1 = {{isset($coin_currency['coin_currency_value'])? $coin_currency['coin_currency_value']." Coins" : 'Coin Currency Not Set' }}</span>
                    </div>
                    
                </div> -->
                <input type="hidden" value="{{$contest_id}}" name="contest_id">
                <div class="row div_tocopy ques_count">
                    <div class="col-md-12">
                        <label>{{__('Contest Question')}} 1<sup>*</sup></label>
                        <textarea class="cat_ques form-control input-css @error('cat_ques.0') is-invalid @enderror" name="cat_ques[]" autocomplete="off" placeholder="Enter Contest Question here...">{{old('cat_ques.0')}}</textarea>
                            @error('cat_ques.0')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror<br>
                    </div>
                    <div class="col-md-3 ">
                        <div class="form-group">
                            <label for="cat_icon">{{__('Option 1')}}<sup>*</sup></label><br>
                            <input type="text" class="cat_option1 form-control input-css @error('cat_option1.*') is-invalid @enderror" id="cat_option1_0" autocomplete="off" value="{{old('cat_option1.0')}}"  name="cat_option1[]" placeholder="Option 1">
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
                            <input type="text" class="cat_option2 form-control input-css @error('cat_option2.0') is-invalid @enderror" id="cat_option2_0" autocomplete="off" value="{{old('cat_option2.0')}}" placeholder="Option 2" name="cat_option2[]">
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
                            <input type="text" class="cat_option3 form-control input-css @error('cat_option3.0') is-invalid @enderror" id="cat_option3_0" autocomplete="off" value="{{old('cat_option3.0')}}"  name="cat_option3[]" placeholder="Option 3">
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
                            <input type="text" class=" cat_option4 form-control input-css @error('cat_option4.0') is-invalid @enderror" id="cat_option4_0" autocomplete="off" value="{{old('cat_option4.0')}}" name="cat_option4[]" placeholder="Option 4">
                        </div> 
                        @error('cat_option4.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror            
                    </div>
                    <div class="col-md-4 ">
                        <label for="cat_option4">{{__('Contest Answer')}}  1<sup>*</sup></label><br>
                        <div class="form-group @error('answer.*') is-invalid @enderror">
                            <div class="form-check-inline">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option1" name="answer[0]">Option 1
                              </label>
                            </div>
                            <div class="form-check-inline">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option2" name="answer[0]">Option 2
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option3" name="answer[0]" >Option 3
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <input type="radio" class="ques_ans ques_ans_0 form-check-input" value="option4" name="answer[0]" >Option 4
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
                        <input type="number" class="point_question form-control input-css @error('point_question') is-invalid @enderror" name="point_question[]" id="point_question" min="0" value="{{old('point_question.0')}}" autocomplete="off">
                        @error('point_question.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-4 ">
                        <label for="cat_option4">{{__('Question Time')}} (Minute:Seconds)<sup>*</sup></label><br>
                        <input type="text" class="time_p_ques datetimepicker-input form-control input-css @error('time_p_ques') is-invalid @enderror" name="time_p_ques[]" id="time_p_ques_0" value="{{old('time_p_ques.0')?old('time_p_ques.0'):'01:00'}}" autocomplete="off" data-target="#time_p_ques_0" data-toggle="datetimepicker">
                        @error('time_p_ques.0')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                </div>
                <!-- div to replicate data -->
                <div class="replicate_div">
                    @if(old('cat_ques'))
                        @if(count(old('cat_ques')) > 1)
                            @for($i=1;$i < count(old('cat_ques'));$i++)
                                <div class="row div_tocopy ques_count appended-content">
                                    <div class="col-md-12">
                                        <label>{{__('Contest Question')}} {{$i+1}}<sup>*</sup></label>
                                        <textarea class="cat_ques form-control input-css @error('cat_ques.*') is-invalid @enderror" name="cat_ques[]" autocomplete="off" placeholder="Enter Contest Question here...">{{(old('cat_ques')[$i])}}</textarea>
                                            @error('cat_ques.*')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror<br>
                                    </div>
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label for="cat_icon">{{__('Option 1')}}<sup>*</sup></label><br>
                                            <input type="text" class="cat_option1 form-control input-css @error('cat_option1.*') is-invalid @enderror" id="cat_option1_{{$i}}" autocomplete="off" value="{{(old('cat_option1')[$i])}}"  name="cat_option1[]" placeholder="Option 1">
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
                                            <input type="text" class="cat_option2 form-control input-css @error('cat_option2.*') is-invalid @enderror" id="cat_option2_{{$i}}" autocomplete="off" value="{{old('cat_option2')[$i]}}" placeholder="Option 2" name="cat_option2[]">
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
                                            <input type="text" class="cat_option3 form-control input-css @error('cat_option3.*') is-invalid @enderror" id="cat_option3_{{$i}}" autocomplete="off" value="{{old('cat_option3')[$i]}}"  name="cat_option3[]" placeholder="Option 3">
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
                                            <input type="text" class=" cat_option4 form-control input-css @error('cat_option4.*') is-invalid @enderror" id="cat_option4_{{$i}}" autocomplete="off" value="{{old('cat_option4')[$i]}}" name="cat_option4[]" placeholder="Option 4">
                                        </div> 
                                        @error('cat_option4.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="cat_option4">{{__('Contest Answer')}} {{$i+1}}<sup>*</sup></label><br>
                                        <div class="form-group @error('answer.*') is-invalid @enderror">
                                            <div class="form-check-inline">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option1" name="answer[{{$i}}]" {{(old('answer')[$i]=='option1')? 'checked="checked" ':""}}>Option 1
                                              </label>
                                            </div>
                                            <div class="form-check-inline">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option2" name="answer[{{$i}}]" {{(old('answer')[$i]=='option2')?'checked="checked"':""}}>Option 2
                                              </label>
                                            </div>
                                            <div class="form-check-inline ">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option3" name="answer[{{$i}}]" {{(old('answer')[$i]=='option3')?'checked="checked"':""}}>Option 3
                                              </label>
                                            </div>
                                            <div class="form-check-inline ">
                                              <label class="form-check-label">
                                                <input type="radio" class="ques_ans ques_ans_{{$i}} form-check-input" value="option4" name="answer[{{$i}}]" {{(old('answer')[$i]=='option4')?'checked="checked"':""}} >Option 4
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
                                        <input type="number" class="form-control input-css @error('point_question.*') is-invalid @enderror" name="point_question[]" id="point_question" min="0" value="{{old('point_question')[$i]}}" autocomplete="off">
                                        @error('point_question.*')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror              
                                    </div>
                                    <div class="col-md-4 ">
                                        <label for="cat_option4">{{__('Question Time')}} (Minute:Seconds)<sup>*</sup></label><br>
                                        <input type="text" class="time_p_ques datetimepicker-input form-control input-css @error('time_p_ques.*') is-invalid @enderror" name="time_p_ques[]" id="time_p_ques_{{$i}}" value="{{old('time_p_ques')[$i]}}" autocomplete="off" data-target="#time_p_ques_{{$i}}" data-toggle="datetimepicker">
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
        
    </div>
</div>
@endsection
