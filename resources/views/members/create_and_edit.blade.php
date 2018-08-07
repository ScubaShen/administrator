@extends('layouts.app')

@section('content')
  <div class="main_container col-md-10">
    <div class="panel">
      <div class="panel-body">
        <div class="create_edit_header">
          <h2>
            <i class="glyphicon glyphicon-edit"></i>
            @if(@$member->id)
              编辑人员
            @else
              新增人员
            @endif
          </h2>
          <div class="return">
            <a href="{{ route('members.index') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-arrow-left"></span> 返回</a>
          </div>
        </div>
        <hr>

        @include('common.error')

        @if(@$member->id)
          <form class="form-horizontal" action="{{ route('members.update', $member->id) }}" method="POST" accept-charset="UTF-8">
            <input type="hidden" name="_method" value="PUT">
        @else
          <form class="form-horizontal" action="{{ route('members.store') }}" id="forms" method="POST" accept-charset="UTF-8">
        @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="roles_header col-md-12">
              <div class="form-group" style="margin-bottom: 35px;">
                <label for="role_id" class="col-md-1 control-label">角色</label>
                <div class="col-md-7 col-xs-12">
                  <select class="selectpicker form-control" data-title="请选择角色(可多选)" multiple required>
                    @foreach($roles as $role)
                      <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="roles_container col-md-offset-1 col-md-11 col-xs-12" style="padding: 0;"></div>
            <div class="col-md-offset-1 col-md-11 col-xs-12">
              <div class="col-md-12">
                <div class="form-group" style="margin-top: 15px;">
                  <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 保存</button>
                </div>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>
@endsection

@section('scriptsAfterJs')
  <script>
    $('.selectpicker').on('hidden.bs.select', function (e) {
      var html = '',
          selectingRoleArray = [],  // 当前页面角色
          selectedRoleArray = $(this).val();  // 更改选择器之后的角色

      // 把当前页面角色转成 ["1","2", ...] 的形式
      $('.add_role').each(function (index, element) {
        let id = $(element).data('id')+'';
        selectingRoleArray.push(id); // 数字转换成字符串

        // 若选择器裡没有当前页面的角色，删除元素
        if ($.inArray(id, selectedRoleArray) == -1) {
          $(element).parents('.role_group').remove();
        }
      });

      $.each(selectedRoleArray, function (index, element) {
        // 若当前页面已经有选择器的角色，返回
        if ($.inArray(element, selectingRoleArray) != -1) return;
        let name;
        switch(element) {
          case '1':
            name = '工程技术员';
            break;
          case '2':
            name = '保管员';
            break;
          case '3':
            name = '安全员';
            break;
          case '4':
            name = '爆破员';
            break;
        }
        html += '<div class="col-md-6 col-xs-12 role_group">'+
                '<div class="col-md-12 role_container" style="border: 1px solid #ddd;padding-bottom: 25px;margin-bottom: 25px;">'+
                '<h3 class="page-header" style="font-size: 22px;">' + name + '</h3>'+
                '<div class="input-bodies">'+
                '<div class="form-group">'+
                '<label for="name" class="col-md-2 col-xs-2 control-label" style="margin-bottom: 10px;">姓名</label>'+
                '<div class="col-md-9 col-xs-8">'+
                '<input class="form-control" type="text" name="name[' + element + '][]" placeholder="请填写姓名" required/>'+
                '</div></div></div>'+
                '<div class="form-group">'+
                '<div class="col-md-offset-2 col-md-9 col-xs-offset-2 col-xs-9">'+
                '<button type="button" class="btn btn-sm btn-default add_role" data-id="' + element + '">添加</button>'+
                '</div></div></div></div>';
      });

      html && $('.roles_container').append(html);
    });

    $('.roles_container').on('click', '.add_role', function () {
      let container = $(this).parents('.role_container').find('.input-bodies'),
          id = $(this).data('id');

      container.append(
              '<div class="form-group">'+
              '<div class="col-md-offset-2 col-xs-offset-2 col-md-9 col-xs-8">'+
              '<input class="form-control" type="text" name="name[' + id + '][]" placeholder="请填写姓名" required/>'+
              '</div>'+
              '<div class="col-md-1 col-xs-1 removeInput" style="padding: 0;line-height: 34px;cursor: pointer;"><span class="glyphicon glyphicon-remove"></span></div>'+
              '</div>'
      );
    });

    $('.roles_container').on('click', '.removeInput', function () {
      $(this).parent().remove();
    });
  </script>
@endsection