<?php
// 前台静态文件存放目录


// 后台静态文件存放目录
if (!defined('BE_ADMIN')) {
    define("BE_ADMIN", '/static/admin/admin/');
}
if (!defined('BE_CONFIG')) {
    define("BE_CONFIG", '/static/admin/config/');
}
if (!defined('BE_COMPONENT')) {
    define("BE_COMPONENT", '/static/admin/component/');
}

// 文件上传目录
if (!defined('UPLOADS')) {
    define("UPLOADS", '/uploads/');
}

