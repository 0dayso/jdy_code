<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family', function (Blueprint $table) {
            $table->increments('id')->comment('家庭账户ID');
            $table->integer('my_uid')->comment('主账号ID');
            $table->integer('family_id')->comment('子账户id');
            $table->string('call_name',10)->comment('子帐号称呼');
            $table->tinyInteger('is_bind')->default(1)->comment('是否绑定');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('family_id','index_family_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('family');
    }
}
