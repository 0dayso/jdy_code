<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvatarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatar', function(Blueprint $table)
        {
            $table->increments('id')->comment('用户头像表');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->char('avatar_url',200)->comment('头像地址');
            $table->tinyInteger('app_request')->default(1)->comment('来源 1pc,2wap,3ios,4android');
            $table->smallInteger('status')->comment('用户头像状态（100:审核失败,显示默认的头像  200:审核通过');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->char('version',50)->comment('APP版本号');
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
