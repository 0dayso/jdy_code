<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/7
 * Time: 下午5:36
 */

namespace Tests\Http\Logics\Project;

use App\Http\Logics\CreditAssign\CreditAssignLogic;
use App\Http\Models\Common\CoreApi\UserModel;

class CreditAssignLogicTest extends \TestCase{


    public function dataArr(){

        return [
            [
                'result'    => true,
                'invest_id' => 278671,
                'cash'      => 10000.00,
                'td_password' => 'qwe123',
                'user_info' => ['trading_password' => '75ffe5488ecc315017681d53b5c0b0b8:27c4a49191e5aa0272bbacb29bd57']
            ]
        ];

    }

    public function dataInvestArr(){

        return [
            [
                'result'    => true,
                'invest_id' => 502,
                'cash'      => 10000.00,
                'td_password'    => 'qwe123',
                'user_info' => ['id' => 250062, 'trading_password' => '75ffe5488ecc315017681d53b5c0b0b8:27c4a49191e5aa0272bbacb29bd57'],
                'client' => 'android'
            ]
        ];

    }


    /**
     * @param $result
     * @param $projectId
     * @param $cash
     * @param $tradingPassword
     * @param $userInfo
     * @param $client
     * @dataProvider dataInvestArr
     * @desc 投资
     */
    public function testDoInvest($result, $projectId,$cash,$tradingPassword,$userInfo, $client){

        $logic = new CreditAssignLogic();

        $res = $logic->doInvest($projectId,$cash,$tradingPassword,$userInfo, $client);

        $this->assertEquals($result, $res['status']);

    }

    /**
     * @param $result
     * @param $investId
     * @param $cash
     * @param $tradingPassword
     * @param $userInfo
     * @dataProvider dataArr
     */
    public function testCreate($result, $investId,$cash,$tradingPassword,$userInfo){

        $logic = new CreditAssignLogic();

        //$res = $logic->userDoCreditAssign($investId, $cash, $tradingPassword, $userInfo);

        //$this->assertEquals($result, $res['status']);

    }

}