<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/3/10
 * Time: 下午2:47
 */

namespace Tests\Http\Logics\Order;

use  App\Http\Logics\Order\WithdrawLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\LoginLogic;
use App\Http\Logics\User\SessionLogic;
use App\Http\Logics\User\TokenLogic;

class WithdrawLogicTest extends \TestCase
{


    protected function setUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'post';
        $_SERVER['REMOTE_ADDR']    = '127.0.0.1';

        parent::setUp();
    }

    /**
     *
     * 提现
     *
     * @return array
     */
    public function preWithdrawProvider(){
        return [
            [
                'status' => true,
                'id'        => 249446,
                'cash'    => 1
            ],
            [
                'status' => false,
                'id'        => 249446111111111,
                'cash'    => 1
            ]
        ];
    }


    /**
     * 提现预览
     *
     * @dataProvider preWithdrawProvider
     *
     */
    public function testPreWithdraw($status, $id, $cash){
        $logic      = new WithdrawLogic();
        $result     = $logic->preWithdraw($id,$cash);
        if($status){
            $this->assertEquals(200, $result['code']);
        }else{
            $this->assertEquals(500, $result['code']);
        }
    }


    /**
     *
     * 提现
     *
     * @return array
     */
    public function doWithdrawProvider(){
        return [
            [
                'status'            => true,
                'data'      => [
                    'user_id'           => 249446,
                    'cash'              => 100,
                    'trading_password'  => 'qwe123',
                    'bank_card_id'      => '5257',
                    'from'              => 'android',
                ],
            ],
//            [
//                'status'            => false,
//                'data'      => [
//                    'user_id'           => 2494469999999,
//                    'cash'              => 1,
//                    'trading_password'  => 'qwe123',
//                    'bank_card_id'      => '5257',
//                    'from'              => 'android',
//                ],
//            ],
        ];
    }

    /**
     * @dataProvider doWithdrawProvider
     *
     * @param $status
     * @param $data
     */
    public function testDoWithdraw($status, $data){
        $client = 'android';
        RequestSourceLogic::setSource($client);
        $this->assertEquals(RequestSourceLogic::getSource(), $client);

        $loginLogic                = new LoginLogic();

        $logicReturn               = $loginLogic->in([
            'factor'     => '123',
            'username'   => '15201594661',
            'password'   => '123qwe',
        ]);

        $this->assertTrue($logicReturn['status']);

        $tokenLogic = new TokenLogic();
        $tokenLogic->setSession($logicReturn['data']['access_token'], '123', '127.0.0.1');

        $logic       = new WithdrawLogic();

        $result      = $logic->doWithdraw($data);

        if($status){
            $this->assertEquals(200, $result['code']);
        }else{
            $this->assertEquals(500, $result['code']);
        }
    }

}