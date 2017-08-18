<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('id')->comment('文章表');
            $table->char('title',200)->comment('文章名称');
            $table->unsignedInteger('picture_id')->comment('图片id');
            $table->text('intro')->comment('介绍');
            $table->char('keywords',80)->comment('关键字');
            $table->char('description',200)->comment('描述');
            $table->integer('category_id')->comment('类别 category.id');
            $table->char('layout', 50)->comment('模板布局');
            $table->text('content')->comment('文章内容');
            $table->integer('hits')->default(0)->comment('点击量');
            $table->integer('sort_num')->comment('排序');
            $table->tinyInteger('is_top')->comment('是否置顶')->default(0);
            $table->tinyInteger('type_id')->comment('1-app媒体资讯，2-文章资讯')->default(1);
            $table->tinyInteger('is_push')->comment('是否推送')->default(0);
            $table->smallInteger('status')->comment('状态 100 未发布, 200 已发布');
            $table->integer('create_by')->comment('创建人');
            $table->timestamp('publish_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('发布时间');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('article');
    }
}
