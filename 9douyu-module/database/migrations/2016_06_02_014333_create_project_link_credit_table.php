<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectLinkCreditTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('project_link_credit', function (Blueprint $table) {
//            $table->increments('id')->comment('项目债权关联表');
//            $table->integer('project_id')->comment('项目id');
//            $table->integer('credit_id')->comment('债权id');
//            $table->tinyInteger('type')->comment('债权类型');
//            $table->unsignedInteger('credit_cash')->comment('使用债权金额');
//            $table->integer('product_line')->comment('项目所属产品线100:九省心 200:九安心 300:闪电付息');
//            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
//            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
//
//            $table->index('project_id');
//            $table->index(['project_id','product_line']);
//        });

        Schema::create('project_link_credit', function (Blueprint $table) {
            $table->increments('id')->comment('项目债权关联表');
            $table->integer('project_id')->comment('项目id');
            $table->integer('product_line')->comment('项目所属产品线100:九省心 200:九安心 300:闪电付息');
            $table->string('credit_info')->comment('债权序列化信息');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('project_link_credit');
    }


}
