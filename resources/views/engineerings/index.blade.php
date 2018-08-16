@extends('layouts.app')
{{--@section('title', '所有用户')--}}

@section('content')
  <div class="main_container col-md-8">

    <div class="results_header">
      <h2>工程</h2>
      <div class="actions">
        <a class="btn btn-w-m btn-primary" href="{{ route('engineerings.create') }}">新建工程</a>
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
        Total: <span id="total_rows">{{ $engineerings->total() }}</span>&nbsp;&nbsp;
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="上一页" id="pre_page" {{ $engineerings->previousPageUrl() == null ? 'disabled' : null }}>
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="下一页" id="next_page" {{ $engineerings->nextPageUrl() == null ? 'disabled' : null }}>
        <input type="text" id="current_page" style="width: 30px;" value="{{ $engineerings->currentPage() }}">
        <span> / <div id="last_page" style="display:initial;">{{ $engineerings->lastPage() }}</div></span>
      </div>
      <div class="per_page">
        <button class="btn btn-white btn-sm" id="refresh" data-toggle="tooltip" data-placement="left" title="刷新" data-original-title="Refresh inbox"><i class="glyphicon glyphicon-refresh"></i></button>
        <select id="rows_per_page">
          <option value="10" {{ $engineerings->perPage() == 10 ? 'selected' : null }}>10</option>
          <option value="20" {{ $engineerings->perPage() == 20 ? 'selected' : null }}>20</option>
          <option value="50" {{ $engineerings->perPage() == 50 ? 'selected' : null }}>50</option>
        </select>
        <span> 条目每页</span>
      </div>
    </div>
    
    <table class="results table table-hover" border="0" cellspacing="0" id="customers" cellpadding="0">
      <thead>
      <tr>
        <th>
          <label for="select-all" style="white-space:nowrap;"><input id="select-all" type="checkbox" value=""></label>
        </th>
        <th><div>名称</div></th>
        <th><div>监理单位</div></th>
        <th><div>开始时间</div></th>
        <th><div>结束时间</div></th>
        <th><div>管理</div></th>
      </tr>
      </thead>
      <tbody class="results_container">
      @foreach($engineerings->items() as $engineering)
      <tr class="result_rows {{ $engineering['id'] === @$currentEngineering->id ? 'selected' : null }}">

        <td><label for="id"><input class="select-checkbox results-checkbox" type="checkbox" value="{{ $engineering['id'] }}"></label></td>
        <td>
          <div style="max-width:260px">
            <a href="javascript:void(0)" class="results-name" data-id="{{ $engineering['id'] }}">{{ $engineering['name'] }}</a>
          </div>
        </td>
        <td>
          <div style="max-width:260px">
            <a href="javascript:void(0)">{{ @$engineering['supervision_name'] }}</a>
          </div>
        </td>
        <td>{{ $engineering['start_at'] ?: '尚未开始' }}</td>
        <td>{{ $engineering['finish_at'] ?: '尚未结束' }}</td>
        <td>
          <div>
            <a href="{{ route('engineerings.edit', $engineering['id']) }}" class="btn btn-primary btn-sm results-edit">
              <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
            </a>
            <a type="button" class="btn btn-danger btn-sm results-delete" data-id="{{ $engineering['id'] }}">
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
          <label for="name" class="control-label">工程名称</label>
          <input class="form-control" name="name" id="search-name" onkeydown="if(event.keyCode==13)return search();">
        </div>

        <div class="form-group">
          <label for="name" class="control-label">开始时间起</label>
          <input class="form-control" name="start_at" type="date" id="search-start_at">
        </div>

        <div class="form-group">
          <label for="name" class="control-label">至</label>
          <input class="form-control" name="end_at" type="date" id="search-end_at">
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
          <label for="view-name" class="control-label">工程名称</label>
          <div class="form-control" id="view-name" contenteditable="true" style="height: auto" readonly>{{ @$currentEngineering->name }}</div>
        </div>

        <div class="form-group">
          <label for="view-user_name" class="control-label">创建人</label>
          <div class="form-control" id="view-user_name" contenteditable="true" style="height: auto" readonly>{{ @$currentEngineering->user->realname }}</div>
        </div>

        <div class="form-group">
          <label for="view-created_at" class="control-label">创建时间</label>
          <div class="form-control" id="view-created_at" contenteditable="true" style="height: auto" readonly>{{ @$currentEngineering->created_at }}</div>
        </div>

        <div class="form-group">
          <label for="view-supervision_name" class="control-label">监理单位</label>
          <div class="form-control" id="view-supervision_name" contenteditable="true" style="height: auto" readonly>{{ @$currentEngineering->supervision->name }}</div>
        </div>

        <div class="form-group">
          <label for="view-batches" class="control-label">所有批次</label>
          <div class="form-control" id="view-batches" contenteditable="true" style="height: auto" readonly>
            @if(@$currentEngineering)
              @foreach($currentEngineering->batches as $index => $batch)
                {{ $index+1 }}. {{ $batch->name }}<br>
              @endforeach
            @endif
          </div>
        </div>

        <div class="form-group">
          <label for="view-description" class="control-label">工程概况</label>
          <div class="form-control view-body" id="view-description" contenteditable="true" style="height: auto" readonly>{!! @$currentEngineering->description !!}</div>
        </div>
      </form>
    </div>
  </div>
@stop

@section('scriptsAfterJs')
  <script type="text/javascript"  src="{{ asset('js/main.js') }}"></script>
  <script>
    $(document).ready(function(){
      indexPage.setResultRowsFormat(function (results, urlArray){
        var html;
        $.each(results, function(index,element){
          let url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + element.id,
              start_at = element.start_at || '尚未开始',
              finish_at = element.finish_at || '尚未结束';
          html += url === window.location.href && "<tr class='result_rows selected'>" || "<tr class='result_rows'>";
          html +=
                  '<td><label for="id"><input class="select-checkbox results-checkbox" type="checkbox" value="' + element.id + '"></label></td>' +
                  '<td><div style="max-width:260px"><a href="javascript:void(0)" class="results-name" data-id="' + element.id +'">'+element.name+'</a></div></td>' +
                  '<td><div style="max-width:260px"><a href="javascript:void(0)">' + element.supervision_name + '</a></div></td>' +
                  '<td>' + start_at + '</td>' +
                  '<td>' + finish_at + '</td>' +
                  '<td><div><a href="' + url + '/edit" class="btn btn-primary btn-sm results-edit"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a> <a type="button" class="btn btn-danger btn-sm results-delete" data-id="' + element.id + '"><i class="glyphicon glyphicon-trash"></i></a></div></td>' +
                  '</tr>';
        });
        return html;
      });
      indexPage.setSearchValidate(function () {
        let $searchName = $('#search-name'),
            $searchStartAt = $('#search-start_at'),
            $searchEndAt = $('#search-end_at');
        if ($searchStartAt.val() && $searchEndAt.val() == '') {
          $searchName.attr('placeholder', '');
          $searchEndAt.focus();
          return false;
        }
        if ($searchEndAt.val() && $searchStartAt.val() == '') {
          $searchName.attr('placeholder', '');
          $searchStartAt.focus();
          return false;
        }
        if ($searchName.val() == '' && $searchStartAt.val() == '' && $searchEndAt.val() == '') {
          $searchName.attr('placeholder', '输入名称').focus();
          return false;
        }
        return true;
      })
    });
  </script>
@endsection