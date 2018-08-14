<div id="bpjgpt-sidebar" style="{{ Cookie::get('sidebar') == 'fold' ? 'width: 50px' : 'width: 180px'}}">
  <div class="sidebar-content">
    <div class="sidebar-topbar sidebar-{{ Cookie::get('sidebar') ?: 'unfold'}}">
      <span class="glyphicon glyphicon-th{{ Cookie::get('sidebar') == 'fold' ? '' : '-large'}}"></span>
    </div>
    <div class="sidebar-nav">
      <div class="nav-title {{ active_class(if_route('engineerings.index') || if_route('engineerings.show') || if_route('engineerings.create') || if_route('engineerings.edit'), 'text-unfold', 'text-fold') }}" title="我的工程" data-toggle="collapse" href="#engineering">
        <div class="sidebar-icon">
          <span class="glyphicon glyphicon-play small-font smallsize-font"></span>
        </div>
        <div class="sidebar-text">
          <span>我的工程</span>
          <span class="ti-settings hide"></span>
        </div>
      </div>
      <ul class="nav-items collapse {{ active_class(if_route('engineerings.index') || if_route('engineerings.show') || if_route('engineerings.create') || if_route('engineerings.edit'), 'in') }}" id="engineering">
        <li class="nav-item {{ active_class(if_route('engineerings.index') || if_route('engineerings.show')) }}" title="工程管理">
          <a href="{{ route('engineerings.index') }}">
            <div class="sidebar-icon">
              <span class="glyphicon glyphicon-th-list"></span>
            </div>
            <div class="sidebar-text">
              <span>工程管理</span>
            </div>
          </a>
        </li>
        <li class="nav-item {{ active_class(if_route('engineerings.create') || if_route('engineerings.edit')) }}" title="工程添加">
          <a href="{{ route('engineerings.create') }}">
            <div class="sidebar-icon">
              <span class="glyphicon glyphicon-plus"></span>
            </div>
            <div class="sidebar-text">
              <span>工程添加</span>
            </div>
          </a>
        </li>
      </ul>
    </div>

    <div class="sidebar-nav">
      <div class="nav-title {{ active_class(if_route('materials.index') || if_route('materials.show') || if_route('materials.create') || if_route('materials.edit'), 'text-unfold', 'text-fold') }}" title="我的爆材" data-toggle="collapse" href="#material">
        <div class="sidebar-icon">
          <span class="glyphicon glyphicon-play small-font smallsize-font"></span>
        </div>
        <div class="sidebar-text">
          <span class="sidebar-title-text">我的爆材</span>
          <span class="ti-settings hide"></span>
        </div>
      </div>
      <ul class="nav-items collapse {{ active_class(if_route('materials.index') || if_route('materials.show') || if_route('materials.create') || if_route('materials.edit'), 'in') }}" id="material">
        <li class="nav-item {{ active_class(if_route('materials.index') || if_route('materials.show')) }}" title="爆材管理">
          <a href="{{ route('materials.index') }}">
            <div class="sidebar-icon">
              <span class="glyphicon glyphicon-th-list"></span>
            </div>
            <div class="sidebar-text">
              <span>爆材管理</span>
            </div>
          </a>
        </li>
        <li class="nav-item {{ active_class(if_route('materials.create') || if_route('materials.edit')) }}" title="爆材添加">
          <a href="{{ route('materials.create') }}">
            <div class="sidebar-icon">
              <span class="glyphicon glyphicon-plus"></span>
            </div>
            <div class="sidebar-text">
              <span>爆材添加</span>
            </div>
          </a>
        </li>
      </ul>
    </div>

    <div class="sidebar-nav">
      <div class="nav-title {{ active_class(if_route('batches.index') || if_route('batches.show') || if_route('batches.create') || if_route('batches.edit'), 'text-unfold', 'text-fold') }}" title="我的爆破批次" data-toggle="collapse" href="#batches">
        <div class="sidebar-icon">
          <span class="glyphicon glyphicon-play small-font smallsize-font"></span>
        </div>
        <div class="sidebar-text">
          <span class="sidebar-title-text">我的爆破批次</span>
          <span class="ti-settings hide"></span>
        </div>
      </div>
      <ul class="nav-items collapse {{ active_class(if_route('batches.index') || if_route('batches.show') || if_route('batches.create') || if_route('batches.edit'), 'in') }}" id="batches">
        <li class="nav-item {{ active_class(if_route('batches.index') || if_route('batches.show')) }}" title="批次管理">
          <a href="{{ route('batches.index') }}">
            <div class="sidebar-icon">
              <span class="glyphicon glyphicon-th-list"></span>
            </div>
            <div class="sidebar-text">
              <span>批次管理</span>
            </div>
          </a>
        </li>
        <li class="nav-item {{ active_class(if_route('batches.create') || if_route('batches.edit')) }}" title="批次添加">
          <a href="{{ route('batches.create') }}">
            <div class="sidebar-icon">
              <span class="glyphicon glyphicon-plus"></span>
            </div>
            <div class="sidebar-text">
              <span>批次添加</span>
            </div>
          </a>
        </li>
      </ul>
    </div>

    <div class="sidebar-nav">
      <div class="nav-title {{ active_class(if_route('members.index') || if_route('members.show') || if_route('members.create'), 'text-unfold', 'text-fold') }}" title="我的人员" data-toggle="collapse" href="#personnel">
        <div class="sidebar-icon">
          <span class="glyphicon glyphicon-play small-font smallsize-font"></span>
        </div>
        <div class="sidebar-text">
          <span class="sidebar-title-text">我的人员</span>
          <span class="ti-settings hide"></span>
        </div>
      </div>
      <ul class="nav-items collapse {{ active_class(if_route('members.index') || if_route('members.show') || if_route('members.create') || if_route('members.edit'), 'in') }}" id="personnel">
        <li class="nav-item {{ active_class(if_route('members.index') || if_route('members.show')) }}" title="人员管理">
          <a href="{{ route('members.index') }}">
            <div class="sidebar-icon">
              <span class="glyphicon glyphicon-th-list"></span>
            </div>
            <div class="sidebar-text">
              <span>人员管理</span>
            </div>
          </a>
        </li>
        <li class="nav-item {{ active_class(if_route('members.create')) }}" title="人员添加">
          <a href="{{ route('members.create') }}">
            <div class="sidebar-icon">
              <span class="glyphicon glyphicon-plus"></span>
            </div>
            <div class="sidebar-text">
              <span>人员添加</span>
            </div>
          </a>
        </li>
      </ul>
    </div>

  </div>
</div>