<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankCardChangeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        //充值银行卡数据表
        Schema::create('bank_card_change_log', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('换卡日志记录表');
            $table->integer('user_id')->unsigned()->default(0)->unqiue()->comment('用户ID');
            $table->string('old_card',30)->default('')->comment('旧银行卡号');
            $table->string('new_card',30)->default('')->comment('新银行卡号');

            $table->timestamps();
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
