<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColCreditLoanBuildingMortgage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credit_loan', function (Blueprint $table) {
            $table->string('creditor_info')->default('池洪英,110222195008065720')->comment('乙方债权出让人信息')->nullable();
        });

        Schema::table('credit_building_mortgage', function (Blueprint $table) {
            $table->string('creditor_info')->default('北京耀盛小额贷款有限公司,91110229MA0051RL0D')->comment('乙方债权出让人信息')->nullable();
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
