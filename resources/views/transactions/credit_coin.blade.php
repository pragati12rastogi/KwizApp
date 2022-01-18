@extends('layouts.main')
@section('title', 'Credit Coin To User Wallet')

@section('user',Auth::user()->name)

@section('breadcrumb')

<li class="breadcrumb-item"><a href="/coin/transaction/list">Coin Transactions Summary</a></li>
<li class="breadcrumb-item active">Credit Coin To User Wallet</li>
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
    
    jQuery.validator.addMethod("credit_amt", function(value, element) {
         return this.optional(element) || /^(\d{1,3})(\.\d{2})$/.test(value);
     }, "Must be in INR currency format 0.99");

    $('#credit_coin_form').validate({ // initialize the plugin
        rules: {

            app_user: {
                required: true
            },
            credit_amt:{
                required:true,
            },
            credit_remark:{
                required:true
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
                  <h5 class="m-0">Credit Coin To User Wallet</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/credit/coin/user')}}" enctype="multipart/form-data" method="POST" id="credit_coin_form" files="true">
            
        <div class="card-body">
               @csrf
                <div class="row">

                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="app_user">{{__('User To Credit Coin Wallet')}} <sup>*</sup></label><br>
                            <select class="select2bs4 form-control @error('app_user') is-invalid @enderror" id="app_user" name="app_user" >
                                <option value="">Select User To Credit Coin Wallet</option>
                                @foreach($app_users as $id=>$name)
                                    <option value="{{$id}}" {{ (old('app_user')== $id)?'selected':''}}>{{$name}}</option>
                                @endforeach
                            </select>
                        </div> 
                        @error('app_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <label>{{__('Coin Amount To Be Credit')}} <sup>*</sup></label>
                        <input autocomplete="off" type="number" step="1" id="credit_amt" class="input-css form-control @error('credit_amt') is-invalid @enderror" name="credit_amt" value="{{ old('credit_amt') }}" placeholder="eg:10">
                        @error('credit_amt')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div><br>
                 <div class="row">
                    
                    <div class="col-md-12 col-sm-12">
                        <label>{{__('Coin Credit Remark')}} <sup>*</sup></label>
                        <textarea autocomplete="off" id="credit_remark" class="input-css form-control @error('credit_remark') is-invalid @enderror" name="credit_remark" value="{{ old('credit_remark') }}" placeholder="Write remark here..."></textarea>
                        @error('credit_remark')
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
</div>
@endsection
