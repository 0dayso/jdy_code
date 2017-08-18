<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/11/16
 * Time: 19:21
 */

namespace Tests\Http\Controllers\Admin\Credit;



use App\Http\Logics\Credit\CreditLogic;
use App\Http\Logics\Credit\CreditThirdDetailLogic;
use App\Tools\ToolArray;
use Illuminate\Database\Eloquent\Model;

class CreditDiversInvestTest extends \TestCase
{

    /**
     * @desc 债权id供给
     * @return array
     */
    public function dataCreditId(){

        $creditId = [
            [
                'credit_third_id' => 15
            ]
        ];
        return $creditId;
    }

    /**
     * @desc 获取债权人信息列表
     * @param $creditId
     * @dataProvider dataCreditId
     */
    public function testCreditDetailList($creditId){

        $creditDetailLogic = new CreditThirdDetailLogic();

        $CreditDetail = $creditDetailLogic->getAbleCreditDetailList($creditId);

       var_dump($CreditDetail);

    }

    /**
     * @desc 从数据库获取债权人信息的数据供给
     * @return array
     */
    public function dataCreditProviderTwo(){

        parent::setUp();

        $creditId = 15;

        $creditDetailLogic = new CreditThirdDetailLogic();

        $CreditDetail = $creditDetailLogic->getAbleCreditDetailList($creditId);

        $creditDetailData =  [
            [
                'amount'       => 8000,
                'creditDetailData' => $CreditDetail['data'],
                'investDetail' =>[
                    0 =>[
                        'id' =>  259,
                        'invest_amount' => 2000.0
                    ],
                    1 =>[
                        'id' =>  260,
                        'invest_amount' => 2000.0
                    ],
                    2 =>[
                        'id' =>  261,
                        'invest_amount' => 2000.0

                    ],
                    3 =>[
                        'id' =>  262,
                        'invest_amount' => 1000.0
                    ],
                    4 =>[
                        'id' =>  263,
                        'invest_amount' => 1000.0
                    ],
                ]

            ]
        ];
        return $creditDetailData;
    }

    /**
     * @desc 获取债权列表的数据供给
     * @return array
     */
    public function dataProviderGetLists(){
        $investList  =  [
            [
                'investDetail' =>[
                    0 =>[
                        'id' =>  259,
                        'invest_amount' => 2000.0
                    ],
                    1 =>[
                        'id' =>  260,
                        'invest_amount' => 2000.0
                    ],
                    2 =>[
                        'id' =>  261,
                        'invest_amount' => 2000.0

                    ],
                    3 =>[
                        'id' =>  262,
                        'invest_amount' => 1000.0
                    ],
                    4 =>[
                        'id' =>  263,
                        'invest_amount' => 1000.0
                    ],
                ]
                ]
        ];
        return $investList;
    }

    /**
     * @desc 债权分散投资的数据模型供给
     */
    public function dataCreditProvider(){

        $creditDetail = [
            [
                'amount' => 8000,

                'creditDetail' =>[
                    0 =>[
                        'id' =>1,
                        'usable_amount' =>3000,
                    ],
                    1 =>[
                        'id' =>2,
                        'usable_amount' =>5000,
                    ],
                    2 =>[
                        'id' =>3,
                        'usable_amount' =>2100,
                    ],
                    3 =>[
                        'id' =>4,
                        'usable_amount' =>500,
                    ]
                ],
                'investDetail' =>[
                    0 =>[
                        'id' =>1,
                        'invest_amount' => 3000
                    ],
                    1 =>[
                        'id' =>2,
                        'invest_amount' => 2500
                    ],
                    2 =>[
                        'id' =>3,
                        'invest_amount' => 2000

                    ],
                    3 =>[
                        'id' =>4,
                        'invest_amount' => 500
                    ],
                ]
            ]
        ];
        return $creditDetail;
    }

    /**
     * @desc 本地模拟数据执行投资分散债权匹配
     * @param $amount
     * @param $creditDetail
     * @param $investDetail
     * @dataProvider  dataCreditProvider
     */
    public function testDoDiverInvestOne($amount, $creditDetail, $investDetail){

        $investResult = CreditLogic::doDiversificationInvest($amount, $creditDetail);

        $this->assertEquals($investDetail, $investResult);
    }

    /**
     * @desc 从数据库读取债权数据执行分散投资债权匹配
     * @param $amount
     * @param $creditArr
     * @param $investDetail
     * @dataProvider dataCreditProviderTwo
     */
    public function testDoDiversInvestTwo($amount,$creditArr,$investDetail){

        $investResult = CreditLogic::doDiversificationInvest($amount, $creditArr);

        //设置断言
        $this->assertEquals($investDetail,$investResult);
    }

    /**
     * @desc  债权匹配返回结果更新债权信息
     * @param $amount
     * @param $creditArr
     * @dataProvider dataCreditProviderTwo
     */
    public function testDoUpdateCreditResult($amount, $creditArr){

        CreditLogic::doDiversificationInvest($amount, $creditArr);
        $return = CreditThirdDetailLogic::updateCreditDiversInvest($creditArr);

        $this->assertEquals('200', $return['code']);
    }

    /**
     * @desc 获取投资的合同债权信息
     * @param $investList
     * @dataProvider dataProviderGetLists
     */
    public function  testGetListByIds($investList){

        $investIds = ToolArray::arrayToIds($investList, 'id');

        $creditList = CreditThirdDetailLogic::getCreditListByIds($investIds);
        $investList = ToolArray::arrayToKey($investList,'id');
        foreach($creditList as $key=>$value){
            if(isset($investList[$value['id']])){
                $creditList[$key]['used_amount'] = $investList[$value['id']]['invest_amount'];
            }
        }

        dd($creditList);
    }

}