<?php

namespace Tests\Http\Dbs\User;
use App\Http\Dbs\User\OAuthAccessTokenDb;

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/10/14
 * Time: 下午6:29
 */
class OAuthAccessTokenDbTest extends \TestCase
{
    /**
     * 测试频繁点击延时过期
     */
    public function testUpdateExpires(){
        $token  = '0f8e566cd57915670c6f222a8bfa2f4d55ff2d3f';
        $return = OAuthAccessTokenDb::updateExpires($token);
        //dd($return);
    }
}