<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" style="height: 100%;">
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

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  </head>
  <body style="background: url('{{ asset('/images/login_bg.png') }}') no-repeat;background-size: cover;">
    <div id="app" class="{{ route_class() }}-page">
      <div class="container" style="margin: 15% auto;">
        <div class="row">
          <div class="col-md-offset-3 col-md-6 col-xs-offset-2 col-xs-8">
            <div class="panel" style="box-shadow: 1px 14px 28px 0px rgba(0,0,0,.6);background-color: transparent;min-height: 377px;">
              <div class="panel-heading" style="background-color: #1081d6;margin: 15px;font-size: 20px;text-align: center;color: white;">爆破作业现场安全管控系统</div>
              <div class="panel-body">
                <div class="col-md-offset-2 col-md-8">
                  <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}

                    <div class="input-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-user"></span>
                    </span>
                      <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="请输入账户" required autofocus>
                    </div>
                    @if ($errors->has('name'))
                      <span class="help-block" style="color: #a94442;">
                        <strong>{{ $errors->first('name') }}</strong>
                      </span>
                    @endif
                    <br>
                    <div class="input-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-lock"></span>
                    </span>
                      <input id="password" type="password" class="form-control" name="password" placeholder="请输入密码" required>
                    </div>
                    @if ($errors->has('password'))
                      <span class="help-block" style="color: #a94442;">
                        <strong>{{ $errors->first('password') }}</strong>
                      </span>
                    @endif
                    <br>
                    <div class="form-group">
                      <div class="col-md-8 col-md-offset-4">
                        <a class="btn btn-link" href="{{ route('password.request') }}" style="color: white;float: right">
                          忘记密码?
                        </a>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <button type="submit" class="btn" style="width: 100%;background-color: #1081d6;color:white">
                          登录
                        </button>
                      </div>
                      <div class="col-md-6">
                        <a href="{{ route('register') }}" class="btn" style="width: 100%;background-color: white;color: black;">
                          注册
                        </a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

