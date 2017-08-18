<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午5:05
 */

namespace Tests\Http\Models\Article;

use App\Http\Models\Category\CategoryModel;

class CategoryModelTest extends \TestCase
{


    public function testData(){

        return [
            [
                'is' => true,
                'data' => [
                    'parent_id' => 0,
                    'name'      => 'test'.rand(1,999999),
                    'alias'     => 'alias_test'.rand(1,999999),
                    'sort_num'  => 0,
                    'status'    => 100,
                ]
            ],
            [
                'is' => true,
                'data' => [
                    'parent_id' => 0,
                    'name'      => 'test'.rand(1,999999),
                    'alias'     => 'alias_test'.rand(1,999999),
                    'sort_num'  => 0,
                    'status'    => 100,
                ]
            ],
            [
                'is' => true,
                'data' => [
                    'parent_id' => 0,
                    'name'      => 'test'.rand(1,999999),
                    'alias'     => 'alias_test'.rand(1,999999),
                    'sort_num'  => 0,
                    'status'    => 100,
                ]
            ],
            [
                'is' => true,
                'data' => [
                    'parent_id' => 0,
                    'name'      => 'test'.rand(1,999999),
                    'alias'     => 'alias_test'.rand(1,999999),
                    'sort_num'  => 0,
                    'status'    => 100,
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

        $model = new CategoryModel();

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
                    'parent_id' => 0,
                    'name'      => 'edit_test'.rand(1,999999),
                    'alias'     => 'edit_alias_test'.rand(1,999999),
                    'sort_num'  => 0,
                    'status'    => 100,
                ]
            ],
            [
                'is' => true,
                'id' => 2,
                'data' => [
                    'parent_id' => 0,
                    'name'      => 'edit_test'.rand(1,999999),
                    'alias'     => 'edit_alias_test'.rand(1,999999),
                    'sort_num'  => 0,
                    'status'    => 100,
                ]
            ],
            [
                'is' => true,
                'id' => 3,
                'data' => [
                    'parent_id' => 0,
                    'name'      => 'edit_test'.rand(1,999999),
                    'alias'     => 'edit_alias_test'.rand(1,999999),
                    'sort_num'  => 0,
                    'status'    => 100,
                ]
            ],
            [
                'is' => false,
                'id' => 999999,
                'data' => [
                    'parent_id' => 0,
                    'name'      => 'edit_test'.rand(1,999999),
                    'alias'     => 'edit_alias_test'.rand(1,999999),
                    'sort_num'  => 0,
                    'status'    => 100,
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

        $model = new CategoryModel();

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