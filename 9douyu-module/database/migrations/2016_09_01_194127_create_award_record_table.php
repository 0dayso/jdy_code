<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwardRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('award_record', function (Blueprint $table) {
            $table->increments('id')->comment('活动额外加息表');
            $table->integer('project_id')->unsigned()->default(0)->comment('投资项目的ID');
            $table->integer('invest_id')->unsigned()->default(0)->comment('投资记录的ID');
            $table->integer('user_id')->unsigned()->default(0)->comment('奖励用户的ID');
            $table->decimal('principal',11,2)->unsigned()->default('0.00')->comment('加息奖励的投资本金');
            $table->decimal('percentage',5,2)->unsigned()->default('0.00')->comment('加息利率');
            $table->decimal('cash',11,2)->unsigned()->default('0.00')->comment('奖励金额');
            $table->integer('event_type')->unsigned()->default(0)->comment('奖励类型');
            $table->smallInteger('status')->unsigned()->default(100)->comment('100:待奖励的  200:已完成奖励  300:奖励取消');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->string('comment',60)->default('加息奖励')->comment('备注信息');
            $table->unique('id');
            $table->index(['project_id','event_type','status']);
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
        Schema::drop('award_record');
    }
}
