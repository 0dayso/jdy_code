<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayLimitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //支付限额数据表
        Schema::create('pay_limit', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('支付限额表');
            $table->tinyInteger('bank_id')->unsigned()->default(0)->comment('银行ID，关系bank表');
            $table->smallInteger('pay_type')->default(0)->comment('支付通道类型：1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣');
            $table->decimal('limit', 20, 2)->unsigned()->default(0)->comment('单笔限额,0表示无限额');
            $table->decimal('day_limit', 20, 2)->unsigned()->default(0)->comment('单日限额,0表示无限额');
            $table->decimal('month_limit', 20, 2)->unsigned()->default(0)->comment('单月限额,0表示无限额');
            $table->tinyInteger('status')->default(1)->comment('通道状态：0-禁用，1-可用');
            $table->timestamp('start_time')->default('0000-00-00 00:00:00')->comment('通道的开始维护时间');
            $table->timestamp('end_time')->default('0000-00-00 00:00:00')->comment('通道的结束维护时间');
            $table->timestamps();
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
