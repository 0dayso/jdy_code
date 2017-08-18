<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditLoanUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_loan_user', function (Blueprint $table) {
            $table->increments('id')->comment('九斗鱼借款用户债权信息');
            //$table->tinyInteger('source')->comment('债权来源');
            $table->tinyInteger('loan_type')->comment('借款人类型');
            //$table->smallInteger('credit_tag')->comment('债权的标签');

            $table->string('credit_name', 255)->comment('债权名称');
            $table->decimal('loan_amounts', 20, 2)->unsigned()->default(0)->comment('借款金额');
            $table->decimal('can_use_amounts', 20, 2)->unsigned()->default(0)->comment('可用金额');
            $table->decimal('manage_fee', 20, 2)->unsigned()->default(0)->comment('平台服务管理费');
            $table->decimal('interest_rate', 5, 2)->unsigned()->default(0)->comment('利率');
            //$table->decimal('project_publish_rate', 5, 2)->unsigned()->default(0)->comment('项目发布利率');
            $table->tinyInteger('repayment_method')->comment('还款方式');
            //$table->date('end_date')->comment('到期日期');
            $table->smallInteger('loan_deadline')->comment('借款周期(天)');
            $table->smallInteger('loan_days')->comment('融资时间（天)');
            $table->string('contract_no', 255)->comment('合同编号');
            $table->char('loan_phone',20)->default('')->comment('借款人手机号');
            $table->char('loan_username', 30)->comment('借款人姓名');
            $table->char('loan_user_identity', 30)->comment('借款人证件号');

            $table->smallInteger('status_code')->default(100)->comment('状态:100-未发布，200-已发布 300，已完结');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('loan_user_identity');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_loan_user');
    }
}
