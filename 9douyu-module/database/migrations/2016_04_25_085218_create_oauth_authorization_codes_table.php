<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthAuthorizationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('oauth_authorization_codes', function(Blueprint $table)
        {
            $table->string('authorization_code',40)->comment('授权码');
            $table->string('client_id',80)->comment('客户端ID');
            $table->string('user_id',255)->comment('用户标示')->nullable();
            $table->timestamp('expires')->comment('过期时间');
            $table->string('redirect_uri', 2000)->nullable()->comment('跳转URL');
            $table->string('scope', 2000)->nullable()->comment('授权范围');
            $table->primary('authorization_code');
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
