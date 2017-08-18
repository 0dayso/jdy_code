<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_log', function (Blueprint $table) {
            $table->increments('id')->comment('日志id');
            $table->bigInteger('user_id')->comment('用户id');
            $table->string('url',255)->default('')->comment('控制器名');
            $table->string('http_referer',255)->default('')->comment('来源');
            $table->char('ip',15)->default('')->comment('ip');
            $table->text('data')->default('')->comment('数据记录');
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
        Schema::drop('admin_log');
    }
}
