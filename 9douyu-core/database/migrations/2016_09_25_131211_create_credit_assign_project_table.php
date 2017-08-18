<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditAssignProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_assign_project', function(Blueprint $table) {
            $table->bigIncrements('id')->comment('债权项目表');
            $table->integer('project_id')->default(0)->comment('原项目ID');
            $table->integer('invest_id')->default(0)->comment('投资ID');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->decimal('total_amount',20,2)->unsigned()->default(0)->comment('总金额');
            $table->decimal('invested_amount',20,2)->unsigned()->default(0)->comment('已投资金额');
            $table->date('end_at')->default('0000-00-00')->comment('项目完结日');
            $table->enum('status',[100,110,120,130])->default(100)->comment('债转项目状态 100-正常可投 110-取消 120-原项目已完结 130-已售罄');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('project_id');
            $table->index('invest_id');
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
