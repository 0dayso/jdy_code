<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestPfbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //普付宝订单表
        Schema::create('invest_pfb',function(Blueprint $table){
            $table->increments('id')->comment('普付宝订单表');
            $table->integer('invest_id')->default(0)->comment('订单ID');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->decimal('cash',20,2)->unsigned()->default(0)->comment('订单金额');
            $table->smallInteger('status')->default(100)->comment('订单状态：100质押冻结，200正常解决冻结');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('添加时间，首次申请质押的时间');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('invest_id');
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
