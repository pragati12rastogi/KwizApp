@extends('layouts.main')
@section('title', 'View App User')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/app/user/management">Manage App Users</a></li>
<li class="breadcrumb-item active">View App User</li>
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
    
       <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              
              @if($users['profile_pic'] != "" || $users['profile_pic'] != null)
                  @if (file_exists(public_path().'/upload/user_image/'.$users['profile_pic'] ))
                      <img src="{{asset('/upload/user_image/')}}/{{$users['profile_pic']}}" class="profile-user-img img-fluid img-circle" alt="User Image">
                  @else
                    <img src="/dist/img/user2-160x160.jpg" class="profile-user-img img-fluid img-circle" alt="User Image">
                  @endif
              @else
                  <img src="/dist/img/user2-160x160.jpg" class="profile-user-img img-fluid img-circle" alt="User Image">
              @endif
                   
            </div>

            <h3 class="profile-username text-center">{{$users['full_name']}}</h3>

            <p class="text-muted text-center">{{$users['status']}}</p>

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
                      <b class="m-4">Verified</b> <span class="float-right mx-sm-5">{{$users['verified']}}</span>
                  </div>
                  <div class="col-md-6 inline-block">
                      <b class="m-4">Date Of Birth</b> <span class="float-right mx-sm-5">{{date('d-m-Y',strtotime($users['dob']))}}</span>
                  </div>
                  
                </div>
                
              </li>
              <li class="list-group-item">
                <div class="row">
                  
                  <div class="col-md-6 inline-block">
                      <b class="m-4">Register Type</b> <span class="float-right mx-sm-5">{{$users['register_type_name']}}</span>
                  </div>
                  <div class="col-md-6 inline-block">
                      <b class="m-4">Refer Code</b> <span class="float-right mx-sm-5">{{$users['refer_code']}}</span>
                  </div>
                  
                </div>
              </li>
              <li class="list-group-item">
                <div class="row">
                  
                  <div class="col-md-6 inline-block">
                      <b class="m-4">Coin Balance</b> <span class="float-right mx-sm-5">{{$users['coin_wallet_balance']}}</span>
                  </div>
                  <div class="col-md-6 inline-block">
                      <b class="m-4">Cash Balance</b> <span class="float-right mx-sm-5">{{$users['cash_wallet_balance']}}</span>
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
