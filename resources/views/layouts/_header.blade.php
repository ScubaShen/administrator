<header class="navbar navbar-default" style="box-shadow: 0px 1px 11px 2px rgba(42, 42, 42, 0.1);-webkit-box-shadow: 0px 1px 11px 2px rgba(42, 42, 42, 0.1);">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="{{ route('home') }}">爆破作业现场安全管控系统</a>
      <button type="button" class="navbar-toggle">
        <span class="sr-only">切换导航</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="example-navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {{ Auth::user()->name }} <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <li>
              <a href="#">
                <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                个人中心
              </a>
            </li>
            <li>
              <a href="#">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                编辑资料
              </a>
            </li>
            <li>
              <a href="{{ route('logout') }}"
                 onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                退出登录
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</header>