<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityFundHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_fund_history', function (Blueprint $table) {
            $table->increments('id')->comment('活动资金明细');
            $table->integer('user_id')->comment('用户id');
            $table->integer('wx_id')->comment('微信id');
            $table->decimal('balance_change', 20, 2)->unsigned()->default(0)->comment('变化金额');
            $table->smallInteger('type')->unsigned()->default(1)->comment('类型：1：转入；2：转出；');
            $table->integer('source')->unsigned()->default(0)->comment('活动来源类别,详见DB设置')->index();
            $table->char('note', 100)->comment('备注');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
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
