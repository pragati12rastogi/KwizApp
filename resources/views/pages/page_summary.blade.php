@extends('layouts.main')
@section('title', 'Required Page Summary')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Required Page Summary</li>
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
            "ajax": "page/summary/api",
            "aaSorting": [],
            "responsive": true,
            "columns": [
              { "data": "page_id" },
              { "data": "page_name" },
              { "data":"content" },
               
              {
                  "targets": [ -1 ],
                  "data":"page_id", "render": function(data,type,full,meta)
                  {
                    var str ="<a href='/quiz/category/edit/"+data+"' target='_blank' ><button class='btn btn-outline-success btn-xs'> Edit </button></a> &nbsp;";
                    str +="<a href='/quiz/category/delete/"+data+"' onclick='return confirm(\"Do You Really Want To Delete?\")' ><button class='btn btn-outline-danger btn-xs'> Delete </button></a> &nbsp;";
                    str +="<a href='/quiz/category/view/"+data+"' ><button class='btn btn-outline-info btn-xs'> View </button></a> &nbsp;";
                    
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
                
                    <div class="col-md-10">
                      <h5>Manage Quiz Category</h5>
                    </div>
                    <div class="col-md-2" >
                        <a href="/quiz/category" class="btn btn-outline-primary btn-sm card-btn-right">Add Quiz Category</a>
                    </div>
            </div>
        </div>
        <div class="card-body">

            <table id="user_table" class="table table-bordered table-striped">
            <thead>
                <tr>
                      <th>Quiz Category Id</th>
                      <th>Quiz Category Name</th>
                      <th>Quiz Category Time</th>
                      <th>Quiz Category Icon</th>
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
