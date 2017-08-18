<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 测试后台债权保理控制器
 */

namespace Tests\Http\Controllers\Admin\Credit;

use App\Http\Dbs\Credit\CreditFactoringDb;

class CreditFactoringControllerTest extends \TestCase{

    protected static $creditId = 0;
    /**
     * 创建视图test
     */
    public function testCreate(){
        $this->visit('/admin/credit/create/factoring')
            ->see('创建耀盛保理债权');
    }

    /**
     * 执行创建供给数据
     */
    public function dataProviderCreate(){
        $array =  [
            ['is' => true,
            'data'=>[
                "source" => "10",
                "type" => "50",
                "credit_tag" => "103",
                 "company_name" => '项目名-',
                "loan_amounts" => "11220000",
                "interest_rate" => "18.00",
                "repayment_method" => "20",
                "expiration_date" => "2016-08-31 00:00:00",
                "loan_deadline" => "6",
                "contract_no" => "jdy-sdfffasdasdxvb-2016-1122",
                "loan_username" => [
                0 => "金灿荣",
                1 => "梁启超",
                2 => "鸣人",
                3 => "卡卡西",
                4 => "佐助",
                ],
                "loan_user_identity" => [
                0 => "230134331231323424333",
                1 => "230134331231323424331",
                2 => "230134331231323424332",
                3 => "230134331231323424334",
                4 => "230134331231323424335",
                ],
                "riskcalc_level" => "5",
                "company_level" => "3",
                "company_level_value" => "99",
                "downstream_level" => "5",
                "downstream_level_value" => "99",
                "profit_level" => "5",
                "profit_level_value" => "99",
                "downstream_refund_level" => "5",
                "downstream_refund_level_value" => "99",
                "liability_level" => "5",
                "liability_level_value" => "99",
                "guarantee_level" => "5",
                "guarantee_level_value" => "99",
                "keywords" => [
                0 => "1",
                1 => "",
                2 => "3",
                3 => "",
                ],
                "credit_desc" => " swasdadasdd",
                "factor_summarize" => "asda",
                "repayment_source" => " 99",
                "factoring_opinion" => "99",
                "business_background" => "99",
                "introduce" => "99",
                "risk_control_measure" => "99",
                "transactional_data" => "<p>99</p>\r\n",
                "traffic_data" => "<p>99</p>\r\n",
                ]
            ],
            ['is' => false,
                'data'=>[
                    "source" => "10",
                    "type" => "50",
                    "credit_tag" => "103",
                    "company_name" => "",
                    "loan_amounts" => "11220000",
                    "interest_rate" => "18.00",
                    "repayment_method" => "20",
                    "expiration_date" => "2016-08-31 00:00:00",
                    "loan_deadline" => "6",
                    "contract_no" => "jdy-sdfffasdasdxvb-2016-1122",
                    "loan_username" => [
                        0 => "金灿荣",
                        1 => "梁启超",
                        2 => "鸣人",
                        3 => "卡卡西",
                        4 => "佐助",
                    ],
                    "loan_user_identity" => [
                        0 => "230134331231323424333",
                        1 => "230134331231323424331",
                        2 => "230134331231323424332",
                        3 => "230134331231323424334",
                        4 => "230134331231323424335",
                    ],
                    "riskcalc_level" => "5",
                    "company_level" => "3",
                    "company_level_value" => "99",
                    "downstream_level" => "5",
                    "downstream_level_value" => "99",
                    "profit_level" => "5",
                    "profit_level_value" => "99",
                    "downstream_refund_level" => "5",
                    "downstream_refund_level_value" => "99",
                    "liability_level" => "5",
                    "liability_level_value" => "99",
                    "guarantee_level" => "5",
                    "guarantee_level_value" => "99",
                    "keywords" => [
                        0 => "1",
                        1 => "",
                        2 => "3",
                        3 => "",
                    ],
                    "credit_desc" => " swasdadasdd",
                    "factor_summarize" => "asda",
                    "repayment_source" => " 99",
                    "factoring_opinion" => "99",
                    "business_background" => "99",
                    "introduce" => "99",
                    "risk_control_measure" => "99",
                    "transactional_data" => "<p>99</p>\r\n",
                    "traffic_data" => "<p>99</p>\r\n",
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
            $this->post('/admin/credit/doCreate/factoring', $data)
                 ->assertRedirectedTo('/admin/credit/lists/factoring', ['message'=>'创建保理债权成功！']);
        }else{
            $this->post('/admin/credit/doCreate/factoring', $data)
                 ->assertHasOldInput();
        }
    }


    /**
     * 保理列表test
     *
     */
    public function testLists(){
        $this->visit('/admin/credit/lists/factoring')
            ->see('耀盛保理列表');
    }


    /**
     * 保理编辑test
     * @dataProvider dataProviderCreate
     */
    public function testEdit($is, $data){
        if($is === true) {
            $data = CreditFactoringDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($data)) {
                $this->assertArrayHasKey('id', $data);
                $this->visit('/admin/credit/edit/factoring/' . $data['id'])
                    ->see('编辑耀盛保理债权');
            }
        }
    }


    /**
     * 保理编辑执行 test
     * @dataProvider dataProviderCreate
     */
    public function testDoEdit($is, $data){
        if($is === true) {
            $return = CreditFactoringDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($return)) {
                $this->assertArrayHasKey('id', $return);
                $data['id'] = $return['id'];
                $data['company_name'] = '企业名称'. md5(rand(19968, 40895));
                $this->post('/admin/credit/doEdit/factoring', $data)
                    ->assertRedirectedTo('/admin/credit/lists/factoring', ['message' => '编辑债权成功！']);
            }
        }else{
            $this->post('/admin/credit/doEdit/factoring', $data)
                ->assertHasOldInput();
        }
    }


}