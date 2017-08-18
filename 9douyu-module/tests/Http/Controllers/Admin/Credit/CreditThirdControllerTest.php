<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 测试后台债权第三方控制器
 */

namespace Tests\Http\Controllers\Admin\Credit;

use App\Http\Dbs\Credit\CreditThirdDb;

class CreditThirdControllerTest extends \TestCase{
    /**
     * 创建视图test
     */
    public function testCreate(){
        $this->visit('/admin/credit/create/third')
            ->see('创建第三方债权');
    }

    /**
     * 执行创建供给数据
     */
    public function dataProviderCreate(){
        $array =  [
            ['is' => true,
                'data'=>[
                    "source" => "40",
  "type" => "50",
  "credit_tag" => "103",
  "company_name" => "企业名称一一一1",
  "loan_amounts" => "1110000",
  "interest_rate" => "18.00",
  "repayment_method" => "10",
  "expiration_date" => "2016-06-01 16:49:57",
  "loan_deadline" => "4",
  "contract_no" => "jdy-sdfffasdasdxvb-2016-1144",
  "loan_username" => [
    0 => "",
    1 => "",
    2 => "ad",
    3 => "",
    4 => "",
  ],
  "loan_user_identity" => [
    0 => "",
    1 => "",
    2 => "asd",
    3 => "",
    4 => "",
                ],
            ],
                ],
            ['is' => false,
                'data'=>[
                    "source" => "40",
                    "type" => "50",
                    "credit_tag" => "103",
                    "company_name" => "",
                    "loan_amounts" => "1110000",
                    "interest_rate" => "18.00",
                    "repayment_method" => "10",
                    "expiration_date" => "2016-06-01 16:49:57",
                    "loan_deadline" => "4",
                    "contract_no" => "jdy-sdfffasdasdxvb-2016-1144",
                    "loan_username" => [
                        0 => "",
                        1 => "",
                        2 => "ad",
                        3 => "",
                        4 => "",
                    ],
                    "loan_user_identity" => [
                        0 => "",
                        1 => "",
                        2 => "asd",
                        3 => "",
                        4 => "",
                    ],
            ]
                ]
        ];

        return $array;
    }


    /**
     * 创建保理test
     * @dataProvider dataProviderCreate
     */
    public function testDoCreate($is, $data){
        if($is === true) {
            $this->post('/admin/credit/doCreate/third', $data)
                ->assertRedirectedTo('/admin/credit/lists/third', ['message'=>'创建债权成功！']);
        }else{
            $this->post('/admin/credit/doCreate/third', $data)
                ->assertHasOldInput();
        }
    }


    /**
     * 保理列表test
     *
     */
    public function testLists(){
        $this->visit('/admin/credit/lists/third')
            ->see('第三方列表');
    }


    /**
     * 保理编辑test
     * @dataProvider dataProviderCreate
     */
    public function testEdit($is, $data){
        if($is === true) {
            $data = CreditThirdDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($data)) {
                $this->assertArrayHasKey('id', $data);
                $this->visit('/admin/credit/edit/third/' . $data['id'])
                    ->see('编辑第三方债权');
            }
        }
    }


    /**
     * 保理编辑执行 test
     * @dataProvider dataProviderCreate
     */
    public function testDoEdit($is, $data){
        if($is === true) {
            $return = CreditThirdDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($return)) {
                $this->assertArrayHasKey('id', $return);
                $data['id'] = $return['id'];
                $data['company_name'] = '企业名称'. md5(rand(19968, 40895));
                $this->post('/admin/credit/doEdit/third', $data)
                    ->assertRedirectedTo('/admin/credit/lists/third', ['message' => '编辑债权成功！']);
            }
        }else{
            $this->post('/admin/credit/doEdit/third', $data)
                ->assertHasOldInput();
        }
    }


}