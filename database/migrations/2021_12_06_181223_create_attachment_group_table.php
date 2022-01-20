<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = 'attachment_groups';
        Schema::create($table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 30)->nullable(false)->comment('分组名称');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
        DB::statement("ALTER TABLE `".env('DB_PREFIX','')."{$table_name}` comment '素材分组表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachment_groups');
    }
}
