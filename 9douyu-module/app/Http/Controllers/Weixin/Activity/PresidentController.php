<?php
/**
 * Created by PhpStorm.
 * User: tianxiaoyan
 * Date: 16/12/27
 * Time: 下午5:00
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Illuminate\Http\Request;

class PresidentController extends WeixinController{

    public function president()
    {
        return view('wap.activity.president.president');
    }

}