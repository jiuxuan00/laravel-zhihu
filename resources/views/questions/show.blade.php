@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">标题：{{$question->title}}</div>
                    <div class="panel-body">正文：{!! $question->body !!}</div>
                </div>
            </div>
        </div>
    </div>

@endsection
