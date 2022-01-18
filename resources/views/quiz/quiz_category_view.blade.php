@extends('layouts.main')
@section('title', 'Quiz Category View')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/quiz/category/list">Manage Quiz Category</a></li>
<li class="breadcrumb-item active">Quiz Category View</li>
@endsection
@section('css')
  <style>
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
    .info-box {
      min-height: 45px;
      background-color:#65eac5;
    }
  </style>
@endsection
@section('js')

<script>
    
  $(function () {
    
    
        
  });
    
</script>
@endsection
@section('content')
<div class="container-fluid">
    @include('flash-message')
</div>
<div class="container-fluid">
    
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              
              @if($cat['quiz_category_icon'] != "" || $cat['quiz_category_icon'] != null)
                  @if (file_exists(public_path().'/upload/quiz_cat_icon/'.$cat['quiz_category_icon'] ))
                      <img src="{{asset('/upload/quiz_cat_icon/')}}/{{$cat['quiz_category_icon']}}" class="" alt="Category Icon">
                 @endif
              @endif
                   
            </div><br>
            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <div class="row">
                  
                  <div class="col-md-12 inline-block">
                      <b class="m-4">Category Name</b> <span class="float-right mx-sm-5">{{$cat['quiz_category_name']}}</span>
                  </div>
                  
                </div>
              </li>
              
            </ul>
            <h5 class="mb-2"><center><b>Quiz Creations</b></center></h5>
            <hr>
          <div class="row">
            @foreach($group as $g)
                <div class="col-md-3 col-sm-6 col-12">
                  <div class="info-box shadow">
                    <div class="info-box-content">
                      <span class="info-box-text">{{$g['quiz_title']}}</span>
                      <!-- <span class="info-box-number">Large</span> -->
                    </div>
                  </div>
                </div>
              @endforeach
          </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        
    
</div>
@endsection
