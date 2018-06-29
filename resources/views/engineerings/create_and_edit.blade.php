@extends('layouts.app')

@section('content')

  <div class="container">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">

        <div class="panel-body">
          <h2 class="text-center">
            <i class="glyphicon glyphicon-edit"></i>
            @if($engineering->id)
              编辑工程
            @else
              新建工程
            @endif
          </h2>

          <hr>

          @include('common.error')

          @if($engineering->id)
            <form class="form-horizontal" action="{{ route('engineerings.update', $engineering->id) }}" method="POST" accept-charset="UTF-8">
              <input type="hidden" name="_method" value="PUT">
          @else
            <form class="form-horizontal" action="{{ route('engineerings.store') }}" method="POST" accept-charset="UTF-8">
          @endif
              <input type="hidden" name="_token" value="{{ csrf_token() }}">

              <div class="form-group">
                <label for="name" class="col-md-2 control-label">工程名称</label>
                <div class="col-md-10">
                  <input class="form-control" type="text" name="name" value="" placeholder="请填写工程名称" required/>
                </div>
              </div>

              <div class="form-group">
                <label for="supervision_id" class="col-md-2 control-label">监理单位</label>
                <div class="col-md-10">
                  <select class="form-control" name="supervision_id" required>
                    <option value="" hidden disabled selected>请选择监理单位</option>
                    <option value="1">Test</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="start_at" class="col-md-2 control-label">工程开始时间</label>
                <div class="col-md-10">
                  <input class="form-control" type="datetime-local" name="start_at" required/>
                </div>
              </div>

              <div class="form-group">
                <label for="finish_at" class="col-md-2 control-label">工程结束时间</label>
                <div class="col-md-10">
                  <input class="form-control" type="datetime-local" name="finish_at" required/>
                </div>
              </div>

              <div class="form-group">
                <label for="finish_at" class="col-md-2 control-label">工程概况</label>
                <div class="col-md-10">
                  <textarea name="description" class="form-control" id="editor" rows="3" placeholder="请填入至少三个字符的内容。" required></textarea>
                </div>
              </div>

              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 保存</button>
                </div>
              </div>

            </form>
        </div>
      </div>
    </div>
  </div>
@endsection