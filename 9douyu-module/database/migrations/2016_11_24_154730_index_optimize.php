<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IndexOptimize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //

        Schema::table('ad', function (Blueprint $table) {
            $table->index('position_id');
        });

        Schema::table('article', function (Blueprint $table) {
            $table->index('category_id');
        });
        Schema::table('family', function (Blueprint $table) {
            $table->index('my_uid');
        });

        Schema::table('identity_card', function (Blueprint $table) {
            $table->index('created_at');
            $table->dropIndex('identity_card_name_index');

        });


        Schema::table('media_invite', function (Blueprint $table) {
            $table->index('channel_id');

        });

        Schema::table('order_check', function (Blueprint $table) {
            $table->index('order_id');

        });

        Schema::table('avatar', function (Blueprint $table) {
            $table->index('user_id');

        });

        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('access_token');

        });

        Schema::table('user_link_wechat', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('openid');

        });

        $sql = "alter table `".env('DB_PREFIX')."bank_list` add id int(10) primary key  auto_increment ";
        DB::connection()->getPdo()->exec($sql);

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
