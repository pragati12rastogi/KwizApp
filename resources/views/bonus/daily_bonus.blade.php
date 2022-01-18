@extends('layouts.main')
@section('title', 'Daily Bonus Setting')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Daily Bonus Setting</li>
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

    $('#bonus_form').validate({ // initialize the plugin
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
                  <h5 class="m-0">Daily Bonus Setting</h5>
                </div>
                
            </div>
        </div>  
        <form  action="{{url('/daily/bonus/setting')}}" enctype="multipart/form-data" method="POST" id="bonus_form" files="true">
            
            <div class="card-body">
               @csrf
               <input type="hidden" name="bonus_id" value="{{isset($get_bonus['bonus_id'])? $get_bonus['bonus_id'] : '' }}">

               <div class="row">

                    <div class="col-md-3" style="color: indianred">
                        <label><b>Coin Currency :</b></label>
                        <span> INR 1 = {{isset($coin_currency['coin_currency_value'])? $coin_currency['coin_currency_value']." Coins" : 'Coin Currency Not Set' }}</span>
                    </div>
                    
               </div>
               <div class="row">

                    <div class="col-md-3" style="color: indianred">
                        <label><b>Updated By :</b></label>
                        <span>{{isset($get_bonus['created_by_name'])? $get_bonus['created_by_name'] : '' }}</span>
                    </div>
                    <div class="col-md-3" style="color: indianred">
                        <label><b>Last Updated By :</b></label>
                        <span>{{isset($get_bonus['last_updated_at'])? date('d-m-Y',strtotime($get_bonus['last_updated_at'])) : '' }}</span>
                    </div>
                    
               </div><br>
                <table id="user_table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                              <th>Days</th>
                              <th>Rewards (In Coins)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> Monday </td>
                            <td> 
                                <input autocomplete="off" type="number" step="0.01" id="monday" class="input-css form-control @error('monday') is-invalid @enderror" name="monday" value="{{isset($get_bonus['monday'])? $get_bonus['monday'] : 0 }}" placeholder="0">
                                @error('monday')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td> Tuesday </td>
                            <td>
                                <input autocomplete="off" type="number" step="0.01" id="tuesday" class="input-css form-control @error('tuesday') is-invalid @enderror" name="tuesday" value="{{isset($get_bonus['tuesday'])? $get_bonus['tuesday'] : 0 }}" placeholder="0">
                                @error('tuesday')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td> Wednesday </td>
                            <td>
                                <input autocomplete="off" type="number" step="0.01" id="wednesday" class="input-css form-control @error('wednesday') is-invalid @enderror" name="wednesday" value="{{isset($get_bonus['wednesday'])? $get_bonus['wednesday'] : 0 }}" placeholder="0">
                                @error('wednesday')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td> Thursday </td>
                            <td> 
                                 <input autocomplete="off" type="number" step="0.01" id="thursday" class="input-css form-control @error('thursday') is-invalid @enderror" name="thursday" value="{{isset($get_bonus['thursday'])? $get_bonus['thursday'] : 0 }}" placeholder="0">
                                @error('thursday')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td> Friday </td>
                            <td>
                                <input autocomplete="off" type="number" step="0.01" id="friday" class="input-css form-control @error('friday') is-invalid @enderror" name="friday" value="{{isset($get_bonus['friday'])? $get_bonus['friday'] : 0 }}" placeholder="0">
                                @error('friday')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td> Saturday </td>
                            <td>
                                <input autocomplete="off" type="number" step="0.01" id="saturday" class="input-css form-control @error('saturday') is-invalid @enderror" name="saturday" value="{{isset($get_bonus['saturday'])? $get_bonus['saturday'] : 0 }}" placeholder="0">
                                @error('saturday')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td> Sunday </td>
                            <td>
                                <input autocomplete="off" type="number" step="0.01" id="sunday" class="input-css form-control @error('sunday') is-invalid @enderror" name="sunday" value="{{isset($get_bonus['sunday'])? $get_bonus['sunday'] : 0 }}" placeholder="0">
                                @error('sunday')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>

                    </tbody>
           
                </table>
                
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
