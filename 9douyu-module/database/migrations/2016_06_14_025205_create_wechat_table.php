<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat', function(Blueprint $table)
        {
            $table->increments('id')->comment('微信信息表');
            $table->string('openid', 100)->comment('微信 openId');
            $table->string('nickname',64)->comment('微信昵称');
            $table->string('headimgurl',200)->comment('微信头像');
            $table->smallInteger('type')->default(0)->comment('类型 1 关注服务号');
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
