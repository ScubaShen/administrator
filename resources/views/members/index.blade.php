@extends('layouts.app')
{{--@section('title', '所有用户')--}}

@section('content')
  <div class="main_container col-md-8">

    <div class="results_header">
      <h2>人员</h2>
      <div class="actions">
        <a class="btn btn-w-m btn-primary" href="{{ route('members.create') }}">新增人员</a>
        <a id="filter-btn" type="button" class="btn btn-w-m btn-success">筛选</a>
      </div>
    </div>

    <div class="page_container">
      <div class="batch_delete">
        <a type="button" id="delete-all" class="btn btn-danger btn-sm disabled">
          <i aria-hidden="true"></i> 批量删除
        </a>
      </div>
      <div class="paginator">
        Total: <span id="total_rows">{{ $members->total() }}</span>&nbsp;&nbsp;
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="上一页" id="pre_page" {{ $members->previousPageUrl() == null ? 'disabled' : null }}>
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="下一页" id="next_page" {{ $members->nextPageUrl() == null ? 'disabled' : null }}>
        <input type="text" id="current_page" style="width: 30px;" value="{{ $members->currentPage() }}">
        <span> / <div id="last_page" style="display:initial;">{{ $members->lastPage() }}</div></span>
      </div>
      <div class="per_page">
        <button class="btn btn-white btn-sm" id="refresh" data-toggle="tooltip" data-placement="left" title="刷新" data-original-title="Refresh inbox"><i class="glyphicon glyphicon-refresh"></i></button>
        <select id="rows_per_page">
          <option value="10" {{ $members->perPage() == 10 ? 'selected' : null }}>10</option>
          <option value="20" {{ $members->perPage() == 20 ? 'selected' : null }}>20</option>
          <option value="50" {{ $members->perPage() == 50 ? 'selected' : null }}>50</option>
        </select>
        <span> 条目每页</span>
      </div>
    </div>

    <table class="results table table-hover" border="0" cellspacing="0" cellpadding="0">
      <thead>
      <tr>
        <th>
          <label for="select-all" style="white-space:nowrap;"><input id="select-all" type="checkbox" value=""></label>
        </th>
        <th><div>姓名</div></th>
        <th><div>角色</div></th>
        <th><div>创建时间</div></th>
        <th><div>管理</div></th>
      </tr>
      </thead>
      <tbody class="results_container">
      @foreach($members as $member)
        <tr class="result_rows {{ $member->id === @$currentMember->id ? 'selected' : null }}">

          <td><label for="id"><input class="select-checkbox results-checkbox" type="checkbox" value="{{ $member->id }}"></label></td>
          <td>
            <div style="max-width:260px">
              <a href="javascript:void(0)" class="edit-name edit" data-id="{{ $member->id }}">{{ $member->name }}</a>
            </div>
          </td>
          <td><div class="results-role_name">{{ $member->role->name }}</div></td>
          <td>{{ $member->created_at }}</td>
          <td>
            <div>
              <a type="button" class="btn btn-primary btn-sm results-edit edit" data-id="{{ $member->id }}">
                <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
              </a>
              <a type="button" class="btn btn-danger btn-sm results-delete" data-id="{{ $member->id }}">
                <i class="glyphicon glyphicon-trash"></i>
              </a>
            </div>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

    <div class="loading_rows">
      <div>载入中...</div>
    </div>

    <div class="no_results">
      <div>没有结果</div>
    </div>
  </div>

  <div class="item_show_container col-md-3" id="item_search_container" style="display: none;">
    <div class="item_show">

      <form id="search-form" role="form" method="post" class="row" onsubmit="return false;">
        {{ csrf_field() }}

        <h2>筛选</h2>

        <div class="form-group">
          <label for="name" class="control-label">姓名</label>
          <input class="form-control" name="name" id="search-name">
        </div>

        <div class="form-group">
          <label class="control-label"></label>
          <button type="button" class="btn btn-primary form-control" id="search"> 查询 </button>
          <button type="button" class="btn btn-default form-control" id="cancel_search" style="margin-top: 5px;"> 重置 </button>
        </div>

      </form>
    </div>
  </div>

  <div class="item_show_container col-md-3" id="item_show_container">
    <div class="item_show">
      <div class="loading" style="display: none;">
        <div class="loading_text">载入中...</div>
      </div>

      <form role="form" class="row" id="view">
        <h2>检视</h2>
        <div class="form-group">
          <label for="view-name" class="control-label">姓名</label>
          <input class="form-control" id="view-name" name="name" value="{{ @$currentMember->name }}">
        </div>

        <div class="form-group">
          <label for="view-role_id" class="control-label">角色</label>
          <select class="form-control" id="view-role_id" name="role_id">
            @foreach($roles as $role)
              <option value="{{ $role->id }}" {{ @$currentMember->role->id == $role->id ? 'selected' : null }}>{{ $role->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label for="view-user_name" class="control-label">创建人</label>
          <div class="form-control" id="view-user_name" contenteditable="true" style="height: auto" readonly>{{ @$currentMember->user->realname }}</div>
        </div>

        <div class="form-group">
          <label for="view-created_at" class="control-label">创建时间</label>
          <div class="form-control" id="view-created_at" contenteditable="true" style="height: auto" readonly>{{ @$currentMember->created_at }}</div>
        </div>

        <div class="form-group">
          <label for="view-updated_at" class="control-label">更新时间</label>
          <div class="form-control" id="view-updated_at" contenteditable="true" style="height: auto" readonly>{{ @$currentMember->updated_at }}</div>
        </div>

        <div class="form-group">
          <label class="control-label"></label>
          <button type="button" class="btn btn-primary form-control view-save" id="view-save" current-id="{{ @$currentMember->id }}"> 保存 </button>
        </div>

      </form>
    </div>
  </div>
@stop

@section('scriptsAfterJs')
  <script type="text/javascript"  src="{{ asset('js/main.js') }}"></script>
  <script>
    $(document).ready(function () {
      indexPage.setResultRowsFormat(function (results, urlArray) {
        var html;
        $.each(results, function (index,element) {
          var url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + element.id;
          html += url === window.location.href && "<tr class='result_rows selected'>" || "<tr class='result_rows'>";
          html +=
                  '<td><label for="id"><input class="select-checkbox results-checkbox" type="checkbox" value="' + element.id + '"></label></td>' +
                  '<td><div style="max-width:260px"><a href="javascript:void(0)" class="edit-name edit" data-id="' + element.id +'">'+element.name+'</a></div></td>' +
                  '<td><div class="results-role_name">' + element.role.name + '</div></td>' +
                  '<td>' + element.created_at + '</td>' +
                  '<td><div><a type="button" class="btn btn-primary btn-sm results-edit edit" data-id="' + element.id + '"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a> <a type="button" class="btn btn-danger btn-sm results-delete" data-id="' + element.id + '"><i class="glyphicon glyphicon-trash"></i></a></div></td>' +
                  '</tr>';
        });
        return html;
      });
      indexPage.setSearchValidate(function () {
        let $searchName = $('#search-name');
        if ($searchName.val() == '') {
          $searchName.attr('placeholder', '输入姓名').focus();
          return false;
        }
        return true;
      })
    });

    $('.results').on('click', '.edit', function () {
      var _this = $(this),
          urlArray = window.location.href.split('/'),
          url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + _this.data('id');

      $('#item_search_container').css('display', 'none');
      $('#item_show_container').css('display', 'block');
      $.ajax({
        url: url + '/view',
        type: 'GET',
        beforeSend: function () {
          history.replaceState('', '', url);
          $('.panel-right').scrollTop(0);
          $('.selected').removeClass('selected');
          _this.parents('.result_rows').addClass('selected');
          $('.loading').css('display', 'block');
        },
        success: function (data) {
          $('#view-name').val(data.name);
          $('#view-role_id').val(data.role_id);
          $('#view-user_name').html(data.user_name);
          $('#view-created_at').html(data.created_at);
          $('#view-updated_at').html(data.updated_at);
          $('.view-save').attr('current-id', data.id);
          $('.loading').css('display', 'none');
        }
      })
    });

    $('#view').on('click', '.view-save', function () {
      var _this = $(this),
          url = window.location.href,
          urlArray = url.split('/');

      // 若是index页面 保存按钮就没功能
      if('{{ route('members.index') }}' == url)return;

      $.ajax({
        url: urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + _this.attr('current-id'),
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: $('#view').serialize() + '&' +'_method=patch',
        success: function (data) {
          let $selected = $('.selected');
          $selected.find('.edit-name').text($('#view-name').val());
          $selected.find('.results-role_name').text($('#view-role_id option:selected').text());
          swal('条目更新成功', '', 'success');
        },
        error: function() {
          swal('权限不足', '', 'error');
        }
      })
    })

  </script>
@endsection