<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/6
 * Time: 上午11:21
 * Desc: 投资使用加息券
 */

class BonusRateTest extends TestCase
{


    /**
     * @param $investId
     * @param $profit
     * @desc 加息券投资
     * @dataProvider additionProvider
     */
    public function testInvest($investId, $profit)
    {

        $logic = new \App\Http\Logics\Refund\ProjectLogic();

        $result = $logic->createRateRecord($investId, $profit);

        $this->assertEquals($result['code'], \App\Http\Logics\Logic::CODE_SUCCESS);

    }

    /**
     * @return array
     * @desc 数组代表投资id，加息利率
     */
    public function additionProvider()
    {

        return [
            [2,1]
        ];

    }

}