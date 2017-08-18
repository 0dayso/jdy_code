<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite', function (Blueprint $table) {
            $table->increments('id')->comment('邀请表');
            $table->unsignedInteger('user_id')->comment('邀请人用户id')->index();
            $table->unsignedInteger('other_user_id')->comment('被邀请人用户id')->index();
            $table->unsignedTinyInteger('type')->default(0)->comment('邀请类型（0-普通，1-手机，2-微信,3-分享');
            $table->unsignedTinyInteger('user_type')->default(1)->comment('1-普通用户邀请，2-自媒体邀请');
            $table->unsignedSmallInteger('source')->default(0)->comment('推广活动附加来源');
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
        //Schema::drop('invite');
    }
}
