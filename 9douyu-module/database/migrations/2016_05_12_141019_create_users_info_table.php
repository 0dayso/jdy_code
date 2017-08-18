<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateUsersInfoTable
 */
class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_info', function(Blueprint $table)
        {
            $table->increments('id', true, true)->comment('用户附加表');
            $table->char('username',30)->comment('老表的username')->nullable();
            $table->char('email',50)->comment('邮箱')->nullable();
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->tinyInteger('user_type')->default(1)->comment('用户类型【1用户、2自媒体邀请、3app推广渠道、4家庭账户】')->nullable();
            $table->char('ip',20)->comment('IP[老系统有空值]')->nullable();
            $table->char('urgent_linkman_name',30)->comment('紧急联系人名')->nullable();
            $table->char('urgent_linkman_phone',20)->comment('紧急联系人手机号')->nullable();
            $table->smallInteger('source_code')->comment('注册来源[1=>pc, 2=>wap, 3=>ios, 4=>android]');
            $table->char('invite_code',20)->comment('邀请码')->nullable();
            $table->char('channel',20)->comment('渠道来源')->nullable();
            $table->char('address',50)->comment('地区：格式：11000_11000_10000;一级行政_二级行政_三级行政')->nullable();
            $table->string('address_text', 255)->comment('详细的地址')->nullable();
            $table->string('note', 255)->comment('备注')->nullable();
            $table->string('third_icon_code', 255)->comment('菜单标示：暂时用到【一码付】')->nullable();
            $table->string('version', 20)->comment('app 版本')->nullable();
            $table->tinyInteger('flow_type')->default(1)->comment('流程类型 1注册 2实名')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('user_id');
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
