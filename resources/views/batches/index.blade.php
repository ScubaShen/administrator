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
              <a href="#">{{ $batch->engineering->name }}</a>
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

      <form role="form" class="row">
        <h2>检视</h2>
        <div class="form-group">
          <label for="view-name" class="control-label">批次名称</label>
          <div class="form-control" id="view-name" contenteditable="true" style="height: auto" readonly>{{ @$specificBatch->name }}</div>
        </div>

        <div class="form-group">
          <label for="view-user_name" class="control-label">创建人</label>
          <input class="form-control" type="text" id="view-user_name" contenteditable="true" style="height: auto"  value="{{ @$specificBatch->user->realname }}" readonly/>
        </div>

        {{--<div class="form-group">--}}
          {{--<label for="view-created_at" class="control-label">创建时间</label>--}}
          {{--<input class="form-control" type="text" id="view-created_at" value="{{ @$specificBatch->created_at }}" readonly/>--}}
        {{--</div>--}}

        <div class="form-group">
          <label for="view-engineering_name" class="control-label">所属工程</label>
          <input class="form-control" type="text" id="view-engineering_name" value="{{ @$specificBatch->engineering->name }}" readonly/>
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

        {{--<div class="form-group">--}}
          {{--<label for="view-start_at" class="control-label">工程开始时间</label>--}}
          {{--<input class="form-control" type="text" id="view-start_at" value="{{ @$specificBatch->start_at }}" readonly/>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
          {{--<label for="view-finish_at" class="control-label">工程结束时间</label>--}}
          {{--<input class="form-control" type="text" id="view-finish_at" value="{{ @$specificBatch->finish_at }}" readonly/>--}}
        {{--</div>--}}

        <div class="form-group">
          <label for="view-description" class="control-label">工程概况</label>
          <div class="form-control" id="view-description" contenteditable="true" style="height: auto" readonly>{{ @$specificBatch->description }}</div>
        </div>
      </form>
    </div>
  </div>
@stop

@section('scriptsAfterJs')
  <script>
    ;(function(){

      'use strict';

      var indexPage = function ()
      {
        var urlArray = window.location.href.split('/');

        // search
        var $searchName = $('#search-name');
        var $searchStartAt = $('#search-start_at');
        var $searchEndAt = $('#search-end_at');

        // paginate
        var $currentPage = $('#current_page');
        var $totalRows = $('#total_rows');
        var $lastPage = $('#last_page');
        var $rowsPerPage = $('#rows_per_page');
        var $selectAll = $('#select-all');
        var $deleteAll = $('#delete-all');
        var $prePage = $('#pre_page');
        var $nextPage = $('#next_page');
        var $refresh = $('#refresh');

        // results
        var $loadingRows = $('.loading_rows');
        var $resultsContainer = $('.results_container');
        var $noResults = $('.no_results');

        $refresh.on('click', function() {
          getRows();
        });

        $rowsPerPage.on('change', function() {
          getRows();
        });
        $currentPage.on('blur', function() {
          getRows();
        });
        $prePage.on('click', function() {
          getRows(parseInt($currentPage.val())-1);
        });
        $nextPage.on('click', function() {
          getRows(parseInt($currentPage.val())+1);
        });

        $('#search').on('click', function() {
          if(searchValidate()) {
            getRowsBySearch();
          }
        });

        $('#cancel_search').on('click', function() {
          $searchName.val('');
          $searchStartAt.val('');
          $searchEndAt.val('');
          getRows(1);
        });

        $resultsContainer.on('click', '.results-checkbox', function() {
          if ($(this).prop('checked')) {
            $deleteAll.removeClass('disabled');
          } else {
            var delete_ids = [];
            $('.select-checkbox').each(function(){
              $(this).prop('checked') && delete_ids.push($(this).val());
            });
            if(delete_ids.length == 0) {
              $deleteAll.addClass('disabled');
            }
          }
        });

        $resultsContainer.on('click', '.results-name', function() {
          var url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + $(this).data('id');
          $('#item_search_container').css('display', 'none');
          $('#item_show_container').css('display', 'block');
          $.ajax({
            url: url + '/view',
            type: 'GET',
            beforeSend: function() {
              history.replaceState('', '', url);
              $('.panel-right').scrollTop(0);
              $('.selected').removeClass('selected');
              $(this).parents('.result_rows').addClass('selected');
              $('.loading').css('display', 'block');
            },
            success: function (data) {   // ****************************************************************要修改之1
              $('#view-name').text(data.name);
              $('#view-user_name').val(data.user_name);
//              $('#view-created_at').val(data.created_at);
              $('#view-engineering_name').val(data.engineering_name);
              $('#view-technicians').text(data.technicians || null);
              $('#view-custodians').text(data.custodians || null);
              $('#view-safety_officers').text(data.safety_officers || null);
              $('#view-powdermen').text(data.powdermen || null);
              $('#view-manager').text(data.manager || null);
//              $('#view-start_at').val(data.start_at);
//              $('#view-finish_at').val(data.finish_at);
              $('#view-description').html(data.description);
              $('.loading').css('display', 'none');
            }
          })
        });

        $resultsContainer.on('click', '.results-delete', function() {
          deleteRows([String($(this).data('id'))]);
        });

        $selectAll.on('click', function() {
          if ($(this).prop('checked')){
            $deleteAll.removeClass('disabled');
            $('.select-checkbox').each(function(){
              $(this).prop('checked', true)
            })
          }else{
            $deleteAll.addClass('disabled');
            $('.select-checkbox').each(function() {
              $(this).prop('checked', false);
            })
          }
        });

        $deleteAll.on('click', function() {
          var delete_ids = [];
          $('.select-checkbox').each(function(){
            $(this).prop('checked') && delete_ids.push($(this).val());
          });
          if (delete_ids.length > 0) {
            deleteRows(delete_ids);
          }
        });

        $('#filter-btn').on('click', function (){
          $('#item_show_container').css('display', 'none');
          $('#item_search_container').css('display', 'block');
        });

        function deleteRows(ids) {
          var url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3];
          swal({
            title: "确认要删除该数据？",
            icon: "warning",
            buttons: ['取消', '确定'],
            dangerMode: true
          })
          .then(function (willDelete) {
            if (!willDelete) {
              return;
            }
            $.ajax({
              url: url + '/batch_delete',
              type: 'POST',
              data: {
                ids: ids,
                _method: 'delete'
              },
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              beforeSend: function() {
                if($.inArray(window.location.href.split('/')[4], ids) !== -1) {
                  history.replaceState('', '', url);
                  $('#view-name').text('');
                  $('#view-user_name').val('');
                  $('#view-created_at').val('');
                  $('#view-supervision_name').val('');  // ****************************************************************要修改之3
                  $('#view-technicians').text('');
                  $('#view-custodians').text('');
                  $('#view-safety_officers').text('');
                  $('#view-powdermen').text('');
                  $('#view-manager').text('');
                  $('#view-start_at').val('');
                  $('#view-finish_at').val('');
                  $('#view-description').text('');
                }
              },
              success: function (data) {
                swal('条目已被删除', '', 'success');
                getRows();
              },
              error: function() {
                swal('权限不足', '', 'error');
              }
            })
          })
        }

        function getRows(page) {
          page = page || $currentPage.val();
          page = (/^\+?[1-9][0-9]*$/.test(page) && (page <= $lastPage.text() && page || $lastPage.text()) || 1);

          // if on searching, use searching method , or use normal function
          if (searchValidate()) {
            getRowsBySearch(page);
          } else {
            $.ajax({
              url: urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/results',
              type: 'POST',
              data: {
                rows_per_page: $rowsPerPage.val(),
                page: page
              },
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              beforeSend: function() {
                $currentPage.val(page);
                $loadingRows.css('display', 'block');
              },
              success: function(data) {
                setResultRows(data);
              },
              error: function() {
                $loadingRows.css('display', 'none');
              }
            })
          }
        }

        function searchValidate() {
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
            $searchName.attr('placeholder', '输入工程名称').focus();
            return false;
          }
          return true;
        }

        function getRowsBySearch(page) {
          page = page || 1;

          $.ajax({
            url: urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/search',
            type: 'POST',
            data: $('#search-form').serialize()+'&'+'page='+page+'&'+'rows_per_page='+$rowsPerPage.val(),
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
              $loadingRows.css('display', 'block');
            },
            success: function (data) {
              setResultRows(data);
            }
          })
        }

        function setResultRows(data) {
//          console.log(data);
//          return;
          $selectAll.prop('checked', false);
          $deleteAll.addClass('disabled');

          data.lastpage == 1 && $prePage.prop('disabled', true)
                  .next().prop('disabled', true) ||
          data.page     == 1 && $prePage.prop('disabled', true)
                  .next().prop('disabled', false) ||
          data.page     == data.lastpage && $prePage.prop('disabled', false)
                  .next().prop('disabled', true) ||
          $prePage.prop('disabled', false)
                  .next().prop('disabled', false);

          $totalRows.text(data.total);
          $currentPage.val(data.page);
          $lastPage.text(data.lastpage);

          if (data.results.length == 0) {
            $resultsContainer.css('display', 'none');
            $noResults.css('display', 'block');
          } else {
            var html;
            $.each(data.results, function(index,element){

              //if this row is current page's parameter ,add class 'selected '
              var url = urlArray[0] + '//' + urlArray[2] + '/' + urlArray[3] + '/' + element.id;
              html += url === window.location.href && "<tr class='result_rows selected'>" || "<tr class='result_rows'>";

              html +=
                      '<td><label for="id"><input class="select-checkbox results-checkbox" type="checkbox" value="' + element.id + '"></label></td>' +
                      '<td><div style="max-width:260px"><a href="javascript:void(0)" class="results-name" data-id="' + element.id +'">'+element.name+'</a></div></td>' +
                      '<td><div style="max-width:260px"><a href="#">' + element.engineering.name + '</a></div></td>' +  // ****************************************************************要修改之2
                      '<td>' + element.start_at + '</td>' +
                      '<td>' + element.finish_at + '</td>' +
                      '<td><div><a href="' + url + '/edit" class="btn btn-primary btn-sm results-edit"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a> <a type="button" class="btn btn-danger btn-sm results-delete" data-id="' + element.id + '"><i class="glyphicon glyphicon-trash"></i></a></div></td>' +
                      '</tr>';

              $noResults.css('display', 'none');
              $resultsContainer.html(html).css('display', '');
            });
          }
          $loadingRows.css('display', 'none');
        }
      };

      $(function(){
        indexPage();
      });
    }());

  </script>
@endsection