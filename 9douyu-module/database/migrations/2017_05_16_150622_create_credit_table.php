<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name',100)->default('')->comment('企业名称');
            $table->string('loan_username',100)->default('')->comment('借款人姓名')->index();
            $table->string('loan_user_identity',120)->default('')->comment('借款人身份证号码')->index();
            $table->decimal('loan_amounts',20,2)->default('0.00')->comment('借款金额');
            $table->decimal('interest_rate',5,2)->default('0.00')->comment('借款利率');
            $table->smallInteger('repayment_method')->default(0)->comment('还款方式');
            $table->date('expiration_date')->default('0000-00-00')->comment('借款到期日期');
            $table->smallInteger('loan_deadline')->default(0)->comment('借款期限(月/天)');
            $table->string('contract_no',30)->default('')->comment('合同编号')->index();
            $table->smallInteger('type')->default(0)->comment('债权类型: 50-常规;60-项目集;70-九省心');
            $table->smallInteger('source')->default(0)->comment('债权来源: 10-耀盛保理;20-耀盛信贷;30-房产抵押;40-第三方');
            $table->smallInteger('status_code')->default(100)->comment('状态 100 未使用 200 已使用');
            $table->string('credit_tag',10)->default('')->comment('债权标签');
            $table->integer('outer_id')->comment('原债权主键ID，数据导入完成后删除');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            //$table->unique(['outer_id', 'type','source']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('credit');
    }
}
