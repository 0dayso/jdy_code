<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotteryConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('lottery_config', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('奖品配置表');
            $table->string('name',50)->default('')->comment('奖品名词');
            $table->integer('number')->default(0)->comment('奖品数量');
            $table->integer('rate')->default(0)->comment('中奖概率');
            $table->smallInteger('type')->default(1)->comment('1-红包,2-加息券,3-实物奖品');
            $table->integer('foreign_id')->default(0)->default(0)->comment('红包或加息券ID');
            $table->smallInteger('order_num')->default('0')->comment('位置');
            $table->smallInteger('group')->default('0')->comment('分组h/等级');
            $table->smallInteger('admin_id')->default('0')->comment('操作者');
            $table->smallInteger('status')->default('10')->comment('状态,10开启，20关闭');
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
        //
    }
}
