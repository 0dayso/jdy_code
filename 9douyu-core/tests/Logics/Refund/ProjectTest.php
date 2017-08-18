<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/23
 * Time: 上午10:30
 * Desc: 定期回款
 */

class ProjectTest extends TestCase
{

    public function testData()
    {

        return [
            [268762]
        ];

    }

    /**
     * @param $res
     * @return array
     * @dataProvider testData
     */
    public function testDoCreateRefund( $res )
    {

        $logic = new \App\Http\Logics\Refund\ProjectLogic();

        $res = $logic->createRecord( $res );

        $this->assertEquals(200, $res['code']);

        return $res;

    }

    /**
     * @return int
     * @desc 拆分回款
     */
    public function testSplitRefund()
    {

        $logic = new \App\Http\Logics\Refund\ProjectLogic();

        $res = $logic->splitRefund();

        $this->assertEquals(200, $res);

        return $res;

    }

    /**
     * @depends testSplitRefund
     */
    public function testDoRefund($res)
    {

        $this->assertEquals(200, $res);

        $logic = new \App\Http\Logics\Refund\ProjectLogic();

        $res = $logic->doRefund();

        $this->assertEquals(200, $res['code'], $res['msg']);

    }

    /*public function testAddRecord()
    {

        $db = new \App\Http\Dbs\RefundRecordDb();

        for( $i=30000; $i<50000; $i++ ){

            $data = [
                'project_id'    => 1,
                'invest_id'     => 1,
                'user_id'       => $i,
                'principal'     => 10000,
                'interest'      => $i*100,
                'cash'          => 10000+$i*100,
                'times'         => '2016-04-23',
                'status'        => 600
            ];
            echo $db->addRefundRecord($data)."\n";
        }

    }*/






}