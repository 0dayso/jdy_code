<?php

use Illuminate\Database\Seeder;

class SystemConfigSeeder extends Seeder {

    public function run()
    {

        if(empty(app('db')->table("system_config")->where('key', 'ACCESS_TOKEN_SERVER')->get())) {
            app('db')->table("system_config")->insert([
                [
                    'name' => '服务 access token',
                    'key' => 'ACCESS_TOKEN_SERVER',
                    'value' => 's:47:"Bearer 8OunB7A4SfbBTjlqaEJKTD0s18ZvBhDBAdhTmCLC";',
                    'user_id' => '1',
                    'status' => '1',
                    'second_des' => 's:47:"Bearer YkiJ0phVvqPqdWXHtoRs35x0lTJZCsO6p8FoEx5K";',
                    'created_at' => '2016-07-05 20:25:55',
                    'updated_at' => '2016-07-06 23:01:33'
                ],
            ]);
        }

        if(empty(app('db')->table("system_config")->where('key', 'ACCESS_TOKEN_CORE')->get())) {
            app('db')->table("system_config")->insert([
                [
                    'name' => '内核 access token',
                    'key' => 'ACCESS_TOKEN_CORE',
                    'value' => 's:47:"Bearer 8OunB7A4SfbBTjlqaEJKTD0s18ZvBhDBAdhTmCLC";',
                    'user_id' => '1',
                    'status' => '1',
                    'second_des' => 's:47:"Bearer YkiJ0phVvqPqdWXHtoRs35x0lTJZCsO6p8FoEx5K";',
                    'created_at' => '2016-07-05 20:25:55',
                    'updated_at' => '2016-07-06 23:01:33'
                ],
            ]);
        }

    }

}
