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
      <div class="per_page">
        <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="刷新" data-original-title="Refresh inbox" onclick="javascript:getRows('{{ route('engineerings.result') }}');"><i class="glyphicon glyphicon-refresh"></i></button>

        <select id="rows_per_page" onchange="javascript:getRows('{{ route('engineerings.result') }}');">
          <option value="10" {{ $engineerings->perPage() == 10 ? 'selected' : null }}>10</option>
          <option value="20" {{ $engineerings->perPage() == 20 ? 'selected' : null }}>20</option>
          <option value="50" {{ $engineerings->perPage() == 50 ? 'selected' : null }}>50</option>
        </select>

        <span> 条目每页</span>
      </div>
      <div class="paginator" style="display:inline-block;">
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
        <td><a href="#">{{ $engineering->supervision->name }}</a></td>
        <td>{{ $engineering->start_at }}</td>
        <td>{{ $engineering->finish_at }}</td>
        <td>
          <div>
            <a href="{{ route('engineerings.edit', $engineering->id) }}" class="btn btn-primary btn-sm" style="background-color: #18a689;border-color: #18a689;color: white;margin: 2px 0;">
              <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
            </a>
            <a type="button" class="btn btn-danger btn-sm btn-del" style="background-color: #ed5565;border-color: #ed5565;color: white;margin: 2px 0;" onclick="javascript:deleteRows(['{{ $engineering->id }}']);">
              <i class="glyphicon glyphicon-trash"></i>
            </a>
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

  <div class="item_show_container col-md-3" id="item_search_container" style="display: none;">
    <div class="item_show">

      <form role="form" class="row">
        <h2>筛选</h2>
        <div class="form-group">
          <label for="name" class="control-label">工程名称 或 ID</label>
          <div class="form-control" contenteditable="true" style="height: auto"></div>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">开始时间起</label>
          <input class="form-control" type="date">
        </div>

        <div class="form-group">
          <label for="name" class="control-label">至</label>
          <input class="form-control" type="date">
        </div>

        <div class="form-group">
          <label class="control-label"></label>
          <button type="button" class="btn btn-primary form-control"> 查询 </button>
        </div>

      </form>
    </div>
  </div>

  <div class="item_show_container col-md-3" id="item_show_container">
    <div class="item_show">
      <div class="loading" style="display: none;">
        <div class="loading_text">载入中...</div>
      </div>

      <form role="form" class="row">
        <h2>检视</h2>
        <div class="form-group">
          <label for="name" class="control-label">工程名称</label>
          <div class="form-control" id="name" contenteditable="true" style="height: auto" readonly>{{ @$specificEngineering->name }}</div>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">创建人</label>
          <input class="form-control" type="text" id="user_name" contenteditable="true" style="height: auto"  value="{{ @$specificEngineering->user->realname }}" readonly/>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">创建时间</label>
          <input class="form-control" type="text" id="created_at" value="{{ @$specificEngineering->created_at }}" readonly/>
        </div>

        <div class="form-group">
          <label for="supervision_id" class="control-label">监理单位</label>
          <input class="form-control" type="text" id="supervision_name" value="{{ @$specificEngineering->supervision->name }}" readonly/>
        </div>

        <div class="form-group">
          <label for="technicians" class="control-label">技术员</label>
          <div class="form-control view-body" id="technicians" contenteditable="true" style="height: auto" readonly>{{ @$data['technicians'] }}</div>
        </div>

        <div class="form-group">
          <label for="custodians" class="control-label">保管员</label>
          <div class="form-control view-body" id="custodians" contenteditable="true" style="height: auto" readonly>{{ @$data['custodians'] }}</div>
        </div>

        <div class="form-group">
          <label for="safety_officers" class="control-label">安全员</label>
          <div class="form-control view-body" id="safety_officers" contenteditable="true" style="height: auto" readonly>{{ @$data['safety_officers'] }}</div>
        </div>

        <div class="form-group">
          <label for="start_at" class="control-label">工程开始时间</label>
          <input class="form-control" type="text" id="start_at" value="{{ @$specificEngineering->start_at }}" readonly/>
        </div>

        <div class="form-group">
          <label for="finish_at" class="control-label">工程结束时间</label>
          <input class="form-control" type="text" id="finish_at" value="{{ @$specificEngineering->finish_at }}" readonly/>
        </div>

        <div class="form-group">
          <label for="finish_at" class="control-label">工程概况</label>
          <div class="form-control view-body" id="description" contenteditable="true" style="height: auto" readonly>{!! @$specificEngineering->description !!}</div>
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
      $.ajax({
        url: url,
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
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
                    "<td><a href='#'>"+element.supervision.name+"</a></td>"+
                    "<td>"+element.start_at+"</td>"+
                    "<td>"+element.finish_at+"</td>"+
                    "<td><div><a href="+"http://bpjgpt.test/engineerings/"+element.id+"/edit"+" class='btn btn-primary btn-sm' style='background-color: #18a689;border-color: #18a689;color: white;margin: 2px 0;'> <i class='glyphicon glyphicon-edit' aria-hidden='true'></i></a>"+' <form action="'+'http://bpjgpt.test/engineerings/'+element.id+'" method="post" style="display: inline-block;">'+'{{ csrf_field() }}{{ method_field('DELETE') }}'+'<button type="button" class="btn btn-danger btn-sm btn-del" onclick="javascript:deleteRows('+"['"+element.id+"']"+');" style="background-color: #ed5565;border-color: #ed5565;color: white;margin: 2px 0;"><i class="glyphicon glyphicon-trash"></i></button></form></div></td>'+
                    "</tr>";
          });

          data.lastpage == 1 && $('#pre_page').prop('disabled', true).next().prop('disabled', true) ||
          data.page == 1 && $('#pre_page').prop('disabled', true).next().prop('disabled', false) ||
          data.page == data.lastpage && $('#pre_page').prop('disabled', false).next().prop('disabled', true) ||
          $('#pre_page').prop('disabled', false).next().prop('disabled', false);

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

    var getView = function(url, _this)
    {
      $('#item_search_container').css('display', 'none');
      $('#item_show_container').css('display', 'block');
      $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
          history.replaceState('', '', url.replace("/view", ''));
          $('.panel-right').scrollTop(0);
          $('.selected').removeClass('selected');
          _this.parents('.result_rows').addClass('selected');
          $('.loading').css('display', 'block');

        },
        success: function (data) {
          $('#name').text(data.name);
          $('#user_name').val(data.user_name);
          $('#created_at').val(data.created_at);
          $('#supervision_name').val(data.supervision_name);
          $('#technicians').text(data.technicians || null);
          $('#custodians').text(data.custodians || null);
          $('#safety_officers').text(data.safety_officers || null);
          $('#start_at').val(data.start_at);
          $('#finish_at').val(data.finish_at);
          $('#description').html(data.description);
          $('.loading').css('display', 'none');
        }
      })
    };

    var deleteRows = function(ids)
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
        $.ajax({
          url: '/engineerings/batch_delete',
          type: 'POST',
          data: {
            ids: ids,
            _token: '{{ csrf_token() }}',
            _method: 'delete'
          },
          beforeSend: function() {
            if($.inArray(window.location.href.split('/')[4], ids) !== -1) {
              history.replaceState('', '', '{{ route('engineerings.index') }}');
              $('#name').text('');
              $('#user_name').val('');
              $('#created_at').val('');
              $('#supervision_name').val('');
              $('#technicians').text('');
              $('#custodians').text('');
              $('#safety_officers').text('');
              $('#start_at').val('');
              $('#finish_at').val('');
              $('#description').text('');
            }
          },
          success: function (data) {
            swal('条目已被删除', '', 'success');
            getRows('{{ route('engineerings.result') }}');
          },
          error: function() {
            swal('权限不足', '', 'error');
          }
        })
      })
    };

    $('#select-all').click(function(){
        if($(this).prop('checked')){
          $('#delete-all').removeClass('disabled');

          $('.select-checkbox').each(function(){
            $(this).prop('checked', true)
          })
        }else{
          $('#delete-all').addClass('disabled');

          $('.select-checkbox').each(function() {
            $(this).prop('checked', false);
          })
        }
    });

    $('.select-checkbox').click(function(){
      if($(this).prop('checked')) {
        $('#delete-all').removeClass('disabled');
      }else {
        var delete_ids = [];
        $('.select-checkbox').each(function(){
          $(this).prop('checked') && delete_ids.push($(this).val());
        });
        if(delete_ids.length == 0) {

          $('#delete-all').addClass('disabled');
        }
      }
    });

    $('#delete-all').click(function(){
      var delete_ids = [];
      $('.select-checkbox').each(function(){
        $(this).prop('checked') && delete_ids.push($(this).val());
      });
      if(delete_ids.length > 0) {

        deleteRows(delete_ids);
      }
    });

    $('#filter-btn').click(function() {
      $('#item_show_container').css('display', 'none');
      $('#item_search_container').css('display', 'block');
    })

  </script>
@endsection