<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 测试后台债权九省心控制器
 */

namespace Tests\Http\Controllers\Admin\Credit;

use App\Http\Dbs\Credit\CreditNineDb;

class CreditNineControllerTest extends \TestCase{
    /**
     * 创建视图test
     */
    public function testCreate(){
        $this->visit('/admin/credit/create/nine')
            ->see('创建九省心债权');
    }

    /**
     * 执行创建供给数据
     */
    public function dataProviderCreate(){
        $array =  [
            ['is' => true,
                'data'=>[
                    "source" => "30",
  "type" => "70",
  "credit_tag" => "101",
  "plan_name" => "计划名",
  "loan_amounts" => "1.11",
  "interest_rate" => "1.22",
  "repayment_method" => "10",
  "expiration_date" => "2016-06-30 16:49:57",
  "loan_deadline" => "6",
  "contract_no" => "jdy-sdfffasdasdxvb-2016-1101",
  "program_no" => "啊实打实大大大叔的",
  "file" => "<p>asada</p>\r\n",
                ]
            ],
            ['is' => false,
                'data'=>[
                    "source" => "30",
                    "type" => "70",
                    "credit_tag" => "103",
                    "plan_name" => "",
                    "loan_amounts" => "1200000",
                    "interest_rate" => "1.22",
                    "repayment_method" => "10",
                    "expiration_date" => "2016-06-01 16:49:57",
                    "loan_deadline" => "6",
                    "contract_no" => "jdy-sdfffasdasdxvb-2016-1101",
                    "program_no" => "啊实打实大大大叔的",
                    "file" => "<p>asada</p>\r\n",
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
            $this->post('/admin/credit/doCreate/nine', $data)
                ->assertRedirectedTo('/admin/credit/lists/nine', ['message'=>'创建债权成功！']);
        }else{
            $this->post('/admin/credit/doCreate/nine', $data)
                ->assertHasOldInput();
        }
    }


    /**
     * 保理列表test
     *
     */
    public function testLists(){
        $this->visit('/admin/credit/lists/nine')
            ->see('九省心列表');
    }


    /**
     * 保理编辑test
     * @dataProvider dataProviderCreate
     */
    public function testEdit($is, $data){
        if($is === true) {
            $data = CreditNineDb::select(['id', 'plan_name'])->where(['plan_name'=>$data['plan_name']])->first()->toArray();
            if(!empty($data)) {
                $this->assertArrayHasKey('id', $data);
                $this->visit('/admin/credit/edit/nine/' . $data['id'])
                    ->see('编辑九省心债权');
            }
        }
    }


    /**
     * 保理编辑执行 test
     * @dataProvider dataProviderCreate
     */
    public function testDoEdit($is, $data){
        if($is === true) {
            $return = CreditNineDb::select(['id', 'plan_name'])->where(['plan_name'=>$data['plan_name']])->first()->toArray();
            if(!empty($return)) {
                $this->assertArrayHasKey('id', $return);
                $data['id'] = $return['id'];
                $data['plan_name'] = '计划名称'. md5(rand(19968, 40895));
                $this->post('/admin/credit/doEdit/nine', $data)
                    ->assertRedirectedTo('/admin/credit/lists/nine', ['message' => '编辑债权成功！']);
            }
        }else{
            $this->post('/admin/credit/doEdit/nine', $data)
                ->assertHasOldInput();
        }
    }


}