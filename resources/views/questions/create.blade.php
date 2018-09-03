@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">面板</div>
                    <div class="panel-body">
                        <form action="{{url('/questions')}}" method="post">
                            {{csrf_field()}}
                            <div class="form-group {{$errors->has('title') ? 'has-error' : ''}}">
                                <label for="title">标题</label>
                                <input value="{{old('title')}}" class="form-control" type="text" name="title">
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group {{$errors->has('title') ? 'has-error' : ''}}">
                                <label for="正文">正文</label>
                                @include('vendor.ueditor.assets',['field'=>'body'])
                                @if ($errors->has('body'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">确认</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
