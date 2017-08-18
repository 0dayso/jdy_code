<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/4
 * Time: 下午3:14
 */

use App\Http\Logics\User\RegisterLogic;

use App\Lang\LangModel;

use App\Http\Logics\User\GetLogic;

//todo depends function [tip function 在有数据提供器传参时候失效 why]
/**
 * 测试注册、激活、获取用户数据
 * Class RegisterTest
 */
class MemberTest extends TestCase{

    protected static $userIds = [];

    protected static $phones  = [];

    /**
     * 错误的用户ID
     * @return array
     */
    public function getErrorUserId(){
        return [
            ['id'=> -1],
            ['id'=> 'a'],
            ['id'=> 'asdad'],
            ['id'=> '00'],
            ['id'=> 0],
            ['id'=>9999999999999999999999999999999]
        ];
    }

    /**
     * 模拟手机号错误的数据
     *
     * @return array
     */
    public function phoneLengthErrorList(){
        return [
            ['phone'=>1111111111, 'password'=>md5(1)],//10
            ['phone'=>111111111111, 'password'=>md5(1)],//12
        ];
    }

    /**
     * 模拟密码错误的数据
     *
     * @return array
     */
    public function passwordLengthList(){
        return [
            ['phone'=>15201114661, 'password'=>'aaaaabbbbbcccccdddddeeeeefffffg'],//31
            ['phone'=>15201124661, 'password'=>'aaaaabbbbbcccccdddddeeeeefffffaaaaabbbbbcccccdddddeeeeefffffggggg1'],//66
        ];
    }

    /**
     * @desc 生成n个随机账号
     * @param int $num 生成的账号号数
     * @return array
     */
    private function randUser($num = 1){
        $result      = [];
        //手机号2-3为数组
        $numberPlace = array(30,31,32,33,34,35,36,37,38,39,50,51,58,59,89);

        for ($i = 0; $i < $num; $i++){
            $mobile = 1;
            $mobile .= $numberPlace[rand(0,count($numberPlace)-1)];
            $mobile .= str_pad(rand(0,99999999),8,0,STR_PAD_LEFT);
            $result[$i]['phone']    = $mobile;
            $result[$i]['password_hash'] = md5($mobile);
        }
        return $result;
    }

    /**
     * 随机模拟10个有效的用户 注册数据
     *
     * @return array
     */
    public function validatedUserList(){
        return $this->randUser(2);
    }

    /**
     * 测试有效手机号有效密码 成功注册 || 有数据
     * @param $phone
     * @param $password
     *
     * @dataProvider validatedUserList
     * @return array
     */
    public function testValidatedUserRegister($phone, $password){
        $RegisterLogic = new RegisterLogic();
        $logicReturn   = $RegisterLogic->create(['phone'=>$phone, 'password_hash'=>$password]);

        $this->assertNotEmpty($logicReturn);
        if($logicReturn['code'] !=200){
            $this->assertTrue(in_array($logicReturn['msg'], [LangModel::ERROR_USER_PHONE_REPEAT, LangModel::ERROR_USER_PHONE_ACTIVE]));
        }else{
            self::$phones[] = $phone;
            self::$userIds[]= $logicReturn['data']['id'];
        }
    }

    /**
     * 测试无效手机号长度
     * @param $phone
     * @param $password
     *
     * @dataProvider phoneLengthErrorList
     */
    public function testPhoneLengthErrorRegister($phone, $password){
        $RegisterLogic = new RegisterLogic();
        $logicReturn   = $RegisterLogic->create(['phone'=>$phone, 'password_hash'=>$password]);

        $this->assertNotEmpty($logicReturn);

        $this->assertEquals($logicReturn['msg'], LangModel::ERROR_USER_PHONE_LENGTH);
    }


    /**
     * 测试无效密码长度
     * @param $phone
     * @param $password
     *
     * @dataProvider passwordLengthList
     */
    public function testPasswordLengthErrorRegister($phone, $password){
        $RegisterLogic = new RegisterLogic();
        $logicReturn   = $RegisterLogic->create(['phone'=>$phone, 'password_hash'=>$password]);

        $this->assertNotEmpty($logicReturn);

        $this->assertEquals($logicReturn['msg'], LangModel::ERROR_USER_PASSWORD_LENGTH);
    }



    /**
     * 测试刚刚注册成功的用户
     */
    public function testDoActive(){
        $this->assertNotEmpty(self::$userIds);
        if(!empty(self::$userIds)) {
            foreach (self::$userIds as $id) {
                $RegisterLogic = new RegisterLogic();
                $logicReturn = $RegisterLogic->doActivate($id);
                $this->assertNotEmpty($logicReturn);
                $this->assertEquals($logicReturn['code'], 200);
            }
        }
    }


    /**
     * 测试激活 错误的userId
     *
     * @dataProvider getErrorUserId
     */
    public function testDoActiveErrorId($id = 0){

        $RegisterLogic = new RegisterLogic();
        $logicReturn = $RegisterLogic->doActivate($id);

        $this->assertNotEmpty($logicReturn);

        $this->assertTrue(in_array($logicReturn['msg'], [LangModel::ERROR_INVALID_USER_ID, LangModel::ERROR_USER_DO_ACTIVE]));
    }

    /**
     * 无效手机号 获取数据测试
     *
     * @dataProvider phoneLengthErrorList
     */
    public function testPhoneErrorGet($phone, $password){
        $GetLogic    = new GetLogic();
        $logicReturn = $GetLogic->getBaseUserInfo($phone);

        $this->assertNotEmpty($logicReturn);

        $this->assertEquals($logicReturn['msg'], LangModel::ERROR_USER_PHONE_LENGTH);
    }

    /**
     * 测试获取有效的基本信息
     */
    public function testGet(){
        $this->assertNotEmpty(self::$phones);
        if(!empty(self::$phones)) {
            foreach (self::$phones as $phone) {
                $GetLogic = new GetLogic();
                $logicReturn = $GetLogic->getBaseUserInfo($phone);
                $this->assertNotEmpty($logicReturn);
                $this->assertArrayHasKey('phone', $logicReturn['data']);
                $this->assertArrayHasKey('status', $logicReturn['data']);
                $this->assertArrayHasKey('password', $logicReturn['data']);
            }
        }
    }

}