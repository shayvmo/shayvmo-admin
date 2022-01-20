<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = 'configs';
        Schema::create('configs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('group_id')->default(0)->comment('组ID');
            $table->string('label', 30)->nullable(false)->comment('配置项名称');
            $table->string('key', 30)->nullable(false)->comment('配置项字段');
            $table->string('config_file_key', 50)->default('')->comment('系统配置文件对应key值');
            $table->string('val', 150)->nullable(false)->comment('配置项值');
            $table->unsignedTinyInteger('type')->default(0)->comment('1 switch 2 text 3 textarea');
            $table->string('tips', 150)->default('')->comment('输入提示');
            $table->unsignedTinyInteger('sort')->default(100)->comment('排序');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
        DB::statement("ALTER TABLE `".env('DB_PREFIX','')."{$table_name}` comment '配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configs');
    }
}
