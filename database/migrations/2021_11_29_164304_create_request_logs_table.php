<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRequestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = 'request_logs';
        Schema::create('request_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('admin_id')->default(0)->comment('操作用户ID');
            $table->string('id_address', 255)->comment('IP地址');
            $table->string('url', 100)->comment('完整请求地址');
            $table->string('route_name', 100)->default('')->comment('请求路由名称');
            $table->string('user_agent', 255)->comment('user-agent');
            $table->string('param')->default('')->comment('参数');
            $table->string('method')->default('')->comment('请求方式：GET、POST、PUT、DELETE、HEAD');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
        DB::statement("ALTER TABLE `".env('DB_PREFIX','')."{$table_name}` comment '请求日志表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_logs');
    }
}
