<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrentPrincipalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //活期待收(截止到今日零点)
        Schema::create('current_principal', function(Blueprint $table)
        {
            $table->increments('id')->comment('活期待收');
            $table->bigInteger('user_id')->default(0)->comment('用户ID');
            $table->decimal('principal',20,2)->default(0)->comment('截止今日0点的活期账户金额');
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
