@extends('layouts.app')

@section('content')
  <div class="main_container col-md-11">
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


            <div class="col-md-7">
              <div class="col-md-12" style="border: 1px solid #ddd;padding-bottom: 25px;margin-bottom: 25px;">

                <h3 class="page-header" style="font-size: 22px;">基本信息</h3>

                <div class="form-group">
                  <label for="name" class="col-md-2 control-label">批次名称</label>
                  <div class="col-md-9">
                    <input class="form-control" type="text" name="name" value="{{ old('name' ,@$batch->name) }}" placeholder="请填写批次名称" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="engineering_id" class="col-md-2 control-label">所属工程</label>
                  <div class="col-md-9">
                    <select class="selectpicker form-control" name="engineering_id" data-title="请选择所属工程" data-size="10" data-live-search="true" required>
                      @foreach($engineerings as $engineering)
                        <option value="{{ $engineering->id }}" {{ $engineering->id === old('engineering', @$batch->engineering_id) ? 'selected' : null }}>{{ $engineering->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="longitude" class="col-md-2 control-label">爆心经度</label>
                  <div class="col-md-9">
                    <input class="form-control" type="number" min="-180" max="180" step="0.01" oninput="if(value.length>5)value=value.slice(0,6)" value="{{ old('longitude' ,@$batch->longitude) }}" name="longitude" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="latitude" class="col-md-2 control-label">爆心纬度</label>
                  <div class="col-md-9">
                    <input class="form-control" type="number" min="0" max="90" step="0.01" oninput="if(value.length>4)value=value.slice(0,5)" value="{{ old('latitude' ,@$batch->latitude) }}" name="latitude" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="range" class="col-md-2 control-label">爆破范围</label>
                  <div class="col-md-9">
                    <input class="form-control" type="number" min="-180" max="180" step="0.01" oninput="if(value.length>5)value=value.slice(0,6)" value="{{ old('longitude' ,@$batch->range) }}" name="range" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="safe_distance" class="col-md-2 control-label">安全距离</label>
                  <div class="col-md-9">
                    <input class="form-control" type="number" min="-180" max="180" step="0.01" oninput="if(value.length>5)value=value.slice(0,6)" value="{{ old('latitude' ,@$batch->safe_distance) }}" name="safe_distance" required/>
                  </div>
                </div>

                <div class="form-group">
                  <label for="start_at" class="col-md-2 control-label">工程开始时间</label>
                  <div class="col-md-9">
                    <input class="form-control" type="datetime-local" value="{{ old('start_at' ,@$batch->start_at) }}" name="start_at" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="finish_at" class="col-md-2 control-label">工程结束时间</label>
                  <div class="col-md-9">
                    <input class="form-control" type="datetime-local" value="{{ old('finish_at' ,@$batch->finish_at) }}" name="finish_at" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="description" class="col-md-2 control-label">工程概况</label>
                  <div class="col-md-9">
                    <textarea class="form-control" name="description" id="editor" rows="3" placeholder="请填入至少三个字符的内容。" required>{{ old('description' ,@$batch->description) }}</textarea>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-5">
              <div class="col-md-12" style="border: 1px solid #ddd;padding-bottom: 25px;margin-bottom: 25px;">
                <h3 class="page-header" style="font-size: 22px;">人员信息</h3>
                <div class="form-group">
                  <label for="technicians" class="col-md-3 control-label">工程技术员</label>
                  <div class="col-md-8">
                    <select class="selectpicker form-control" name="technicians[]" data-title="请选择..." data-size="10" data-live-search="true" data-selected-text-format="count > 2" multiple required>
                      @if(@$users[1])
                        @foreach($users[1] as $user)
                          <option value="{{ $user->id }}" {{ @in_array($user->id, old('technicians', json_decode($batch->groups)->technicians)) ? 'selected' : null }}>{{ $user->realname }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="custodians" class="col-md-3 control-label">保管员</label>
                  <div class="col-md-8">
                    <select class="selectpicker form-control" name="custodians[]" data-title="请选择..." data-size="10" data-live-search="true" data-selected-text-format="count > 2" multiple required>
                      @if(@$users[2])
                        @foreach($users[2] as $user)
                          <option value="{{ $user->id }}" {{ @in_array($user->id, old('custodians', json_decode($batch->groups)->custodians)) ? 'selected' : null }}>{{ $user->realname }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="safety_officers" class="col-md-3 control-label">安全员</label>
                  <div class="col-md-8">
                    <select class="selectpicker form-control" name="safety_officers[]" data-title="请选择..." data-size="10" data-live-search="true" data-selected-text-format="count > 2" multiple required>
                      @if(@$users[3])
                        @foreach($users[3] as $user)
                          <option value="{{ $user->id }}" {{ @in_array($user->id, old('safety_officers', json_decode($batch->groups)->safety_officers)) ? 'selected' : null }}>{{ $user->realname }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="powdermen" class="col-md-3 control-label">爆破员</label>
                  <div class="col-md-8">
                    <select class="selectpicker form-control" name="powdermen[]" data-title="请选择..." data-size="10" data-live-search="true" data-selected-text-format="count > 2" multiple required>
                      @if(@$users[4])
                        @foreach($users[4] as $user)
                          <option value="{{ $user->id }}" {{ @in_array($user->id, old('powdermen', json_decode($batch->groups)->powdermen)) ? 'selected' : null }}>{{ $user->realname }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="manager" class="col-md-3 control-label">负责人</label>
                  <div class="col-md-8">
                    <select class="selectpicker form-control" name="manager" data-title="请选择..." data-size="10" data-live-search="true" required>
                      @if(@$users[1])
                        @foreach($users[1] as $user)
                          <option value="{{ $user->id }}" {{ @in_array($user->id, old('manager', json_decode($batch->groups)->manager)) ? 'selected' : null }}>{{ $user->realname }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>
                </div>
              </div>


              <div class="col-md-12" style="border: 1px solid #ddd;padding-bottom: 25px;margin-bottom: 25px;">
                <h3 class="page-header" style="font-size: 22px;">爆材信息</h3>
                <div class="form-group">
                  <label for="detonator" class="col-md-3 control-label">雷管</label>
                  <div class="col-md-8">
                    <input class="form-control" type="number" min="0" step="1" value="{{ old('detonator' ,@json_decode($batch->materials)->detonator) ?: 0 }}" name="detonator" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="dynamite" class="col-md-3 control-label">炸药</label>
                  <div class="col-md-8">
                    <input class="form-control" type="number" min="0" step="1" value="{{ old('dynamite' ,@json_decode($batch->materials)->dynamite) ?: 0 }}" name="dynamite" required/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="materials" class="col-md-3 control-label">其他爆材</label>
                  <div class="col-md-8">
                    <input class="form-control" type="text"/>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary" style=""><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 保存</button>
                </div>
              </div>
            </div>
          </form>
      </div>
    </div>
  </div>


@endsection