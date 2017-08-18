<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleMediaInviteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('media_invite', function(Blueprint $table)
        {
            $table->increments('id')->comment('推广用户来源记录表');
            $table->integer('channel_id')->unsigned()->default(0)->comment('分组ID');
            $table->integer('user_id')->unsigned()->default(0)->comment('渠道名称');
            $table->enum('app_request',['wap','android'])->default('android')->comment('注册来源');
            $table->string('version',20)->default('')->comment('app端版本号');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->unique('user_id');
            $table->index('created_at');
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
