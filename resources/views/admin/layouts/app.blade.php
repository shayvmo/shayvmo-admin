<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>沙屿沫</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="/js/jquery-2.0.0.min.js"></script>
    <script src="/static/unpkg/vue@2.6.10/dist/vue.js"></script>
    <script src="/static/unpkg/element-ui@2.12.0/lib/index.js"></script>
    <script src="/js/axios.min.js"></script>
    <link rel="stylesheet" href="/static/unpkg/element-ui@2.12.0/lib/theme-chalk/index.css">
    <style>
        .vue-container {
            margin: 10px;
        }
        .margin-top-bottom-10 {
            margin: 10px 0;
        }
    </style>
    @yield('style')
</head>
<body>
<div class="vue-container">
    @yield('content')
</div>

<script src="/js/qs.min.js"></script>
<script src="/js/my-request.js"></script>
@yield('script')
</body>
</html>
