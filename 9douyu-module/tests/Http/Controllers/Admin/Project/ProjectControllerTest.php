<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/2
 * Time: 下午5:55
 */

namespace Tests\Http\Controllers\Admin\Project;


use App\Http\Dbs\Project\ProjectLinkCreditDb;

class ProjectControllerTest extends \TestCase
{

    /**
     * 创建视图test
     */
    public function testCreate(){
        $this->visit('/admin/project/create')
            ->see('创建项目');
    }

    /**
     * 执行创建供给数据
     */
    public function dataProviderCreate(){
        $array =  [
            [
                'is' => true,
                'data'=>[
                    "product_line"  => "101",
                    "invest_days"   => "10",
                    "base_rate"     => "10",
                    "after_rate"    => "1",
                    "refund_type"   => "10",
                    "publish_time"  => "2016-06-03",
                    "invest_time"   => "20",
                    "end_at"        => "2016-06-23",
                    "total_amount"  => "100001",
                    "credit"        => [
                        1   => [
                            'type'          =>  '70',
                            'cash'          =>  '11100',
                            'product_line'  => '101',
                        ],
                    "credit_id" => [1]
                    ]

                ]
            ],
            [
                'is' => false,
                'data'=>[
                    "product_line"  => "101",
                    "invest_days"   => "10",
                    "base_rate"     => "10",
                    "after_rate"    => "1",
                    "refund_type"   => "10",
                    "publish_time"  => "2016-06-03",
                    "invest_time"   => "21",
                    "end_at"        => "2016-06-24",
                    "total_amount"  => "100001",
                    "credit"        => [
                        1   => [
                            'type'          => '70',
                            'cash'          => '11100',
                            'product_line'  => '101',
                        ],
                        "credit_id" => [1]
                    ]
                ],
            ]
        ];

        return $array;
    }

    /**
     * @param $is
     * @param $data
     * @dataProvider dataProviderCreate
     */
    public function testDoCreate($is, $data){
        if($is === true) {
            $this->post('/admin/project/doCreate', $data)
                ->assertRedirectedTo('/admin/project/lists', ['message'=>'项目创建成功！']);
        }else{
            $this->post('/admin/project/doCreate', $data)
                ->assertHasOldInput();
        }
    }

    /**
     * @param $is
     * @param $data
     * @desc 更新项目
     * @dataProvider dataProviderCreate
     */
    public function testUpdate($is, $data){
        if($is === true) {
            $data = ProjectLinkCreditDb::select(['id'])->where(['product_line'=>$data['product_line']])->first()->toArray();
            if(!empty($data)) {
                $this->assertArrayHasKey('id', $data);
                $this->visit('/admin/project/update/' . $data['id'])
                    ->see('编辑项目');
            }
        }
    }

    /**
     * @param $is
     * @param $data
     * @dataProvider dataProviderCreate
     */
    public function testDoUpdate($is, $data){
        if($is === true) {
            $return = ProjectLinkCreditDb::select(['id'])->where(['product_line'=>$data['product_line']])->first()->toArray();
            if(!empty($return)) {
                $this->assertArrayHasKey('id', $return);
                $data['id'] = $return['id'];
                $data['name'] = '项目名称'. md5(rand(19968, 40895));
                $this->post('/admin/project/doUpdate', $data)
                    ->assertRedirectedTo('/admin/project/lists', ['message' => '项目更新成功！']);
            }
        }else{
            $this->post('/admin/project/doUpdate', $data)
                ->assertHasOldInput();
        }
    }


}