@extends('layouts.app')
{{--@section('title', '所有用户')--}}

@section('content')
  <div class="main_container col-md-8">

    <div class="results_header">
      <h2>批次</h2>
      <div class="actions">
        <a class="btn btn-w-m btn-primary" href="{{ route('batches.create') }}">新建批次</a>

        <a id="filter-btn" type="button" class="btn btn-w-m btn-success">筛选</a>
      </div>
    </div>

    <div class="page_container">
      <div class="per_page">
        <button class="btn btn-white btn-sm" id="refresh" data-toggle="tooltip" data-placement="left" title="刷新" data-original-title="Refresh inbox"><i class="glyphicon glyphicon-refresh"></i></button>

        <select id="rows_per_page">
          <option value="10" {{ $batches->perPage() == 10 ? 'selected' : null }}>10</option>
          <option value="20" {{ $batches->perPage() == 20 ? 'selected' : null }}>20</option>
          <option value="50" {{ $batches->perPage() == 50 ? 'selected' : null }}>50</option>
        </select>

        <span> 条目每页</span>
      </div>
      <div class="paginator" style="display:inline-block;">
        <a type="button" id="delete-all" class="btn btn-danger btn-sm disabled">
          <i aria-hidden="true"></i> 批量删除
        </a>
        Total: <span id="total_rows">{{ $batches->total() }}</span>&nbsp;&nbsp;
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="上一页" id="pre_page" {{ $batches->previousPageUrl() == null ? 'disabled' : null }}>
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="下一页" id="next_page" {{ $batches->nextPageUrl() == null ? 'disabled' : null }}>
        <input type="text" id="current_page" style="width: 30px;" value="{{ $batches->currentPage() }}">
        <span> / <div id="last_page" style="display:initial;">{{ $batches->lastPage() }}</div></span>
      </div>
    </div>

    <table class="results table table-hover" border="0" cellspacing="0" id="customers" cellpadding="0">
      <thead>
      <tr>
        <th>
          <label for="select-all" style="white-space:nowrap;"><input id="select-all" type="checkbox" value=""></label>
        </th>
        <th><div>名称</div></th>
        <th><div>所属工程</div></th>
        <th><div>开始时间</div></th>
        <th><div>结束时间</div></th>
        <th><div>管理</div></th>
      </tr>
      </thead>
      <tbody class="results_container">
      @foreach($batches as $batch)
        <tr class="result_rows {{ $batch->id === @$specificBatch->id ? 'selected' : null }}">

          <td><label for="id"><input class="select-checkbox results-checkbox" type="checkbox" value="{{ $batch->id }}"></label></td>
          <td>
            <div style="max-width:260px">
              <a href="javascript:void(0)" class="results-name" data-id="{{ $batch->id }}">{{ $batch->name }}</a>
            </div>
          </td>
          <td>
            <div style="max-width:260px">
              <a href="javascript:void(0)">{{ $batch->engineering->name }}</a>
            </div>
          </td>
          <td>{{ $batch->start_at }}</td>
          <td>{{ $batch->finish_at }}</td>
          <td>
            <div>
              <a href="{{ route('batches.edit', $batch->id) }}" class="btn btn-primary btn-sm results-edit">
                <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
              </a>
              <a type="button" class="btn btn-danger btn-sm results-delete" data-id="{{ $batch->id }}">
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
          <label for="name" class="control-label">批次名称</label>
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
          <button type="button" class="btn btn-default form-control" id="cancel_search" style="margin-top: 5px;"> 返回 </button>
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
          <label for="view-name" class="control-label">批次名称</label>
          <div class="form-control" id="view-name" contenteditable="true" style="height: auto" readonly>{{ @$specificBatch->name }}</div>
        </div>

        <div class="form-group">
          <label for="view-user_name" class="control-label">创建人</label>
          <div class="form-control" id="view-user_name" contenteditable="true" style="height: auto" readonly>{{ @$specificBatch->user->realname }}</div>
        </div>

        <div class="form-group">
          <label for="view-engineering_name" class="control-label">所属工程</label>
          <div class="form-control" id="view-engineering_name" contenteditable="true" style="height: auto" readonly>{{ @$specificBatch->engineering->name }}</div>
        </div>

        <div class="form-group">
          <label for="view-technicians" class="control-label">技术员</label>
          <div class="form-control" id="view-technicians" contenteditable="true" style="height: auto" readonly>{{ @$groups['technicians'] }}</div>
        </div>

        <div class="form-group">
          <label for="view-custodians" class="control-label">保管员</label>
          <div class="form-control" id="view-custodians" contenteditable="true" style="height: auto" readonly>{{ @$groups['custodians'] }}</div>
        </div>

        <div class="form-group">
          <label for="view-safety_officers" class="control-label">安全员</label>
          <div class="form-control" id="view-safety_officers" contenteditable="true" style="height: auto" readonly>{{ @$groups['safety_officers'] }}</div>
        </div>

        <div class="form-group">
          <label for="view-powdermen" class="control-label">爆破员</label>
          <div class="form-control" id="view-powdermen" contenteditable="true" style="height: auto" readonly>{{ @$groups['powdermen'] }}</div>
        </div>

        <div class="form-group">
          <label for="view-manager" class="control-label">负责人</label>
          <div class="form-control" id="view-manager" contenteditable="true" style="height: auto" readonly>{{ @$groups['manager'] }}</div>
        </div>

        <div class="form-group">
          <label for="view-description" class="control-label">工程概况</label>
          <div class="form-control" id="view-description" contenteditable="true" style="height: auto" readonly>{{ @$specificBatch->description }}</div>
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
          var url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + element.id;
          html += url === window.location.href && "<tr class='result_rows selected'>" || "<tr class='result_rows'>";
          html +=
                  '<td><label for="id"><input class="select-checkbox results-checkbox" type="checkbox" value="' + element.id + '"></label></td>' +
                  '<td><div style="max-width:260px"><a href="javascript:void(0)" class="results-name" data-id="' + element.id +'">'+element.name+'</a></div></td>' +
                  '<td><div style="max-width:260px"><a href="javascript:void(0)">' + element.engineering.name + '</a></div></td>' +
                  '<td>' + element.start_at + '</td>' +
                  '<td>' + element.finish_at + '</td>' +
                  '<td><div><a href="' + url + '/edit" class="btn btn-primary btn-sm results-edit"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a> <a type="button" class="btn btn-danger btn-sm results-delete" data-id="' + element.id + '"><i class="glyphicon glyphicon-trash"></i></a></div></td>' +
                  '</tr>';
        });
        return html;
      });
    });
  </script>
@endsection