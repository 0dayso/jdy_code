<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //用户
        Schema::create('user', function(Blueprint $table)
        {
            $table->increments('id')->comment('用户表');
            $table->char('phone',15)->comment('手机号');
            $table->char('password_hash',65)->comment('密码');
            $table->char('trading_password',65)->comment('交易密码');
            $table->decimal('balance',20,2)->unsigned()->default(0)->comment('账户余额');
            $table->smallInteger('status_code')->default(200)->comment('状态');
            $table->char('real_name',30)->comment('姓名');
            $table->char('identity_card',20)->comment('身份证');
            $table->char('note',50)->comment('备注');
            $table->unique('phone'); //手机号 唯一索引
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('identity_card');

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
