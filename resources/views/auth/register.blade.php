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
    <style type="text/css">
      /*密码强度*/
      .pw-strength {clear: both;position: relative;top: 8px;width: 180px;}
      .pw-bar{background: url('{{ asset('/images/pwd-1.png') }}') no-repeat;height: 14px;overflow: hidden;width: 179px;}
      .pw-bar-on{background:  url('{{ asset('/images/pwd-2.png') }}') no-repeat; width:0px; height:14px;position: absolute;top: 0px;left: 2px;transition: width .5s ease-in;-moz-transition: width .5s ease-in;-webkit-transition: width .5s ease-in;-o-transition: width .5s ease-in;}
      .pw-weak .pw-defule{ width:0px;}
      .pw-weak .pw-bar-on {width: 60px;}
      .pw-medium .pw-bar-on {width: 120px;}
      .pw-strong .pw-bar-on {width: 179px;}
      .pw-txt {padding-top: 2px;width: 180px;overflow: hidden;}
      .pw-txt span {color: #707070;float: left;font-size: 12px;text-align: center;width: 58px;}
    </style>
  </head>
  <body style="background: url('{{ asset('/images/login_bg.png') }}') no-repeat;background-size: cover;">
    <div id="app" class="{{ route_class() }}-page">
      <div class="container" style="margin: 10% auto;">
        <div class="row">
          <div class="col-md-offset-3 col-md-6 col-xs-offset-2 col-xs-8">
            <div class="panel panel-default" style="box-shadow: 1px 14px 28px 0px rgba(0,0,0,.6);min-height: 377px;">
              <div class="panel-heading" style="background-color: #1081d6;margin: 15px;font-size: 20px;text-align: center;color: white;">注册新用户</div>

              <div class="panel-body">
                <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                  {{ csrf_field() }}

                  <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="col-md-4 control-label">用户名</label>

                    <div class="col-md-6">
                      <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                      @if ($errors->has('name'))
                        <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>

                  <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">密码</label>

                    <div class="col-md-6">
                      <input id="password" type="password" class="form-control" name="password" required>

                      @if ($errors->has('password'))
                        <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-offset-4 col-md-6">
                      <div id="level" class="pw-strength">
                        <div class="pw-bar"></div>
                        <div class="pw-bar-on"></div>
                        <div class="pw-txt">
                          <span>弱</span>
                          <span>中</span>
                          <span>强</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password-confirm" class="col-md-4 control-label">确认密码</label>

                    <div class="col-md-6">
                      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                  </div>

                  <div class="form-group{{ $errors->has('realname') ? ' has-error' : '' }}">
                    <label for="realname" class="col-md-4 control-label">真实姓名</label>

                    <div class="col-md-6">
                      <input id="realname" type="text" class="form-control" name="realname" value="{{ old('realname') }}" required>

                      @if ($errors->has('realname'))
                        <span class="help-block">
                        <strong>{{ $errors->first('realname') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>

                  <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                    <label for="company" class="col-md-4 control-label">所属公司</label>

                    <div class="col-md-6">
                      <select id="company" name="company_id" class="form-control" required>
                        <option value="">请选择</option>
                        @foreach($companies as $company)
                          <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : null }}>{{ $company->name }}</option>
                        @endforeach
                      </select>

                      @if ($errors->has('company_id'))
                        <span class="help-block">
                        <strong>{{ $errors->first('company_id') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>

                  <div class="form-group {{ $errors->has('captcha') ? ' has-error' : '' }}">
                    <label for="captcha" class="col-md-4 control-label">验证码</label>

                    <div class="col-md-6">
                      <input id="captcha" class="form-control" name="captcha" >

                      <img class="thumbnail captcha" src="{{ captcha_src('flat') }}" onclick="this.src='/captcha/flat?'+Math.random()" title="点击图片重新获取验证码">

                      @if ($errors->has('captcha'))
                        <span class="help-block">
                        <strong>{{ $errors->first('captcha') }}</strong>
                      </span>
                      @endif
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="col-md-6 col-md-offset-4" style="padding: 0;">
                      <div class="col-md-6">
                        <button type="submit" class="btn" style="width: 100%;background-color: #1081d6;color:white;">
                          提交
                        </button>
                      </div>
                      <div class="col-md-6">
                        <a href="{{ route('login') }}" class="btn" style="width: 100%;background-color: #dddddd;color: black;">
                          返回
                        </a>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script type="text/javascript">
      $(function(){
        $('#password').keyup(function () {
          var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
          var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
          var enoughRegex = new RegExp("(?=.{6,}).*", "g");

          if (false == enoughRegex.test($(this).val())) {
            $('#level').removeClass('pw-weak');
            $('#level').removeClass('pw-medium');
            $('#level').removeClass('pw-strong');
            $('#level').addClass('pw-defule');
            //密码小于六位的时候，密码强度图片都为灰色
          }
          else if (strongRegex.test($(this).val())) {
            $('#level').removeClass('pw-weak');
            $('#level').removeClass('pw-medium');
            $('#level').removeClass('pw-strong');
            $('#level').addClass('pw-strong');
            //密码为八位及以上并且字母数字特殊字符三项都包括,强度最强
          }
          else if (mediumRegex.test($(this).val())) {
            $('#level').removeClass('pw-weak');
            $('#level').removeClass('pw-medium');
            $('#level').removeClass('pw-strong');
            $('#level').addClass('pw-medium');
            //密码为七位及以上并且字母、数字、特殊字符三项中有两项，强度是中等
          }
          else {
            $('#level').removeClass('pw-weak');
            $('#level').removeClass('pw-medium');
            $('#level').removeClass('pw-strong');
            $('#level').addClass('pw-weak');
            //如果密码为6为及以下，就算字母、数字、特殊字符三项都包括，强度也是弱的
          }
          return true;
        });
      })
    </script>
  </body>
</html>

