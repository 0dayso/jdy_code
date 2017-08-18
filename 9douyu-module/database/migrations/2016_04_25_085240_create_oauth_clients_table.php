<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_clients', function(Blueprint $table)
        {
            $table->string('client_id',80)->comment('客户端ID');
            $table->string('client_secret',80)->comment('客户端秘钥')->nullable();
            $table->string('redirect_uri', 2000)->comment('跳转URL');
            $table->string('grant_types',80)->comment('授权类型')->nullable();
            $table->string('scope', 100)->nullable()->comment('权限范围');
            $table->string('user_id',80)->comment('用户标示')->nullable();
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
