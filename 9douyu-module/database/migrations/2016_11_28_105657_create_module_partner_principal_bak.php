<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulePartnerPrincipalBak extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('partner_principal_bak', function(Blueprint $table)
        {
            $table->increments('id')->comment('合伙人邀请待收明细');
            $table->bigInteger('user_id')->default(0)->comment('邀请人用户ID');
            $table->bigInteger('invited_user_id')->default(0)->comment('被邀请人用户ID');
            $table->decimal('current_principal',20,2)->default(0)->comment('活期待收本金');
            $table->decimal('term_principal',20,2)->default(0)->comment('定期待收本金');
            $table->date('date')->default('0000-00-00')->comment('数据日期');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index(['user_id','date']);

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
