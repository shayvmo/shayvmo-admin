<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cate_id')->default(0)->comment('分类ID');
            $table->string('title', 50)->nullable(false)->comment('标题');
            $table->string('cover_img')->default('')->nullable(false)->comment('封面图');
            $table->string('author', 20)->default('')->comment('作者');
            $table->mediumText('markdown')->comment('markdown内容');
            $table->mediumText('content')->comment('HTML内容');
            $table->string('article_url', 100)->default('')->comment('原文链接');
            $table->unsignedInteger('views')->default(0)->comment('浏览量');
            $table->unsignedTinyInteger('is_top')->default(0)->comment('1 置顶 0 否');
            $table->unsignedTinyInteger('status')->default(0)->comment('1 显示 0 隐藏');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });

        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pid')->default(0)->comment('父级ID');
            $table->string('path', 100)->default('')->comment('层级链');
            $table->string('title', 20)->nullable(false)->comment('名称');
            $table->unsignedInteger('sort')->default(100)->comment('倒序排序');
            $table->unsignedTinyInteger('status')->default(0)->comment('1 显示 0 隐藏');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });

        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->string('content', 200)->default('')->comment('内容');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 30)->comment('名称');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });

        Schema::create('article_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('article_id')->default(0)->comment('文章ID');
            $table->unsignedInteger('pid')->default(0)->comment('上个回复ID');
            $table->string('path')->default('')->comment('层级链');
            $table->string('content', 200)->comment('评论内容');
            $table->unsignedTinyInteger('status')->default(1)->comment('1 显示 0 隐藏');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });

        Schema::create('article_stars', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('article_id')->default(0)->comment('文章ID');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });

        Schema::create('article_collections', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('article_id')->default(0)->comment('文章ID');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
        Schema::create('article_tags', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('article_id')->default(0)->comment('文章ID');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('category');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('article_comments');
        Schema::dropIfExists('article_stars');
        Schema::dropIfExists('article_collections');
        Schema::dropIfExists('article_tags');
    }
}
