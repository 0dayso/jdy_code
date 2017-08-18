<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderExtendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //订单扩展数据表
        Schema::create('order_extend', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('订单扩展表');
            $table->string('order_id',32)->default('')->unique()->comment('订单号');
            $table->tinyInteger('bank_id')->unsigned()->default(0)->comment('银行ID，关联bank表的主键');
            $table->string('card_number',30)->default('')->comment('银行卡号');
            $table->string('trade_no',32)->default('')->comment('第三方交易流水号');
            $table->smallInteger('type')->unsigned()->default(0)->comment('支付、提现类型:1000-京东网银，1001-融宝网银，1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣，2000-提现');
            $table->string('note',255)->default('')->comment('订单备注信息');
            $table->enum('app_request',['pc','wap','android','ios'])->default('pc')->comment('订单来源：pc,wap,android,ios');
            $table->string('version',12)->default('')->comment('手机端版本号');
            $table->index('created_at');

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
