@extends('layouts.app')

@section('content')
  <div class="main_container col-md-8">
    <div class="panel">
      <div class="panel-body">
        <div class="create_edit_header">
          <h2>
            <i class="glyphicon glyphicon-edit"></i>
            @if(@$material->id)
              编辑爆材
            @else
              新建爆材
            @endif
          </h2>
          <div class="return">
            <a href="{{ route('materials.index') }}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-arrow-left"></span> 返回</a>
          </div>
        </div>
        <hr>

        @include('common.error')

        @if(@$material->id)
          <form class="form-horizontal" action="{{ route('materials.update', $material->id) }}" method="POST" accept-charset="UTF-8">
            {{ method_field('PATCH') }}
        @else
          <form class="form-horizontal" action="{{ route('materials.store') }}" id="forms" method="POST" accept-charset="UTF-8">
        @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label for="name" class="col-md-2 control-label">爆材名称</label>
              <div class="col-md-9">
                <input class="form-control" type="text" name="name" value="{{ old('name' ,@$material->name) }}" placeholder="请填写工程名称" required/>
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