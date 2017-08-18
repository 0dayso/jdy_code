<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //投资
        Schema::create('invest', function(Blueprint $table)
        {
            $table->increments('id')->comment('投资记录表');
            $table->integer('project_id')->comment('项目id')->index();
            $table->integer('user_id')->comment('用户id')->index();
            $table->decimal('cash',20,2)->unsigned()->default(0)->comment('投资金额');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('投资时间')->index();

            $table->tinyInteger('invest_type')->default(0)->comment('0-正常投资 1-债转投资');
            $table->integer('assign_project_id')->default(0)->comment('债权项目ID');
            $table->index('assign_project_id');
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
