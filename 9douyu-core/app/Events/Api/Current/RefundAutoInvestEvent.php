<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/8
 * Time: 11:06
 */


namespace App\Events\Api\Current;

use App\Events\Api\ApiEvent;

/**
 * 回款自动进零钱计划事件
 * Class RefundAutoInvestEvent
 * @package App\Events\Api\Current
 */
class RefundAutoInvestEvent extends ApiEvent
{

    /**
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
    }
}
