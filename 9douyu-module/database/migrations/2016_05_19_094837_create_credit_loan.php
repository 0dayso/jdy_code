<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditLoan extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_loan');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_loan', function(Blueprint $table)
        {
            /*债权要素*/
            $table->increments('id')->comment('耀盛信贷债权表');
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
            $table->tinyInteger('riskcalc_level')->comment('riskcalc信用评级	');

            $table->tinyInteger('company_level')->comment('企业经营规模');
            $table->tinyInteger('profit_level')->comment('企业盈利能力');
            $table->tinyInteger('liability_level')->comment('资产负债水平');
            $table->tinyInteger('guarantee_level')->comment('担保方实力');

            $table->string('company_level_value', 20)->comment('企业经营规模');
            $table->string('profit_level_value', 20)->comment('企业盈利能力');
            $table->string('liability_level_value', 20)->comment('资产负债水平');
            $table->string('guarantee_level_value', 20)->comment('担保方实力');
            //债权其他信息 risk

            $table->text('keywords')->comment('关键字')->nullable();
            $table->text('credit_desc')->comment('债权综述')->nullable();
            //耀盛信贷项目描述
            $table->string('financing_company', 255)->comment('融资企业')->nullable();
            $table->timestamp('founded_time')->comment('成立时间')->nullable();
            $table->string('program_area_location', 255)->comment('项目区域位置')->nullable();
            $table->string('registered_capital', 255)->comment('注册资金')->nullable();
            $table->string('annual_income', 255)->comment('年收入')->nullable();

            $table->text('loan_use')->comment('借款用途')->nullable();
            $table->text('repayment_source')->comment('还款来源')->nullable();
            $table->text('background')->comment('企业背景')->nullable();

            $table->text('financial')->comment('企业财务状况')->nullable();

            //实际控制人
            $table->tinyInteger('sex')->comment('性别');
            $table->smallInteger('age')->comment('年龄')->nullable();
            $table->string('family_register', 255)->comment('户籍所在地')->nullable();
            $table->string('residence', 255)->comment('居住地')->nullable();
            $table->string('home_stability', 255)->comment('家庭稳定性')->nullable();
            $table->text('esteemn')->comment('财产状况')->nullable();
            $table->text('credibility')->comment('征信记录')->nullable();
            $table->text('involved_appeal')->comment('涉诉状况')->nullable();

            $table->text('submit_data')->comment('企业提交资料');

            $table->text('risk_control_message')->comment('风控信息');
            $table->text('risk_control_security')->comment('风险保障');

            $table->text('contract_agreement')->comment('合同协议')->nullable();
            $table->text('company_photo')->comment('企业照片')->nullable();

            $table->index('credit_tag');
        });
    }


}
