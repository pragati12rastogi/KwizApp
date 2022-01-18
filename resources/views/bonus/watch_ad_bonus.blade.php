@extends('layouts.main')
@section('title', 'Watch Ad Bonus')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Watch Ad Bonus</li>
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
                  <h5 class="m-0">Watch Ad Bonus</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/setting/watch/add/bonus')}}" enctype="multipart/form-data" method="POST" id="coin_currency_form" files="true">
            
        <div class="card-body">
               @csrf
               <div class="row">

                    <div class="col-md-3" style="color: indianred">
                        <label><b>Coin Currency :</b></label>
                        <span> INR 1 = {{isset($coin_currency['coin_currency_value'])? $coin_currency['coin_currency_value']." Coins" : 'Coin Currency Not Set' }}</span>
                    </div>
                    
               </div>
               <div class="row">

                    <div class="col-md-3" style="color: indianred">
                        <label><b>Updated By :</b></label>
                        <span>{{isset($bonus['created_by_name'])? $bonus['created_by_name'] : '' }}</span>
                    </div>
                    <div class="col-md-3" style="color: indianred">
                        <label><b>Last Updated By :</b></label>
                        <span>{{isset($bonus['last_updated_at'])? date('d-m-Y',strtotime($bonus['last_updated_at'])) : '' }}</span>
                    </div>
                    
               </div><br>

                <div class="row">
                    <input type="hidden" name="watch_ad_id" value="{{isset($bonus['watch_ad_bonus_id'])? $bonus['watch_ad_bonus_id'] : '' }}">
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Set Bonus Value')}} (In Coins)<sup>*</sup></label><br>
                        <span><b>1 Ad Watch = </b></span><input autocomplete="off" type="number" step="1" id="coin_value" class="input-css form-control col-md-5 @error('coin_value') is-invalid @enderror" name="coin_value" value="{{isset($bonus['bonus_amount'])? $bonus['bonus_amount'] : 0 }}" placeholder="0"  style="display: inline-block;">
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
