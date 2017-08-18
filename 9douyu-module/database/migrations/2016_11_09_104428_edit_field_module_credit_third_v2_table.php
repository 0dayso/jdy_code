<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditFieldModuleCreditThirdV2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = 'alter table `{db_prefix}credit_third` modify column `credit_list` longtext null comment "债权列表"';
        $sql = str_replace('{db_prefix}', env('DB_PREFIX'), $sql);
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
