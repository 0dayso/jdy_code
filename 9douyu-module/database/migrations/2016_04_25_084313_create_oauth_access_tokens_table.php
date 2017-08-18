<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * php artisan make:migration create_oauth_access_token_table
 * Class CreateOauthAccessTokenTable
 */
class CreateOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_access_tokens', function(Blueprint $table)
        {
            $table->string('access_token',40)->comment('访问资源的令牌');
            $table->string('client_id',80)->comment('客户端ID');
            $table->string('user_id',255)->comment('用户标示')->nullable();
            $table->timestamp('expires')->comment('过期时间');
            $table->string('scope', 2000)->nullable()->comment('权限范围');
            $table->primary('access_token');
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
