<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestExtendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invest_extend', function (Blueprint $table) {
            $table->increments('id')->comment('投资扩展表');
            $table->integer('invest_id')->comment('投资表id');
            $table->enum('bonus_type',[100,300])->default(100)->comment('红包类型；100：定期加息券；300：红包');
            $table->decimal('bonus_value',20, 2)->comment('bonus值（红包金额 or 加息券利率）');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index('invest_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('invest_extend');
    }
}
