<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract', function (Blueprint $table) {
            $table->increments('id')->comment('合同表');
            $table->integer('invest_id')->comment('核心表投资id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('project_id')->comment('项目id');
            $table->decimal('cash', 20, 2)->unsigned()->default(0)->comment('投资金额');
            $table->string('contract_num')->comment('合同编号');
            $table->string('pdf_path')->comment('合同路径');
            $table->string('ebq_pdf_path')->comment('宝全合同路径');
            $table->string('preservation_id')->comment('保全ID');
            $table->tinyInteger('status')->default(0)->comment('保全状态');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('invest_id','index_invest_id');
            $table->index('user_id','index_user_id');
            $table->index('project_id','index_project_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('contract');
    }
}
