<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuggestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('suggest', function (Blueprint $table) {
            $table->increments('id')->comment('意见反馈日志表');
            $table->bigInteger('user_id')->default(0)->comment('用户ID');
            $table->text('content')->default('')->comment('用户反馈内容');
            $table->unsignedTinyInteger('status')->default(100)->comment('处理状态 100-未处理 200-已处理');
            $table->string('dev_info',100)->default('')->comment('设备信息');
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
