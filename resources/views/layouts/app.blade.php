<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Kwiz App</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link href="{{ asset('css/login.css') }}" rel="stylesheet">
  @hasSection('css')
        @yield('css')
    @endif
</head>
<body>
  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    @yield('content')
  </main>
  <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <!-- JQuery -->
    <script src="/plugins/jquery/jquery.js"></script>


     @yield('js')
</body>
</html>
