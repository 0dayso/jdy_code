<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //资金流水
        Schema::create('fund_history', function(Blueprint $table)
        {
            $table->increments('id')->comment('资金流水表');
            $table->integer('user_id')->comment('用户id');
            $table->decimal('balance_before',20,2)->unsigned()->default(0)->comment('变更前金额');
            $table->decimal('balance_change',20,2)->default(0)->comment('变更额');
            $table->decimal('balance',20,2)->unsigned()->default(0)->comment('变更后金额');
            $table->smallInteger('event_id')->comment('事件id');
            $table->char('note',100)->comment('备注')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->index(['user_id','event_id']);
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
