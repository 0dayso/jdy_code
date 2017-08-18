<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCurrentNewFundHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_current_new_fund_history', function(Blueprint $table)
        {

            $table->increments('id')->comment('零钱计划账户操作');
            $table->bigInteger('user_id')->default(0)->comment('用户ID')->index();
            $table->decimal('change_balance',20,2)->unsigned()->default(0)->comment('操作金额');
            $table->decimal('after_balance',20,2)->unsigned()->default(0)->comment('操作后金额');
            $table->integer('event_id')->unsigned()->default('400')->comment('400:转入 401:转出 402:计息 500:冻结');
            $table->date('times')->comment('日期')->index();
            $table->char('note')->comment('备注');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

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
