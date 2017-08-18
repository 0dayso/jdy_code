<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLinkWechatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_link_wechat', function(Blueprint $table)
        {
            $table->increments('id')->comment('微信信息表');
            $table->string('openid', 100)->comment('微信 openId');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->bigInteger('wechat_id')->comment('微信表ID');
            $table->tinyInteger('is_binding')->default(0)->comment('绑定状态 1 绑定');
            $table->tinyInteger('is_subscribe')->default(0)->comment('关注状态 1 关注');

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
        //
    }
}
