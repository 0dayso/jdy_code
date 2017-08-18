<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/15
 * Time: 下午2:04
 * Desc: 系统配置
 */

class SystemConfigTest extends TestCase
{

    public function testList()
    {

        $logic = new \App\Http\Logics\Module\SystemConfig\SystemConfigLogic();

        $list = $logic->getList();

        print_r($list);

    }

}