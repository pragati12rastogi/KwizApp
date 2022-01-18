@extends('layouts.main')
@section('title', 'Refer And Earn Bonus')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Refer And Earn Bonus</li>
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

    $('#refer_earn_form').validate({ // initialize the plugin
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
                  <h5 class="m-0">Refer And Earn Bonus</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/refer/and/earn/bonus')}}" enctype="multipart/form-data" method="POST" id="refer_earn_form" files="true">
            
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
                        <span>{{isset($bonus['updated_by_name'])? $bonus['updated_by_name'] : '' }}</span>
                    </div>
                    <div class="col-md-3" style="color: indianred">
                        <label><b>Last Updated By :</b></label>
                        <span>{{isset($bonus['last_updated_at'])? date('d-m-Y',strtotime($bonus['last_updated_at'])) : '' }}</span>
                    </div>
                    
               </div><br>

                <div class="row">
                    <input type="hidden" name="refer_earn_id" value="{{isset($bonus['refer_and_earn_id'])? $bonus['refer_and_earn_id'] : '' }}">
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Bonus Amount For Reference User')}}<b> (In Coins)</b><sup>*</sup></label><br>
                        <span><b>Reference = </b></span><input autocomplete="off" type="number" step="1" id="coin_value_ref" class="input-css form-control col-md-8 @error('coin_value_ref') is-invalid @enderror" name="coin_value_ref" value="{{isset($bonus['refer_bonus_amount'])? $bonus['refer_bonus_amount'] : 0 }}" placeholder="0"  style="display: inline-block;">
                        @error('coin_value_ref')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                </div><br>
                <div class="row">
                    
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Bonus Amount To Joinee On Reference')}} <b> (In Coins)</b><sup>*</sup></label><br>
                        <span><b>Joinee = </b></span><input autocomplete="off" type="number" step="1" id="coin_value_join" class="input-css form-control col-md-8 @error('coin_value_join') is-invalid @enderror" name="coin_value_join" value="{{isset($bonus['join_bonus_amount'])? $bonus['join_bonus_amount'] : 0 }}" placeholder="0"  style="display: inline-block;">
                        @error('coin_value_join')
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
