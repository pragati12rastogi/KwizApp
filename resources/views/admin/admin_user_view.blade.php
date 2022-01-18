@extends('layouts.main')
@section('title', 'View Admin User')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/admin/user/list">Manage Admin Users</a></li>
<li class="breadcrumb-item active">View Admin User</li>
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
    
    
        <!-- <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5 card-head-left">
                      <h5>View Admin Users</h5>
                    </div>
                </div>
            </div>
        </div> -->
        
       <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              
              @if($users['profile_picture'] != "" || $users['profile_picture'] != null)
                  @if (file_exists(public_path().'/upload/admin_profile/'.$users['profile_picture'] ))
                      <img src="{{asset('/upload/admin_profile/')}}/{{$users['profile_picture']}}" class="profile-user-img img-fluid img-circle" alt="User Image">
                  @else
                    <img src="/dist/img/user2-160x160.jpg" class="profile-user-img img-fluid img-circle" alt="User Image">
                  @endif
              @else
                  <img src="/dist/img/user2-160x160.jpg" class="profile-user-img img-fluid img-circle" alt="User Image">
              @endif
                   
            </div>

            <h3 class="profile-username text-center">{{$users['name']}}</h3>

            <p class="text-muted text-center">{{$users['role_name']}}</p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <div class="row">
                  
                  <div class="col-md-6 inline-block">
                      <b class="m-4">Email</b> <span class="float-right mx-sm-5">{{$users['email']}}</span>
                  </div>
                  <div class="col-md-6 inline-block">
                      <b class="m-4">Phone</b> <span class="float-right mx-sm-5">{{$users['phone']}}</span>
                  </div>
                  
                  
                </div>
              </li>
              <li class="list-group-item">
                <div class="row">
                  
                 
                  <div class="col-md-6 inline-block">
                      <b class="m-4">Status</b> <span class="float-right mx-sm-5">{{$users['status']}}</span>
                  </div>
                  
                </div>
              </li>
              
            </ul>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
          
            
    
</div>
@endsection
