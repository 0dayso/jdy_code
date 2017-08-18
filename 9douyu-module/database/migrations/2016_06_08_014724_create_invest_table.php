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
        Schema::create('invest', function(Blueprint $table)
        {
            $table->increments('id')->comment('用户投资表');
            $table->integer('invest_id')->comment('核心表投资id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('project_id')->comment('项目id');
            $table->decimal('cash', 20, 2)->unsigned()->default(0)->comment('投资金额');
            $table->integer('bonus_id')->comment('红包id');
            $table->enum('bonus_type',[100,300])->comment('红包类型；100：定期加息券；300：红包');
            $table->decimal('bonus_value',20, 2)->comment('bonus值（红包金额 or 加息券利率）');
            $table->enum('app_request',['pc','wap','ios','android'])->default('pc')->comment('客户端类型：pc,wap,android,ios');
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
        //
    }
}
