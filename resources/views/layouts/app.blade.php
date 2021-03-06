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
  @yield('styles')
</head>
<body>
  <div id="app" class="{{ route_class() }}-page">
    @include('layouts._header')
    @include('layouts._sidebar')
    <div class="panel-right" style="{{ Cookie::get('sidebar') == 'fold' ? 'left: 50px' : 'left: 180px'}}">
      @include('layouts._message')
      @yield('content')
    </div>
  </div>

  <script src="{{ mix('js/app.js') }}"></script>
  <!-- sidebar -->
  <script>
    $(function(){
      $('.underline').css('width', '200px');
      setTimeout(function(){$('.flash-message').css('width', '0');}, 2500);
    })
  </script>
  @yield('scriptsAfterJs')
</body>
</html>