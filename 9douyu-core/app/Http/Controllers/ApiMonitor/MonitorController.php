<?php
/**
 * User: Zjmainstay
 * Date: 16/4/27
 * Desc: Api可用性监控
 */

namespace App\Http\Controllers\ApiMonitor;

use App\Http\Controllers\Controller;
use App\Http\Logics\Auth\SecurityAuthLogic;
use Ares333\CurlMulti\Core as CurlMulti;

class MonitorController extends Controller{
    public function testAllApi() {
        $data = [
            'event_name'    => 'App\\Events\\Api\\User\\RegisterSuccessEvent',
            'notify_url'    => 'http://test.com/core_9douyu_notify.php',
            'name'          => '123',
        ];
        $info = SecurityAuthLogic::getInfoByName($data['name']);
        $data['sign'] = SecurityAuthLogic::getMd5Sign($info['name'], $info['secret_key'], json_encode($data));
        $this->_doTestApi('http://core.9douyu.com/event/register', $data);
    }
    
    protected function _doTestApi($url, $postData, $curlOpts = array()) {
        $curl = new CurlMulti();
        $opt  = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
        ];
        
        $postOpt = $curlOpts + $opt;
        
        $curl->add([
            'url'       => $url,
            'args'      => [
                'post_data' => $postData,
                'post_opt'  => $postOpt,
            ],
            'opt'       => $postOpt,
        ], function($response, $args){
            $result = json_decode($response['content'], true);
            self::returnJson($result);
        }, function($err, $args){
            self::returnJson($err);
        });
        $curl->start();
    }
}
