<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditNine extends Migration
{

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_nine');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_nine', function(Blueprint $table)
        {
            /*债权要素*/
            $table->increments('id')->comment('九省心债权表');
            $table->tinyInteger('source')->comment('债权来源');
            $table->tinyInteger('type')->comment('债权类型');
            $table->smallInteger('credit_tag')->comment('债权标签【产品线】');
            $table->decimal('loan_amounts', 20, 2)->unsigned()->default(0)->comment('借款金额');
            $table->decimal('can_use_amounts', 20, 2)->unsigned()->default(0)->comment('可用金额');
            $table->decimal('interest_rate', 5, 2)->unsigned()->default(0)->comment('利率');
            $table->tinyInteger('repayment_method')->comment('还款方式');
            $table->date('expiration_date')->comment('到期日期');
            $table->smallInteger('loan_deadline')->comment('借款期限');
            $table->string('contract_no', 255)->comment('合同编号');

            $table->smallInteger('status_code')->default(100)->comment('状态');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            //债权其他信息
            $table->string('plan_name', 255)->comment('计划名称');
            $table->string('program_no', 255)->comment('项目编号');
            $table->text('file')->comment('文件');
            $table->mediumText('credit_info')->comment('债权详情 兼容老九省心');
            $table->index('credit_tag');
        });
    }


}
