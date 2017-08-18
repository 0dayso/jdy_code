<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/27
 * Time: 上午11:47
 */

namespace App\Http\Controllers\Weixin\Module;

use App\Http\Logics\Weixin\Module\MenuLogic;

use App\Http\Controllers\Weixin\WeixinController;

use Log;
/**
 * 微信菜单
 *
 * Class WeixinServerMenuController
 * @package App\Http\Controllers\Weixin\Module
 */
class WeixinServerMenuController extends WeixinController
{
    /**
     * 添加菜单
     */
    public function add(){
        $menuLogic = new MenuLogic();
        $isAdd     = $menuLogic->add();
        Log::info(__METHOD__, [$isAdd]);
        dd($isAdd);
    }


    /**
     * 删除所有菜单
     */
    public function del(){
        $wechat        = app('wechat');
        $isDestroy     = $wechat->menu->destroy();
        Log::info(__METHOD__, [$isDestroy]);

        dd($isDestroy);
    }

}