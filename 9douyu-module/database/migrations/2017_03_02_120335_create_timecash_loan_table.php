<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimecashLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timecash_loan', function (Blueprint $table) {
            $table->increments('id')->comment('快金-我要借款');
            $table->char('name',  30)->default('')->comment('借款人姓名');
            $table->char('phone', 20)->default('')->comment('借款人电话');
            $table->string('loan_amount',100)->comment('借款额度');
            $table->string('loan_time',  100)->comment('借款期限');
            $table->string('refund_type',100)->comment('还款方式');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('phone');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('timecash_loan');
    }
}
