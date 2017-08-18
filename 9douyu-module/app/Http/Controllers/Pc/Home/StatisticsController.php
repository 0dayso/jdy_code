<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 20:55
 * Desc: 首页平台数据详情
 */

namespace App\Http\Controllers\Pc\Home;

use App\Http\Controllers\Controller;
use App\Http\Logics\Project\ProjectLogic;

class StatisticsController extends Controller{

    /**
     * 首页平台数据详情
     */
    public function index(){
        
        $logic      = new ProjectLogic();
        $result     = $logic->getHomeStatisticsDetail();

        return self::returnJson($result);
    }
}