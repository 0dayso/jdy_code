<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldModulePartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('partner', function (Blueprint $table) {
            $table->unsignedInteger('invite_num')->default(0)->comment('邀请人数');
            $table->decimal('rate',5,1)->default(0)->comment('利率');
            $table->index('interest_time');
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
