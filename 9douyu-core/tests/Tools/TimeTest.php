<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/28
 * Time: 上午11:01
 */

use Carbon\Carbon;

class TimeTest extends TestCase
{


    public function testA()
    {



        $a = '2016-04-30 11:00:22';

        $b = '2016-04-30 12:00:22';





        var_dump(\App\Tools\ToolTime::getDayDiff($a, $b));




    }

}
