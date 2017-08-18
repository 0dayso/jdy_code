<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventNotifyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('event_notify', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('事件通知表');
            $table->unsignedInteger('auth_id')->comment('app_security表id');
            $table->string('event_name')->comment('事件名');
            $table->string('notify_url')->comment('事件通知地址');
            $table->timestamps();
            $table->unique(['auth_id', 'event_name']);
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
