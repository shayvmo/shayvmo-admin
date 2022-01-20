@extends('admin.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>
                <div class="layui-form-item">
                    通知
                </div>
            </h2>
        </div>
        <div class="layui-card-body">
            <lable>时间：</lable>
            {{ $message['time'] }}
        </div>
        <div class="layui-card-body">
            <lable>接收者：</lable>
            <img src="{{ $message['avatar'] }}" alt="接收者" width="30" height="30">
            {{ $user->nickname }}
        </div>
        <div class="layui-card-body">
            <lable>标题：</lable>
            {{ $message['title'] ?: '通知标题' }}
        </div>
        <div class="layui-card-body">
            <lable>内容：</lable>
            {{ $message['content'] ?: '通知内容' }}
        </div>
    </div>
@endsection

@section('script')

@endsection

