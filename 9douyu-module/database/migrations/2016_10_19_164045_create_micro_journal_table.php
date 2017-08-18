<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMicroJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //
        Schema::create('micro_journal', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('后台微刊管理表');
            $table->string('date',20)->default('')->comment('微刊刊号');
            $table->integer('picture_id')->default(0)->comment('图片ID');
            $table->string('params',255)->default('')->comment('微刊数据');
            $table->integer('status')->default(100)->comment('状态,100:未开启;200:开启;');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('date');
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
