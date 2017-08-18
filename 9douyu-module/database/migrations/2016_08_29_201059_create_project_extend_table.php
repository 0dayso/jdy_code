<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectExtendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_extend', function(Blueprint $table)
        {
            $table->increments('id')->comment('项目扩展表,可记录新手项目、项目的特殊标志');
            $table->integer('project_id')->unsigned()->default(0)->comment('项目id');
            $table->integer('type')->unsigned()->default(0)->comment('类型,具体见DB');
            $table->integer('status')->unsigned()->default(100)->comment('状态,具体见DB');
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
