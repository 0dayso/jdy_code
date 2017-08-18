<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermPrincipalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        //定期待收(截止到今日零点)
        Schema::create('term_principal', function(Blueprint $table)
        {
            $table->increments('id')->comment('定期待收');
            $table->bigInteger('user_id')->default(0)->comment('用户ID');
            $table->decimal('principal',20,2)->default(0)->comment('截止今日0点的定期待收本金');
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
