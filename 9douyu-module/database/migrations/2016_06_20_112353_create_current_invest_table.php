<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentInvestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('current_invest', function(Blueprint $table)
        {
            $table->increments('id')->comment('零钱计划用户投资表');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->decimal('cash', 20, 2)->unsigned()->default(0)->comment('投资金额');
            $table->enum('app_request',['pc','wap','android','ios'])->default('pc')->comment('客户端类型：pc,wap,android,ios');
            $table->smallInteger('type')->default(400)->comment('400:零钱计划转入,401:零钱计划转出,402:回款自动转零钱计划');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('user_id');
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
