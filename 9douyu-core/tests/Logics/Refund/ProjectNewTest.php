<?php
/**
 * Created by PhpStorm.
 * User: lqh-dev
 * Date: 17/4/26
 * Time: 上午11:14
 * Desc: 新定期生成还款计划
 */

class ProjectNewTest extends TestCase
{

    /**
     * @return int
     * @desc 拆分回款
     */
    public function testSplitRefund()
    {

        $logic = new \App\Http\Logics\Refund\ProjectLogic();

        $res = $logic->projectFullCreateRefundRecord( 3475 );



        $this->assertEquals(200, $res['code']);

        //return $res;

    }


}