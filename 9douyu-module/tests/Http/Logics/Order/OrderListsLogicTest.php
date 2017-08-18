<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 上午11:22
 */

namespace Tests\Http\Logics\Order;

use App\Http\Logics\Order\OrderListsLogic;

/**
 * 资金 记录/流水
 * Class OrderListsLogicTest
 * @package App\Http\Logics\Order
 */
class OrderListsLogicTest extends \TestCase{

    /**
     * 获取订单记录
     * todo 待完善
     * @param array $data
     */
    public function testGetList($data = []){
        $data['userId']  = 10;
        $data['page']    = 1;
        $data['size']    = 20;


        $return = OrderListsLogic::formatGetListOutput($data);

        echo print_r($return, true);
    }
}