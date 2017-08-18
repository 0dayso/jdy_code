<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午8:16
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Http\Request;
use App\Tools\ToolCurl;
use App\Http\Logics\Micro\MicroJournalLogic;
use Cache;

class CustodyController extends WeixinController{


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 江西银行资金存管
     */
    
    public function index()
    {

        return view('wap.activity.custody.index');

    }

    public function second()
    {

        return view('wap.activity.custody.second');

    }

    
}
