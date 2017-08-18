<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/28
 * Time: 下午4:56
 */

namespace Tests\Http\Controllers\App;

class BonusTest extends \TestCase
{

    public function testData(){

        return [

            [
                [
                    'type'=>1,
                    'client' => 'ios',
                ]
            ],
            [
                [
                    'type'=>1,
                    'client' => 'android',
                ]
            ],
        ];

    }


    public function testProject(){

        return [

            [
                [
                    'project_id'=>2,
                    'client' => 'ios',
                ]
            ],
            [
                [
                    'project_id'=>3,
                    'client' => 'android',
                ]
            ],
        ];

    }

    public function testCurrent(){

        return [

            [
                [
                    'client' => 'ios',
                ]
            ],
            [
                [
                    'client' => 'android',
                ]
            ],
        ];

    }

    /**
     * @param $data
     * @dataProvider testProject
     */
    public function testProjectInvestBonusList($data){

        $this -> post('user_usable_bonus', $data);

    }

    /**
     * @param $data
     * @dataProvider testCurrent
     */
    public function testCurrentInvestBonusList($data){

        //$this -> post('user_current_bonus_list', $data);

    }

    /**
     * @param $data
     * @dataProvider testData
     */
    public function testAppList($data){

        //$this -> post('user_bonus', $data);

    }

}