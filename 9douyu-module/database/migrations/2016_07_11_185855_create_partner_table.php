<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner', function (Blueprint $table) {
            $table->increments('id')->comment('合伙人');
            $table->unsignedInteger('user_id')->comment('用户Id')->index();
            $table->decimal('cash', 20, 2)->unsigned()->default(0)->comment('用户佣金收益（转出到余额后的收益）');
            $table->decimal('interest', 20, 2)->unsigned()->default(0)->comment('用户佣金总利息额');
            $table->decimal('yesterday_interest', 20, 2)->unsigned()->default(0)->comment('昨日利息');
            $table->decimal('yesterday_cash', 20, 2)->unsigned()->default(0)->comment('昨日本金');
            $table->timestamp('interest_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('计息时间');
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
        //Schema::drop('partner');
    }
}
