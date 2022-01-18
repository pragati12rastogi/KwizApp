<!DOCTYPE html>
<html lang="en">
<head>
  <title>Coin Transaction Invoice</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <center><h3>Coin Transaction Invoice</h3></center>
        <div style="width: 100%">
           <p style="">Invoice Creation Date: {{$created}}</p>
        </div>
        <div class="invoice p-3 mb-3">
                <div class="row">
                  

                    <table id="t01">
                      <tbody>
                          <tr>
                            <th>Transaction ID</th>
                            <td>{{$coin_trans_view['formed_trans_id']}}</td>
                          </tr> 
                          <tr> 
                            <th>User Name</th>
                            <td>{{$coin_trans_view['full_name']}}y</td>
                          </tr> 
                          <tr> 
                            <th>Transaction Type(DR)/(CR)</th>
                            <td>{{$coin_trans_view['trans_type']}}</td>

                          </tr> 
                          <tr> 
                            <th>Description</th>
                            <td>{{$coin_trans_view['coin_wallet_remark']}}</td>
                          </tr> 
                          <tr> 
                            <th>Transaction Date</th>
                            <td>{{$coin_trans_view['trans_date']}}</td>

                          </tr>
                          <tr> 
                            <th>Amount</th>
                            <td>{{$coin_trans_view['coin_wallet_amount']}}</td>

                          </tr> 
                          <tr> 
                            <th>Status</th>
                            <td>{{$coin_trans_view['trans_status']}}</td>
                          </tr>
                          
                      </tbody>
                    </table>
                  
          
                </div>
              
            
        </div>
    </div>
</body>