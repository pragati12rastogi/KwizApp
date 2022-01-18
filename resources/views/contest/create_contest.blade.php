@extends('layouts.main')
@section('title', 'Contest Creation')

@section('user',Auth::user()->name)

@section('breadcrumb')

<li class="breadcrumb-item"><a href="/contest/summary">Manage Contest</a></li>
<li class="breadcrumb-item active">Contest Creation</li>
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

</style>

@endsection
@section('js')

<script>

  $(function() {
    
   
    $('#contest_form').validate({ // initialize the plugin
        rules: {

            start_time: {
                required: true
            },
            end_time:{
                required:true
            },
            contest_fee:{
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
                required:true
            },
            winner_no: {
                required: true
            },
            join_user:{
                required:true
            }

        }
    });
    var startTime = $('#start_time').datetimepicker({
        format: 'DD-MM-YYYY hh:mm A',
        minDate: new Date()
    });
    var endTime = $('#end_time').datetimepicker({
        format: 'DD-MM-YYYY hh:mm A',
        minDate: new Date()
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
                
                <div class="col-md-8">
                  <h5 class="m-0">Contest Creation</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/contest/create')}}" enctype="multipart/form-data" method="POST" id="contest_form" files="true">
            
        <div class="card-body">
                <div class="row">
                    <div class="col-md-12" style="color: indianred">
                        <label><b>Coin Currency :</b></label>
                        <span> INR 1 = {{isset($coin['coin_currency_value'])? $coin['coin_currency_value']." Coins" : 'Coin Currency Not Set' }}</span>
                    </div>
                    
                </div>
               @csrf
               <div class="row">
                    
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="contest_name">{{__('Contest Name')}} <sup>*</sup></label><br>
                            <input type="text" class="capital form-control input-css @error('contest_name') is-invalid @enderror" name="contest_name" id="contest_name" value="{{old('contest_name')}}" autocomplete="off">
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
                            <input type="number" class="form-control input-css @error('join_user') is-invalid @enderror" name="join_user" id="join_user" min="0" value="{{old('join_user')}}" autocomplete="off">
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
                            <input type="text" class="datetimepicker-input form-control input-css @error('start_time') is-invalid @enderror" name="start_time" id="start_time" value="{{old('start_time')}}" autocomplete="off" data-target="#start_time" data-toggle="datetimepicker">
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
                            <input type="text" class="datetimepicker-input form-control input-css @error('end_time') is-invalid @enderror" name="end_time" id="end_time" value="{{old('end_time')}}" autocomplete="off" data-target="#end_time" data-toggle="datetimepicker" >
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
                    
                    
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label for="contest_icon">{{__('Contest Icon')}}<sup>*</sup></label><br>
                            <input type="file" class="input-css @error('contest_icon') is-invalid @enderror" id="contest_icon"  name="contest_icon" accept="image/*">
                        </div> 
                        @error('contest_icon')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="contest_fee">{{__('Enter Contest Joining Fee')}}(In Coins) <sup>*</sup></label><br>
                            <input type="number" class="form-control input-css @error('contest_fee') is-invalid @enderror" name="contest_fee" id="contest_fee" value="{{old('contest_fee')}}" autocomplete="off">
                        </div> 
                        @error('contest_fee')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    
                </div><br>
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="contest_name">{{__('Enter Winners for Contest')}} <sup>*</sup></label><br>
                            <input type="number" class="form-control input-css @error('winner_no') is-invalid @enderror" name="winner_no" id="winner_no" value="{{old('winner_no')}}" autocomplete="off" placeholder="eg: 10 (prize distribution for top 10)" onchange="create_input()">
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
                    @if(old('position'))
                        @for($i =0;$i < count(old('position'));$i++)
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Winner No. {{$i+1}} <b>(INR)</b><sup>*</sup></label><br>
                                    <input type="number" class="winner-input form-control input-css" name="position[]" autocomplete="off" id="winner_input_{{$i+1}}" value="{{old('position')[$i]}}" required>
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
        
    </div>
</div>
@endsection
<!-- <div class="row">
    
    <div class="col-md-6 col-sm-12">
        <label for="ques_points">{{__('Points Per Question')}} <sup>*</sup></label>
        <input type="number" class="form-control input-css @error('ques_points') is-invalid @enderror" name="ques_points" id="ques_points" min="0" value="" autocomplete="off">
        @error('ques_points')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="time_p_ques">{{__('Time Per Question')}} (Minute:Seconds) <sup>*</sup></label><br>
            <input type="text" class="datetimepicker-input form-control input-css @error('time_p_ques') is-invalid @enderror" name="time_p_ques" id="time_p_ques" value="" autocomplete="off" data-target="#time_p_ques" data-toggle="datetimepicker">
        </div> 
        @error('time_p_ques')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror 
    </div>

</div><br> -->