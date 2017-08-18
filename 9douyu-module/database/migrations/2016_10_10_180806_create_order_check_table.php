<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCheckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('order_check', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('充值订单对账表');
            $table->string('order_id',30)->default('')->comment('订单号');
            $table->integer('user_id')->default(0)->comment('订单的用户id');
            $table->integer('pay_channel')->default(0)->comment('支付类型:1000-京东网银，1001-融宝网银，1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣');
            $table->decimal('cash',20, 2)->default(0)->unsigned()->comment('对账金额');
            $table->integer('is_check')->default(10)->comment('10:未核实,未处理，20:已核实,已处理');
            $table->string('note',50)->default('')->comment('异常备注');
            $table->string('tackle_note',50)->default('0')->comment('处理的备注');
            $table->integer('admin_id')->default('0')->comment('操作者');
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
