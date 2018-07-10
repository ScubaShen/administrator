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
          <option value="10" {{ $engineerings->perPage() == 10 ? 'selected' : null }}>10</option>
          <option value="20" {{ $engineerings->perPage() == 20 ? 'selected' : null }}>20</option>
          <option value="50" {{ $engineerings->perPage() == 50 ? 'selected' : null }}>50</option>
        </select>

        <span> 条目每页</span>
      </div>
      <div class="paginator">
        <a type="button" id="delete-all" class="btn btn-danger btn-sm disabled">
          <i aria-hidden="true"></i> 批量删除
        </a>
        Total: <span id="total_rows">{{ $engineerings->total() }}</span>&nbsp;&nbsp;
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="上一页" id="pre_page" onclick="javascript:getRows('{{ route('engineerings.result') }}', parseInt($('#current_page').val())-1);" {{ $engineerings->previousPageUrl() == null ? 'disabled' : null }}>
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="下一页" id="next_page" onclick="javascript:getRows('{{ route('engineerings.result') }}', parseInt($('#current_page').val())+1);" {{ $engineerings->nextPageUrl() == null ? 'disabled' : null }}>
        <input type="text" id="current_page" style="width: 30px;" value="{{ $engineerings->currentPage() }}" onblur="javascript:getRows('{{ route('engineerings.result') }}');">
        <span> / <div id="last_page" style="display:initial;">{{ $engineerings->lastPage() }}</div></span>
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
      <tbody class="results_container">
      @foreach($engineerings as $engineering)
      <tr class="result_rows {{ $engineering->id === @$specificEngineering->id ? 'selected' : null }}">

        <td><label for="id"><input class="select-checkbox" type="checkbox" value="{{ $engineering->id }}"></label></td>
        <td>{{ $engineering->id }}</td>
        <td>
          <div style="max-width:260px">
            <a href="javascript:void(0)" onclick="javascript:getView('{{ route('engineerings.view', $engineering->id) }}', $(this));">{{ $engineering->name }}</a>
          </div>
        </td>
        <td><a href="#">{{ $engineering->supervision_id }}</a></td>
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
              <button type="button" class="btn btn-danger btn-sm btn-del" style="background-color: #ed5565;border-color: #ed5565;color: white;margin: 2px 0;" onclick="javascript:deleteRow($(this));">
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
      current_page = (/^\+?[1-9][0-9]*$/.test(current_page) && (current_page <= $('#last_page').text() && current_page || $('#last_page').text()) || 1);
      $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
      $.ajax({
        url: url,
        type: 'POST',
        data: {
          rows_per_page: $('#rows_per_page').val(),
          current_page: current_page
        },
        beforeSend: function() {
          $('#current_page').val(current_page);
          $('.loading_rows').css('display', 'block');
        },
        success: function(data) {
          var html;
          $.each(data.results, function(index,element){
            html += 'http://bpjgpt.test/engineerings/'+element.id === window.location.href && "<tr class='result_rows selected'>" || "<tr class='result_rows'>";

            html +=
                    "<td><label for='id'><input class='select-checkbox' type='checkbox' value='"+element.id+"'></label></td>"+
                    "<td>"+element.id+"</td>"+
                    "<td><div style='max-width:260px'><a href='javascript:void(0)' onclick='javascript:getView("+'"http://bpjgpt.test/engineerings/'+element.id+'/view"'+", $(this));'>"+element.name+"</a></div></td>"+
                    "<td><a href='#'>"+element.supervision_id+"</a></td>"+
                    "<td>"+element.start_at+"</td>"+
                    "<td>"+element.finish_at+"</td>"+
                    "<td id='model_row_cell_operation'><div class='operation-row'><a href="+"http://bpjgpt.test/engineerings/"+element.id+"/edit"+" class='btn btn-primary btn-sm' style='background-color: #18a689;border-color: #18a689;color: white;margin: 2px 0;'> <i class='glyphicon glyphicon-edit' aria-hidden='true'></i></a>"+' <form action="'+'http://bpjgpt.test/engineerings/'+element.id+'" method="post" style="display: inline-block;">'+'{{ csrf_field() }}{{ method_field('DELETE') }}'+'<button type="button" class="btn btn-danger btn-sm btn-del" onclick="javascript:deleteRow($(this));" style="background-color: #ed5565;border-color: #ed5565;color: white;margin: 2px 0;"><i class="glyphicon glyphicon-trash"></i></button></form></div></td>'+
                    "</tr>";
          });
          if(data.lastpage == 1){$('#pre_page').attr('disabled', true);$('#next_page').attr('disabled', true);}
          else if(data.page == 1){$('#pre_page').attr('disabled', true);$('#next_page').attr('disabled', false);}
          else if(data.page == data.lastpage){$('#pre_page').attr('disabled', false);$('#next_page').attr('disabled', true);}
          else {$('#pre_page').attr('disabled', false);$('#next_page').attr('disabled', false);}

          $('#total_rows').text(data.total);
          $('#current_page').val(data.page);
          $('#last_page').text(data.lastpage);
          $('.results_container').html(html);
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
    };

    var deleteRow = function(_this)
    {
      swal({
        title: "确认要删除该数据？",
        icon: "warning",
        buttons: ['取消', '确定'],
        dangerMode: true,
      })
      .then(function (willDelete) {
        if (!willDelete) {
          return;
        }
        _this.parent().submit();
      })
    };

  </script>
@endsection