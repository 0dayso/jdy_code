<?php
/**
 * User: zhangshuang
 * Date: 16/5/4
 * Time: 16:02
 * Desc: 绑卡、未绑卡用户限额列表
 */

use GuzzleHttp\Client;

class GuzzleHttpTest extends TestCase{

    /**
     * @doc http://docs.guzzlephp.org/en/latest/quickstart.html
     * @dataProvider urlList
     */
    public function testUrlRequest($url, $type, $textContain, $params = []) {
        
        $client = new GuzzleHttp\Client();

        if(!empty($params)) {
            $params = ['form_params' => $params];
        }
        $response = $client->request($type, $url, $params);
        
        $body = (string)$response->getBody();
        
        $this->assertContains($textContain, $body);
    }
    
    public function urlList() {
        return [
            ['http://test.zjmainstay.cn', 'get', 'Zjmainstay'],
            ['http://www.9douyu.com', 'get', '九斗鱼'],
            ['http://www.zjmainstay.cn', 'post', 'Search for', [
                'searchword' => 'PHP',
                'task' => 'search',
                'option' => 'com_search',
                'Itemid' => '482',
                ]
            ],
        ];
    }

}