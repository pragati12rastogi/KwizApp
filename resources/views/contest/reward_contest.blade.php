@extends('layouts.main')
@section('title', 'Contest Reward Distribution')

@section('user',Auth::user()->name)

@section('breadcrumb')

<li class="breadcrumb-item"><a href="/contest/summary">Manage Contest</a></li>
<li class="breadcrumb-item active">Contest Reward Distribution</li>
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
                  <h5 class="m-0">Contest Reward Distribution</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/contest/reward')}}/{{$contest_id}}" enctype="multipart/form-data" method="POST" id="contest_form" files="true">
            
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
                                    <label for="">Winner No. {{$i+1}}<sup>*</sup></label><br>
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