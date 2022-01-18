@extends('layouts.main')
@section('title', 'Manage App Users')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Manage App Users</li>
@endsection
@section('css')

@endsection
@section('js')

<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    
    $(function () {
    
    $('#user_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "/app/user/management/list/api",
            "aaSorting": [],
            "responsive": true,
            "columns": [
              { "data": "app_user_id" },
              { "data": "full_name" }, 
              { "data": "dob" },
              { "data": "email" },
              { "data": "phone" },
              { "data": "register_type_name" },
              { "data": "verified" },
              { "data": "cash_wallet_balance" },
              { "data": "coin_wallet_balance" },
                {
                  "targets": [ -1 ],
                  "data":"profile_pic", "render": function(data,type,full,meta)
                  {
                     var str = '';

                     if(data=='' || data==null ){
                      str = "";
                     }else{
                        str = "<img src='/upload/user_image/"+data+"' width='30' height='30' target='_blank'>";
                     }

                     return str;

                  },
                 "orderable": false
              },
              {
                  "targets": [ -1 ],
                  "data":"app_user_id", "render": function(data,type,full,meta)
                  {
                    
                    var str ="<a href='/app/user/view/"+data+"' target='_blank' ><button class='btn btn-outline-success btn-xs'> View </button></a> &nbsp;";
                    
                    str +="<a href='/app/user/management/update/"+data+"' target='_blank' ><button class='btn btn-outline-secondary btn-xs'> Edit </button></a> &nbsp;";
                    
                    str += '<a href="/app/user/management/delete/'+data+'" onclick="return confirm(\'Do You Really Want To Delete?\')"><button class="btn btn-outline-danger btn-xs"> Delete</button></a> &nbsp;' ; 
                    
                    return str;
                  },
                  "orderable": false
              }
           ]
       });
        
  });
    
</script>
@endsection
@section('content')
<div class="container-fluid">
    @include('flash-message')
</div>
<div class="container-fluid">
    <!-- <div class="row">
        <div class="col-md-12">
            
            <div class="offset-md-10 col-md-2" style="display: inline">
                <a href="/user/management/create" class="btn btn-success">Add Users</a>
            </div>
             <div class="col-md-2" style="display: inline">
                <a href="/user/management/archive/listing" class="btn btn-success">Archive Users</a>
            </div> 
            
        </div>
    </div>
    <br> -->
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5 card-head-left">
                      <h5>Manage App Users</h5>
                    </div>
                    
                    <div class="card-btn-div card-btn-right" >
                        <a href="/app/user/management/create" class="btn btn-outline-primary btn-sm ">Add App User</a>
                        <a href="/app/user/export" class="btn btn-outline-dark btn-sm ">Export</a>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="card-body">

            <table id="user_table" class="table table-bordered table-striped">
            <thead>
                <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Date Of Birth</th>
                      <th>Email</th>
                      <th>Phone Number</th>
                      <th>Registration Type</th>
                      <th>Verification Status</th>
                      <th>Cash Balance</th>
                      <th>Coin Balance</th>
                      <th>Profile Pic</th>
                      <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
       
          </table>
        </div>
    </div>
</div>
@endsection
