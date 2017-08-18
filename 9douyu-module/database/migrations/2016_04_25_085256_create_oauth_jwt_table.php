<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthJwtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_jwt', function(Blueprint $table)
        {
            $table->string('client_id',80)->comment('客户端ID');
            $table->string('subject', 80)->comment('')->nullable();
            $table->string('public_key', 2000)->nullable()->comment('');
            $table->primary('client_id');
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
