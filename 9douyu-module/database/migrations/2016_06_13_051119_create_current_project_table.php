<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //零钱计划项目
        Schema::create('current_project', function(Blueprint $table)
        {
            $table->increments('id')->comment('零钱计划项目');
            $table->char('name',100)->comment('名称');
            $table->decimal('total_amount', 20, 2)->unsigned()->default(0)->comment('总金额');
            $table->decimal('invested_amount', 20, 2)->unsigned()->default(0)->comment('已投资');
            $table->tinyInteger('create_by')->comment('创建人id');
            $table->timestamp('publish_at')->comment('发布时间');
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
