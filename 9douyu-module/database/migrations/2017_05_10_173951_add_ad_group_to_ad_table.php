<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdGroupToAdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ad', function (Blueprint $table) {
            //app4.0 广告分组
            $table->tinyInteger('group_sort')->default('0')->comment('app4.0 广告分组');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ad', function (Blueprint $table) {
            //
        });
    }
}
