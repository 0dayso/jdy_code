<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldModuleBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bonus', function (Blueprint $table) {
            //
            $table->smallInteger('effect_type')->default(100)->comment('生效类型 100:旧版红包; 200:即时生效; 300:按时间生效');
            $table->date('effect_start_date')->comment('生效开始日期');
            $table->date('effect_end_date')->comment('生效结束日期');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonus', function (Blueprint $table) {
            //
        });
    }
}