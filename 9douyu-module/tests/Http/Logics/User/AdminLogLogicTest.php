<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/28
 * Time: 上午9:30
 */

namespace Tests\Http\Logics\User;

use App\Http\Logics\User\AdminLogLogic;

class AdminLogLogicTest extends \TestCase
{

    public function sendData(){

        return [
            [
                'param'  => [
                    'user_id'      => 12,
                    'url'          => 'admin/test',
                    'http_referer' => 'http://www.zhuotao.test.9dy.in/test',
                    'ip'           => '1.1.1.1',
                    'data'         => 'test'
                ],
                'status' => true
            ],
            [
                'param'  => [
                    'user_id'      => 0,
                    'url'          => '',
                    'http_referer' => '',
                    'ip'           => '',
                    'data'         => ''
                ],
                'status' => true
            ],
            [
                'param'  => [
                    'user_id'      => 0,
                    'url'          => '',
                    'http_referer' => '',
                ],
                'status' => false
            ],
        ];
    }

    /**
     * @param $param
     * @param $status
     * @dataProvider sendData
     */
    public function testCreateRecord($param, $status){

        $logic = new AdminLogLogic();
        $result = $logic->createRecord($param);

        $this->assertEquals($status,$result['status']);

    }
}