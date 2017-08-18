<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/3/02
 * Time: 11:56 Am
 * Desc: App4.0首页文章测试用例
 */

namespace Tests\Http\Logics\Article;

use App\Http\Logics\Article\ArticleLogic;

class HomeV4ArticleLogicTest extends \TestCase{

    /**
     * @desc 文章类型和条数数据供给
     * @return array
     */
    public function articleData(){
        $articleData = [
            [
            'data1' =>[
                'type'=>18,
                'limit'=>1,
            ],
            'data2' =>[
                'type'=>100,
                'limit'=>1,
            ],
            ]
            ];
        return $articleData;
    }


    /**
     * @desc 获取AppV4.0首页的头条文章列表
     * @dataProvider articleData
     */
    public function testGetArticle($data1, $data2){

        $articleLogic = new ArticleLogic();

        $articleList1 = $articleLogic->getArticleList([$data1['type']],$data1['limit']);

        $articleList2 = $articleLogic->getArticleList([$data2['type']],$data2['limit']);
        //断言数组是否包含key
        $this->assertArrayHasKey('title', $articleList1[0]);
       //短信返回为空
        $this->assertEquals([],$articleList2);
    }


    /**
     * @desc 测试App4.0首页头条文章格式化结果
     * @dataProvider articleData
     */
    public function testFormatArticle($data1, $data2){

        $articleLogic = new ArticleLogic();

        $articleList1 = $articleLogic->getArticleList([$data1['type']],$data1['limit']);

        $articleList2 = $articleLogic->getArticleList([$data2['type']],$data2['limit']);

        $formatData1 =$articleLogic->formatAppV4HomeArticle($articleList1);

        $formatData2 =$articleLogic->formatAppV4HomeArticle($articleList2);

        //断言格式化结果是否包含key
        $this->assertArrayHasKey('shareInfo', $formatData1);

        //断言空值格式化返回空
        $this->assertEquals([],  $formatData2);
    }

}


