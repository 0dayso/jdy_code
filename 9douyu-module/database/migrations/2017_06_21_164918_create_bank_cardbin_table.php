<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankCardbinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('bank_cardbin', function (Blueprint $table) {
            $table->increments('id')->comment('银行卡bin表');
            $table->string('verify_code',20)->index()->default('')->comment('卡标识号取值');
            $table->integer('verify_length')->default('0')->comment('卡标识长度');
            $table->integer('pan_length')->default('0')->comment('卡号长度');
            $table->string('bin',50)->default('')->comment('发卡行标识代码');
            $table->string('card_name',100)->default('')->comment('卡号');
            $table->string('card_type',30)->default('')->comment('卡类型');
            $table->string('bank_code',30)->default('')->comment('发卡行代码');
            $table->string('bank_name',100)->default('')->comment('发卡行名称');
            $table->timestamp('create_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
