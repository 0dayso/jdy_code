<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_list', function (Blueprint $table) {
            $table->increments('id')->comment('批量操作列表');
            $table->integer('status')->default(100)->comment('状态,100:待审核;200:已完成;300:审核成功待执行');
            $table->char('type',30)->default(0)->comment('类别:短信、微信、App、发送红包');
            $table->integer('admin_id')->default(0)->comment('管理员id');
            $table->char('file_path')->default(0)->comment('文件路径,方便查询');
            $table->char('content')->default(0)->comment('发送内容:推送消息,发送红包加息券不需要该字段');
            $table->char('note',30)->default(0)->comment('备注');
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
