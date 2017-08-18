<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCheckBatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('order_check_batch', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('对账操作记录表');
            $table->string('name',20)->default('')->comment('订单号');
            $table->integer('pay_channel')->default(0)->comment('支付类型:1000-京东网银，1001-融宝网银，1101-连连认证支付，1102-易宝认证支付，1201-钱袋宝代扣，1202-联动优势代扣，1203-翼支付代扣，1204-融宝代扣');
            $table->string('file_path',100)->default('0')->comment('对账的文件附件');
            $table->integer('status')->default(100)->comment('状态,100:待审核;200:已完成;300:审核成功待执行');
            $table->string('note',50)->default('')->comment('备注信息');
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
