<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/02
 * Time: 15:56 Pm
 * Desc: App4.0首页banner和按钮广告测试用例
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Logics\Ad\AdLogic;

class HomeV4AdLogicTest extends TestCase
{

    /**
     * @desc App4.0首页广告位数据供给
     * @return arrray
     */
    public function AdData(){

        $data = [
            [

            'position_id'=> [
                'position_banner_id' => 5,
                'position_button_id' => 6,
                'position_test_id' => 100,
               ],
             ]
            ];

        return $data;
    }

    /**
     * @desc 首页banner广告位测试
     * @dataProvider AdData
     */
    public function testHomeV4Banner($position_id){

        $banner1 = AdLogic::getUseAbleListByPositionId($position_id['position_banner_id']);

        $banner2 = AdLogic::getUseAbleListByPositionId($position_id['position_test_id']);

        $this->assertArrayHasKey('position_id', $banner1[0]);

        $this->assertEquals([], $banner2);

        $formatData1 = AdLogic::formatAppV4AdData($banner1);

        $formatData2 = AdLogic::formatAppV4AdData($banner2);

        $this->assertArrayHasKey('word', $formatData1[0]);

        $this->assertEquals([], $formatData2);

    }

    /**
     * @desc 首页button广告按钮图片测试
     * @dataProvider AdData
     */
    public function testHomeV4Button($position_id){

        $button1 = AdLogic::getUseAbleListByPositionId($position_id['position_button_id']);

        $button2 = AdLogic::getUseAbleListByPositionId($position_id['position_test_id']);
        $this->assertArrayHasKey('position_id', $button1[0]);

        $this->assertEquals([], $button2);

        $formatData1 = AdLogic::formatAppV4AdData($button1);

        $formatData2 = AdLogic::formatAppV4AdData($button2);

        $this->assertArrayHasKey('word', $formatData1[0]);

        $this->assertEquals([], $formatData2);

    }

}
