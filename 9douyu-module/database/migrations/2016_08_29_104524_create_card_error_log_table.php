<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardErrorLogTable extends Migration
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
        //零钱计划每日利率
        Schema::create('card_error_log', function(Blueprint $table)
        {
            $table->increments('id')->comment('卡鉴权错误日志表');
            $table->string('name',30)->default('')->comment('姓名');
            $table->string('id_card',30)->default('')->comment('身份证号');
            $table->string('card_no',30)->default('')->comment('银行卡号');
            $table->string('register_phone',15)->default('')->comment('手机号');
            $table->string('bind_phone',15)->default('')->comment('银行预留手机号');
            $table->string('channel',20)->default('')->comment('Reapal - 融宝 , Umpay - 联动优势');
            $table->string('result_code',30)->default('')->comment('返回的错误状态码');
            $table->string('result_msg',255)->default('')->comment('鉴权结果错误信息');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->smallInteger('status')->default(500)->comment('200 - 成功 500 - 失败');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index(['card_no','id_card','name']);

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
