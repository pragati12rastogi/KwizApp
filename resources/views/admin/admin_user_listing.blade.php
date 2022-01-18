@extends('layouts.main')
@section('title', 'Manage Admin User')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Manage Admin User</li>
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
  </style>
@endsection
@section('js')

<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    
    $(function () {
    
    $('#user_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "/admin/user/list/api",
            "aaSorting": [],
            "responsive": true,
            "columns": [
              { "data": "id" },
              { "data": "name" }, 
              { "data": "email" },
              { "data": "role_name" },
              {
                  "targets": [ -1 ],
                  "data":"profile_picture", "render": function(data,type,full,meta)
                  {
                     var str = '';

                     if(data=='' || data== null){
                      str = "";
                     }else{
                        str = "<img src='/upload/admin_profile/"+data+"' width='30' height='30' target='_blank'>";
                     }

                     return str;

                  },
                 "orderable": false
              },
              {
                  "targets": [ -1 ],
                  "data":"id", "render": function(data,type,full,meta)
                  {
                    var str ="<a href='/admin/user/view/"+data+"' target='_blank' ><button class='btn btn-outline-success btn-xs'> View </button></a> &nbsp;";
                    
                    str +="<a href='/admin/user/update/"+data+"' target='_blank' ><button class='btn btn-outline-secondary btn-xs'> Edit </button></a> &nbsp;";
                    
                    str += '<a href="/admin/user/delete/'+data+'" onclick="return confirm(\'Do You Really Want To Delete?\')"><button class="btn btn-outline-danger btn-xs"> Delete</button></a> &nbsp;' ; 
                    
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
    
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-5 card-head-left">
                      <h5>Manage Admin Users</h5>
                    </div>
                    <div class="col-md-2 card-btn-div" >
                        <a href="/admin/user/create" class="btn btn-outline-primary btn-sm card-btn-right">Add Admin User</a>
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
                        <th>Email</th>
                        <th>Role</th>
                        <th>Profile Picture</th>
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
