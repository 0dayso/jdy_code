<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/3/8
 * Time: 下午3:25
 */

namespace Tests\Http\Logics\User;
use App\Http\Logics\User\UserLogic;

/**
 * 实名认证+绑卡+设置交易密码
 *
 * Class UserLogicTest
 * @package Tests\Http\Logics\User
 */
class UserLogicTest extends \TestCase
{

    /**
     * 发送手机验证码数据提供器
     *
     * @return array
     */
    public function doVerifyTradingPasswordProvider()
    {
        return [
            [
                'status'          => true,
                'userId'          => '249446',
                'name'            => '周诚',
                'cardNo'          => '6226220125673771',
                'idCard'          => '110103198504030016',
                'from'            => 'android',
                'tradingPassword' => '123qwe',
                'bankId'          => 8,
            ],
            [
                'status'          => false,
                'userId'          => '249446',
                'name'            => '周诚',
                'cardNo'          => '6226220125673771',
                'idCard'          => '11010319850403001',
                'from'            => 'android',
                'tradingPassword' => '123qwe',
                'bankId'          => 8,
            ],
        ];
    }

    /**
     * @dataProvider doVerifyTradingPasswordProvider
     *
     * 实名+绑卡+设置交易密码
     */
    public function testDoVerifyTradingPassword($status, $userId, $name, $cardNo, $idCard, $from, $tradingPassword, $bankId)
    {
        $logic            = new UserLogic();
        $result           = $logic->doVerifyTradingPassword($userId, $name, $cardNo, $idCard, $from, $tradingPassword, $bankId);

        if($status){
            $this->assertEquals(200, $result['code']);
        }else{
            $this->assertEquals(4000, $result['code']);
        }
    }
}