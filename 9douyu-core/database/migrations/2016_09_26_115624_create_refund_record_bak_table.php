<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundRecordBakTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //回款记录表
        Schema::create('refund_record_bak', function(Blueprint $table)
        {
            $table->increments('id')->comment('已债转用户还款计划备份表');
            $table->integer('refund_id')->comment('还款id');
            $table->integer('project_id')->comment('项目id');
            $table->integer('invest_id')->comment('投资id');
            $table->integer('user_id')->comment('用户id');
            $table->decimal('principal',20,2)->unsigned()->default(0)->comment('本金');
            $table->decimal('interest',20,2)->unsigned()->default(0)->comment('利息');
            $table->decimal('cash',20,2)->unsigned()->default(0)->comment('本息');
            $table->date('times')->comment('回款日');
            $table->smallInteger('status')->default(600)->comment('状态;600:未回款;200:已回款');
            $table->smallInteger('type')->default(0)->comment('加息券利息');
            $table->tinyInteger('before_refund')->comment('提前还款');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('user_id');
            $table->index('project_id');
            $table->index('times');
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
