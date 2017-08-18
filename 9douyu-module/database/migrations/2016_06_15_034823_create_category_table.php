<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id')->comment('文章分类');
            $table->unsignedInteger('parent_id')->comment('父分类ID')->default(0);
            $table->char('name', 30)->comment('类别名称')->unique('name','index_name');
            $table->char('alias', 50)->comment('别名（备用)')->ablenull();
            $table->integer('sort_num')->comment('排序')->default(0);
            $table->smallInteger('status')->comment('状态：100: 未发布 200:发布')->default(100);
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
        //Schema::drop('category');
    }
}
