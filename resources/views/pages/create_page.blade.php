@extends('layouts.main')
@section('title', 'App Page Creation')

@section('user',Auth::user()->name)

@section('breadcrumb')
<!-- <li class="breadcrumb-item"><a href="/quiz/category/list">App Pages Creation</a></li> -->
<li class="breadcrumb-item active">App Page Creation</li>
@endsection
@section('css')
  <style>
    label{
      font-weight: 500!important;
    }
    sup{
      color: red;
    }
    .card-btn-right{
      float: right;
    }
    .card-btn-div{
      display: inline;
    }
    .card-head-left{
      float: left;
      padding-top: 5px;
    }
    .img-fluid {
        height: 95px;
    }
    a{
      color: black;
    }
  </style>
@endsection
@section('js')
<script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
<script>
    
  $(function () {
    

    $('#create_form').validate({ // initialize the plugin
        rules: {

            page_name: {
                required: true
            },
            editor1: {
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
                <div class="col-md-12">
                    <div class="col-md-2 card-head-left">
                      <h5 class="m-0">App Page Creation</h5>
                    </div>
                </div>
            </div>
        </div>  
        <form  action="{{url('/create/required/page')}}" enctype="multipart/form-data" method="POST" id="create_form" files="true">
            
        <div class="card-body">
               @csrf
                <div class="row">

                    <div class="col-md-12">
                        <label><b>{{__('Page Name')}} </b><sup>*</sup></label>
                        <div class="form-group @error('page_name') is-invalid @enderror">
                            <div class="form-check-inline">
                              <label class="form-check-label">
                                <a href="/create/required/page/privacy_policy">  
                                  <input type="radio" class="form-check-input" value="privacy_policy" name="page_name" {{($title=="privacy_policy")?'checked=checked':''}}>Privacy Policy
                                </a>
                              </label>
                            </div>
                            <div class="form-check-inline">
                              <label class="form-check-label">
                                <a href="/create/required/page/terms_and_conditions">
                                  <input type="radio" class="form-check-input" value="terms_and_conditions" name="page_name" {{($title=="terms_and_conditions")?'checked=checked':''}}>Terms And Conditions
                                </a>
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <a href="/create/required/page/about_us">
                                  <input type="radio" class="form-check-input" value="about_us" name="page_name" {{($title=="about_us")?'checked=checked':''}}>About Us
                                </a>
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <a href="/create/required/page/contact_us">
                                  <input type="radio" class="form-check-input" value="contact_us" name="page_name" {{($title=="contact_us")?'checked=checked':''}}>Contact Us
                                </a>
                              </label>
                            </div>
                            <div class="form-check-inline ">
                              <label class="form-check-label">
                                <a href="/create/required/page/how_to_use_app">
                                  <input type="radio" class="form-check-input" value="how_to_use_app" name="page_name" {{($title=="how_to_use_app")?'checked=checked':''}}>How To Use App
                                </a>
                              </label>
                            </div>
                        </div> 
                            @error('page_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>
                    
                </div><br>
                <div class="row">
                  <div class="col-md-12">
                      <textarea name="editor1" id="editor1" class='ckeditor' required="required">{{ isset($page_detail['content'])?$page_detail['content']:'' }}</textarea>
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
