<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = 'users';
        Schema::create($table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid', 100)->unique()->comment('openid');
            $table->string('password')->comment('密码');
            $table->string('api_token')->nullable(true)->default('')->comment('api_token');
            $table->string('session_key', 100)->nullable(true)->default('')->comment('session_key');
            $table->string('avatar')->default('')->comment('头像');
            $table->string('nickname', 50)->default('')->comment('呢称');
            $table->string('mobile', 15)->default('')->comment('手机号');
            $table->tinyInteger('gender')->nullable(false)->default(0)->comment('性别： 0 未知');
            $table->unsignedTinyInteger('status')->default(1)->comment('状态1 正常 0 禁用');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
        DB::statement("ALTER TABLE `".env('DB_PREFIX','')."{$table_name}` comment '用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
