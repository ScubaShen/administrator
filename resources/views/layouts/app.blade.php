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

      $('.nav-title').on('click', function(){
        $(this).hasClass('text-fold') && $(this).removeClass('text-fold').addClass('text-unfold') ||
        $(this).hasClass('text-unfold') && $(this).removeClass('text-unfold').addClass('text-fold');
      });

//      var cookie = {
//        set:function(key,val,time){//设置cookie方法
//          var date=new Date(); //获取当前时间
//          var expiresDays=time;  //将date设置为n天以后的时间
//          date.setTime(date.getTime()+expiresDays*24*3600*1000); //格式化为cookie识别的时间
//          document.cookie=key + "=" + val +";expires="+date.toGMTString();  //设置cookie
//        },
//        get:function(key){//获取cookie方法
//          /*获取cookie参数*/
//          var getCookie = document.cookie.replace(/[ ]/g,"");  //获取cookie，并且将获得的cookie格式化，去掉空格字符
//          var arrCookie = getCookie.split(";");  //将获得的cookie以"分号"为标识 将cookie保存到arrCookie的数组中
//          var tips;  //声明变量tips
//          for(var i=0;i<arrCookie.length;i++){   //使用for循环查找cookie中的tips变量
//            var arr=arrCookie[i].split("=");   //将单条cookie用"等号"为标识，将单条cookie保存为arr数组
//            if(key==arr[0]){  //匹配变量名称，其中arr[0]是指的cookie名称，如果该条变量为tips则执行判断语句中的赋值操作
//              tips=arr[1];   //将cookie的值赋给变量tips
//              break;   //终止for循环遍历
//            }
//          }
//          return tips;
//        },
//        delete:function(key){ //删除cookie方法
//          var date = new Date(); //获取当前时间
//          date.setTime(date.getTime()-10000); //将date设置为过去的时间
//          document.cookie = key + "=v; expires =" +date.toGMTString();//设置cookie
//        }
//      };

      $('.sidebar-topbar').on('click', function(){
        let _this = $(this);
        if(_this.hasClass('sidebar-fold')){

          _this.removeClass('sidebar-fold').addClass('sidebar-unfold');
          _this.children().attr('class', 'glyphicon glyphicon-th-large');
          $('.panel-right').css('left','180px');
          $('#bpjgpt-sidebar').css('width','180px');

          document.cookie="sidebar=unfold";
        }
        else if(_this.hasClass('sidebar-unfold')){

          _this.removeClass('sidebar-unfold').addClass('sidebar-fold');
          _this.children().attr('class', 'glyphicon glyphicon-th');
          $('.panel-right').css('left','50px');
          $('#bpjgpt-sidebar').css('width','50px');

          document.cookie="sidebar=fold";
        }
      });
    })
  </script>
  @yield('scriptsAfterJs')
</body>
</html>