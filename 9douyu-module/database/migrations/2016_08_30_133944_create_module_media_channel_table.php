<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleMediaChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::create('media_channel', function(Blueprint $table)
        {
            $table->increments('id')->comment('推广渠道');
            $table->integer('group_id')->unsigned()->default(0)->comment('分组ID');
            $table->string('name',50)->default('')->comment('渠道名称');
            $table->string('desc',255)->default('')->comment('渠道描述');
            $table->string('url',200)->default('')->comment('渠道落地页');
            $table->string('package')->default('')->comment('推广包名');
            $table->string('award_key')->default('')->comment('对应奖励key');
            $table->date('start_date')->default('0000-00-00')->comment('推广开始日期');
            $table->date('end_date')->default('0000-00-00')->comment('推广结束日期');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->unique('name');
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
