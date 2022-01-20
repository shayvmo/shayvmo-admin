<?php

return [

    'success_code' => 'success',       // 成功

    'fail_code' => 'failed',        // 失败

    'result_code' => [                // 业务处理结果

        'success' => 200,             // 成功

        'fail' => 1,               // 失败

        'login_expire' => -1,          // 未登录或登录已失效

        'forbidden' => -2,             // 无权限

    ],

    // 系统
    'system' => [

        'system_open_tag' => 1, // 系统开放标识： 1 开放 0 未开放

        'mail_receive_address' => '',// 邮件接收地址

    ],

    // 文件上传
    'upload' => [

        /*
         * 默认 public 本地,对应 storage 里面的配置即可
         * 优先选择云存储，其次本地存储
         */
        'storage' => env('FILESYSTEM_CLOUD') ?: env('FILESYSTEM_DRIVER', 'public'),

        /*
         * 默认文件或文件组名称
         */
        'file_index' => 'file',

        /*
         * 默认文件最大10M，单位：MB
         */
        'file_size' => 10,

        /*
         * 允许上传的文件类型
         */
        'file_ext' => [
            'jpg',
            'jpeg',
            'png',
            'gif',
        ],

        /*
         * 允许上传的文件mime类型
         */
        'file_mime' => [
            'image/jpeg',
            'image/png',
            'image/gif',
        ],
    ],
];
