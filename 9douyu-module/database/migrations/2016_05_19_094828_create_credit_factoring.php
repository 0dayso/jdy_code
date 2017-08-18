<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditFactoring extends Migration
{

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_factoring');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_factoring', function(Blueprint $table)
        {
            /*债权要素*/
            $table->increments('id')->comment('耀盛保理债权表');
            $table->tinyInteger('source')->comment('债权来源');
            $table->tinyInteger('type')->comment('债权类型');
            $table->smallInteger('credit_tag')->comment('债权标签【产品线】');

            $table->string('company_name', 255)->comment('企业名称');
            $table->decimal('loan_amounts', 20, 2)->unsigned()->default(0)->comment('借款金额');
            $table->decimal('can_use_amounts', 20, 2)->unsigned()->default(0)->comment('可用金额');
            $table->decimal('interest_rate', 5, 2)->unsigned()->default(0)->comment('利率');
            $table->tinyInteger('repayment_method')->comment('还款方式');
            $table->date('expiration_date')->comment('到期日期');
            $table->smallInteger('loan_deadline')->comment('借款期限');
            $table->string('contract_no', 255)->comment('合同编号');
            $table->text('loan_username')->comment('借款人姓名')->nullable();
            $table->text('loan_user_identity')->comment('借款人证件号')->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->smallInteger('status_code')->default(100)->comment('状态');
            //债权其他信息 risk
            $table->tinyInteger('riskcalc_level')->comment('riskcalc信用评级');

            $table->tinyInteger('company_level')->comment('企业经营规模');
            $table->tinyInteger('downstream_level')->comment('下游企业实力');
            $table->tinyInteger('profit_level')->comment('企业盈利能力');
            $table->tinyInteger('downstream_refund_level')->comment('下游回款能力');
            $table->tinyInteger('liability_level')->comment('资产负债水平');
            $table->tinyInteger('guarantee_level')->comment('担保方实力');

            $table->string('company_level_value', 20)->comment('企业经营规模');
            $table->string('downstream_level_value', 20)->comment('下游企业实力');
            $table->string('profit_level_value', 20)->comment('企业盈利能力');
            $table->string('downstream_refund_level_value', 20)->comment('下游回款能力');
            $table->string('liability_level_value', 20)->comment('资产负债水平');
            $table->string('guarantee_level_value', 20)->comment('担保方实力');
            //债权其他信息 risk

            $table->text('keywords')->comment('关键字')->nullable();
            $table->text('credit_desc')->comment('债权综述')->nullable();

            $table->text('factor_summarize')->comment('项目综述');
            $table->text('repayment_source')->comment('还款来源');
            $table->text('factoring_opinion')->comment('保理公司意见');
            $table->text('business_background')->comment('原债权企业背景');
            $table->text('introduce')->comment('原债务企业介绍');
            $table->text('risk_control_measure')->comment('风控措施');

            $table->text('transactional_data')->comment('基础交易材料')->nullable();
            $table->text('traffic_data')->comment('保理业务材料')->nullable();

            $table->index('credit_tag');

        });
    }

}
