<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 上午11:22
 */

namespace Tests\Http\Logics\User;

use App\Http\Logics\User\TokenLogic;

class TokenLogicTest extends \TestCase{


    /**
     * 删除过期数据
     */
    public function testDeleteExpire(){

        TokenLogic::deleteExpire();

        \App\Http\Dbs\User\OAuthAccessTokenDb::getSql();
    }
}