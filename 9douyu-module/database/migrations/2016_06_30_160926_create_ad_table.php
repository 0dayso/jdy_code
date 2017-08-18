<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //广告表
        Schema::create('ad', function(Blueprint $table)
        {
            $table->increments('id')->comment('广告表');
            $table->char('title', 50)->default('')->comment('广告标题描述');
            $table->tinyInteger('sort')->default('1')->comment('排序，1-99越小越靠前显示');
            $table->integer('position_id')->default('0')->comment('所属广告位');
            $table->char('app_version',20)->default('')->comment('App版本号');
            $table->integer('manage_id')->default('0')->comment('添加人');
            $table->text('param')->default('')->comment('json存储的广告参数');
            $table->timestamp('publish_at')->default('0000-00-00 00:00:00');
            $table->timestamp('end_at')->default('0000-00-00 00:00:00');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('publish_at');
            $table->index('end_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
