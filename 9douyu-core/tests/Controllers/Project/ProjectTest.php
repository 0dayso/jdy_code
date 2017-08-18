<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/1
 * Time: 下午8:08
 * Desc: 项目测试用例 47条
 */

use \Ares333\CurlMulti\Core;

class ProjectTest extends TestCase
{

    /**
     * @param $name
     * @param $totalAmount
     * @param $investDays
     * @param $investTime
     * @param $refundType
     * @param $type
     * @param $baseProfit
     * @param $afterProfit
     * @param $productLine
     * @param $createdBy
     * @param $publishTime
     * @param $result
     * @throws \Ares333\CurlMulti\Exception
     * @dataProvider additionProvider
     */
    public function testCreate( $name, $totalAmount, $investDays, $investTime, $refundType, $type, $baseProfit, $afterProfit, $productLine, $createdBy, $publishTime, $result ){

        $curl = new Core();
        $url = "http://test-core.9douyu.com/create/project";

        $postData = [
            'name'              => $name,
            'total_amount'      => $totalAmount,
            'invest_days'       => $investDays,
            'invest_time'       => $investTime,
            'refund_type'       => $refundType,
            'type'              => $type,
            'product_line'      => $productLine,
            'base_rate'         => $baseProfit,
            'after_rate'        => $afterProfit,
            'profit_percentage' => $baseProfit+$afterProfit,
            'created_by'        => $createdBy,
            'publish_time'      => $publishTime,
            'results'           => $result,

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
            ['九省心',100,3,29,10,1,1,2,100,1,"2016-06-02 00:00:00",200],
            ['九省心',100,3,3,20,3,3,2,100,1,"2016-06-02 00:00:00",200],
            ['九省心',100,3,6,20,6,6,2,100,1,"2016-06-02 00:00:00",200],
            ['九省心',100,3,12,20,12,12,2,100,1,"2016-06-02 00:00:00",200],
            ['九安心',100,3,58,10,0,24,2,200,1,"2016-06-02 00:00:00",200],
            ['闪电付息',100,3,29,30,3,1,2,300,1,"2016-06-02 00:00:00",200],
            ['闪电付息',100,3,3,30,6,3,2,300,1,"2016-06-02 00:00:00",200],
            ['闪电付息',100,3,6,30,12,6,2,300,1,"2016-06-02 00:00:00",200],
            ['闪电付息',100,3,12,30,1,12,2,300,1,"2016-06-02 00:00:00",200],
            ['',100,3,29,10,500,1,2,100,1,"2016-06-02 00:00:00",500],
            ['九省心',0,3,29,10,1,1,2,100,1,"2016-06-02 00:00:00",500],
            ['九省心',10,0,29,10,1,1,2,100,1,"2016-06-02 00:00:00",500],
            ['九省心',10,1,0,10,500,1,2,100,1,"2016-06-02 00:00:00",500],
            ['九省心',100,3,29,11,500,1,2,100,1,"2016-06-02 00:00:00",500],
            ['九省心',100,3,29,10,501,1,2,100,1,"2016-06-02 00:00:00",500],
            ['九省心',100,3,29,10,500,0,2,100,1,"2016-06-02 00:00:00",500],
            ['九省心',100,3,29,10,1,1,0,100,1,"2016-06-02 00:00:00",200],
            ['九省心',100,3,29,10,500,1,0,101,1,"2016-06-02 00:00:00",500],
            ['九省心',100,3,29,10,1,1,0,100,0,"2016-06-02 00:00:00",200],
            ['九省心',100,3,29,10,500,1,0,100,0,"",500],
        );

    }

