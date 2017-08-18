<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //提现银行卡数据表
        Schema::create('fund_ticket', function(Blueprint $table)
        {
            $table->bigIncrements('id')->comment('资金检票表(防止用户重复加钱)');
            $table->string('ticket_id',24)->default('')->comment('唯一ticket ID');
            $table->integer('fund_id')->unsigned()->default(0)->comment('资金流水ID,关联fund_history表');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->index();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->unique('ticket_id');

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
