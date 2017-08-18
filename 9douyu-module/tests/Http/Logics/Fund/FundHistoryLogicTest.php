<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 上午11:22
 */

namespace Tests\Http\Logics\Fund;

use App\Http\Logics\Fund\FundHistoryLogic;

/**
 * 资金 记录/流水
 * Class FundHistoryLogicTest
 * @package App\Http\Logics\Fund
 */
class FundHistoryLogicTest extends \TestCase{

    /**
     * 获取资金记录
     * @param array $data
     */
    public function testGetList($data = []){
        $data['user_id']  = 10;
        $data['page']    = 1;
        $data['size']    = 20;
        $data['type']    = 'all';

        $return = FundHistoryLogic::getListByType($data);

        echo print_r($return, true);
    }
}