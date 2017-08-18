<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/2/23
 * Time: 下午2:12
 */

namespace Tests\Http\Logics\Invest;

use App\Http\Logics\Invest\TermLogic;

ini_set('date.timezone','Asia/Shanghai');
class DetailInvestTest extends \TestCase
{
    public function investData(){
        return [
            //不用红包投资
            [
                'is' => true,
                'data' => [
                    "userId" => "1426125",
                    "projectId" => "3244",
                    "cash" => "2000",
                    "tradePassword" => "123qwe",
                    "bonusId" => "",
                    "appRequest" => "",
                ]
            ],
            //即时生效红包投资
            [
                'is' => true,
                'data' => [
                    "userId" => "1426125",
                    "projectId" => "3244",
                    "cash" => "3000",
                    "tradePassword" => "123qwe",
                    "bonusId" => "2250189",
                    "appRequest" => "pc",
                ]
            ],
            //在有效期内使用红包投资
            [
                'is' => true,
                'data' => [
                    "userId" => "1426125",
                    "projectId" => "3244",
                    "cash" => "4000",
                    "tradePassword" => "123qwe",
                    "bonusId" => "2250192",
                    "appRequest" => "pc",
                ]
            ],
            //未到有效期内红包投资
            [
                'is' => false,
                'data' => [
                    "userId" => "1426125",
                    "projectId" => "3244",
                    "cash" => "5000",
                    "tradePassword" => "123qwe",
                    "bonusId" => "2250197",
                    "appRequest" => "pc",
                ]
            ],
            //过期红包投资
            [
                'is' => false,
                'data' => [
                    "userId" => "1426125",
                    "projectId" => "3244",
                    "cash" => "6000",
                    "tradePassword" => "123qwe",
                    "bonusId" => "2250183",
                    "appRequest" => "pc",
                ]
            ],
        ];
    }

    /**
     * @param $is
     * @param $data
     * @dataProvider investData
     */
    public function testDoSend($is, $data){
        $logic = new TermLogic();
        $result = $logic -> doInvest($data['userId'],$data['projectId'],$data['cash'],$data['tradePassword'],$data['bonusId'],$data['appRequest']);

        if($is === true) {
            print_r($result);
            $this->assertTrue($result['status']);
        }else{
            print_r($result);
            $this->assertNotTrue($result['status']);
        }
    }
}