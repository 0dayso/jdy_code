<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDbKvdbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('db_kvdb', function(Blueprint $table)
        {
            $table->increments('id')->comment('账户资金统计表');
            $table->string('rawkey',30)->comment('统计KEY值');
            $table->char('md5key',32)->comment('md5加密key');
            $table->text('val')->comment('统计内容');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('created_at','index_db_kvdb_created_at');
            $table->index('rawkey','index_db_kvdb_rawkey');
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
