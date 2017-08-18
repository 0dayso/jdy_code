<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 测试后台债权信贷控制器
 */

namespace Tests\Http\Controllers\Admin\Credit;

use App\Http\Dbs\Credit\CreditLoanDb;

class CreditLoanControllerTest extends \TestCase{
    /**
     * 创建视图test
     */
    public function testCreate(){
        $this->visit('/admin/credit/create/loan')
            ->see('创建耀盛信贷债权');
    }

    /**
     * 执行创建供给数据
     */
    public function dataProviderCreate(){
        $array =  [
            ['is' => true,
                'data'=>[
  "source" => "20",
  "type" => "50",
  "credit_tag" => "103",
  "company_name" => "企业名称",
  "loan_amounts" => "33.33",
  "interest_rate" => "18.00",
  "repayment_method" => "10",
  "expiration_date" => "2016-05-31 00:00:00",
  "loan_deadline" => "6",
  "contract_no" => "jdy-sdfffasdasdxvb-2016-1144",
  "loan_username" => [
    0 => "",
    1 => "",
    2 => "",
    3 => "",
    4 => "",
  ],
  "loan_user_identity" => [
    0 => "",
    1 => "",
    2 => "",
    3 => "",
    4 => "",
  ],
  "riskcalc_level" => "5",
  "company_level" => "5",
  "company_level_value" => "5",
  "profit_level" => "5",
  "profit_level_value" => "5",
  "liability_level" => "5",
  "liability_level_value" => "5",
  "guarantee_level" => "5",
  "guarantee_level_value" => "5",
  "keywords" => [
    0 => "",
    1 => "",
    2 => "",
    3 => "",
  ],
  "credit_desc" => "  ",
  "financing_company" => "we",
  "founded_time" => "2016-11-11 00:00:00",
  "program_area_location" => "asad",
  "registered_capital" => "12311",
  "annual_income" => "123",
  "loan_use" => " 123   ",
  "repayment_source" => "1  ",
  "background" => " 1 ",
  "financial" => " 1 ",
  "sex" => "1",
  "age" => "41",
  "family_register" => "北京",
  "residence" => "门头沟",
  "home_stability" => "sdf",
  "esteemn" => "  asd",
  "credibility" => "  ads",
  "involved_appeal" => "  as",
  "submit_data" => [
    "license" => "on",
    "tax_submit_dataority" => "on",
    "organization_code" => "on",
    "bank_account_cert" => "on",
    "company_rule" => "on",
    "capital_verified_report" => "on",
    "qualification" => "on",
    "representative" => "on",
    "controller" => "on",
    "premise" => "on",
    "residence" => "on",
    "business_info" => "on",
    "house_property" => "on",
    "car" => "on",
    "onsite" => "on",
    "other" => "on",
  ],
  "risk_control_message" => "  a",
  "risk_control_security" => "  a",
]


            ],
            ['is' => false,
                'data'=>[
                "source" => "20",
                "type" => "50",
                "credit_tag" => "103",
                "company_name" => "",
                "loan_amounts" => "33.33",
                "interest_rate" => "18.00",
                "repayment_method" => "10",
                "expiration_date" => "2016-05-31 00:00:00",
                "loan_deadline" => "6",
                "contract_no" => "jdy-sdfffasdasdxvb-2016-1144",
                "loan_username" => [
                    0 => "",
                    1 => "",
                    2 => "",
                    3 => "",
                    4 => "",
                ],
                "loan_user_identity" => [
                    0 => "",
                    1 => "",
                    2 => "",
                    3 => "",
                    4 => "",
                ],
                "riskcalc_level" => "5",
                "company_level" => "5",
                "company_level_value" => "5",
                "profit_level" => "5",
                "profit_level_value" => "5",
                "liability_level" => "5",
                "liability_level_value" => "5",
                "guarantee_level" => "5",
                "guarantee_level_value" => "5",
                "keywords" => [
                    0 => "",
                    1 => "",
                    2 => "",
                    3 => "",
                ],
                "credit_desc" => "  ",
                "financing_company" => "we",
                "founded_time" => "2016-11-11 00:00:00",
                "program_area_location" => "asad",
                "registered_capital" => "12311",
                "annual_income" => "123",
                "loan_use" => " 123   ",
                "repayment_source" => "1  ",
                "background" => " 1 ",
                "financial" => " 1 ",
                "sex" => "1",
                "age" => "41",
                "family_register" => "北京",
                "residence" => "门头沟",
                "home_stability" => "sdf",
                "esteemn" => "  asd",
                "credibility" => "  ads",
                "involved_appeal" => "  as",
                "submit_data" => [
                    "license" => "on",
                    "tax_submit_dataority" => "on",
                    "organization_code" => "on",
                    "bank_account_cert" => "on",
                    "company_rule" => "on",
                    "capital_verified_report" => "on",
                    "qualification" => "on",
                    "representative" => "on",
                    "controller" => "on",
                    "premise" => "on",
                    "residence" => "on",
                    "business_info" => "on",
                    "house_property" => "on",
                    "car" => "on",
                    "onsite" => "on",
                    "other" => "on",
                ],
                "risk_control_message" => "  a",
                "risk_control_security" => "  a",
            ]
            ],
        ];

        return $array;
    }


    /**
     * 创建信贷test
     * @dataProvider dataProviderCreate
     */
    public function testDoCreate($is, $data){
        if($is === true) {
            $this->post('/admin/credit/doCreate/loan', $data)
                ->assertRedirectedTo('/admin/credit/lists/loan', ['message'=>'创建债权成功！']);
        }else{
            $this->post('/admin/credit/doCreate/loan', $data)
                ->assertHasOldInput();
        }
    }


    /**
     * 信贷列表test
     *
     */
    public function testLists(){
        $this->visit('/admin/credit/lists/loan')
            ->see('耀盛信贷列表');
    }


    /**
     * 信贷编辑test
     * @dataProvider dataProviderCreate
     */
    public function testEdit($is, $data){
        if($is === true) {
            $data = CreditLoanDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($data)) {
                $this->assertArrayHasKey('id', $data);
                $this->visit('/admin/credit/edit/loan/' . $data['id'])
                    ->see('编辑耀盛信贷债权');
            }
        }
    }


    /**
     * 信贷编辑执行 test
     * @dataProvider dataProviderCreate
     */
    public function testDoEdit($is, $data){
        if($is === true) {
            $return = CreditLoanDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($return)) {
                $this->assertArrayHasKey('id', $return);
                $data['id'] = $return['id'];
                $data['company_name'] = '企业名称'. md5(rand(19968, 40895));
                $this->post('/admin/credit/doEdit/loan', $data)
                    ->assertRedirectedTo('/admin/credit/lists/loan', ['message' => '编辑债权成功！']);
            }
        }else{
            $this->post('/admin/credit/doEdit/loan', $data)
                ->assertHasOldInput();
        }
    }


}