@extends('layouts.app')
{{--@section('title', '所有用户')--}}

@section('content')
  <div class="main_container col-md-8">

    <div class="results_header">
      <h2>工程</h2>
      <div class="actions">
        <a class="btn btn-w-m btn-primary" href="{{ route('engineerings.create') }}">新建工程</a>

        <input id="filter-btn-success" type="button" value="筛选" class="btn btn-w-m btn-success">
      </div>
    </div>

    <div class="page_container">
      <div class="per_page">
        <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="刷新" data-original-title="Refresh inbox" onclick="javascript:getRows('{{ route('engineerings.result') }}');"><i class="glyphicon glyphicon-refresh"></i></button>

        <select id="rows_per_page" onchange="javascript:getRows('{{ route('engineerings.result') }}');">
          <option value="10" selected>10</option>
          <option value="20">20</option>
        </select>

        <span> 条目每页</span>
      </div>
      <div class="paginator">
        <a type="button" id="delete-all" class="btn btn-danger btn-sm disabled">
          <i class="fa fa-trash" aria-hidden="true"></i> 批量删除
        </a>
        Total: <span>{{ $engineerings->total() }}</span>&nbsp;&nbsp;
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="上一页" id="pre_page" onclick="javascript:getRows('{{ route('engineerings.result') }}', parseInt($('#current_page').val())-1);" disabled>
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="下一页" id="next_page" onclick="javascript:getRows('{{ route('engineerings.result') }}', parseInt($('#current_page').val())+1);">
        <input type="text" id="current_page" style="width: 30px;" value="1" onblur="javascript:getRows('{{ route('engineerings.result') }}');">
        <span> / {{ $engineerings->lastPage() }}</span>
      </div>
    </div>

    <table class="results table table-hover" border="0" cellspacing="0" id="customers" cellpadding="0">
      <thead>
      <tr>
        <th>
          <label for="select-all" style="white-space:nowrap;"><input id="select-all" type="checkbox" value=""></label>
        </th>
        <th class="sortable sorted-desc"><div>ID</div></th>
        <th><div>名称</div></th>
        <th><div>监理单位</div></th>
        <th><div>开始时间</div></th>
        <th><div>结束时间</div></th>
        <th><div>管理</div></th>
      </tr>
      </thead>
      <tbody>
      @foreach($engineerings as $engineering)
      <tr class="result_rows {{ $engineering->id === @$specificEngineering->id ? 'selected' : null }}">

        <td><label for=""><input class="select-checkbox" type="checkbox" value="{{ $engineering->id }}"></label></td>
        <td>{{ $engineering->id }}</td>
        <td>
          <div style="max-width:260px">
            <a href="javascript:void(0)" target="item_show_container" onclick="javascript:getView('{{ route('engineerings.view', $engineering->id) }}', $(this));">{{ $engineering->name }}</a>
          </div>
        </td>
        <td><a href="#" target="_blank">{{ $engineering->supervision_id }}</a></td>
        <td>{{ $engineering->start_at }}</td>
        <td>{{ $engineering->finish_at }}</td>
        <td id="model_row_cell_operation">
          <div class="operation-row">
            <a href="{{ route('engineerings.edit', $engineering->id) }}" class="btn btn-primary btn-sm" style="background-color: #18a689;border-color: #18a689;color: white;margin: 2px 0;">
              <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
            </a>
            <form action="{{ route('engineerings.destroy', $engineering->id) }}" method="post" style="display: inline-block;">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" class="btn btn-danger btn-sm" style="background-color: #ed5565;border-color: #ed5565;color: white;margin: 2px 0;">
                <i class="glyphicon glyphicon-trash"></i>
              </button>
            </form>
          </div>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>

    <div class="loading_rows" style="display: none;position: absolute;top: 140px;left: 0;right: 0;bottom: 0;background-color: #AAD2C9;opacity: 0.50;text-align: center;margin: 0 12px;border-radius: 5px;">
      <div style="position: absolute;top: 20%;left: 0;right: 0;color: #fff;font-size: 40px;text-shadow: #000 1px 1px 1px;">载入中...</div>
    </div>

    <div class="no_results" style="display: none;">
      <div>没有结果</div>
    </div>
  </div>



  <div class="item_show_container col-md-3" id="item_show_container">
    <div class="item_show">
      <div style="display: none;">
        <div class="loading">载入中...</div>
      </div>

      <form role="form" class="row">
        <h2>检视</h2>
        <div class="form-group">
          <label for="name" class="control-label">工程名称</label>
          <div class="form-control" id="name" contenteditable="true" style="height: auto" disabled>{{ @$specificEngineering->name }}</div>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">创建人</label>
          <input class="form-control" type="text" id="user_name" contenteditable="true" style="height: auto"  value="{{ @$specificEngineering->user->name }}" disabled/>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">创建时间</label>
          <input class="form-control" type="text" id="created_at" value="{{ @$specificEngineering->created_at }}" disabled/>
        </div>

        <div class="form-group">
          <label for="supervision_id" class="control-label">监理单位</label>
          <input class="form-control" type="text" id="supervision_name" value="{{ @$specificEngineering->supervision_id }}" disabled/>
        </div>

        <div class="form-group">
          <label for="start_at" class="control-label">工程开始时间</label>
          <input class="form-control" type="text" id="start_at" value="{{ @$specificEngineering->start_at }}" disabled/>
        </div>

        <div class="form-group">
          <label for="finish_at" class="control-label">工程结束时间</label>
          <input class="form-control" type="text" id="finish_at" value="{{ @$specificEngineering->finish_at }}" disabled/>
        </div>

        <div class="form-group">
          <label for="finish_at" class="control-label">工程概况</label>
          <div class="form-control view-body" id="description" contenteditable="true" style="height: auto" disabled>{!! @$specificEngineering->description !!}</div>
        </div>
      </form>
    </div>
  </div>
@stop

@section('scriptsAfterJs')
  <script>
    var getRows = function(url, current_page)
    {
      current_page = current_page || $('#current_page').val();
      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
      $.ajax({
        url: url,
        type: 'POST',
        data: {
          {{--_token : '{{ csrf_token() }}',--}}
          rows_per_page: $('#rows_per_page').val(),
          current_page: /^\+?[1-9][0-9]*$/.test(current_page) && current_page || 1
        },
        beforeSend: function() {
          $('.loading_rows').css('display', 'block');
        },
        success: function(data) {
          $.each(data, function(index,element){
            //console.log($('.result_rows').eq(index).find('td').eq(0).find('input')[0].value);
            //element.name;
          });return;
          $('.loading_rows').css('display', 'none');
        },
        error: function() {
          $('.loading_rows').css('display', 'none');
        }
      })
    };

    var getView = function(url,_this)
    {
      $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
          history.replaceState('','',url.replace("/view", ''));
          $('.panel-right').scrollTop(0);
          $('.selected').removeClass('selected');
          _this.parents('.result_rows').addClass('selected');
          $('.loading').css('opacity', '1').parent().css('display', 'block');

        },
        success: function (data) {
          $('#name').text(data.name);
          $('#user_name').val(data.user_name);
          $('#created_at').val(data.created_at);
          $('#supervision_name').val(data.supervision_id);
          $('#start_at').val(data.start_at);
          $('#finish_at').val(data.finish_at);
          $('#description').html(data.description);
          $('.loading').css('opacity', '0').parent().css('display', 'none');
        }
      })
    }
  </script>
@endsection