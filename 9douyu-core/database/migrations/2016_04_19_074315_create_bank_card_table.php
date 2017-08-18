<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //提现银行卡数据表
        Schema::create('bank_card', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('提现银行卡表');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户ID');
            $table->tinyInteger('bank_id')->unsigned()->default(0)->comment('银行ID，关联bank表');
            $table->string('card_number',30)->default('')->unique()->comment('银行卡号');
            $table->timestamps();

            $table->index('user_id');

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
