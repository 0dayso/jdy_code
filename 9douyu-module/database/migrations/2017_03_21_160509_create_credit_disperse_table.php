<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditDisperseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_disperse', function (Blueprint $table) {

            $table->increments('id')->comment('分散债权列表');
            $table->char('credit_name','30')->default('')->comment('债权名称');
            $table->decimal('amounts',20,2)->default('0.00')->comment('债权金额');
            $table->decimal('usable_amount',20,2)->defalut('0.00')->comment('剩余可用金额');
            $table->decimal('interest_rate',6,2)->default('0.00')->comment('债权利率');
            $table->smallInteger('loan_deadline')->comment('借款期限');
            $table->smallInteger('status')->default(100)->comment('债权状态: 100-正常，200-已匹配');
            $table->date('start_time')->default('0000-00-00')->comment('开始日期');
            $table->date('end_time')->default('0000-00-00')->comment('到期日期');
            $table->string('loan_realname',30)->default('')->comment('借款人姓名');
            $table->string('loan_idcard',30)->default('')->comment('借款人身份证号');
            $table->string('contract_no', 50)->default('')->comment('合同编号');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('债权录入时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('status');
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
        Schema::drop('credit_disperse');
    }
}
