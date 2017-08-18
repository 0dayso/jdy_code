<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditGroup extends Migration
{

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('credit_group');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_group', function(Blueprint $table)
        {
            /*债权要素*/
            $table->increments('id')->comment('项目集债权表');
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
            $table->text('loan_username')->comment('借款人姓名');
            $table->text('loan_user_identity')->comment('借款人证件号');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->smallInteger('status_code')->default(100)->comment('状态');
            //债权其他信息

            $table->string('financing_company', 255)->comment('融资企业')->nullable();
            $table->string('program_area_location', 255)->comment('项目区域位置')->nullable();
            $table->text('loan_use')->comment('借款用途')->nullable();
            $table->text('repayment_source')->comment('还款来源')->nullable();
            $table->text('loan_contract')->comment('借款合同')->nullable();

            $table->index('credit_tag');
        });
    }


}
