<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //广告位
        Schema::create('ad_position', function(Blueprint $table)
        {
            $table->increments('id')->comment('广告位');
            $table->char('name', 50)->default('')->comment('描述');
            $table->tinyInteger('type')->default(1)->comment('广告位类型;1:pc;2:wap;3:app');
            $table->integer('position_id')->default('0')->comment('所属广告位');
            $table->text('param')->default('')->comment('json存储的广告位参数');
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
