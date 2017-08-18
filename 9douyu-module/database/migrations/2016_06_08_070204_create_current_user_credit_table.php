<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentUserCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('current_user_credit', function(Blueprint $table)
        {
            /*债权要素*/
            $table->increments('id')->comment('用户匹配零钱计划债权表');
            $table->bigInteger('user_id')->unsigned()->default(0)->comment('用户ID');
            $table->decimal('cash', 20, 2)->unsigned()->default(0)->comment('零钱计划金额');
            $table->text('credit')->default('')->comment('匹配的债权数据');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('user_id');
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
