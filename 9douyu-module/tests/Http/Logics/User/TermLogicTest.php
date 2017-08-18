<?php
/**
 * @desc    我的资产-定期资产
 * @date    2017-03-10
 * @author  @linglu
 *
 */

namespace Tests\Http\Logics\User;
use App\Http\Logics\Invest\TermLogic;

class TermLogicTest extends \TestCase
{
    /**
     * @desc    获取我的资产-列表
     */
    public function testData(){
        return [
            //持有中-有数据
            [
                'is'   => true,
                'data' => [
                    "user_id"   => 1399201,
                    "type"      => 'investing',
                    "page"      => 1,
                    "size"      => 5,
                ]
            ],
            //持有中-无数据
            [
                'is'   => false,
                'data' => [
                    "user_id"   => 85524,
                    "type"      => 'investing',
                    "page"      => 1,
                    "size"      => 5,
                ]
            ],

            //已完结-有数据
            [
                'is'   => true,
                'data' => [
                    "user_id"   => 1399201,
                    "type"      => 'finish',
                    "page"      => 1,
                    "size"      => 5,
                ]
            ],
            //已完结-无数据
            [
                'is'   => false,
                'data' => [
                    "user_id"   => 1426124,
                    "type"      => 'finish',
                    "page"      => 1,
                    "size"      => 5,
                ]
            ],
            //转让中-有数据
            [
                'is'   => true,
                'data' => [
                    "user_id"   => 1399201,
                    "type"      => 'assignment',
                    "page"      => 1,
                    "size"      => 5,
                ]
            ],
            //转让中-无数据
            [
                'is'   => false,
                'data' => [
                    "user_id"   => 1426124,
                    "type"      => 'assignment',
                    "page"      => 1,
                    "size"      => 5,
                ]
            ],
        ];
    }

    /**
     * @desc    获取我的资产-详情
     */
    public function testDetail(){
        return [
            //对应的用户Id-投资Id
            [
                'is'   => true,
                'data' => [
                    "user_id"   => '85523',
                    "invest_id" => '250756',
                ]
            ],
            //不对应的用户id-投资Id
            [
                'is'   => false,
                'data' => [
                    "user_id"   => '85523',
                    "invest_id" => '265958',
                ]
            ],
            //用户ID、用户投资ID-丢失
            [
                'is'   => false,
                'data' => [
                    "user_id"   => '',
                    "invest_id" => '',
                ]
            ],
            //用户ID-丢失
            [
                'is'   => false,
                'data' => [
                    "user_id"   => '',
                    "invest_id" => '265958',
                ]
            ],
            //用户投资ID-丢失
            [
                'is'   => false,
                'data' => [
                    "user_id"   => '85523',
                    "invest_id" => '',
                ]
            ],

        ];
    }

    /**
     * @param   $userId
     * @param   $type
     * @param   $page
     * @param   $size
     * @dataProvider testData
     */
    public function testAppV4UserTermRecord($is, $data){

        $termLogic  = new TermLogic();

        $resData    = $termLogic->appV4UserTermRecord($data['user_id'],$data['type'],$data['page'],$data['size']);

        print_r($resData);

        if($is === true){

            $this->assertNotEmpty($resData['record']);

        }else{
            $this->assertEmpty($resData['record']);
        }

    }

    /**
     * @param   $userId
     * @param   $investId
     * @dataProvider testDetail
     *
     */
    public function testAppV4UserTermDetail($is, $data){

        $termLogic  = new TermLogic();

        $resData    = $termLogic->appV4UserTermDetail($data['user_id'],$data['invest_id']);

        print_r($resData);

        if($is === true) {
            $this->assertTrue($resData['status']);
        }else{
            $this->assertNotTrue($resData['status']);
        }

    }
}