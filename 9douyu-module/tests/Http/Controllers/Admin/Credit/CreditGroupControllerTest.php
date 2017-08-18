<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 测试后台债权项目集控制器
 */

namespace Tests\Http\Controllers\Admin\Credit;

use App\Http\Dbs\Credit\CreditGroupDb;

class CreditGroupControllerTest extends \TestCase{
    /**
     * 创建视图test
     */
    public function testCreate(){
        $this->visit('/admin/credit/create/group')
            ->see('创建项目集债权');
    }

    /**
     * 执行创建供给数据
     */
    public function dataProviderCreate(){
        $array =  [
            ['is' => true,
                'data'=>[
                    "source" => "10",
                "type" => "60",
                "credit_tag" => "103",
                "company_name" => "12123",
                "loan_amounts" => "1.10",
                "interest_rate" => "1.11",
                "repayment_method" => "20",
                "expiration_date" => "2016-06-01 16:49:57",
                "loan_deadline" => "7",
                "contract_no" => "jdy-sdfffasdasdxvb-2016-1133",
                "loan_username" => [
                0 => "",
                1 => "",
                2 => "33",
                3 => "",
                4 => "",
                ],
                    "loan_user_identity" => [
                0 => "",
                1 => "",
                2 => "11",
                3 => "",
                4 => "",
                ],
                "financing_company" => "asad",
                "program_area_location" => "asad",
                    "loan_use" => "asad",
                "repayment_source" => "   asad   ",
                "loan_contract" => "<p>dfg</p>\r\n",
]
            ],
            ['is' => false,
                'data'=>[
                    "source" => "10",
                    "type" => "60",
                    "credit_tag" => "103",
                    "company_name" => "",
                    "loan_amounts" => "1.10",
                    "interest_rate" => "1.11",
                    "repayment_method" => "20",
                    "expiration_date" => "2016-06-01 16:49:57",
                    "loan_deadline" => "7",
                    "contract_no" => "jdy-sdfffasdasdxvb-2016-1133",
                    "loan_username" => [
                        0 => "",
                        1 => "",
                        2 => "33",
                        3 => "",
                        4 => "",
                    ],
                    "loan_user_identity" => [
                        0 => "",
                        1 => "",
                        2 => "11",
                        3 => "",
                        4 => "",
                    ],
                    "financing_company" => "asad",
                    "program_area_location" => "asad",
                    "loan_use" => "asad",
                    "repayment_source" => "   asad   ",
                    "loan_contract" => "<p>dfg</p>\r\n",
                ]
            ],
        ];

        return $array;
    }


    /**
     * 创建保理test
     * @dataProvider dataProviderCreate
     */
    public function testDoCreate($is, $data){
        if($is === true) {
            $this->post('/admin/credit/doCreate/group', $data)
                ->assertRedirectedTo('/admin/credit/lists/group', ['message'=>'创建债权成功！']);
        }else{
            $this->post('/admin/credit/doCreate/group', $data)
                ->assertHasOldInput();
        }
    }


    /**
     * 保理列表test
     *
     */
    public function testLists(){
        $this->visit('/admin/credit/lists/group')
            ->see('项目集列表');
    }


    /**
     * 保理编辑test
     * @dataProvider dataProviderCreate
     */
    public function testEdit($is, $data){
        if($is === true) {
            $data = CreditGroupDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($data)) {
                $this->assertArrayHasKey('id', $data);
                $this->visit('/admin/credit/edit/group/' . $data['id'])
                    ->see('编辑项目集债权');
            }
        }
    }


    /**
     * 保理编辑执行 test
     * @dataProvider dataProviderCreate
     */
    public function testDoEdit($is, $data){
        if($is === true) {
            $return = CreditGroupDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($return)) {
                $this->assertArrayHasKey('id', $return);
                $data['id'] = $return['id'];
                $data['company_name'] = '企业名称'. md5(rand(19968, 40895));
                $this->post('/admin/credit/doEdit/group', $data)
                    ->assertRedirectedTo('/admin/credit/lists/group', ['message' => '编辑债权成功！']);
            }
        }else{
            $this->post('/admin/credit/doEdit/group', $data)
                ->assertHasOldInput();
        }
    }


}