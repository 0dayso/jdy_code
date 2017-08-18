<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPayListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //用户充值成功记录数据表
        Schema::create('user_pay_list', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('用户充值记录表');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户ID');
            $table->tinyInteger('bank_id')->unsigned()->default(0)->comment('银行ID，关联bank表');
            $table->smallInteger('pay_type')->default(0)->comment('支付通道类型：1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣');
            $table->decimal('day_cash', 20, 2)->default(0)->comment('当日成功充值金额');
            $table->decimal('month_cash', 20, 2)->default(0)->comment('当月成功充值金额');
            $table->timestamps();
            $table->unique(['user_id','bank_id','pay_type']);
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
