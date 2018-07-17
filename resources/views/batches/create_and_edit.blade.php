@extends('layouts.app')

@section('content')
  <div class="main_container col-md-8">
    <div class="panel">
      <div class="panel-body">
        <h2>
          <i class="glyphicon glyphicon-edit"></i>
          @if(@$batch->id)
            编辑批次
          @else
            新建批次
          @endif
        </h2>
        <hr>

        @include('common.error')

        @if(@$batch->id)
          <form class="form-horizontal" action="{{ route('batches.update', $batch->id) }}" method="POST" accept-charset="UTF-8">
            <input type="hidden" name="_method" value="PUT">
        @else
          <form class="form-horizontal" action="{{ route('batches.store') }}" id="forms" method="POST" accept-charset="UTF-8">
        @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label for="name" class="col-md-2 control-label">工程名称</label>
              <div class="col-md-9">
                <input class="form-control" type="text" name="name" value="{{ old('name' ,@$batch->name) }}" placeholder="请填写工程名称" required/>
              </div>
            </div>
            <div class="form-group">
              <label for="technician" class="col-md-2 control-label">技术员</label>
              <div class="col-md-9">
                <select class="selectpicker form-control" name="technician[]" data-title="请选择..." data-live-search="true" multiple>
                  @if(@$users_array[1])
                    @foreach($users_array[1] as $user)
                      <option value="{{ $user->id }}" {{ @in_array($user->id, old('technician', json_decode($engineering->data)->technicians)) ? 'selected' : null }}>{{ $user->realname }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="custodian" class="col-md-2 control-label">保管员</label>
              <div class="col-md-9">
                <select class="selectpicker form-control" name="custodian[]" data-title="请选择..." data-live-search="true" multiple>
                  @if(@$users_array[2])
                    @foreach($users_array[2] as $user)
                      <option value="{{ $user->id }}" {{ @in_array($user->id, old('custodian', json_decode($engineering->data)->custodians)) ? 'selected' : null }}>{{ $user->realname }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="safety_officer" class="col-md-2 control-label">安全员</label>
              <div class="col-md-9">
                <select class="selectpicker form-control" name="safety_officer[]" data-title="请选择..." data-live-search="true" multiple>
                  @if(@$users_array[3])
                    @foreach($users_array[3] as $user)
                      <option value="{{ $user->id }}" {{ @in_array($user->id, old('safety_officer', json_decode($engineering->data)->safety_officers)) ? 'selected' : null }}>{{ $user->realname }}</option>
                    @endforeach
                  @endif
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