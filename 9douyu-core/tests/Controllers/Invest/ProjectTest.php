<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/5/4
 * Time: 下午4:35
 * Desc: 定期投资
 */

use \Ares333\CurlMulti\Core;

class ProjectInvestTest extends TestCase
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
        $url = "http://test-core.9douyu.com/invest/project";

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
            ),
            'args' => $postData,
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
            [7,123,'7120dbcb0bbabc24b32174ddd0a02a96',1,1,10000],
            [8,123,'7120dbcb0bbabc24b32174ddd0a02a96',1,1,10],
        );

    }

    public function resultCallback($response, $args){
        $result = json_decode($response['content']);
        if($args['case'] < 8 ){
            echo $args['case'].'-'.$result->code.'-'.$result->msg."\n\r";
            $this->assertEquals('500',$result->code);
        }else{
            echo $args['case'].'-'.$result->code.'-'.$result->msg.'-invest_id:'.$result->data->invest_id."\n\r";
            $this->assertEquals('200',$result->code);
        }
    }

}