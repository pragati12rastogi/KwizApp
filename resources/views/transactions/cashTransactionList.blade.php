@extends('layouts.main')
@section('title', 'Cash Transactions Summary')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Cash Transactions Summary</li>
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
    .select2{
      width: 208.2px!important;

    }
  </style>
@endsection
@section('js')

<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script>
    var dataTable;
    $(function () {

      if(dataTable){
        dataTable.destroy();
      }
      dataTable =  $('#user_table').DataTable({
            "processing": true,
            "serverSide": true,
            "aaSorting": [],
            "responsive": true,
            "ajax": {
            "url": "/cash/transaction/list/api",
            "datatype": "json",
                "data": function (data) {
                    var transfer_type = $('#transfer_type').val();
                    data.transfer_type = transfer_type;
                }
            },
            "columns": [
              { "data": "formed_trans_id" },
              { "data": "full_name" },
              { "data": "phone" },
              { "data": "email" },
                {
                  "targets": [ -1 ],
                  "data":"trans_type", "render": function(data,type,full,meta)
                  {
                     var str = '';

                     if(data=='Debit'){
                      str = "<span class='right badge badge-danger'>"+data+"</span>";
                     }else if(data=='Credit'){
                        str = "<span class='right badge badge-success'>"+data+"</span>";
                     }else{
                        str = "<span class='right badge badge-info'>"+data+"</span>";
                     }

                     return str;

                  },
              },
              {"data":"cash_wallet_amount"},
              {"data":"cash_wallet_remark"},
              {"data":"trans_date"},
              {"data":"trans_status"},
              {
                  "targets": [ -1 ],
                  "data":"cash_wallet_trans_id", "render": function(data,type,full,meta)
                  {
                    var str ="<a href='/cash/transaction/view/"+data+"' target='_blank' ><button class='btn btn-outline-success btn-xs'> View </button></a> &nbsp;";
                    if(full.trans_status == "Pending"){
                      str +="<a href='/cash/transaction/approve/"+data+"' onclick='return confirm(\"Do You Really Want To Approve?\")'><button class='btn btn-outline-danger btn-xs'> Approve </button></a> &nbsp;";

                    }
                    return str;
                  },
                  "orderable": false
              }
           ]
       });
        
  });
  $('#transfer_type').change(function(){
    dataTable.draw();
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
                        <h4 class="m-0">{{__("FAQ Summary")}}</h4>
                    </div>
                     <div class="col-md-2" >
                        <a href="{{url('admin/faq/create')}}" class="btn btn-outline-primary btn-sm card-btn-right">{{__("Add FAQ")}}</a>
                    </div>
            </div>
        </div>
        <div class="card-body">
            
            <table id="user_table" class="table table-bordered table-striped">
            <thead>
                <tr>
                      <th>Transaction Id</th>
                      <th>User Name</th>
                      <th>Phone</th>
                      <th>Email</th>
                      <th>Transaction Type(DR)/(CR)</th>
                      <th>Amount</th>
                      <th>Description</th>
                      <th>Transaction Date</th>
                      <th>Status</th>
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
