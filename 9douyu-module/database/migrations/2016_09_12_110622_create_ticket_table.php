<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_checking', function (Blueprint $table) {
            $table->increments('id')->comment('验票表');
            $table->string('uuid')->comment('票标示');
            $table->smallInteger('from_code')->default(100)->comment('渠道标示：100 一码付');
            $table->index(['from_code', 'uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ticket');
    }
}
