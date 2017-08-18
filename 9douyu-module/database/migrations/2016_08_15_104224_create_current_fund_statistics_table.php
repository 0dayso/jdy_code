<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentFundStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //零钱计划资金汇总表
        Schema::create('current_fund_statistics', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('零钱计划资金汇总表');
            $table->decimal('cash',20,2)->unsigned()->default(0)->comment('截止今日0:00零钱计划总资金');
            $table->decimal('invest_in',20,2)->unsigned()->default(0)->comment('昨日零钱计划转入金额');
            $table->decimal('invest_out', 20, 2)->unsigned()->default(0)->comment('昨日零钱计划转出金额');
            $table->decimal('total_invest_in', 20, 2)->unsigned()->default(0)->comment('零钱计划总转入金额');
            $table->decimal('total_invest_out', 20, 2)->unsigned()->default(0)->comment('零钱计划总转出金额');
            $table->decimal('interest', 20, 2)->unsigned()->default(0)->comment('零钱计划总收益');
            $table->decimal('day_interest',20,2)->unsigned()->default(0)->comment('统计日的收益');
            $table->decimal('cost',20,2)->unsigned()->default(0)->comment('统计日期的成本');
            $table->decimal('rate',5,2)->unsigned()->default(0)->comment('统计日期的基准利率');
            $table->date('date')->default('0000-00-00')->comment('数据统计的日期');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('date');

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
