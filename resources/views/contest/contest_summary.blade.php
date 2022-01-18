@extends('layouts.main')
@section('title', 'Manage Contest')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Manage Contest</li>
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
    
    $('#contest_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "/contest/summary/api",
            "aaSorting": [],
            "responsive": true,
            "columns": [
              { "data": "contest_id" },
              { "data": "contest_name" },
              { "data":"bothtime" },
              { "data":"user_can_join" },
              { "data":"total_question" },
                {
                  "targets": [ -1 ],
                  "data":"contest_icon", "render": function(data,type,full,meta)
                  {
                     var str = '';

                     if(data=='' || data==null ){
                      str = "";
                     }else{
                        str = "<a href='/upload/quiz_cat_icon/"+data+"' target='_blanck'><img src='/upload/quiz_cat_icon/"+data+"' width='30' height='30' ></a>";
                     }

                     return str;

                  },
              
              },
              {
                  "targets": [ -1 ],
                  "data":"contest_id", "render": function(data,type,full,meta)
                  {
                    var str ="<a href='/contest/edit/"+data+"' target='_blanck' ><button class='btn btn-outline-success btn-xs'> Edit </button></a> &nbsp;";
                    str +="<a href='/contest/view/"+data+"' target='_blanck' ><button class='btn btn-outline-warning btn-xs'> View </button></a> &nbsp;";
                    str +="<a href='/contest/question/create/"+data+"' target='_blanck' ><button class='btn btn-outline-dark btn-xs'> Add Question </button></a> &nbsp;";
                    str +="<a href='/contest/delete/"+data+"' onclick='return confirm(\"Do You Really Want To Delete?\")'><button class='btn btn-outline-danger btn-xs'> Delete </button></a> &nbsp;";
                    
                    /*if(full.winner_count > 0){
                      
                      str +="<a href='/contest/reward/update/"+data+"'><button class='btn btn-outline-secondary btn-xs'> Update Reward </button></a> &nbsp;";
                    
                    }else{
                      str +="<a href='/contest/reward/"+data+"' target='_blanck' ><button class='btn btn-outline-secondary btn-xs'> Add Reward </button></a> &nbsp;";
                    }*/

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
                      <h5>Manage Contest</h5>
                    </div>
                    <div class="col-md-2" >
                        <a href="/contest/create" class="btn btn-outline-primary btn-sm card-btn-right">Add Contest</a>
                    </div>
            </div>
        </div>
        <div class="card-body">

            <table id="contest_table" class="table table-bordered table-striped">
            <thead>
                <tr>
                      <th>Contest Id</th>
                      <th>Contest Name</th>
                      <th>Contest Time</th>
                      <th>Total Users</th>
                      <th>Total Questions</th>
                      <th>Contest Icon</th>
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
