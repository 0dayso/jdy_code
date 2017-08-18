<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //订单数据表
        Schema::create('order', function(Blueprint $table) {
            $table->bigIncrements('id')->comment('订单表');
            $table->string('order_id', 32)->default('')->unique()->comment('订单号');
            $table->decimal('cash', 20, 2)->unsigned()->default(0)->comment('订单金额，单位：元');
            $table->smallInteger('status')->unsigned()->default(300)->comment('订单状态：200-成功，300-处理中，301-提现处理中，401-充值超时，402-取消提现,500-失败');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户ID');
            $table->decimal('handling_fee',20,2)->unsigned()->default(0)->comment('手续费');
            $table->timestamp('success_time')->default('0000-00-00 00:00:00')->comment('处理成功时间');
            $table->tinyInteger('type')->unsigned()->default(1)->comment('订单类型：1-充值订单，2-提现订单');
            $table->string('random', 8)->default('0');
            $table->timestamps();
            $table->index('user_id');
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
