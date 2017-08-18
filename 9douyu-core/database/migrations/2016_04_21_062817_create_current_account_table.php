<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //零钱计划账户
        Schema::create('current_account', function(Blueprint $table)
        {
            $table->increments('id')->comment('零钱计划账户');
            $table->integer('user_id')->unique('user_id','index_user_id')->comment('用户id');
            $table->decimal('cash',20,2)->unsigned()->default(0)->comment('金额');
            $table->decimal('interest',20,2)->unsigned()->default(0)->comment('利息');
            $table->decimal('yesterday_interest',10,2)->unsigned()->default(0)->comment('昨日利息');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('interested_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
