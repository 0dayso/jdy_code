<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/14
 * Time: 下午5:15
 */
class OrmTest extends TestCase
{
    public function testQuery(){
        app('db')->connection()->enableQueryLog();
        $results = DB::select("SELECT * FROM core_user");
        $results2 = app('db')->select("SELECT * FROM core_user");
        echo print_r([$results, $results2], true);
        echo print_r(DB::getQueryLog(), true);
    }
}