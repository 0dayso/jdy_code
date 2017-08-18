<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 下午6:27
 * Desc: 零钱计划投资
 */

use \Ares333\CurlMulti\Core;

class CurrentTest extends TestCase
{


    /**
     * @param name
     * @param sign
     * @param user_id
     * @param project_id
     * @param cash
     * @dataProvider additionProvider
     */
    public function testInvest( $case, $name, $sign, $user_id, $project_id, $cash ){

        $curl = new Core();
        $url = "http://lumen.9douyu.com/invest/current";

        $postData = [
            'case'       => $case,
            'name'       => $name,
            'sign'       => $sign,
            'user_id'    => $user_id,
            'project_id' => $project_id,
            'cash'       => $cash,

        ];

        $curl->add(array (
            'url' => $url,
            'opt' => array(
                CURLOPT_POST=>true,
                CURLOPT_POSTFIELDS => $postData,
            )
        ), array($this, 'resultCallback'));

        $curl->start();

    }

    public function additionProvider(){

        return array(
            [1,1,'','','',''],
            [2,123,'7120dbcb0bbabc24b32174ddd0a02a96',2,'',''],
            [3,123,'7120dbcb0bbabc24b32174ddd0a02a96',3,'',10],
            [4,123,'7120dbcb0bbabc24b32174ddd0a02a96',3,10,100000000000],
            [5,123,'7120dbcb0bbabc24b32174ddd0a02a96',1,10,100000000000],
            [6,123,'7120dbcb0bbabc24b32174ddd0a02a96',1,10,100],
            [7,123,'7120dbcb0bbabc24b32174ddd0a02a96',1,1,10],
        );

    }

    public function resultCallback($response){

        $result = json_decode($response['content']);

        //断言返回值显示错误
        $this->assertEquals('200',$result->code, $result->msg);

    }




}
