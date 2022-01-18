@extends('layouts.main')
@section('title', 'Coin Transactions Summary')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item active">Coin Transactions Summary</li>
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
      dataTable = $('#trans_table').DataTable({
            "processing": true,
            "serverSide": true,
            "aaSorting": [],
            "responsive": true,
            "ajax": {
            "url": "/coin/transaction/list/api",
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
              {"data":"coin_wallet_amount"},
              {"data":"coin_wallet_remark"},
              {"data":"trans_date"},
              {"data":"trans_status"},
              {
                  "targets": [ -1 ],
                  "data":"coin_wallet_trans_id", "render": function(data,type,full,meta)
                  {
                    var str ="<a href='/coin/transaction/view/"+data+"' target='_blank' ><button class='btn btn-outline-success btn-xs'> View </button></a> &nbsp;";
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
                      <h5>Coin Transactions Summary</h5>
                    </div>
                     <div class="col-md-2" >
                        <a href="/credit/coin/user" class="btn btn-outline-primary btn-sm card-btn-right">Add Credit Coin</a>
                    </div>
            </div>
        </div>
        <div class="card-body">
            <div class="mailbox-read-time col-md-3 mb-1" style="float: right;">
                <select name="transfer_type" class="input-css select2bs4 " id="transfer_type">
                   <option value="">Select Trasaction Type</option>
                   <option value="1">Debit</option>
                   <option value="2">Credit</option>
                </select>
            </div>
            <table id="trans_table" class="table table-bordered table-striped">
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