    /**
     * @param $id
     * @param $name
     * @param $totalAmount
     * @param $investDays
     * @param $investTime
     * @param $refundType
     * @param $type
     * @param $baseProfit
     * @param $afterProfit
     * @param $productLine
     * @param $createdBy
     * @param $publishTime
     * @param $result
     * @return bool
     * @throws \Exception
     * @dataProvider dataUpdate
     */
    public function testUpdate( $id, $name, $totalAmount, $investDays, $investTime, $refundType, $type, $baseProfit, $afterProfit, $productLine, $createdBy, $publishTime, $result)
    {
        $curl = new Core();
        $url = "http://test-core.9douyu.com/update/project";

        $postData = [
            'id'                => $id,
            'name'              => $name,
            'total_amount'      => $totalAmount,
            'invest_days'       => $investDays,
            'invest_time'       => $investTime,
            'refund_type'       => $refundType,
            'type'              => $type,
            'product_line'      => $productLine,
            'base_rate'         => $baseProfit,
            'after_rate'        => $afterProfit,
            'profit_percentage' => $baseProfit+$afterProfit,
            'created_by'        => $createdBy,
            'publish_time'      => $publishTime,
            'results'           => $result,

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

    public function dataUpdate(){

        return array(
            [1,'九省心1',100,3,29,10,1,4,2,100,1,"2016-06-02 00:00:00",200],
            [2,'九省心2',100,3,3,20,3,6,2,100,1,"2016-06-02 00:00:00",200],
            [3,'九省心3',100,3,6,20,6,6,2,100,1,"2016-06-02 00:00:00",200],
            [4,'九省心',100,3,12,20,12,12,2,100,1,"2016-06-02 00:00:00",200],
            [5,'九安心',100,3,58,10,0,24,2,200,1,"2016-06-02 00:00:00",200],
            [6,'闪电付息',100,3,29,30,3,1,2,300,1,"2016-06-02 00:00:00",200],
            [7,'闪电付息',100,3,3,30,6,3,2,300,1,"2016-06-02 00:00:00",200],
            [8,'闪电付息',100,3,6,30,12,6,2,300,1,"2016-06-02 00:00:00",200],
            [9,'闪电付息',100,3,12,30,1,12,2,300,1,"2016-06-02 00:00:00",200],
            [10,'',100,3,29,10,500,1,2,100,1,"2016-06-02 00:00:00",500],
            [11,'九省心',0,3,29,10,1,1,2,100,1,"2016-06-02 00:00:00",500],
            [12,'九省心',10,0,29,10,1,1,2,100,1,"2016-06-02 00:00:00",500],
            [13,'九省心',10,1,0,10,500,1,2,100,1,"2016-06-02 00:00:00",500],
            [14,'九省心',100,3,29,11,500,1,2,100,1,"2016-06-02 00:00:00",500],
            [15,'九省心',100,3,29,10,501,1,2,100,1,"2016-06-02 00:00:00",500],
            [16,'九省心',100,3,29,10,500,0,2,100,1,"2016-06-02 00:00:00",500],
            [17,'九省心',100,3,29,10,1,1,0,100,1,"2016-06-02 00:00:00",500],
            [18,'九省心',100,3,29,10,500,1,0,101,1,"2016-06-02 00:00:00",500],
            [19,'九省心',100,3,29,10,1,1,0,100,0,"2016-06-02 00:00:00",500],
            [20,'九省心',100,3,29,10,500,1,0,100,0,"",500],
        );

    }

    /**
     * @param $id
     * @param $result
     * @desc 删除项目
     * @dataProvider delData
     */
    public function testDel($id,$result){

        $curl = new Core();
        $url = "http://test-core.9douyu.com/delete/project";

        $postData = [
            'id'                => $id,
            'results'           => $result,

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

    /**
     * @return array
     */
    public function delData(){

        return [
            [6,200],
            [7,200],
            [8,200],
            [9,200],
            [10,200],
            [700000,500],
            [9000000,500],
        ];

    }

    public function resultCallback($response, $args){

        $result = json_decode($response['content']);
        echo $result->code.'-'.$result->msg."\n\r";
        //var_dump($result);
        $this->assertEquals($args['results'],$result->code);

    }

}