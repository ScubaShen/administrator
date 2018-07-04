@extends('layouts.app')

@section('content')

  <div class="row">

    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="text-center">
            作者：{{ $engineering->user->name }}
          </div>
          <hr>
          <div class="media">
            <div align="center">
              <a href="{{ route('users.show', $engineering->user->id) }}">picture
                {{--<img class="thumbnail img-responsive" src="{{ $engineering->user->avatar }}" width="300px" height="300px">--}}
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 topic-content">
      <div class="panel panel-default">
        <div class="panel-body">
          <h1 class="text-center">
            {{ $engineering->name }}
          </h1>

          <div class="article-meta text-center">
            {{ $engineering->created_at->diffForHumans() }}
            ⋅
            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>0
{{--            {{ $topic->reply_count }}--}}
          </div>

          <div class="topic-body">
            {!! $engineering->name !!}
          </div>

          @can('update', $engineering)
          <div class="operate">
            <hr>
            <a href="{{ route('topics.edit', $engineering->id) }}" class="btn btn-default btn-xs pull-left" role="button">
              <i class="glyphicon glyphicon-edit"></i> 编辑
            </a>

            <form action="{{ route('topics.destroy', $engineering->id) }}" method="post">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
              <button type="submit" class="btn btn-default btn-xs pull-left" style="margin-left: 6px">
                <i class="glyphicon glyphicon-trash"></i>
                删除
              </button>
            </form>
          </div>
          @endcan

        </div>
      </div>

      {{-- 用户回复列表 --}}
      {{--<div class="panel panel-default topic-reply">--}}
        {{--<div class="panel-body">--}}
          {{--@includeWhen(Auth::check(), 'topics._reply_box', ['topic' => $topic])--}}
          {{--@include('topics._reply_list', ['replies' => $topic->replies()->with('user')->get()])--}}
        {{--</div>--}}
      {{--</div>--}}
    </div>
  </div>
@stop