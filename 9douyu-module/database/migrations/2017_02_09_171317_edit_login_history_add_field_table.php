<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLoginHistoryAddFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('login_history', function(Blueprint $table)
        {
            $table->string('client_type',50)->default('')->comment('client_type');
            $table->string('client_version',50)->default('')->comment('设备信息版本号');
            $table->string('client_note',150)->default('')->comment('设备信息备注');

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
