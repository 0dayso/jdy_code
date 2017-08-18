<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('notice', function (Blueprint $table) {
            $table->increments('id')->comment('站内信');
            $table->char('title',30)->comment('标题');
            $table->char('user_id',50)->index()->comment('接收站内信用户id，值为0表示系统站内信');
            $table->char('message',200)->comment('内容');
            $table->tinyInteger('is_read')->index()->comment('是否已读（发给单独用户记录）');
            $table->tinyInteger('type')->comment('各种不同类型的通知,方便将来做扩展');
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
