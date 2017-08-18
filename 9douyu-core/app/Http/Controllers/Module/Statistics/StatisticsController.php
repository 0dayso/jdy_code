<?php

namespace App\Http\Controllers\Module\Statistics;


use App\Http\Controllers\Controller;
use App\Http\Logics\Module\Invest\StatisticsLogic;

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/9/22
 * Time: 下午7:14
 */
class StatisticsController extends Controller
{

    /**
     * @SWG\Post(
     *   path="/getStatistics",
     *   tags={"Statistics:九斗鱼平台数据"},
     *   summary="获取九斗鱼平台数据",
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    /**
     * @return array
     * @desc 平台数据统计
     */
    public function getStatistics()
    {

        $logic = new StatisticsLogic();

        $list = $logic->getStatistics();

        return self::returnJson($list);

    }


    /**
     * @SWG\Post(
     *   path="/getHomeStat",
     *   tags={"Statistics:九斗鱼后台首页数据"},
     *   summary="获取九斗鱼后台首页数据",
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getHomeStat()
    {

        $logic  = new StatisticsLogic();
        $list   = $logic->getHomeStat();
        return  self::returnJson($list);

    }


}