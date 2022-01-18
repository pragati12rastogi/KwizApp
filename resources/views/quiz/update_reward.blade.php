@extends('layouts.main')
@section('title', 'Quiz Reward Distribution Edit')

@section('user',Auth::user()->name)

@section('breadcrumb')

<li class="breadcrumb-item"><a href="/quiz/category/list">Manage Quiz Category</a></li>
<li class="breadcrumb-item active">Quiz Reward Distribution Edit</li>
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

            winner_no: {
                required: true
            }
        }
    });

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
    
    <div class="card">
        <div class="card-header">
           <div class="row">
                
                <div class="col-md-8">
                  <h5 class="m-0">Quiz Reward Distribution Edit</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/quiz/reward/update/')}}/{{$quiz_id}}" enctype="multipart/form-data" method="POST" id="contest_form" files="true">
            
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
        
    </div>
</div>
@endsection