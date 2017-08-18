<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectRefundPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('project_refund_plan', function (Blueprint $table) {
//            $table->increments('id')->comment('项目回款计划表');
//            $table->integer('project_id')->comment('项目id')->index();
//            $table->date('refund_time')->comment('回款日');
//            $table->unsignedInteger('refund_cash')->comment('回款金额');
//            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('project_refund_plan');
    }
}
