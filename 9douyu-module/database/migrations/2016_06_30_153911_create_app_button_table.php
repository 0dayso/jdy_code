<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppButtonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_button', function (Blueprint $table) {
            $table->increments('id')->comment('app图标');
            $table->char('name')->comment('图标名称');
            $table->unsignedInteger('picture_id')->comment('图片id');
            $table->tinyInteger('position')->default(1)->comment('图片位置:1-账户中心中间图片，2-下边菜单图片按钮');
            $table->unsignedTinyInteger('position_num')->default(0)->comment('图片位置，数字从大到小，图片位置从左到右排序');
            $table->unsignedSmallInteger('status')->default(100)->comment('状态 100 关闭 200 开启');
            $table->timestamp('start_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('开始时间');
            $table->timestamp('end_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('结束时间');
            $table->integer('location_type')->comment('链接类型,100为app控制跳转,200为H5链接');
            $table->text('location_message')->comment('H5链接的内容, 字段值为序列化后的值 share_url|share_title|share_desc|share_img');
            $table->integer('sort_num')->default(0)->comment('排列顺序desc排');
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
        //Schema::drop('app_button');
    }
}
