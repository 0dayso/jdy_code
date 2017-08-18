<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditThirdDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_third_detail', function (Blueprint $table) {
            /*第三方债权人要素*/
            $table->increments('id')->comment('第三方债权人详情表');
            $table->bigInteger('credit_third_id')->comment('债权ID,关联credit_third表的主键');
            $table->string('name',30)->default('')->comment('姓名');
            $table->string('id_card',30)->default()->comment('身份证号');
            $table->decimal('amount',20,2)->default('0.00')->comment('债权金额');
            $table->decimal('usable_amount',20,2)->default('0.00')->comment('剩余可用债权金额');
            $table->date('loan_time')->default('0000-00-00')->comment('借款日期');
            $table->date('refund_time')->default('0000-00-00')->comment('还款日期');
            $table->smallInteger('status')->default(100)->comment('状态: 100-正常, 200-已使用');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('credit_third_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_third_detail');
    }
}
