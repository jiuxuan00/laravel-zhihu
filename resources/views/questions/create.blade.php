@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">面板</div>
                    <div class="panel-body">

                        {{--百度编辑器--}}
                        @include('vendor.ueditor.assets',['field'=>'body'])

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
