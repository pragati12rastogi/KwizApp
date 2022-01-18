@extends('layouts.main')
@section('title', 'Add Quiz Category')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/quiz/category/list">Manage Quiz Category</a></li>
<li class="breadcrumb-item active">Add Quiz Category</li>
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
    
    $('#quiz_create_form').validate({ // initialize the plugin
        rules: {

            cat_name: {
                required: true,
                maxlength:200
            },
            cat_time:{
                required:true
            },
            cat_icon: {
                required: true
            }
        }
    });

    $('#timepicker').datetimepicker({
      showSecond: true,
      format: 'mm:ss'
    })

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
                      <h5 class="m-0">Add Quiz Category</h5>
                    </div>
                </div>
            </div>
        </div>  
        <form  action="{{url('/quiz/category')}}" enctype="multipart/form-data" method="POST" id="quiz_create_form" files="true">
            
        <div class="card-body">
               @csrf
                <div class="row">

                    <div class="col-md-6">
                        <label>{{__('Category Name')}} <sup>*</sup></label>
                        <input type="text" class="capital form-control input-css @error('cat_name') is-invalid @enderror" name="cat_name"
                            value="{{ old('cat_name') }}" autocomplete="off">
                            @error('cat_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    <!-- <div class="col-md-6">
                        <label>{{__('Category Time')}} <sup>*</sup></label>
                        <input type="text" class="datetimepicker-input form-control input-css @error('cat_time') is-invalid @enderror" name="cat_time" id="timepicker" value="" autocomplete="off" data-target="#timepicker" data-toggle="datetimepicker">
                            @error('cat_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div> -->
                </div><br>
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="form-group">
                            <label for="cat_icon">{{__('Category Icon')}}<sup>*</sup></label><br>
                            <input type="file" class="input-css @error('cat_icon') is-invalid @enderror" id="cat_icon"  name="cat_icon" accept="image/*">
                        </div> 
                        @error('cat_icon')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror              
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
