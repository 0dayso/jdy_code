<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/02
 * Time: 18:00 Pm
 * Desc: App4.0首页平台统计测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\Statistics\StatLogic;

class HomeV4StatLogicTest extends TestCase
{

    /**
     * @desc App4.0首页数据统计测试
     * @dataProvider
     */
    public function testStatData(){

        $statLogic = new StatLogic();

        $statData = $statLogic->getV4HomeStatistics();

        //测试数据统计返回的数据
        $this->assertArrayHasKey('userCountNote', $statData);
    }
}
