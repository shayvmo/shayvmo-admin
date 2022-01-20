<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = 'admins';
        Schema::create($table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 15)->unique('s_admins_username_unique')->comment('登录账号');
            $table->string('password')->comment('密码');
            $table->string('nickname', 50)->comment('呢称');
            $table->string('email', 50)->default('')->comment('邮箱');
            $table->unsignedTinyInteger('status')->default(1)->comment('1启用 0停用');
            $table->char('mobile', 11)->default('')->comment('手机号');
            $table->string('last_login_ip', 32)->default('')->comment('上次登录IP');
            $table->dateTime('last_login_at')->nullable()->comment('上次登录时间');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
        DB::statement("ALTER TABLE `".env('DB_PREFIX','')."{$table_name}` comment '管理员表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
