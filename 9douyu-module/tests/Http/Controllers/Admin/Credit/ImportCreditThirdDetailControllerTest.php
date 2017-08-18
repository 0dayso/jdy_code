<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/11/23
 * Time: 14:34
 * Desc: 第三方债权信息导入
 */

namespace Tests\Http\Controllers\Admin\Credit;


use App\Http\Models\Credit\CreditThirdCreditModel;
use App\Tools\ToolArray;

class ImportCreditThirdDetailControllerTest extends \TestCase
{
    /**
     * @desc
     * @return array
     */
    public function dataCreditProvider(){

        parent::setUp();

        $result = \DB::select("select id,credit_list from module_credit_third");

        $result = ToolArray::objectToArray($result);

        return [
          [
              'credit_list' =>$result
          ]
        ];
    }

    /**
     * @desc 导入债权的操作
     * @param $credit_list
     * @dataProvider dataCreditProvider
     */
    public function testImportCreditData($credit_list){

        foreach($credit_list as $key=>$value){

            $data = [
               'credit_id' => $value['id'],
               'credit_list' => $value['credit_list'],
            ];

            \Event::fire(new \App\Events\Admin\Credit\CreditThirdDetailEvent(
                ['data'=> $data]
            ));
        }

    }
}