<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexOptimize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('credit_assign_project', function (Blueprint $table) {
            $table->index('user_id');

        });

        Schema::table('refund_record', function (Blueprint $table) {
            $table->dropIndex('refund_record_status_index');

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
