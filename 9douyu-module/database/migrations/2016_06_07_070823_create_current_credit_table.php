<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('current_credit', function(Blueprint $table)
        {
            /*债权要素*/
            $table->increments('id')->comment('零钱计划债权表');
            $table->string('name',100)->default('')->comment('债权名称');
            $table->decimal('total_amount', 20, 2)->unsigned()->default(0)->comment('债权金额');
            $table->decimal('usable_amount', 20, 2)->unsigned()->default(0)->comment('可用金额');
            $table->tinyInteger('refund_type')->unsigned()->default(20)->comment('还款方式:10-等额本息,20-先息后本,30-到期还本息');
            $table->smallInteger('invest_time')->unsigned()->default(0)->comment('融资周期');
            $table->decimal('percentage', 20, 2)->unsigned()->default(0)->comment('利率');
            $table->string('contract_no',100)->default('')->comment('合同编号');
            $table->date('end_time')->comment('截止日期');
            $table->integer('create_by')->unsigned()->default(0)->comment('管理员ID');
            $table->date('refund_time')->comment('还款执行时间');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('end_time');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_current');

    }
}
