<?php

/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/2
 * Time: 上午11:17
 */

namespace Tests\Http\Logics\Notice;

use App\Http\Logics\Article\ArticleLogic;

class ArticleLogicTest extends \TestCase
{

    public function sendTestData(){

        return [
            [
                'is' => true,
                'data' => [
                    'page'=>1,
                    'size'=>5,
                ]
            ],
            [
                'is' => true,
                'data' => [
                    'page'=>-1,
                    'size'=>5,
                ]
            ],
            [
                'is' => false,
                'data' => [
                    'page'=>10000,
                    'size'=>10000,
                ]
            ],
            [
                'is' => false,
                'data' => [
                    'page'=>2,
                    'size'=>0,
                ]
            ],
        ];

    }

    /**
     * @param $is
     * @param $data
     * @dataProvider sendTestData
     */
    public function testGetNoticeList($is,$data){

        $logic = new ArticleLogic();

        $notice = $logic->getAllNoticeList($data['page'], $data['size']);

        $data = $logic->formatAppNoticeData($notice);

        if($is === true){
            $this->assertArrayHasKey('id', $notice[0]);
            $this->assertArrayHasKey('title', $notice[0]);
            $this->assertArrayHasKey('publish_time', $notice[0]);
            $this->assertArrayHasKey('url', $data[0]);
        }else{
            $this->assertEmpty($notice);
            $this->assertEmpty($data);
        }
    }
}
