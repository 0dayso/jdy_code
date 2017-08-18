<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentOutLimitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_out_limit', function (Blueprint $table) {
            $table->increments('id')->comment('零钱计划额度表');
            $table->unsignedBigInteger('user_id')->default(0)->comment('用户id');
            $table->integer('cash')->default(0)->comment('金额');
            $table->integer('admin_id')->default(1)->comment('操作者');
            $table->integer('status')->default(20)->comment('状态 10 未发布, 20 已发布');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment("创建时间");
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新时间');
            $table->integer('in_cash')->default(0)->comment('转入金额');

            $table->unique('user_id');
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
