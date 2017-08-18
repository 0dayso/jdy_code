<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/28
 * Time: 下午8:07
 */

namespace Tests\Http\Controllers\App\Server;


class ServerTest extends \TestCase
{

    public function testData(){

        return [

            [
                ['client'=>'android']
            ],
            [
                ['client'=>'ios']
            ],
        ];

    }

    /**
     * @param $data
     * @dataProvider testData
     */
    public function testGetServerList($data){

        $this -> post('get_server_list', $data);

    }

}