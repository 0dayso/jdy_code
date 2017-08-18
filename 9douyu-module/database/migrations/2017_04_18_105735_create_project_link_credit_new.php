<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectLinkCreditNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_link_credit_new', function (Blueprint $table) {
            $table->increments('id')->comment('新的债权项目关联表-方便以后的债权项目查询');
            $table->integer('project_id')->unique()->comment('项目的id');
            $table->integer('credit_id')->comment('新的债权对应的id');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('credit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('project_link_credit_new');
    }
}
