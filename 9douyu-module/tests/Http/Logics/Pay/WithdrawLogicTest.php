<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/3/10
 * Time: 下午2:07
 */

namespace Tests\Http\Logics\Pay;

use App\Http\Logics\Pay\WithdrawLogic;

class WithdrawLogicTest extends \TestCase
{


    /**
     *
     * @return array
     */
    public function bindWithdrawCardProvider(){
        return [
            [
                'status' => false,
                'data'   => [
                    'id'        => 249446,
                    'bankId'   => '8',
                    'cardNo'    => '6226220125673771',
                ]
            ],
            [
                'status' => true,
                'data'   => [
                    'id'        => 24941,
                    'bankId'   => '8',
                    'cardNo'    => '622622012567377122',
                ]
            ]
        ];
    }


    /**
     * 绑定提现银行卡
     *
     * @dataProvider bindWithdrawCardProvider
     *
     * @param $status
     * @param $data
     */
    public function testBindWithdrawCard($status, $data){

		$logic = new WithdrawLogic();

		extract($data);

		$result = $logic->bindWithdrawCard($id,$bankId,$cardNo);

		if($status){
		    $this->assertEquals(500, $result['code']);
        }else{
            $this->assertEquals(200, $result['code']);
        }
    }


    /**
     * 获取可提现银行卡
     */
    public function testGetWithdrawBanksForApp(){
        $logic  = new WithdrawLogic();
        $result = $logic->getWithdrawBanksForApp();

        $this->assertEquals(200, $result['code']);
        $this->assertArrayHasKey('data', $result);
        $this->assertNotEmpty($result['data']);
    }


    /**
     *
     *
     * @return array
     */
    public function getWithdrawCardForAppProvider(){
        return [
            [
                'status' => false,
                'id'     => 1111111111111,
            ],
            [
                'status' => true,
                'id'     => 249446,
            ]
        ];
    }


    /**
     *
     * 获取用户提现银行卡
     *
     * @dataProvider getWithdrawCardForAppProvider
     */
    public function testGetWithdrawCardForApp($status, $userId){
        $logic  = new WithdrawLogic();

        $result = $logic->getWithdrawCardForApp($userId);
        if($status){
            $this->assertEquals(200, $result['code']);
        }else{
            $this->assertEquals(200, $result['code']);
        }

    }



}