<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/31
 * Time: 下午3:26
 */
namespace Tests\Http\Models\Credit\Lable;

use App\Http\Models\Credit\Lable\CreditLightningSixModel;

use App\Http\Models\Credit\Lable\CreditLableModel;
/**
 * 闪电付息债权按标签筛选数据
 *
 * Class CreditLightningModelSixTest
 */
class CreditLightningModelSixTest extends \TestCase
{

    /**
     * 获取未使用的债权集合
     * @return mixed
     */
    public function testGetUnusedCreditList(){
        $obj  = new CreditLightningSixModel;
        $data = $obj->getUnusedCreditList();
        $this->assertTrue(is_array($data));
        return $data;
    }

}