<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus', function(Blueprint $table)
        {
            $table->increments('id')->comment('红包加息券表');
            $table->string('name',255)->comment('名称');
            $table->smallInteger('type', false, true)->comment('类型');
            $table->text('client_type')->comment('客户端类型 9:所有 1:WEB 2:WAP 3:APP');
            $table->text('project_type')->comment('可投类型 产品线');
            $table->decimal('rate',10,2)->unsigned()->default(0)->comment('加息券利率[零钱计划 | 定期]');
            $table->decimal('money', 20, 2)->unsigned()->default(0)->comment('红包金额');
            $table->smallInteger('use_type')->comment('使用类型');
            $table->decimal('min_money', 20 ,2)->unsigned()->default(0)->comment('最小使用金额');
            $table->decimal('max_money', 20, 2)->unsigned()->default(0)->comment('最大使用金额');

            $table->integer('expires')->comment('期限');

            $table->integer('current_day')->comment('零钱计划计息天数');

            $table->date('send_start_date')->comment('发布开始日期');
            $table->date('send_end_date')->comment('发布结束日期');

            $table->text('using_desc')->comment('使用范围描述');
            $table->text('note')->comment('备注项（用于备注红包的来源及使用)');

            $table->smallInteger('status')->default(100)->comment('状态 100:未发布；200：已发布；300：锁定');
            $table->smallInteger('give_type')->default(300)->comment('是否可以转增 300：不可；200：可以');

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
