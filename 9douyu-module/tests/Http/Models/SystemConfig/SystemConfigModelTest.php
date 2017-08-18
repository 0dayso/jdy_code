<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/21
 * Time: 下午4:17
 */
namespace tests\Http\Models\SystemConfig;

use App\Http\Models\SystemConfig\SystemConfigModel;

class SystemConfigModelTest extends \TestCase
{

    public function testGetConfig()
    {

        $key = 'REGISTER_AWARD';

        dump(SystemConfigModel::getConfig($key));

        $key .= '.START_TIME';

        dump(SystemConfigModel::getConfig($key));

    }

    public function dataTest(){

        return [
            [
                '1111123123rrrr',
                'qqq',
            ],

            [
                '1111123123',
                [
                    'test'=>'test',
                    'test_1'=>'test_1',
                ],
            ]

        ];

    }

    /**
     * @param $key
     * @param $data
     * @dataProvider dataTest
     */
    public function testDoUpdateByKey($key, $data)
    {

        try {

            $model = new SystemConfigModel();

            $res = $model -> doUpdateByKey($key, $data);

            $this ->assertEquals(1,$res);

        }catch(\Exception $e){

            dump($e->getMessage(),$e->getCode(),$e->getFile(),$e->getLine());

        }


    }


}