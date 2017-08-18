<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_users', function(Blueprint $table) {
            $table->increments('id')->comment('内核授权用户表');
            $table->string('name')->comment('用户名');
            $table->string('email')->unique()->comment('用户邮箱');
            $table->string('password', 60)->comment('用户密码');
            $table->rememberToken()->comment('记住我');
            $table->timestamps(); 
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
