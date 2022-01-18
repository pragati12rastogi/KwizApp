@extends('layouts.main')
@section('title', 'Coin Currency')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Coin Currency</li>
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
    
    jQuery.validator.addMethod("coin_value", function(value, element) {
         return this.optional(element) || /^(\d{1,3})(\.\d{2})$/.test(value);
     }, "Must be in INR currency format 0.99");

    $('#coin_currency_form').validate({ // initialize the plugin
        rules: {

            coin_value: {
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
                  <h5 class="m-0">Coin Currency</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/coin/currency')}}" enctype="multipart/form-data" method="POST" id="coin_currency_form" files="true">
            
        <div class="card-body">
               @csrf
               
                <div class="row">
                    <input type="hidden" name="coin_currency_id" value="{{isset($coin_currency['coin_currency_id'])? $coin_currency['coin_currency_id'] : '' }}">
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Set Coin Currency Value')}} <sup>*</sup></label><br>
                        <span><b>Rs.1 = </b></span><input autocomplete="off" type="number" step="1" id="coin_value" class="input-css form-control col-md-5 @error('coin_value') is-invalid @enderror" name="coin_value" value="{{isset($coin_currency['coin_currency_value'])? $coin_currency['coin_currency_value'] : 0 }}" placeholder="0"  style="display: inline-block;">
                        @error('coin_value')
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
                        <button type="submit" class="btn btn-primary"><i class="fa fa-thumbs-up"></i> Save</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
</div>
@endsection
