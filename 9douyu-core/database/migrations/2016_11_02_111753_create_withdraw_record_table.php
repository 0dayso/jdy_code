<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrawRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //定期项目
        Schema::create('withdraw_record', function(Blueprint $table)
        {
            $table->increments('id')->comment('每日提现记录表');
            $table->timestamp('start_time')->comment('提现开始时间')->default('0000-00-00 00:00:00');
            $table->timestamp('end_time')->comment('提现结束时间')->default('0000-00-00 00:00:00');
            $table->decimal('cash',20,2)->default(0)->comment('提现总金额');
            $table->unsignedInteger('num')->default(0)->comment('订单总数');
            $table->tinyInteger('status')->default(0)->comment('0-未处理 1-已处理');
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
