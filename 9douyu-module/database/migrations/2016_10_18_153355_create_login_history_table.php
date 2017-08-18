<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/10/18
 * Time: 15:34
 */
use \Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLoginHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){

        Schema::create('login_history',function(Blueprint $table){
            $table->increments('id')->comment('用户登录记录表');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->timestamp('login_time')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('登录时间');
            $table->char('login_ip',20)->default('0.0.0.0')->comment('登录IP');
            $table->integer('app_request')->default(0)->comment('登录来源 [1=>pc, 2=>wap, 3=>ios, 4=>android 5=>pfb]');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->index('user_id');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){

    }
}