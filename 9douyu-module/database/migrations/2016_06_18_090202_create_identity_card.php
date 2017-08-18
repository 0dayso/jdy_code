<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentityCard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        //实名成功日志记录表
        Schema::create('identity_card', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('实名成功日志记录表');
            $table->string('name',20)->default('')->comment('姓名');
            $table->string('identity_card',20)->default('')->unique()->comment('身份证号');
            $table->string('photo',8)->default('')->comment('图片存储的文件ID');
            $table->string('app_request',20)->default('pc')->comment('请求来源');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('name');
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
