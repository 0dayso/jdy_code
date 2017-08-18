<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/14
 * Time: 下午5:16
 */

namespace Tests\Http\Controllers\Admin\SystemConfig;

class SystemConfigTest extends \TestCase
{

    /**
     * 列表视图test
     */
    public function testIndexView(){
        $this->visit('/admin/system_config')
            ->see('配置列表');
    }

    /**
     * 创建视图test
     */
    public function testCreateView(){
        $this->visit('/admin/system_config/create')
            ->see('添加配置');
    }

    public function testData(){

        return [
            [
                'is'    => true,
                'data'  => [
                    "name"      => "test1",
                    "status"    => "1",
                    "key"       => "TEST_1",
                    "value"     => "sdd"
                ],
            ],
            [
                'is'    => true,
                'data'  => [
                    "name"          => "test2",
                    "status"        => "1",
                    "key"           => "TEST_2",
                    "value"         => "sdd",
                    "second_key"    => [
                        0 => "INVITE_TIME"
                    ],
                    "second_value"  => [
                        0 => "30"
                    ],
                    "second_des"    => [
                        0 => "融宝的鉴权的商户ID"
                    ]
                ],
            ],
            [
                'is'    => false,
                'data'  => [
                    "name"      => "test1",
                    "status"    => "1",
                    "key"       => "TEST_1",
                    "value"     => "sdd"
                ],
            ],

        ];

    }

    /**
     * @param $is
     * @param $data
     * @desc 执行创建
     * @dataProvider testData
     */
    public function testDoCreate($is, $data){

        if($is === true) {
            $this->post('/admin/system_config/doCreate', $data)
                ->assertRedirectedTo('/admin/system_config', ['message'=>'配置添加成功！']);
        }else{
            $this->post('/admin/system_config/doCreate', $data)
                ->assertHasOldInput();
        }

    }

    /**
     * 编辑视图test
     */
    public function testUpdateView(){
        $this->visit('/admin/system_config/update/29')
            ->see('修改配置');
    }

    public function testDataUpdate(){

        return [
            [
                'is'    => true,
                'data'  => [
                    "id"        => 1,
                    "name"      => "test1".rand(0,99999),
                    "status"    => "1",
                    "key"       => "TEST_1".rand(0,99999),
                    "value"     => "sdd"
                ],
            ],
            [
                'is'    => true,
                'data'  => [
                    "id"            => 2,
                    "name"          => "test2".rand(0,99999),
                    "status"        => "1",
                    "key"           => "TEST_2".rand(0,99999),
                    "value"         => "sdd",
                    "second_key"    => [
                        0 => "INVITE_TIME"
                    ],
                    "second_value"  => [
                        0 => "30"
                    ],
                    "second_des"    => [
                        0 => "融宝的鉴权的商户ID"
                    ]
                ],
            ],
            [
                'is' => false,
                'data' => [
                    "id"        => 1,
                    "name"      => "test1",
                    "status"    => "1",
                    "key"       => "TEST_1",
                    "value"     => "sdd"
                ],
            ],

        ];

    }

    /**
     * @param $is
     * @param $data
     * @desc 执行创建
     * @dataProvider testDataUpdate
     */
    public function testDoUpdate($is, $data){

        if($is === true) {
            $this->post('/admin/system_config/doUpdate', $data)
                ->assertRedirectedTo('/admin/system_config', ['message'=>'配置编辑成功！']);
        }else{
            $this->post('/admin/system_config/doUpdate', $data)
                ->assertHasOldInput();
        }

    }



}