<?php

namespace App\Events\User;

use App\Events\Event;
use Log;
use Illuminate\Support\Facades\Request;

/**
 * 新手注册成功奖励事件
 * Class RegistrationSuccessfulEvent
 * @package App\Events\User
 */
class NoviceRewardsEvent extends Event
{
    /**
     * @var array 传入event参数
     */
    public $data = [];

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;

        Log::info('data：' . json_encode($this->data));
    }

}
