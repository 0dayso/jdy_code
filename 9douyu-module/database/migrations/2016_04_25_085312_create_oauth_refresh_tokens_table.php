<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthRefreshTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_refresh_tokens', function(Blueprint $table)
        {
            $table->string('refresh_token',40)->comment('刷新令牌');
            $table->string('client_id',80)->comment('客户端ID');
            $table->string('user_id',255)->comment('用户标示')->nullable();
            $table->timestamp('expires')->comment('过期时间');
            $table->string('scope', 2000)->nullable()->comment('权限范围');
            $table->primary('refresh_token');
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
