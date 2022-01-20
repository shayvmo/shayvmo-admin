<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedConfig extends Migration
{
    public function up()
    {
        $configs = [
            [
                'group_key' => 'wechat',
                'name' => '微信配置',
                'sort' => 9,
                'children' => [
                    [
                        'label' => 'appid',
                        'key' => 'wechat_miniprogram_appid',
                        'config_file_key' => 'wechat.mini_program.default.app_id',
                        'val' => '',
                        'type' => 2,
                        'tips' => '小程序APPID',
                    ],
                    [
                        'label' => 'secret',
                        'key' => 'wechat_miniprogram_secret',
                        'config_file_key' => 'wechat.mini_program.default.secret',
                        'val' => '',
                        'type' => 2,
                        'tips' => '小程序secret',
                    ],
                    [
                        'label' => 'token',
                        'key' => 'wechat_miniprogram_token',
                        'config_file_key' => 'wechat.mini_program.default.token',
                        'val' => '',
                        'type' => 2,
                        'tips' => '小程序token(非必填)',
                    ],
                    [
                        'label' => 'aes_key',
                        'key' => 'wechat_miniprogram_aes_key',
                        'config_file_key' => 'wechat.mini_program.default.aes_key',
                        'val' => '',
                        'type' => 3,
                        'tips' => '小程序aes_key(非必填)',
                    ],
                ],
            ],
        ];

        foreach ($configs as $item) {
            $group = \App\Models\ConfigGroup::firstOrCreate([
                'group_key' => $item['group_key'],
            ], [
                'name' => $item['name'],
                'sort' => $item['sort'],
            ]);
            if (!empty($item['children'])) {
                $group->configs()->createMany($item['children']);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
