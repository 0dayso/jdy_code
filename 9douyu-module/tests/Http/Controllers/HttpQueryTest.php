<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/14
 * Time: 下午5:16
 */

namespace Tests\Http\Controllers;

use App\Http\Models\Common\HttpQuery;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Log;

class HttpQueryTest extends \TestCase
{
    //新获取的access_token信息
    protected $_tokenInfo = [];
    
    public function setUp() {
        parent::setUp();
        $this->_tokenInfo = HttpQuery::corePost('/oauth/access_token', $this->getClientInfo());
    }
    protected function getCoreAccessToken() {
        return SystemConfigModel::getConfig('ACCESS_TOKEN.CORE');
    }
    
    protected function getClientInfo() {
        return [
            'grant_type'        => 'client_credentials',
            'client_id'         => env('OAUTH_CORE_CLIENT_ID'),
            'client_secret'     => env('OAUTH_CORE_CLIENT_SECRET'),
        ];
    }

    //测试获取内核access_token，并使用该token测试接口可用性
    public function testGetCoreAccessToken() {
        $this->assertArrayHasKey("access_token", $this->_tokenInfo);
    }
    
    public function testNewGetCoreAccessTokenCanUse() {
        //测试access_token可用
        $authorizationStr = "{$this->_tokenInfo['token_type']} {$this->_tokenInfo['access_token']}";
        $res = HttpQuery::corePost('/access_token_ok', [], $authorizationStr);
        $this->assertEquals(true, $res["status"]);
    }

    //模拟使用错误access_token得到异常结果
    public function testCorePostErrorAccessToken() {
        $res = HttpQuery::corePost('/access_token_ok', [], 'ErrorAccessToken');

        $this->assertEquals(false, $res["status"]);
    }

    //通过存储的 access_token 测试接口
    public function testCorePostByConfigAccessToken() {
        $res = HttpQuery::corePost('/access_token_ok', [], $this->getCoreAccessToken());

        $this->assertEquals(true, $res["status"]);
    }

    public function testUpdateCoreAccessToken() {
        //更新access_token
        $systemConfigModel = new SystemConfigModel();

        $authorizationStr = "{$this->_tokenInfo['token_type']} {$this->_tokenInfo['access_token']}";
        $res = $systemConfigModel->doUpdateByKey('ACCESS_TOKEN_CORE', $authorizationStr);

        $this->assertEquals(true, $res);
    }
}
