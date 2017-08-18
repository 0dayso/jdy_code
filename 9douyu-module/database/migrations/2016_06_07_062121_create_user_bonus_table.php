<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bonus', function (Blueprint $table) {
            $table->increments('id')->comment("用户红包/加息券表");
            $table->unsignedSmallInteger('bonus_id')->comment("红包/加息券id :module_bonus.id");
            $table->unsignedInteger('user_id')->comment("用户id");
            $table->unsignedInteger('send_user_id')->comment("红包/加息券 发送人id");
            $table->timestamp('get_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment("红包/加息券 获取时间");
            $table->date('use_end_time')->comment("红包/加息券 使用截止时间");
            $table->timestamp('used_time')->default('0000-00-00 00:00:00')->comment("红包/加息券 使用时间");
            $table->date('rate_used_time')->comment("红包/加息券 零钱计划加息截止时间");
            $table->integer('foreign_id')->comment("红包/加息券 定期对应投资id");
            $table->tinyInteger('from_type')->comment("0:用户自己获得;1:系统发送");
            $table->tinyInteger('app_request')->default(1)->comment("使用来源1:pc;2:wap;3:android;4:ios ");
            $table->tinyInteger('lock')->comment("锁");
            $table->text('memo')->comment("备注");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        //Schema::drop('user_bonus');
    }
}
