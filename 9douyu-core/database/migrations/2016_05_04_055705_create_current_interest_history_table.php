<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentInterestHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //零钱计划收益记录
        Schema::create('current_interest_history', function(Blueprint $table)
        {
            $table->increments('id')->comment('零钱计划收益记录');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->decimal('rate',5,2)->comment('利率');
            $table->decimal('interest',20,2)->unsigned()->default(0)->comment('利息');
            $table->date('interest_date')->comment('计息日期');
            $table->decimal('principal',20,2)->unsigned()->default(0)->comment('计息本金');
            $table->tinyInteger('type')->default(1)->comment('利息来源 1-零钱计划基准利息 2-加息券利息');
            $table->unique(['user_id','interest_date','type']);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

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
        //
    }
}
