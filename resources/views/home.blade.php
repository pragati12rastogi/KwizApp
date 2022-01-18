@extends('layouts.main')
@section('title', 'Dashboard')

@section('user',Auth::user()->name)
@section('css')

@endsection
@section('js')
<script>
  $(function(){
    total_user_donut();
    convert_amount_pie();
  });

  function total_user_donut(){
    var daily_reg = @php echo($daily_register); @endphp;
    var weekly_reg = @php echo($reg_weekly); @endphp;
    var monthly_reg = @php echo($reg_monthly); @endphp;

    var donutChartCanvas = $('#donutChart').get(0).getContext('2d');
    var donutData        = {
      labels: [
          'Daily',
          'Weekly',
          'Monthly',
      ],
      datasets: [
        {
          data: [daily_reg,weekly_reg,monthly_reg],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12'],
        }
      ]
    }
    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions
    })

  }

   function convert_amount_pie(){
    var daily_conv = @php echo($daily_convert['given']?$daily_convert['given']:0); @endphp;
    var weekly_conv = @php echo($weekly_convert['given']?$weekly_convert['given']:0); @endphp;
    var monthly_conv = @php echo($monthly_convert['given']?$monthly_convert['given']:0); @endphp;

    var donutData        = {
      labels: [
          'Daily',
          'Weekly',
          'Monthly',
      ],
      datasets: [
        {
          data: [daily_conv,weekly_conv,monthly_conv],
          backgroundColor : ['#f56954', '#00a65a', '#f39c12'],
        }
      ]
    }
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })
  }
</script>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{$totalAppUser}}</h3>

                <p>Total App Users</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-contacts"></i>
              </div>
              <a href="/app/user/management" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$totalActiveAppUser}}</h3>

                <p>Total Active User</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="/app/user/management" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$daily_register}}</h3>

                <p>New Registrations</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="/app/user/management" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$cash_credit['given']}}</h3>

                <p>Total Cash Credit</p>
              </div>
              <div class="icon">
                <i class="ion ion-social-usd"></i>
              </div>
              <a href="/cash/transaction/list" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
    </div>
    <div class="row">
        
          <div class="col-lg-3 col-sm-12">
              <div class="card card-blue">
                <div class="card-header">
                  <h3 class="card-title">New Users Registered count</h3>

                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body" style="padding: 9px;">
                  <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                
              </div>
              
          </div>

          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-dark">
              <span class="info-box-icon"><i class="far fa-wallet fa"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Cash Credit Daily</span>
                <span class="info-box-number">{{$daily_credit['given']?($daily_credit['given']/$cash_credit['given']*100):0}}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: {{$daily_credit['avg']?($daily_credit['avg']/$cash_credit['given']*100):0}}%"></div>
                </div>
                
              </div>
            </div>
            <div class="info-box bg-gradient-dark mt-4">
              <span class="info-box-icon"><i class="far fa-weight-hanging fa"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Cash Credit Weekly</span>
                <span class="info-box-number">{{$weekly_credit['given']?$weekly_credit['given']:0}}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: {{$weekly_credit['avg']?($weekly_credit['avg']/$cash_credit['given']*100):0}}%"></div>
                </div>
                
              </div>
            </div>
            <div class="info-box bg-gradient-dark mt-4">
              <span class="info-box-icon"><i class="far fa-calendar "></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Cash Credit Monthly</span>
                <span class="info-box-number">{{$monthly_credit['given']?$monthly_credit['given']:0}}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: {{$monthly_credit['given']?($monthly_credit['given']/$cash_credit['given']*100):0}}%"></div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-olive">
              <span class="info-box-icon"><i class="far fa-walking fa"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Cash Redeem Daily</span>
                <span class="info-box-number">{{$daily_debit['given']?($daily_debit['given']/$cash_credit['given']*100):0}}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: {{$daily_debit['avg']?($daily_debit['avg']/$cash_debit['given']*100):0}}%"></div>
                </div>
                
              </div>
            </div>
            <div class="info-box bg-gradient-olive mt-4">
              <span class="info-box-icon"><i class="far fa-retweet fa"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Cash Redeem Weekly</span>
                <span class="info-box-number">{{$weekly_debit['given']?$weekly_debit['given']:0}}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: {{$weekly_debit['avg']?($weekly_debit['avg']/$cash_debit['given']*100):0}}%"></div>
                </div>
                
              </div>
            </div>
            <div class="info-box bg-gradient-olive mt-4">
              <span class="info-box-icon"><i class="far fa-calendar-alt "></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Cash Redeem Monthly</span>
                <span class="info-box-number">{{$monthly_debit['given']?$monthly_debit['given']:0}}</span>

                <div class="progress">
                  <div class="progress-bar" style="width: {{$monthly_debit['given']?($monthly_debit['given']/$cash_debit['given']*100):0}}%"></div>
                </div>
                
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-sm-12">
            <div class="card card-purple">
                  <div class="card-header">
                    <h3 class="card-title">Total Points Converted in Amount</h3>

                  </div>
                  <div class="card-body" style="padding: 9px;">
                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                  <!-- /.card-body -->
                </div>
          </div>
    </div>
    <div class="row">
      
    </div>
    <!-- <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 style="margin:0px">{{ __('Dashboard') }}</h4></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Congratulations, You are now logged in to the Kwizz App Portal!') }}
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection
