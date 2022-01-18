<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="/plugins/summernote/summernote-bs4.min.css">

  <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
  <!-- <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}"> -->
  <link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
  <!-- Select2 -->
  <link rel="stylesheet" href="/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!---Data Tables--->
  <link rel="stylesheet" href="{{asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css')}}">    
  <link rel="stylesheet" href="{{asset('plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css')}}">    
  <!--<link rel="stylesheet" href="{{asset('css/main.css')}}">-->
  <style type="text/css">
    .error{
        color: red;
        font-weight:100!important;
        font-size: small;
    }
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
    .user-panel img {
        height: 32px;
        width: 2.1rem;
    }
    .inline-block{
      display: inline-block;
    }
    .brand-link {
        padding: .5rem .5rem;
    }
  </style>

   @yield('css')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/home" class="nav-link">Dashboard</a>
      </li>
      
    </ul>

    <!-- Right navbar links -->
    <!--<ul class="navbar-nav ml-auto">-->
      
    <!--  <li class="nav-item">-->
    <!--    <a class="nav-link" data-widget="fullscreen" href="#" role="button">-->
    <!--      <i class="fas fa-expand-arrows-alt"></i>-->
    <!--    </a>-->
    <!--  </li>-->
    <!--  <li class="nav-item">-->
    <!--    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">-->
    <!--      <i class="fas fa-th-large"></i>-->
    <!--    </a>-->
    <!--  </li>-->
    <!--</ul>-->
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/home')}}" class="brand-link" style="text-align: center;">
      <img src="/dist/img/kwizz_app_logo_5.png" alt="KwizzApp Logo" style="opacity: .97;background: white;border-radius: 10px;">
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          @if(Auth::user()->profile_picture != "" || Auth::user()->profile_picture != null)
              @if (file_exists(public_path().'/upload/admin_profile/'.Auth::user()->profile_picture ))
                  <img src="{{asset('/upload/admin_profile/')}}/{{Auth::user()->profile_picture}}" class="img-circle elevation-2" alt="User Image">
              @else
                <img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
              @endif
          @else
              <img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          @endif
        </div>
        <div class="info">
          <!-- <a href="{{url('/profile/update')}}" class="d-block">@yield('user')</a> -->
          <a href="" class="d-block">@yield('user')</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <!-- <li class="nav-item ">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Admin Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/admin/user/list" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Admin Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/admin/user/create" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Admin User</p>
                </a>
              </li>
              
            </ul>
        </li>
          <li class="nav-item ">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                App Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/app/user/management" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage App Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/app/user/management/create" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add App User</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Transactions
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/cash/transaction/list" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cash Transaction Summary</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/credit/cash/user" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Credit Cash</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/coin/transaction/list" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Coin Transaction Summary</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/credit/coin/user" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Credit Coin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/coin/currency" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Coin Currency</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-question-circle"></i>
              <p>
                Quiz
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/quiz/category/list" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Quiz Category</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/quiz/category/question/list" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Quiz Creation</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-comments-dollar"></i>
              <p>
                Contest
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/contest/create" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Contest Creation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/contest/summary" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Contest</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item ">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Configuration
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/daily/bonus/setting" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Daily Bonus
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/setting/watch/add/bonus" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Watch Ad Bonus
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/refer/and/earn/bonus" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Refer And Earn 
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/redeem/money" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Redeem Money
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/create/required/page/privacy_policy" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Required Page Creation
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/setting/banner/popup" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Banner And Popup
                  </p>
                </a>
              </li>
            </ul>
          </li> -->
          @each('layouts.sidebar', Request::get('userAlloweds')['layout'], 'menu')
          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link">
              <i class="nav-icon far fa-sign-out-alt fa"></i>
              <p>
                {{ __('Logout') }}
              </p>
            </a>
          </li>
        </ul>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
                <ol class="breadcrumb float-sm-left">
                  @yield('breadcrumb')
                </ol>
            <!--<h1 class="m-0">@yield('title')</h1>-->
          </div><!-- /.col -->
          <div class="col-sm-6">
            
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright © 2021 - <a href="/home">Kwiz App</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>

<!-- JQuery -->
<script src="/plugins/jquery/jquery.js"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/plugins/jszip/jszip.min.js"></script>
<script src="/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Select2 -->
<script src="/plugins/select2/js/select2.full.min.js"></script>

<!-- jquery-validation -->
<script src="/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="/plugins/jquery-validation/additional-methods.min.js"></script>
<!-- jQuery Knob Chart -->
<script src="/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/plugins/moment/moment.min.js"></script>
<script src="/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>
<script type="text/javascript">
  $('.select2').select2();

  $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

   $('.datepicker').datetimepicker({
        format: 'L'
    });
</script>
@yield('js')
</body>
</html>
