<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldModuleCreditThirdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_third', function (Blueprint $table) {
            $table->text('project_desc')->comment('项目描述')->nullable();
            $table->text('risk_control')->comment('风险控制')->nullable();
            $table->text('credit_list')->comment('债权列表')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('credit_third', function (Blueprint $table) {
//            //
//        });
    }
}
