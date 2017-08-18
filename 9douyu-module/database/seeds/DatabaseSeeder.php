<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(OAuthSeeder::class);
        //$this->call(SystemConfigSeeder::class);
        $this->call(RbacSeeder::class);
        //$this->call(AdPositionSeeder::class);
        $this->call(MediaChannelSeeder::class);
    }
}
