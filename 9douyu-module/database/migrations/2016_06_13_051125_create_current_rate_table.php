<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentRateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //零钱计划每日利率
        Schema::create('current_rate', function(Blueprint $table)
        {
            $table->increments('id')->comment('零钱计划每日利率');
            $table->decimal('rate',5,2)->unsigned()->default(0)->comment('利率');
            $table->date('rate_date')->comment('利率日期');
            $table->char('profit_percentage',10)->comment('利率带加号');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('rate_date');

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
