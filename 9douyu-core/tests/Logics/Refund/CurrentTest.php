<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/5/5
 * Time: 上午11:14
 * Desc: 零钱计划计息
 */

class CurrentTest extends TestCase
{

    /**
     * @return int
     * @desc 拆分回款
     */
    public function testSplitRefund()
    {

        $logic = new \App\Http\Logics\Refund\CurrentLogic();

        $res = $logic->splitRefund();

        $this->assertEquals(200, $res['code']);

        //return $res;

    }


}