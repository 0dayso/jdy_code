<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLotteryRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('lottery_record', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('中奖记录表');
            $table->integer('user_id')->default(0)->comment('分组ID');
            $table->string('user_name',20)->default('')->comment('中奖者姓名');
            $table->string('phone',11)->default('')->comment('中奖者手机号码');
            $table->string('award_name',20)->default('')->comment('奖品名称');
            $table->smallInteger('type')->default('3')->comment('1-红包,2-加息券,3-实物奖品');
            $table->integer('prizes_id')->default(0)->comment('奖品序号');
            $table->smallInteger('activity_id')->default(0)->comment('活动标示');
            $table->smallInteger('status')->default(10)->comment('10-未审核,20-审核通过，30，失败');
            $table->string('note',50)->default('')->comment('说明');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->index('created_at');
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
