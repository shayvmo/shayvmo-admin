<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = 'attachments';
        Schema::create($table_name, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('group_id')->default(0)->comment('分组ID');
            $table->string('storage_type',20)->default('public')->comment('存储方式： public 服务器本地 qiniu 七牛云');
            $table->string('name',50)->nullable(false)->comment('文件名称');
            $table->string('path')->nullable(false)->comment('存储路径');
            $table->string('md5_file',32)->comment('文件md5');
            $table->unsignedInteger('size')->default(0)->comment('文件大小，单位： B');
            $table->string('mime_type',30)->nullable()->comment('文件mime类型');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
        DB::statement("ALTER TABLE `".env('DB_PREFIX','')."{$table_name}` comment '资源表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
