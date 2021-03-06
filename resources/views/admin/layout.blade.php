<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<title> 后台管理系统 </title>
        <!-- 依 赖 样 式 -->
		<link rel="stylesheet" href="{{asset(BE_COMPONENT.'/pear/css/pear.css')}}" />
        <!-- 加 载 样 式-->
		<link rel="stylesheet" href="{{asset(BE_ADMIN.'/css/load.css')}}" />
        <!-- 布 局 样 式 -->
		<link rel="stylesheet" href="{{asset(BE_ADMIN.'/css/admin.css')}}" />
	</head>
    <!-- 结 构 代 码 -->
	<body class="layui-layout-body pear-admin">
		<!-- 布 局 框 架 -->
		<div class="layui-layout layui-layout-admin">
			<div class="layui-header">
				<!-- 顶 部 左 侧 功 能 -->
				<ul class="layui-nav layui-layout-left">
					<li class="collaspe layui-nav-item"><a href="#" class="layui-icon layui-icon-shrink-right"></a></li>
					<li class="refresh layui-nav-item"><a href="#" class="layui-icon layui-icon-refresh-1" loading = 600></a></li>
				</ul>
                <!-- 顶 部 右 侧 菜 单 -->
				<div id="control" class="layui-layout-control"></div>
				<ul class="layui-nav layui-layout-right">
                    <li class="layui-nav-item layui-hide-xs"><a href="javascript:void(0);" class="layui-icon layui-icon-fonts-clear" id="clearcahe" title="清除缓存"></a></li>
					<li class="layui-nav-item layui-hide-xs"><a href="#" class="fullScreen layui-icon layui-icon-screen-full"></a></li>
{{--					<li class="layui-nav-item layui-hide-xs"><a href="{{route('admin.index')}}" target="_blank" class="layui-icon layui-icon-website"></a></li>--}}
					<li class="layui-nav-item">
						<!-- 头 像 -->
						<a href="javascript:;">
							<img src="/img/default.jpg" class="layui-nav-img" alt="{{$guard->nickname ?? $guard->username}}" title="{{$guard->nickname ?? $guard->username}}">
						</a>
                        <!-- 功 能 菜 单 -->
						<dl class="layui-nav-child">
							<dd><a href="javascript:void(0);" user-menu-url="{{route('admin.profile')}}" user-menu-id="admin.profile" user-menu-title="基本资料">基本资料</a></dd>
                            <dd><a href="javascript:void(0);" user-menu-url="{{route('admin.password')}}" user-menu-id="admin.password" user-menu-title="修改密码">修改密码</a></dd>
							<dd><a href="javascript:void(0);" class="logout">注销登录</a></dd>
						</dl>

					</li>
                    <!-- 主 题 配 置 -->
					<li class="layui-nav-item setting"><a href="#" class="layui-icon layui-icon-more-vertical"></a></li>
				</ul>
			</div>
            <!-- 侧 边 区 域 -->
			<div class="layui-side layui-bg-black">
				<!-- 菜 单 顶 部 -->
				<div class="layui-logo">
					<!-- 图 标 -->
					<img class="logo"/>
                    <!-- 标 题 -->
					<span class="title"></span>
				</div>
                <!-- 菜 单 内 容 -->
				<div class="layui-side-scroll">
					<div id="sideMenu"></div>
				</div>
			</div>
            <!-- 视 图 页 面 -->
			<div class="layui-body">
				<!-- 内 容 页 面 -->
				<div id="content"></div>
			</div>
            <!-- 遮 盖 层 -->
			<div class="pear-cover"></div>
            <!-- 加 载 动 画-->
			<div class="loader-main">
				<div class="loader"></div>
			</div>
		</div>
        <!-- 移 动 端 便 捷 操 作 -->
		<div class="pear-collasped-pe collaspe">
			<a href="#" class="layui-icon layui-icon-shrink-right"></a>
		</div>
        <!-- 依 赖 脚 本 -->
		<script src="{{asset(BE_COMPONENT.'/layui/layui.js')}}"></script>
		<script src="{{asset(BE_COMPONENT.'/pear/pear.js')}}"></script>
        <!-- 框 架 初 始 化 -->
		<script>
			layui.use(['admin','jquery', 'popup','notice'], function() {
                const admin = layui.admin;
                const $ = layui.jquery;
                const popup = layui.popup;
                const notice = layui.notice;

                admin.setConfigType("json");
                admin.setConfigPath("{{asset(BE_CONFIG.'/pear.config.json')}}");
                admin.render();

                // 登出逻辑
                admin.logout(function(){

                    popup.success("注销成功",function(){
                        location.href = "{{route('admin.logout')}}";
                    })
                })

                $("#clearcahe").click(function () {
                    $.get("/clear-cache",function(data,status){
                        notice.success(data);
                    });
                })
            })
		</script>
	</body>
</html>
