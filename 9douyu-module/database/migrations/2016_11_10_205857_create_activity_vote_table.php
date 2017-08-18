<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityVoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('activity_vote', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('投票记录表');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->string('phone',11)->default('')->comment('原手机号码');
            $table->integer('activity_id')->default(0)->comment('活动ID');
            $table->integer('choices')->default(0)->comment('选项');
            $table->string('note',50)->default('')->comment('备注');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('user_id');
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
