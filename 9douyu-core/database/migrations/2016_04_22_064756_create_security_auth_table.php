<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //接口安全验证
        Schema::create('security_auth', function(Blueprint $table)
        {
            $table->increments('id')->comment('接口安全验证');
            $table->char('name',30)->unique('name','index_name')->comment('名称');
            $table->char('secret_key',20)->comment('秘钥');
            $table->char('desc',100)->comment('描述');
            $table->integer('status')->comment('状态；200：正常');
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
