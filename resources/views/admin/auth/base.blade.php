
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> 后台管理系统 </title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset(BE_COMPONENT.'/pear/css/pear.css')}}"/>
    <link rel="stylesheet" href="{{asset(BE_ADMIN.'/css/other/login.css')}}" />
    @yield('style')
</head>
<body background="/static/admin/admin/images/background.svg" style="background-size: cover;">
    <form class="layui-form" action="javascript:void(0);">
        {{csrf_field()}}
        <div class="layui-form-item">
            <img class="logo" src="/static/admin/admin/images/logo.png" />
            <div class="title">沙屿沫 Admin</div>
            <div class="desc">总 有 人 山 高 路 远 为 你 而 来</div>
        </div>
        @yield('content')
    </form>

<!-- 资 源 引 入 -->
<script src="{{asset(BE_COMPONENT.'/layui/layui.js')}}"></script>
<script src="{{asset(BE_COMPONENT.'/pear/pear.js')}}"></script>
<script>
    layui.use(['layer'], function() {
        var $ = layui.jquery;
        var layer = layui.layer;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //错误提示
        @if(count($errors)>0)
        @foreach($errors->all() as $error)
        layer.msg("{{$error}}",{icon:2});
        @break
        @endforeach
        @endif

        //一次性正确信息提示
        @if(session('success'))
        layer.msg("{{session('success')}}",{icon:1});
        @endif

        //一次性错误信息提示
        @if(session('error'))
        layer.msg("{{session('error')}}",{icon:2});
        @endif

    });

</script>
@yield('script')
</body>

</html>



