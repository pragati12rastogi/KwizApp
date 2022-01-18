@extends('layouts.main')
@section('title', 'Redeem Money')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Redeem Money</li>
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

    $('#redeem_form').validate({ // initialize the plugin
        rules: {

            redeem_coin_min: {
                required: true
            },
            redeem_coin_max: {
                required: true
            },
            redeem_cash_min: {
                required: true
            },
            redeem_cash_max: {
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
                  <h5 class="m-0">Redeem Money</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/redeem/money')}}" enctype="multipart/form-data" method="POST" id="redeem_form" files="true">
            
        <div class="card-body">
               @csrf
               <div class="row">

                    <div class="col-md-6" style="color: indianred">
                        <label><b>Coin Currency :</b></label>
                        <span> INR 1 = {{isset($coin['coin_currency_value'])? $coin['coin_currency_value']." Coins" : 'Coin Currency Not Set' }}</span>
                    </div>
                    
               </div>
               <div class="row">

                    <div class="col-md-3" style="color: indianred">
                        <label><b>Updated By :</b></label>
                        <span>{{isset($redeem['updated_by_name'])? $redeem['updated_by_name'] : '' }}</span>
                    </div>
                    <div class="col-md-3" style="color: indianred">
                        <label><b>Last Updated By :</b></label>
                        <span>{{isset($redeem['last_updated_at'])? date('d-m-Y',strtotime($redeem['last_updated_at'])) : '' }}</span>
                    </div>
                    
               </div><br>

                <div class="row">
                    <input type="hidden" name="redeem_money_id" value="{{isset($redeem['redeem_money_id'])? $redeem['redeem_money_id'] : '' }}">
                    <div class="col-md-12">
                        <label>{{__('Coin to Cash Redeem Amount')}} <sup>*</sup></label><br>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Min')}} <sup>*</sup></label><br>
                        <input autocomplete="off" type="number" step="1" id="redeem_coin_min" class="input-css form-control col-md-8 @error('redeem_coin_min') is-invalid @enderror" name="redeem_coin_min" value="{{isset($redeem['redeem_coin_amt_min'])? $redeem['redeem_coin_amt_min'] : 0 }}" placeholder="0"  style="display: inline-block;">
                        @error('redeem_coin_min')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Max')}} <sup>*</sup></label><br>
                        <input autocomplete="off" type="number" step="1" id="redeem_coin_max" class="input-css form-control col-md-8 @error('redeem_coin_max') is-invalid @enderror" name="redeem_coin_max" value="{{isset($redeem['redeem_coin_amt_max'])? $redeem['redeem_coin_amt_max'] : 0 }}" placeholder="0"  style="display: inline-block;">
                        @error('redeem_coin_max')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                </div><br>
                <div class="row">
                    <div class="col-md-12">
                        <label>{{__('Cash to Paytm Redeem Amount')}} <sup>*</sup></label><br>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Min')}} <sup>*</sup></label><br>
                        <input autocomplete="off" type="number" step="0.01" id="redeem_cash_min" class="input-css form-control col-md-8 @error('redeem_cash_min') is-invalid @enderror" name="redeem_cash_min" value="{{isset($redeem['redeem_cash_amt_min'])? $redeem['redeem_cash_amt_min'] : 0 }}" placeholder="0"  style="display: inline-block;">
                        @error('redeem_cash_min')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Max')}} <sup>*</sup></label><br>
                        <input autocomplete="off" type="number" step="0.01" id="redeem_cash_max" class="input-css form-control col-md-8 @error('redeem_cash_max') is-invalid @enderror" name="redeem_cash_max" value="{{isset($redeem['redeem_cash_amt_max'])? $redeem['redeem_cash_amt_max'] : 0 }}" placeholder="0"  style="display: inline-block;">
                        @error('redeem_cash_max')
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
