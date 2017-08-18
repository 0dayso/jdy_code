<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date:16/09/27
 */
//namespace Tests\Logics\User;

class BirthdayUserTest extends TestCase{
    /**
     * @desc 当日生日的用户
     * @throws Exception
     */
    public function testUserBirthday(){
         /*$logic = new \App\Http\Logics\User\GetLogic();

         $users = $logic->getBirthdayUser();*/

        $logic = new \App\Http\Logics\Refund\RefundRecordLogic();

        $users = $logic->getTodayRefundUser();


        dd($users['data'][0]);
    }
}