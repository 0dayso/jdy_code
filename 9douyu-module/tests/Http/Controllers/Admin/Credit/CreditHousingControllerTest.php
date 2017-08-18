<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 测试后台债权房产抵押控制器
 */

namespace Tests\Http\Controllers\Admin\Credit;

use App\Http\Dbs\Credit\CreditHousingDb;

class CreditHousingControllerTest extends \TestCase{
    /**
     * 创建视图test
     */
    public function testCreate(){
        $this->visit('/admin/credit/create/housing')
            ->see('创建房产抵押债权');
    }

    /**
     * 执行创建供给数据
     */
    public function dataProviderCreate(){
        $array =  [
            ['is' => true,
                'data'=>[
                    "source" => "30",
  "type" => "50",
  "credit_tag" => "103",
  "company_name" => "12123",
  "loan_amounts" => "12.2",
  "interest_rate" => "15.00",
  "repayment_method" => "10",
  "expiration_date" => "2016-09-30 00:00:00",
  "loan_deadline" => "6",
  "contract_no" => "jdy-sdfffasdasdxvb-2016-11",
  "loan_username" => [
    0 => "",
    1 => "rr",
    2 => "",
    3 => "",
    4 => "",
  ],
  "loan_user_identity" => [
    0 => "",
    1 => "",
    2 => "4",
    3 => "",
    4 => "",
  ],
  "credit_desc" => "    dfg",
  "housing_location" => "df",
  "housing_area" => "3000",
  "housing_valuation" => "70000",
  "sex" => "1",
  "age" => "41",
  "family_register" => "北京",
  "residence" => "门头沟",
  "credibility" => "    sdf",
  "involved_appeal" => "     sdf",
  "risk_control_message" => "     sf",
  "certificates" => "<p>sdf</p>\r\n",
  "mortgage" => "<p>sd</p>\r\n",
                ]
            ],
            ['is' => false,
                'data'=>[
                    "source" => "30",
                    "type" => "50",
                    "credit_tag" => "103",
                    "company_name" => "",
                    "loan_amounts" => "12.2",
                    "interest_rate" => "15.00",
                    "repayment_method" => "10",
                    "expiration_date" => "2016-09-30 00:00:00",
                    "loan_deadline" => "6",
                    "contract_no" => "jdy-sdfffasdasdxvb-2016-11",
                    "loan_username" => [
                        0 => "",
                        1 => "rr",
                        2 => "",
                        3 => "",
                        4 => "",
                    ],
                    "loan_user_identity" => [
                        0 => "",
                        1 => "",
                        2 => "4",
                        3 => "",
                        4 => "",
                    ],
                    "credit_desc" => "    dfg",
                    "housing_location" => "df",
                    "housing_area" => "3000",
                    "housing_valuation" => "70000",
                    "sex" => "1",
                    "age" => "41",
                    "family_register" => "北京",
                    "residence" => "门头沟",
                    "credibility" => "    sdf",
                    "involved_appeal" => "     sdf",
                    "risk_control_message" => "     sf",
                    "certificates" => "<p>sdf</p>\r\n",
                    "mortgage" => "<p>sd</p>\r\n",
                ]
            ],
        ];

        return $array;
    }


    /**
     * 创建房产抵押test
     * @dataProvider dataProviderCreate
     */
    public function testDoCreate($is, $data){
        if($is === true) {
            $this->post('/admin/credit/doCreate/housing', $data)
                ->assertRedirectedTo('/admin/credit/lists/housing', ['message'=>'创建债权成功！']);
        }else{
            $this->post('/admin/credit/doCreate/housing', $data)
                ->assertHasOldInput();
        }
    }


    /**
     * 房产抵押列表test
     *
     */
    public function testLists(){
        $this->visit('/admin/credit/lists/housing')
            ->see('房产抵押列表');
    }


    /**
     * 房产抵押编辑test
     * @dataProvider dataProviderCreate
     */
    public function testEdit($is, $data){
        if($is === true) {
            $data = CreditHousingDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($data)) {
                $this->assertArrayHasKey('id', $data);
                $this->visit('/admin/credit/edit/housing/' . $data['id'])
                    ->see('编辑房产抵押债权');
            }
        }
    }


    /**
     * 房产抵押编辑执行 test
     * @dataProvider dataProviderCreate
     */
    public function testDoEdit($is, $data){
        if($is === true) {
            $return = CreditHousingDb::select(['id', 'company_name'])->where(['company_name'=>$data['company_name']])->first()->toArray();
            if(!empty($return)) {
                $this->assertArrayHasKey('id', $return);
                $data['id'] = $return['id'];
                $data['company_name'] = '企业名称'. md5(rand(19968, 40895));
                $this->post('/admin/credit/doEdit/housing', $data)
                    ->assertRedirectedTo('/admin/credit/lists/housing', ['message' => '编辑债权成功！']);
            }
        }else{
            $this->post('/admin/credit/doEdit/housing', $data)
                ->assertHasOldInput();
        }
    }


}