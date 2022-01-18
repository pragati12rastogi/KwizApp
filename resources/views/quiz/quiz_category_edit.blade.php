@extends('layouts.main')
@section('title', 'Edit Quiz Category')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/quiz/category/list">Manage Quiz Category</a></li>
<li class="breadcrumb-item active">Edit Quiz Category</li>
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
    
    $('#quiz_edit_form').validate({ // initialize the plugin
        rules: {

            cat_name: {
                required: true,
                maxlength:200
            },
            cat_time:{
                required:true
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
                      <h5 class="m-0">Edit Quiz Category</h5>
                    </div>
                </div>
            </div>
        </div>  
        <form  action="{{url('/quiz/category/edit')}}/{{$cat['quiz_category_id']}}" enctype="multipart/form-data" method="POST" id="quiz_edit_form" files="true">
            
        <div class="card-body">
               @csrf
                <div class="row">

                    <div class="col-md-6">
                        <label>{{__('Category Name')}} <sup>*</sup></label>
                        <input type="text" class="capital form-control input-css @error('cat_name') is-invalid @enderror" name="cat_name"
                            value="{{ $cat['quiz_category_name'] }}" autocomplete="off">
                            @error('cat_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    
                </div><br>
                <div class="row">
                    <div class="col-md-6 {{ $errors->has('cat_icon') ? ' has-error' : ''}}">
                        <label for="">{{__('Profile Picture')}} </label>
                        <br>
                        @if($cat['quiz_category_icon'] != "" || $cat['quiz_category_icon'] != null)
                            @if (file_exists(public_path().'/upload/quiz_cat_icon/'.$cat['quiz_category_icon'] ))
                                <img src="{{asset('/upload/quiz_cat_icon/')}}/{{$cat['quiz_category_icon']}}" height="50" width="100">
                            @endif
                        @endif
                        <br>
                        <br>
                        <input type="file" accept="image/*" name="cat_icon" value="{{$cat['quiz_category_icon']}}" id="" class="cat_icon ">
                        {!! $errors->first('upd_user_photo', '<p class="help-block">:message</p>') !!} 
                    </div>
                    <input type="text" name="old_cat_icon" value="{{$cat['quiz_category_icon']}}" hidden>
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
