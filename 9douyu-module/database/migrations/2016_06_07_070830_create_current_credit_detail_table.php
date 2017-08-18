<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentCreditDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ////
        Schema::create('current_credit_detail', function(Blueprint $table)
        {
            /*债权要素*/
            $table->increments('id')->comment('零钱计划债权人详情表');
            $table->bigInteger('credit_id')->unsigned()->default(0)->comment('债权ID,关联credit_current表主键');
            $table->string('name',30)->default('')->comment('姓名');
            $table->string('id_card',30)->default()->comment('身份证号');
            $table->decimal('amount', 20, 2)->unsigned()->default(0)->comment('债权金额');
            $table->decimal('usable_amount', 20, 2)->unsigned()->default(0)->comment('剩余可用金额');
            $table->date('time')->comment('借款日期');
            $table->smallInteger('status')->unsigned()->default(200)->comment('状态,200-正常,400-已删除');
            $table->string('address',50)->default('')->comment('债权人地址');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('credit_id');
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
