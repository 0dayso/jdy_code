<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/3/2
 * Time: 上午10:09
 */

namespace Tests\Http\Logics\Pay;

use App\Http\Logics\Pay\RechargeLogic;



/**
 * 充值相关 logic
 *
 * Class RechargeLogicTest
 * @package Tests\Http\Logics\pay
 */
class RechargeLogicTest extends \TestCase
{


    /**
     *
     *
     * @return array
     */
    public function rechargeCardsForAppProvider(){
        return [
            [
                'status' => true,
                'data'   => [
                    'id'        => 249446,
                    'version'   => '4.0.0',
                    'client'    => 'android',
                ]
            ],
            [
                'status' => true,
                'data'   => [
                    'id'        => 249446,
                    'version'   => '4.0.0',
                    'client'    => 'android',
                ]
            ]
        ];
    }


    /**
     * @dataProvider rechargeCardsForAppProvider
     *
     * 获取银行卡
     */
    public function testGetRechargeCardsForApp($status, $data){

        $rechargeLogic = new RechargeLogic();

        extract($data);

        $result        = $rechargeLogic->getRechargeCardsForApp($id, $version, $client);

        if($status) {
            $this->assertArrayHasKey('status', $result);
            $this->assertTrue($result['status']);
        }
    }

    /**
     * @dataProvider rechargeCardsForAppProvider
     *
     * APP可充值卡列表返回数据
     */
    public function testGetRechargeBanksForApp($status, $data){
        $rechargeLogic = new RechargeLogic();

        extract($data);

        $result        = $rechargeLogic->getRechargeBanksForApp($version, $client);

        if($status) {
            $this->assertArrayHasKey('status', $result);
            $this->assertTrue($result['status']);
            $this->assertArrayHasKey('data', $result);
            $this->assertNotEmpty($result['data']);
        }
    }

    /**
     *
     *
     * @return array
     */
    public function makeOrderProvider(){
        return [
            [
                'status'    => true,
                'userId'    => 1424408,
                'bankId'    => '1',
                'cardNo'    => '6212260200016857001',
                'cash'      => '4000',
                'payType'   => '1101',
                'client'    => 'ios',
                'version'   => '4.0.0',
            ],
            [
                'status'    => false,
                'userId'    => 24944611111,
                'bankId'    => '11111111',
                'cardNo'    => '6212260200016857001',
                'cash'      => '400',
                'payType'   => '1101',
                'client'    => 'ios',
                'version'   => '4.0.0',
            ]
        ];
    }


    /**
     * 生成订单
     *
     * @dataProvider makeOrderProvider
     */
    public function testMakeOrder($status,$userId,$bankId,$cardNo,$cash,$payType,$client,$version)
    {
        $logic = new RechargeLogic();
        $result = $logic->makeOrder($userId,$bankId,$cardNo,$cash,$payType,$client,$version);

        if($status){
            $this->assertEquals(200, $result['code']);
        }else{
            $this->assertEquals(500, $result['code']);
        }
    }


    /**
     *
     *
     * @return array
     */
    public function giveUpRechargeProvider(){
        return [
            [
                'status' => false,
                'id'    => 2494460000,
            ]
        ];
    }


    /**
     * 放弃支付
     *
     * @dataProvider giveUpRechargeProvider
     */
    public function testGiveUpRecharge($status, $orderId){
        $logic		= new RechargeLogic();
        $result		= $logic->giveUpRecharge($orderId);
        if($status){

        }else {
            $this->assertArrayHasKey('code', $result);
            $this->assertEquals(500,$result['code']);
        }
    }



}