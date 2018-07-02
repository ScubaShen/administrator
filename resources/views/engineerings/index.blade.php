@extends('layouts.app')
{{--@section('title', '所有用户')--}}

@section('content')
  <div class="table_container col-md-8" style="background-color: white;border: 1px solid #E0E0E0;padding: 13px;min-height: 400px;margin: 15px 15px 50px 15px;position: relative;">

    <div class="results_header" style="height: 57px;">
      <h2 style="margin-top: 15px;color: #676A6C;">工程</h2>
      <div class="actions" style="position: absolute;top: 27px;right: 15px;">
        <a class="btn btn-w-m btn-primary" href="{{ route('engineerings.create') }}" style="min-width: 120px;">新建工程</a>

        <input id="filter-btn-success" type="button" value="筛选" class="btn btn-w-m btn-success" style="min-width: 120px;">
      </div>

      <div class="action_message" style="display: none;"></div>
    </div>

    <div class="page_container" style="position: relative;padding: 5px;border: 1px solid #d4d4d4;">
      <div class="per_page" style="vertical-align: middle;position: absolute;right: 15px;top: 5px;overflow: hidden;">
        <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="" data-original-title="Refresh inbox" style="border: 1px solid #e7eaec;background-color: white;"><i class="glyphicon glyphicon-refresh"></i></button>

        <select style="height: 27px;">
          <option>20</option>
          <option>50</option>
          <option>100</option>
          <option>200</option>
          <option>1000</option>
        </select>
        <input type="hidden" value="20" tabindex="-1" class="select2-offscreen" style="">
        <span> 条目每页</span>
      </div>
      <div class="paginator">
        <a type="button" id="delete-all" class="btn btn-danger btn-sm disabled">
          <i class="fa fa-trash" aria-hidden="true"></i> 批量删除
        </a>
        Total: <span>101</span>&nbsp;&nbsp;
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="上一页" disabled>
        <input type="button" class="btn btn-outline btn-primary btn-xs" value="下一页">
        <input type="text" style="width: 30px;">
        <span> / 6</span>
      </div>
    </div>

    <table class="results table table-hover" border="0" cellspacing="0" id="customers" cellpadding="0" style="margin-top: 12px;">
      <thead style="background-color: rgba(236, 245, 243, 0.7);border: 1px solid #C8E4DF;">
      <tr>
        <th style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">
          <label for="select-all" style="white-space:nowrap;"><input id="select-all" type="checkbox" value=""></label>
        </th>
        <th style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;" class="sortable sorted-desc">
          <div>ID</div>
        </th>

        <th style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">
          <div>名称</div>
        </th>

        <th style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">
          <div>监理单位</div>
        </th>

        <th style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">
          <div>开始时间</div>
        </th>

        <th style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">
          <div>结束时间</div>
        </th>

        <th style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">
          <div>管理</div>
        </th>

      </tr>
      </thead>
      <tbody>
      @foreach($engineerings as $engineering)
      <tr class="result odd">

        <td style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;"><label for=""><input class="select-checkbox" type="checkbox" value="{{ $engineering->id }}"></label></td>
        <td style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">{{ $engineering->id }}</td>
        <td style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">
          <div style="max-width:260px">
            <a href="javascript:void(0)" target="_blank" onclick="$.get('{{ route('engineerings.show', $engineering->id) }}', function(data) {
                    console.log(data);
                    //$('name').val();
                    })">{{ $engineering->name }}
            </a>
          </div>
        </td>
        <td style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;"><a href="#" target="_blank">{{ $engineering->supervision_id }}</a></td>
        <td style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">{{ $engineering->start_at }}</td>
        <td style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;">{{ $engineering->finish_at }}</td>
        <td style="border: 1px solid #D7D8D8;border-style: dotted;line-height: 1.42857;padding: 8px;vertical-align: middle;text-align: center;" id="model_row_cell_operation">

          <div class="operation-row">
            <a href="javascript:void(0)" class="btn btn-primary btn-sm" style="background-color: #18a689;border-color: #18a689;color: white;margin: 2px 0;">
              <i class="glyphicon glyphicon-edit" aria-hidden="true"></i>
            </a>
            <a type="button" class="btn btn-danger btn-sm" style="background-color: #ed5565;border-color: #ed5565;color: white;margin: 2px 0;">
              <i class="	glyphicon glyphicon-trash" aria-hidden="true"></i>
            </a>
          </div>
        </td>
      </tr>
      @endforeach
      </tbody>
    </table>

    <div class="loading_rows" style="display: none;">
      <div>载入中...</div>
    </div>

    <div class="no_results" style="display: none;">
      <div>没有结果</div>
    </div>
  </div>



  <div class="item_edit_container scrollable col-md-3" style="top: 15px;overflow: auto;">
    <div class="item_edit" style="margin-left: 2px;border: 1px solid rgb(224, 224, 224);padding: 10px 25px 50px 25px;">
      <div class="loading" style="display: none;">载入中...</div>

      <form role="form" class="row" id="specificEngineering">
        <h2 style="margin-bottom: 25px;color: #676A6C;">检视</h2>
        <div class="form-group">
          <label for="name" class="control-label">工程名称</label>
          <input class="form-control" type="text" id="name" name="name" value="{{ @$specificEngineering ? $specificEngineering->name : null }}" disabled/>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">创建人</label>
          <input class="form-control" type="text" id="user_name" name="user_name" value="{{ @$specificEngineering ? $specificEngineering->user->name : null }}" disabled/>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">创建时间</label>
          <input class="form-control" type="text" id="created_at" name="created_at" value="{{ @$specificEngineering ? $specificEngineering->created_at : null }}" disabled/>
        </div>

        <div class="form-group">
          <label for="supervision_id" class="control-label">监理单位</label>
          <input class="form-control" type="text" id="supervision_id" name="supervision_id" value="{{ @$specificEngineering ? $specificEngineering->supervision_id : null }}" disabled/>
        </div>

        <div class="form-group">
          <label for="start_at" class="control-label">工程开始时间</label>
          <input class="form-control" type="text" id="start_at" name="start_at" value="{{ @$specificEngineering ? $specificEngineering->start_at : null }}" disabled/>
        </div>

        <div class="form-group">
          <label for="finish_at" class="control-label">工程结束时间</label>
          <input class="form-control" type="text" id="finish_at" name="finish_at" value="{{ @$specificEngineering ? $specificEngineering->finish_at : null }}" disabled/>
        </div>

        <div class="form-group">
          <label for="finish_at" class="control-label">工程概况</label>
          <textarea class="form-control" id="description" name="description" rows="3" disabled>{{ @$specificEngineering ? $specificEngineering->description : null }}</textarea>
        </div>
      </form>
    </div>
  </div>
@stop