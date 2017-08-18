<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/29
 * Time: 下午3:54
 */

namespace Tests\Http\Controllers\App;


class UserTest extends \TestCase
{

    public function testData(){

        return [
            [
                'user_id' => 1,
                'client'  => 'ios',
            ]
        ];

    }


    public function testList(){

        $this -> post('user_info',[]);

    }

}