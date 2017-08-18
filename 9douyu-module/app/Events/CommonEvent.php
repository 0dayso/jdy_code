<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2017/3/3
 * Time: 下午12:43
 * Desc: 公共事件类,监听者获取数据源会统一,而且该类可以横向扩展接口,给其他监听者提供服务
 */

namespace App\Events;

class CommonEvent extends Event{

    /**
     * @var array @desc 传入的event参数
     */
    protected $data = [];

    public function __construct($data = [])
    {

        $this->data = $data;

    }

    /**
     * @param string $key
     * @return array|mixed
     * @desc 获取data, 参数key为数组的键
     */
    public function getDataByKey($key=''){

        return (isset($this->data[$key]) && !empty($key)) ? $this->data[$key] : $this->data;

    }

}