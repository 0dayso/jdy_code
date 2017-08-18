<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWithdrowBillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_bill', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('order_id',32)->unique('key','ids_order_id')->comment('订单ID');
            $table->smallInteger('bill_status')->comment('代付处理状态');
            $table->timestamp('bill_time')->comment('代付处理时间');
            $table->decimal('cash', 20, 2)->unsigned()->default(0)->comment('订单金额');
            $table->string('note',100)->default()->comment('备注');
            $table->tinyInteger('type')->default(1)->comment('类型 1:网银在线提现代付');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->tinyInteger('cron_status')->default(0)->comment('任务执行状态 0:未执行 1:已完毕');
            $table->smallInteger('order_status')->default(200)->comment('提现订单状态 200:提现成功 500:提现失败');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('withdraw_bill');
    }
}
