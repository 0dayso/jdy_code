<?php
/**
 * create By Phpstorm
 * @author linguanghui
 * Date 16/07/25  AM 10:43
 * @desc 微信首页
 */
namespace App\Http\Controllers\Weixin\Home;

use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Common\IncomeModel;

class IndexController extends WeixinController{
    /**
     * @desc Wap端首页
     * @author linguanghui
     */
    public function index(){
        $userId = $this->getUserId();
        $from           = RequestSourceLogic::getSource();
        //零钱计划数据
        $currentLogic  = new CurrentLogic();
        $current       = $currentLogic->projectDetail($userId,$from);
        $current['data']['formatFreeAmount'] = round($current['data']['freeAmount']/IncomeModel::TEN_THOUSANDS, 2); //格式化剩余可投金额
        $wapBannerList = AdLogic::getUseAbleListByPositionId(2);
        $data = [
            'current'       => $current['data'],
            'indexActive'   => 'active',
            'wapBannerList' => $wapBannerList
        ];
        return view('wap.home.index', $data);
    }
}