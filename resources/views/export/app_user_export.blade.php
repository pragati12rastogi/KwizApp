@extends('layouts.main')
@section('title', 'App User Export')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/app/user/management">Manage App Users</a></li>
<li class="breadcrumb-item active">App User Export</li>
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
    .select2-search__field{
        min-width: 245px;
    }
</style>
@endsection
@section('js')
<script>
  $(function() {
    
    $('#export_form').validate({ // initialize the plugin
        rules: {

            user_type: {
                required: true
            },
            time_report: {
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
                <div class="col-md-12">
                    <div class="col-md-2 card-head-left">
                      <h5 class="m-0">App User Export</h5>
                    </div>
                </div>
            </div>
        </div>  
        <form  action="{{url('/app/user/export')}}" enctype="multipart/form-data" method="POST" id="user_create_form" files="true">
            
        <div class="card-body">
               @csrf
                <div class="row">

                    <div class="col-md-6 ">
                        <label>{{__('Export For')}} <sup>*</sup></label>
                        <select name="user_type" class="select2bs4 form-control">
                            <option value="total_user">Total Users</option>
                            <option value="online_user">Online Users</option>
                        </select> 
                        @error('user_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label>{{__('Filter Records')}} <sup>*</sup></label>
                        <select name="time_report" class="select2bs4 form-control">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="last7day">Last 7 Day</option>
                            <option value="last30day">Last 30 Day</option>
                            <option value="currentweek">Current Week</option>
                            <option value="currentmonth">Current Month</option>
                            <option value="lastmonth">Last Month</option>
                            <option value="all">Over All</option>
                        </select> 
                         @error('time_report')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-md-12 " >
                        <label for="columns_in_excel">Columns Include In Excel</label>

                        <select class="form-control select2bs4 columns_in_excel input-css" id="columns_in_excel"
                        data-placeholder="Select Fields (for all keep it blank)" style="width: 100%;" name="columns_in_excel[]" multiple>
                            @foreach($columns as $key=>$val)
                                <option value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                        {!! $errors->first('excel', '<p class="help-block">:message</p>') !!}

                    </div> 
                    
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