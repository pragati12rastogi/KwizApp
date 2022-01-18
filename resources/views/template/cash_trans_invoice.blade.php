<!DOCTYPE html>
<html lang="en">
<head>
  <title>Cash Transaction Invoice</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css"> -->
  <!-- Theme style -->
  <!-- <link rel="stylesheet" href="/dist/css/adminlte.min.css"> -->
  <style type="text/css">
    
    .invoice {
        border: 1px solid rgba(0,0,0,.125);
    }
    .p-3 {
        padding: 1rem!important;
    }
    body{
      font-size: 12px;
    }
    table {
      width:100%;
    }
    table, th, td {
      border: 1px solid black;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      text-align: left;
    }
    #t01 tr:nth-child(even) {
      background-color: #eee;
    }
    #t01 tr:nth-child(odd) {
     background-color: #fff;
    }
    #t01 th {
      background-color: black;
      color: white;
    }
    div {
        display: block;
    }
  </style>
</head>
<body>
    <div class="wrapper">
      <center><h3>Cash Transaction Invoice</h3></center>
        <div style="width: 100%">
           <p style="">Invoice Creation Date: {{$created}}</p>
        </div>
        <div class="invoice p-3 mb-3">
                <div class="row">
                  

                    <table id="t01">
                      <tbody>
                          <tr>
                            <td>Transaction ID</td>
                            <td>{{$cash_trans_view['formed_trans_id']}}</td>
                          </tr> 
                          <tr> 
                            <td>User Name</td>
                            <td>{{$cash_trans_view['full_name']}}y</td>
                          </tr> 
                          <tr> 
                            <td>Transaction Type(DR)/(CR)</td>
                            <td>{{$cash_trans_view['trans_type']}}</td>

                          </tr> 
                          <tr> 
                            <td>Description</td>
                            <td>{{$cash_trans_view['cash_wallet_remark']}}</td>
                          </tr> 
                          <tr> 
                            <td>Transaction Date</td>
                            <td>{{$cash_trans_view['trans_date']}}</td>

                          </tr> 
                          <tr>
                            <td>Amount</td>
                            <td>{{$cash_trans_view['cash_wallet_amount']}}</td>

                          </tr> 
                          <tr> 
                            <td>Status</td>
                            <td>{{$cash_trans_view['trans_status']}}</td>
                          </tr>
                          
                      </tbody>
                    </table>
                  
          
                </div>
                
        </div>
    </div>
</body>