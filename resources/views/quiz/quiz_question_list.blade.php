@extends('layouts.main')
@section('title', 'Manage Quiz Creation')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Manage Quiz Creation</li>
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
    
    $('#quiz_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "/quiz/category/question/list/api",
            "aaSorting": [],
            "responsive": true,
            "columns": [
              { "data": "group_id" },
              { "data": "quiz_title" },
              { "data": "quiz_category_name" },
              { "data":"ques_count" },
              { "data":"quiz_time" },
                
              {
                  "targets": [ -1 ],
                  "data":"group_id", "render": function(data,type,full,meta)
                  {
                    var str ="<a href='/quiz/category/questions/update/"+data+"' ><button class='btn btn-outline-success btn-xs'> Edit Quiz Questions </button></a> &nbsp;";

                    str +="<a href='/quiz/category/question/view/"+data+"' ><button class='btn btn-outline-info btn-xs'> View Quiz Questions </button></a> &nbsp;";

                    str +="<a href='/quiz/group/delete/"+data+"' onclick='return confirm(\"Do You Really Want To Delete?\")' ><button class='btn btn-outline-danger btn-xs'> Delete Quiz Questions </button></a> &nbsp;";
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
                      <h5>Manage Quiz Creation</h5>
                    </div>
                    <div class="col-md-2" >
                        <a href="/quiz/category/questions/create" class="btn btn-outline-primary btn-sm card-btn-right">Create Quiz</a>
                    </div>
            </div>
        </div>
        <div class="card-body">

            <table id="quiz_table" class="table table-bordered table-striped">
            <thead>
                <tr>
                      <th>Quiz Category Id</th>
                      <th>Quiz Title</th>
                      <th>Quiz Category Name</th>
                      <th>Question Count</th>
                      <th>Question Time</th>
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
