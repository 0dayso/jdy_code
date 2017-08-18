<?php

use Illuminate\Database\Seeder;

class OAuthSeeder extends Seeder {

    public function run()
    {

        app('db')->table("oauth_clients")->delete();

        app('db')->table("oauth_clients")->insert([
            [
                'client_id' => '9e304d4e8df1b74cfa009913198428ab',
                'grant_types' => 'password refresh_token'
            ],
            [
                'client_id' => 'bc54f4d60f1cec0f9a6cb70e13f2127a',
                'grant_types' => 'password refresh_token'
            ],
            [
                'client_id' => 'c31b32364ce19ca8fcd150a417ecce58',
                'grant_types' => 'password refresh_token'
            ],
            [
                'client_id' => 'ca4d8c5af3036c2f6d8f533a054457fd',
                'grant_types' => 'password refresh_token'
            ],
            [
                'client_id' => '4d5363eee79c01dfd9e62ba9e35628d4',
                'grant_types' => 'password refresh_token'
            ],
            [
                'client_id' => '3628abd72cf535289d48cbfffcd2ddc3',
                'grant_types' => 'password refresh_token'
            ],
        ]);

    }

}
