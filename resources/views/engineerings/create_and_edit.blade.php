@extends('layouts.app')

@section('content')

  <div class="main_container col-md-8">

      <div class="panel">

        <div class="panel-body">
          <h2>
            <i class="glyphicon glyphicon-edit"></i>
            @if(@$engineering->id)
              编辑工程
            @else
              新建工程
            @endif
          </h2>

          <hr>

          @include('common.error')

          @if(@$engineering->id)
            <form class="form-horizontal" action="{{ route('engineerings.update', $engineering->id) }}" method="POST" accept-charset="UTF-8">
              <input type="hidden" name="_method" value="PUT">
          @else
            <form class="form-horizontal" action="{{ route('engineerings.store') }}" method="POST" accept-charset="UTF-8">
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
                  <select class="form-control" name="supervision_id" required>
                    <option value="" hidden disabled selected>请选择监理单位</option>
                    <option value="1">Test</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="start_at" class="col-md-2 control-label">人员信息</label>
                <div class="col-md-9">
                  <button type="button" class="btn btn-default">浏览</button>
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

  <div class="item_show_container col-md-3">
    <div class="item_show">
      <form role="form" class="row" style="padding-top: 17px;">
        <h2 style="margin-bottom: 29px;">检视</h2>
        <div class="form-group">
          <label for="name" class="control-label">技术员</label>
          <input class="form-control" type="text" id="name" name="name" value="" readonly/>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">保管员</label>
          <input class="form-control" type="text" id="user_name" name="user_name" value="" readonly/>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">安全员</label>
          <input class="form-control" type="text" id="created_at" name="created_at" value="" readonly/>
        </div>
      </form>
    </div>

    {{--<div class="item_show" style="margin-top: 30px;">--}}
        {{--<form role="form" class="row" style="padding-top: 17px;">--}}
        {{--<h2 style="margin-bottom: 29px;">技术员</h2>--}}
        {{--<div class="form-group">--}}
          {{--<select class="form-control">--}}
            {{--<option>请选择...</option>--}}
          {{--</select>--}}
          {{--<div class="select2-drop" id="select2-drop">--}}
            {{--<div class="select2-search">--}}
              {{--<input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input form-control">--}}
            {{--</div>--}}
            {{--<ul class="select2-results">--}}
              {{--<li class="select2-no-results">请再输入1个字符</li>--}}
            {{--</ul>--}}
          {{--</div>--}}
        {{--</div>--}}
      {{--</form>--}}
    {{--</div>--}}

    {{--<div class="item_show" style="margin-top: 30px;">--}}
      {{--<form role="form" class="row" style="padding-top: 17px;">--}}
        {{--<h2 style="margin-bottom: 29px;">保管员</h2>--}}
        {{--<div class="form-group">--}}
          {{--<ul style="">--}}
            {{--<li class="option">--}}
              {{--<input id="selectall" class="allcase" name="all" type="checkbox" value="all">--}}
              {{--<a href="#">全部</a>--}}
            {{--</li>--}}
            {{--<li class="option">--}}
              {{--<input class="case" name="checkbox" type="checkbox" value="">--}}
              {{--<a href="#">选项一</a>--}}
            {{--</li>--}}
            {{--<li class="option">--}}
              {{--<input class="case" name="checkbox" type="checkbox" value="">--}}
              {{--<a href="#">选项二</a>--}}
            {{--</li>--}}
            {{--<li class="option">--}}
              {{--<input class="case" name="checkbox" type="checkbox" value="">--}}
              {{--<a href="#">选项三</a>--}}
            {{--</li>--}}
            {{--<li class="option">--}}
              {{--<input class="case" name="checkbox" type="checkbox" value="">--}}
              {{--<a href="#">选项四</a>--}}
            {{--</li>--}}
            {{--<li class="option">--}}
              {{--<input class="case" name="checkbox" type="checkbox" value="">--}}
              {{--<a href="#">选项五</a>--}}
            {{--</li>--}}

          {{--</ul>--}}
          {{--<select class="form-control" contenteditable="true" multiple>--}}
            {{--<option>请选择...</option>--}}
            {{--@for($i=1;$i<=50;$i++)--}}
              {{--<option>{{ $i }}</option>--}}
            {{--@endfor--}}
          {{--</select>--}}
        {{--</div>--}}
      {{--</form>--}}
    {{--</div>--}}

    <div class="item_show" style="margin-top: 30px;">
      <form role="form" class="row" style="padding-top: 17px;">
        <h2 style="margin-bottom: 29px;">安全员</h2>

        <div class="form-group">
          <label for="name" class="control-label">搜尋姓名:</label>
          <input class="form-control" type="text" id="created_at" name="created_at" value=""/>
        </div>

        <div class="form-group">
          <label for="name" class="control-label">人员</label>
          <div class="dropdown">
            <button class="dropdown-toggle form-control" type="button" data-toggle="dropdown" id="dropdownMenu1">请选择...</button>
            <div class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="position: relative;width: 100%;min-width: unset;margin-top: 0;border-top: 0;">
              <div class="scroll">
                <ul class="row" style="list-style: none;padding-left: 0;margin: 0;">
                  <li class="option col-md-6" style="">
                    <div style="text-align: left;">
                      <input class="case" name="checkbox" type="checkbox" value="">
                      <span>选项一</span>
                    </div>
                  </li>
                  <li class="option col-md-6" style="">
                    <div style="text-align: left;">
                      <input class="case" name="checkbox" type="checkbox" value="">
                      <span>选项一</span>
                    </div>
                  </li>

                </ul>
              </div>
              <button class="btn btn-primary btn-sm">确定<span class="count">(共选择&nbsp;<i id="count">0</i>&nbsp;项)</span></button>
              <button class="btn btn-default btn-sm">取消</button>

            </div>
          </div>
        </div>
      </form>
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