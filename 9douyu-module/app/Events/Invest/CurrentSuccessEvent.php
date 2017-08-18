<?php

namespace App\Events\Invest;

use App\Events\Event;

use Log;

/**
 * 零钱计划转入成功事件
 * Class CurrentSuccessEvent
 * @package App\Events\Invest
 */
class CurrentSuccessEvent extends Event
{
    /**
     * @var array 传入event参数
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array
     * 获取加息券信息
     */
    public function getBonusData(){

        return [
            'user_id'   => $this->data['user_id'],
            'bonus_id'  => $this->data['bonus_id']
        ];
    }

    /**
     * 获取项目数据
     */
    public function getProjectData(){

        return [
            'left_amount' => $this->data['left_amount'],
            'cash'        => $this->data['cash']
        ];
    }



}
