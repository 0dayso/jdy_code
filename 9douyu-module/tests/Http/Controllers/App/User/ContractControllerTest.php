<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/10/21
 * Time: 上午10:22
 */

namespace Tests\Http\Controllers\App;
use App\Http\Logics\Agreement\AgreementLogic;

/**
 * 合同协议
 *
 * Class ContractControllerTest
 * @package Tests\Http\Controllers\App
 */
class ContractControllerTest extends \TestCase
{

    protected $baseUrl = 'http://testmodule.jiudouyu.com.cn';
    /**
     * 合同数据提供
     */
    public function dataProviderCreate(){

        //所有类型的合同
        $typeData = array_keys(AgreementLogic::BLADE_ARR);
        $array    =  [
            [   'is'   => true,
                'data' =>[
                    "investCash"        => "17.14",
                    "contract"          => "",//不验证密码
                    "token"             => "b079003eaf35405c9961f9c4aa3880111fb1b56d",
                    "trading_password"  => "admin123",
                    "investId"          => "205826",
                    "client"            => "android",
                    "uuid"              => "862033030912743_jdy",
                    "version"           => "2.2.101",
                    "sign"              => "96ac0d4c62f8f51990a7273943343805",
                    "unique"            => "1476966079-970807-69-abd4c15e1b605c412fe2ade54601d740",
                    "project_id"        => "2000",

                    "type"              => "50",
                    "request"           => "invest_credit_assign_agreement",
                ]
            ],
        ];

        $dataProvider = $array;

//        foreach($typeData as $type){//合同
//            $array[0]['data']['type'] = $type;
//            $array[0]['data']['request'] = "invest_contract";
//            $dataProvider[] = $array[0];
//        }

//        foreach($typeData as $type){//协议
//            $array[0]['data']['type'] = $type;
//            $array[0]['data']['request'] = "invest_agreement";
//            $dataProvider[] = $array[0];
//        }

        return $dataProvider;
    }
    /**
     * 合同测试
     * @dataProvider dataProviderCreate
     */
    public function testContract($is, $data){

        if($is === true) {
            $this->post('/app/gateway', $data)
                ->seeJson([
                    'status' => '2000',
                ]);
        }else{
            $this->post('/app/gateway', $data)
                ->seeJson([
                    'status' => "2000",
                ]);
        }
    }

//    /**
//     * 协议测试
//     * @dataProvider dataProviderCreate
//     */
//    public function testAgreement($is, $data){
//        if($is === true) {
//            $this->post('/app/gateway', $data)
//                ->seeJson([
//                    'status' => "2000",
//                ]);
//        }
//    }
}