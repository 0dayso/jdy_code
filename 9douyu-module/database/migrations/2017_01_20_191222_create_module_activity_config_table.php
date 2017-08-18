<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleActivityConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('activity_config', function (Blueprint $table) {
            $table->increments('id')->comment('活动配置表');
            $table->char('name',50)->comment('键名描述');
            $table->char('key',50)->unique('key','index_key')->comment('键名');
            $table->text('value')->comment('键值');
            $table->integer('admin_id')->comment('操作人ID');
            $table->tinyInteger('status')->comment('状态，0：未启用；1：启用');
            $table->text('second_desc')->comment('二级参数的描述');
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
