<?php

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\WeixinController;

class TestController extends WeixinController{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 测试界面
     */
    public function test(){

        $userId = $this->getUserId();

        return view('wap.activity.test', ['userId'=>$userId]);

    }

}

?>