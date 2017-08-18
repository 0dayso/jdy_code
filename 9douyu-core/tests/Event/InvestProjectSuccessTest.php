<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/14
 * Time: 下午6:24
 * Desc: 投资定期项目成功
 */

class InvestProjectSuccessTest extends TestCase
{

    public function testEvent()
    {

        $log = [
            'event_name'      => 'App\Events\Api\Invest\ProjectSuccessEvent',
            'invest_id'     => 1,
            'user_id'       => 1,
            'project_id'    => 1,
            'cash'          => 10000,
        ];

        \Event::fire('App\Events\Api\Invest\ProjectSuccessEvent', [$log]);


    }



}