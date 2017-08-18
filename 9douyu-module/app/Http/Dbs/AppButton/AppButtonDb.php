<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/4
 * Time: 下午5:11
 */

namespace App\Http\Dbs\AppButton;


use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class AppButtonDb extends JdyDb
{

    const
        BUTTON_OPEN                 = 200,  //开启状态
        BUTTON_CLOSE                = 100,  //关闭状态
        POSITION_CENTER             = 1,    //中间图片
        POSITION_DOWN               = 2,    //下边图片
        REFUND_RECORD_PIC_URL       = 1,    //回款计划图片
        BONS_PIC_URL                = 2,    //我的优惠券图片
        BAKN_PIC_URL                = 3,    //我的银行卡图片
        TRANSACTION_PIC_URL         = 4,    //交易明细图片
        CREDIT_ASSIGN_PIC_URL       = 5,    //债权转让图片
        INVITATION_FIRENDS_PIC_URL  = 6;    //邀请好友图片


    /**
     * @return mixed
     * @desc 用户中心app按钮列表
     */
    public function getAppUserCenterButton(){

        return $this->where('position',self::POSITION_CENTER)
            ->where('status',self::BUTTON_OPEN)
            ->orderBy('position_num')
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 请求tabBar图片
     */
    public function getAppUserDownButton(){

        $dbNow = ToolTime::dbNow();

        return $this->where('position', self::POSITION_DOWN)
            ->where('status', self::BUTTON_OPEN)
            ->where('start_time', '<=', $dbNow)
            ->where('end_time', '>=', $dbNow)
            ->orderBy('position_num')
            ->get()
            ->toArray();


    }

    //新增针对WAP端的中心区域菜单的对应链接
    static public function centerMenuParam()
    {
        return [
            '1' => ['location'=>'/RefundPlan/',     'image' => assetUrlByCdn('/static/weixin/images/wap2/wap2-asset-icon1.png') ],  //回款计划
            '2' => ['location'=>'/bonus/index/',    'image' => assetUrlByCdn('/static/weixin/images/wap2/wap2-asset-icon2.png') ],  //我的优惠券
            '3' => ['location'=>'/bank/userCard',   'image' => assetUrlByCdn('/static/weixin/images/wap2/wap2-asset-icon3.png') ],  //我的银行卡
            '4' => ['location'=>'/user/account',    'image' => assetUrlByCdn('/static/weixin/images/wap2/wap2-asset-icon4.png') ],  //交易明细
          //'5' => ['location'=>'/OwnCreditAssign', 'image' => assetUrlByCdn('/static/weixin/images/wap2/wap2-asset-icon5.png') ],  //债权转让
        ];

    }

}