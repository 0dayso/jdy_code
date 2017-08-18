<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_credit', function (Blueprint $table) {
            $table->increments('id')->comment('债权匹配结果');
            $table->integer('user_id')->index()->comment('用户id');
            $table->integer('credit_id')->index()->default(0)->comment('债权id');
            $table->decimal('amount',20,2)->unsigned()->default(0)->comment('匹配金额');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique(['user_id', 'credit_id']);
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
