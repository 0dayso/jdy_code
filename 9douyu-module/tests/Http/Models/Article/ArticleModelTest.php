<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午5:05
 */

namespace Tests\Http\Models\Article;

use App\Http\Models\Article\ArticleModel;
use App\Tools\ToolTime;

class ArticleModelTest extends \TestCase
{


    public function testData(){

        return [
            [
                'is' => true,
                'data' => [
                    'title'         => 'title'.rand(1,9999999),
                    'picture_id'    => 1,
                    'intro'         => 'intro'.rand(1,9999999),
                    'keywords'      => 'keywords'.rand(1,9999999),
                    'description'   => 'description'.rand(1,9999999),
                    'category_id'   => 1,
                    'layout'        => 'layout'.rand(1,9999999),
                    'content'       => 'content'.rand(1,9999999),
                    'sort_num'      => 0,
                    'is_top'        => 1,
                    'type_id'       => 1,
                    'is_push'       => 1,
                    'status'        => 100,
                    'create_by'     => 1,
                    'publish_time'  => ToolTime::dbNow(),
                ]
            ],
            [
                'is' => true,
                'data' => [
                    'title'         => 'title'.rand(1,9999999),
                    'picture_id'    => 1,
                    'intro'         => 'intro'.rand(1,9999999),
                    'keywords'      => 'keywords'.rand(1,9999999),
                    'description'   => 'description'.rand(1,9999999),
                    'category_id'   => 1,
                    'layout'        => 'layout'.rand(1,9999999),
                    'content'       => 'content'.rand(1,9999999),
                    'sort_num'      => 0,
                    'is_top'        => 1,
                    'type_id'       => 1,
                    'is_push'       => 1,
                    'status'        => 100,
                    'create_by'     => 1,
                    'publish_time'  => ToolTime::dbNow(),
                ]
            ],
            [
                'is' => true,
                'data' => [
                    'title'         => 'title'.rand(1,9999999),
                    'picture_id'    => 1,
                    'intro'         => 'intro'.rand(1,9999999),
                    'keywords'      => 'keywords'.rand(1,9999999),
                    'description'   => 'description'.rand(1,9999999),
                    'category_id'   => 1,
                    'layout'        => 'layout'.rand(1,9999999),
                    'content'       => 'content'.rand(1,9999999),
                    'sort_num'      => 0,
                    'is_top'        => 1,
                    'type_id'       => 1,
                    'is_push'       => 1,
                    'status'        => 100,
                    'create_by'     => 1,
                    'publish_time'  => ToolTime::dbNow(),
                ]
            ],
            [
                'is' => true,
                'data' => [
                    'title'         => 'title'.rand(1,9999999),
                    'picture_id'    => 1,
                    'intro'         => 'intro'.rand(1,9999999),
                    'keywords'      => 'keywords'.rand(1,9999999),
                    'description'   => 'description'.rand(1,9999999),
                    'category_id'   => 1,
                    'layout'        => 'layout'.rand(1,9999999),
                    'content'       => 'content'.rand(1,9999999),
                    'sort_num'      => 0,
                    'is_top'        => 1,
                    'type_id'       => 1,
                    'is_push'       => 1,
                    'status'        => 100,
                    'create_by'     => 1,
                    'publish_time'  => ToolTime::dbNow(),
                ]
            ]
        ];

    }

    /**
     * @param $is
     * @param $data
     * @throws \Exception
     * @dataProvider testData
     */
    public function testDoCreate( $is, $data ){

        $model = new ArticleModel();

        $result = $model -> doCreate($data);

        if($is == true) {
            $this -> assertTrue($result);
        }else{
            $this -> assertNotTrue($result);
        }


    }


    public function testDataUpdate(){

        return [
            [
                'is' => true,
                'id' => 1,
                'data' => [
                    'title'         => 'title'.rand(1,9999999),
                    'picture_id'    => 1,
                    'intro'         => 'intro'.rand(1,9999999),
                    'keywords'      => 'keywords'.rand(1,9999999),
                    'description'   => 'description'.rand(1,9999999),
                    'category_id'   => 1,
                    'layout'        => 'layout'.rand(1,9999999),
                    'content'       => 'content'.rand(1,9999999),
                    'sort_num'      => 0,
                    'is_top'        => 1,
                    'type_id'       => 1,
                    'is_push'       => 1,
                    'status'        => 100,
                    'create_by'     => 1,
                    'publish_time'  => ToolTime::dbNow(),
                ]
            ],
            [
                'is' => true,
                'id' => 2,
                'data' => [
                    'title'         => 'title'.rand(1,9999999),
                    'picture_id'    => 1,
                    'intro'         => 'intro'.rand(1,9999999),
                    'keywords'      => 'keywords'.rand(1,9999999),
                    'description'   => 'description'.rand(1,9999999),
                    'category_id'   => 1,
                    'layout'        => 'layout'.rand(1,9999999),
                    'content'       => 'content'.rand(1,9999999),
                    'sort_num'      => 0,
                    'is_top'        => 1,
                    'type_id'       => 1,
                    'is_push'       => 1,
                    'status'        => 100,
                    'create_by'     => 1,
                    'publish_time'  => ToolTime::dbNow(),
                ]
            ],
            [
                'is' => true,
                'id' => 3,
                'data' => [
                    'title'         => 'title'.rand(1,9999999),
                    'picture_id'    => 1,
                    'intro'         => 'intro'.rand(1,9999999),
                    'keywords'      => 'keywords'.rand(1,9999999),
                    'description'   => 'description'.rand(1,9999999),
                    'category_id'   => 1,
                    'layout'        => 'layout'.rand(1,9999999),
                    'content'       => 'content'.rand(1,9999999),
                    'sort_num'      => 0,
                    'is_top'        => 1,
                    'type_id'       => 1,
                    'is_push'       => 1,
                    'status'        => 100,
                    'create_by'     => 1,
                    'publish_time'  => ToolTime::dbNow(),
                ]
            ],
            [
                'is' => false,
                'id' => 999999,
                'data' => [
                    'title'         => 'title'.rand(1,9999999),
                    'picture_id'    => 1,
                    'intro'         => 'intro'.rand(1,9999999),
                    'keywords'      => 'keywords'.rand(1,9999999),
                    'description'   => 'description'.rand(1,9999999),
                    'category_id'   => 1,
                    'layout'        => 'layout'.rand(1,9999999),
                    'content'       => 'content'.rand(1,9999999),
                    'sort_num'      => 0,
                    'is_top'        => 1,
                    'type_id'       => 1,
                    'is_push'       => 1,
                    'status'        => 100,
                    'create_by'     => 1,
                    'publish_time'  => ToolTime::dbNow(),
                ]
            ]
        ];

    }

    /**
     * @param $is
     * @param $id
     * @param $data
     * @throws \Exception
     * @dataProvider testDataUpdate
     */
    public function testDoEdit( $is, $id, $data){

        $model = new ArticleModel();

        try{

            $result = $model -> doUpdate($id, $data);

            if($is === true) {
                $this -> assertEquals($result,1);
            }

        }catch (\Exception $e){

            $this -> assertEquals($is,false);

        }

    }

}