<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_list', function (Blueprint $table) {
            $table->smallInteger('type')->default(0)->comment('支付通道类型：1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣');
            $table->tinyInteger('bank_id')->unsigned()->default(0)->comment('银行ID');
            $table->string('alias',6)->comment('银行别名');
            $table->tinyInteger('sort')->unsigned()->default(0)->comment('排序');
            $table->tinyInteger('status')->default(1)->comment('状态，0：未启用；1：启用');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bank_list');
    }
}
