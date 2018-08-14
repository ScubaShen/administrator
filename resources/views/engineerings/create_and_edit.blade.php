@extends('layouts.app')

@section('content')
  <div class="main_container col-md-8">
    <div class="panel">
      <div class="panel-body">
        <div class="create_edit_header">
          <h2>
            <i class="glyphicon glyphicon-edit"></i>
            @if(@$engineering->id)
              编辑工程
            @else
              新建工程
            @endif
          </h2>
          <div class="return">
            <a href="{{ route('engineerings.index') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-arrow-left"></span> 返回</a>
          </div>
        </div>
        <hr>

        @include('common.error')

        @if(@$engineering->id)
          <form class="form-horizontal" action="{{ route('engineerings.update', $engineering->id) }}" method="POST" accept-charset="UTF-8">
            {{ method_field('PATCH') }}
        @else
          <form class="form-horizontal" action="{{ route('engineerings.store') }}" id="forms" method="POST" accept-charset="UTF-8">
        @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label for="name" class="col-md-2 control-label">工程名称</label>
              <div class="col-md-9">
                <input class="form-control" type="text" name="name" value="{{ old('name' ,@$engineering->name) }}" placeholder="请填写工程名称" required/>
              </div>
            </div>
            <div class="form-group">
              <label for="supervision_id" class="col-md-2 control-label">监理单位</label>
              <div class="col-md-9">
                <select class="selectpicker form-control" name="supervision_id" data-title="请选择监理单位" data-live-search="true" required>
                  @foreach($supervisions as $supervision)
                    <option value="{{ $supervision->id }}" {{ old('supervision_id', @$engineering->supervision_id) == $supervision->id ? 'selected' : null }}>{{ $supervision->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="start_at" class="col-md-2 control-label">工程开始时间</label>
              <div class="col-md-9">
                <input class="form-control" type="datetime-local" value="{{ old('start_at' ,@$engineering->start_at) }}" name="start_at" required/>
              </div>
            </div>
            <div class="form-group">
              <label for="finish_at" class="col-md-2 control-label">工程结束时间</label>
              <div class="col-md-9">
                <input class="form-control" type="datetime-local" value="{{ old('finish_at' ,@$engineering->finish_at) }}" name="finish_at" required/>
              </div>
            </div>
            <div class="form-group">
              <label for="finish_at" class="col-md-2 control-label">工程概况</label>
              <div class="col-md-9">
                <textarea name="description" class="form-control" id="editor" rows="3" placeholder="请填入至少三个字符的内容。" required>{{ old('description' ,@$engineering->description) }}</textarea>
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
@endsection

@section('styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop

@section('scriptsAfterJs')
  <script type="text/javascript"  src="{{ asset('js/module.js') }}"></script>
  <script type="text/javascript"  src="{{ asset('js/hotkeys.js') }}"></script>
  <script type="text/javascript"  src="{{ asset('js/uploader.js') }}"></script>
  <script type="text/javascript"  src="{{ asset('js/simditor.js') }}"></script>
  <script>
    $(document).ready(function(){
      var editor = new Simditor({
        textarea: $('#editor'),
        upload: {
          url: '{{ route('engineerings.upload_image') }}',
          params: { _token: '{{ csrf_token() }}' },
          fileKey: 'upload_file',  //服务器端获取图片的键值
          connectionCount: 3,  //最多只能同时上传 3 张图片
          leaveConfirm: '文件上传中，关闭此页面将取消上传。'  //用户关闭页面提醒
        },
        pasteImage: true,  //是否支持图片黏贴
      });
    });
  </script>
@endsection