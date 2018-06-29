<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta name="keywords" content="爆破作业现场安全管控系统">
  <meta name="description" content="爆破作业现场安全管控系统">
  <meta name="renderer" content="webkit">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>爆破作业现场安全管控系统</title>
  {{--<title>@yield('title', 'liuweb')</title>--}}

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
  <div id="app" class="{{ route_class() }}-page">
    @include('layouts._header')
    @include('layouts._sidebar')
    <div class="panel-right">
      @yield('content')
    </div>
  </div>

  <script src="{{ mix('js/app.js') }}"></script>
  <!-- sidebar -->
  <script>
    $('.nav-title').on('click', function(){
      $(this).hasClass('text-fold') && $(this).removeClass('text-fold').addClass('text-unfold') ||
      $(this).hasClass('text-unfold') && $(this).removeClass('text-unfold').addClass('text-fold');
    })
  </script>
  @yield('scriptsAfterJs')
</body>
</html>