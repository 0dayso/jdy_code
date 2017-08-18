<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('invite_rates', function(Blueprint $table)
        {
            $table->increments('id')->comment('邀请佣金加息券');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('status')->default(100)->comment('100:未使用;200:使用中');
            $table->integer('days')->default(0)->comment('使用天数');
            $table->integer('admin_id')->default(0)->comment('管路员id');
            $table->decimal('rate', 20, 2)->default(0.00)->comment('加息利率');
            $table->date('rate_start_time')->default('0000-00-00')->comment('加息开始时间');
            $table->timestamp('rate_end_time')->default('0000-00-00 00:00:00')->comment('加息截止时间');
            $table->timestamp('use_expire_time')->default('0000-00-00 00:00:00')->comment('使用截止时间');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index(['user_id', 'rate_end_time']);
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
