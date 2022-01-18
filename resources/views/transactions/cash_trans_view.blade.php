@extends('layouts.main')
@section('title', 'Cash Transactions Details')

@section('user',Auth::user()->name)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="/cash/transaction/list">Cash Transactions Summary</a></li>
<li class="breadcrumb-item active">Cash Transactions Details</li>
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
    
    <div class="card">
        <div class="card-header">
            <div class="row">
                
                    <div class="col-md-11">
                      <h5>Cash Transactions Details</h5>
                    </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
              <div class="col-12 table-responsive">
                <table class="table table-striped">
                  <tbody>
                      <tr>
                        <th>Transaction ID</th>
                        <td>{{$cash_trans_view['formed_trans_id']}}</td>
                      </tr> 
                      <tr> 
                        <th>User Name</th>
                        <td>{{$cash_trans_view['full_name']}}y</td>
                      </tr>
                      <tr> 
                        <th>User Phone Number</th>
                        <td>{{$cash_trans_view['phone']}}</td>
                      </tr>
                      <tr> 
                        <th>User Email</th>
                        <td>{{$cash_trans_view['email']}}</td>
                      </tr> 
                      <tr> 
                        <th>Transaction Type(DR)/(CR)</th>
                        <td>{{$cash_trans_view['trans_type']}}</td>

                      </tr> 
                      <tr> 
                        <th>Description</th>
                        <td>{{$cash_trans_view['cash_wallet_remark']}}</td>
                      </tr> 
                      <tr> 
                        <th>Transaction Date</th>
                        <td>{{$cash_trans_view['trans_date']}}</td>

                      </tr> 
                        <th>Amount</th>
                        <td><i class="fa fa-money-bill-alt" style="color: green"></i> {{$cash_trans_view['cash_wallet_amount']}}</td>

                      </tr> 
                      <tr> 
                        <th>Status</th>
                        <td>{{$cash_trans_view['trans_status']}}</td>
                      </tr>
                      
                  </tbody>
                </table>
              </div>
      
            </div>
            <div class="row no-print">
              <div class="col-12">
                <a href="/cash/transaction/pdf/{{$cash_trans_view['cash_wallet_trans_id']}}" class="btn btn-primary float-right" style="margin-right: 5px;">
                  <i class="fas fa-download"></i> Generate PDF
                </a>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
