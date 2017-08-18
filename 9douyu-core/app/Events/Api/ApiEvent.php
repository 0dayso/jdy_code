<?php

namespace App\Events\Api;
use App\Events\Event;

/**
 * 核心Api事件基类
 * @author Zjmainstay
 *
 * @package App\Events\Api
 */
class ApiEvent extends Event
{
    protected $_data = array();
    
    public function __construct($data) {
        $this->_data = $data;
    }
    
    public function getData() {
        return $this->_data;
    }
}
