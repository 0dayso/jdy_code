<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/31
 * Time: 下午3:26
 */
namespace Tests\Http\Models\Credit\Lable;

use App\Http\Models\Credit\Lable\CreditFactoringModel;

use App\Http\Models\Credit\Lable\CreditLableModel;
/**
 * 保理债权按标签筛选数据
 *
 * Class CreditFactoringModelTest
 */
class CreditFactoringModelTest extends \TestCase
{

    /**
     * 获取未使用的债权集合
     * @return mixed
     */
    public function testGetUnusedCreditList(){
        $obj  = new CreditFactoringModel;
        $data = $obj->getUnusedCreditList();
        $this->assertTrue(is_array($data));
        return $data;
    }


    /**
     * @depends testGetUnusedCreditList
     * 根据标示 更新状态
     */
    public function testUpdateStatus(array $data){
        $tmp = [];
        if($data){
            foreach($data as $item){
                $tmp[] = ['id'=>$item['id'], 'update_status_identifier'=>$item['update_status_identifier'], 'status_code'=>200, 'cash'=> 20];
            }
        }
        if($tmp) {
            $is = CreditLableModel::occupationCanUseAmount($tmp);
            $this->assertTrue($is);

            $is = CreditLableModel::returnCanUseAmount($tmp);

            $this->assertTrue($is);


        }
    }
}