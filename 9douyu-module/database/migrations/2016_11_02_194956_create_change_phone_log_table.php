<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangePhoneLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('change_phone_log', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('更换手机号码日志');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->string('phone',11)->default('')->comment('原手机号码');
            $table->string('old_phone',11)->default('')->comment('新手机号码');
            $table->string('comment',100)->default('')->comment('异常备注');
            $table->integer('admin_id')->default('0')->comment('操作者');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
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
