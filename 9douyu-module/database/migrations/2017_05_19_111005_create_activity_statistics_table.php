<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('activity_statistics', function (Blueprint $table) {
            $table->increments('id')->comment('活动投资表,方便统计活动数据');
            $table->integer('user_id')->index()->default('0')->comment('用户Id');
            $table->integer('invest_id')->default('0')->comment('投资表中的id');
            $table->integer('project_id')->default('0')->comment('项目的id');
            $table->decimal('cash',20,2)->unsigned()->default(0)->comment('投资金额');
            $table->integer('act_id')->index()->default('0')->comment('活动的唯一性标示');
            $table->char('note')->comment('备注');
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
