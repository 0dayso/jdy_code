<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 下午6:27
 */


class InvestTest extends TestCase
{

    /**
     * 投资定期
     */
    public function testProject()
    {

        $log = [
            'invest_id'     => 1,
            'user_id'       => 2,
            'project_id'    => 3,
            'cash'          => 4
        ];

        //触发事件
        Event::fire('App\Events\Api\Invest\ProjectSuccessEvent', [$log]);

        /*$curl = new \Curl\Curl();

        $url = "http://lumen.9douyu.com/invest/project";

        $postData = [
            "app_id" => 1,
            "sign" => '7120dbcb0bbabc24b32174ddd0a02a96',
            "user_id" => 1,
            "project_id" => 1,
            'cash' => -100
        ];

        $curl->post($url, $postData);

        print_r(json_decode($curl->response));*/

        /*

        $curl = new \Ares333\CurlMulti\Core();



        $curl->add(array (
            'url' => $url,
            'opt' => array(
                CURLOPT_POST=>true,
                CURLOPT_POSTFIELDS => $post_data,
            ),
            'args' => array (
                'user_id' => $post_data['user_id'],
            )
        ), array($this, 'resultCallback'));

        $curl->start();*/

    }




}
