<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitySignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_sign', function (Blueprint $table) {
            $table->increments('id')->comment('活动签到');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->integer('sign_continue_num')->default(1)->comment('连续签到天数');
            $table->date('last_sign_day')->default('0000-00-00')->comment('最后签到日期');
            $table->char('note', 30)->default('')->comment('备注');
            $table->smallInteger('type')->unsigned()->default(0)->comment('活动类型');
            $table->char('sign_record', 150)->default('')->comment('签到记录');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
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