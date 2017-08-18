<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/12/27
 * Time: 上午11:33
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\ReceiveBonusLogic;
use App\Tools\ToolJump;
use Illuminate\Http\Request;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\Partner\PartnerLogic;

class ReceiveBonusController extends WeixinController
{


    public function index( Request $request)
    {
        $token          =   strtolower($request->input('token',''));

        $client         =   RequestSourceLogic::getSource();

        //$receiveTotal   =   ReceiveBonusLogic::getUserReceiveBonusTotal();

        $activityTime   =   ReceiveBonusLogic::getActivityTime();

        //$bonusLit       =   ReceiveBonusLogic::getBonusList();

        $userId         =   $this->getUserId();

        if( $client == 'android' && $userId ){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);

        }

//        $userReceiveTotal   =   [];
//
//        if( $userId && $userId != 0 ){
//
//            $userReceiveTotal   =   ReceiveBonusLogic::getUserReceiveBonusTotal($userId);
//        }
        
        //设置登录跳转url
        ToolJump::setLoginUrl('/activity/bonusDay');

        $viewsData      =   [
            'activityTime'      =>  $activityTime,
            //'receiveTotal'      =>  $receiveTotal,
            'client'            =>  $client,
            'token'             =>  $token,
            'userStatus'        => (!empty($userId)||$userId!=0) ? true : false,
            //'userReceiveTotal'  => $userReceiveTotal,
            //'bonusLit'          =>  $bonusLit,
        ];
        //dd($viewsData);
        return view('wap.activity.receiveBonus.currentAdd',$viewsData);
    }

    /**
     * @param Request $request
     * @return array
     * @desc 执行红包的领取
     */
    public function doReceiveBonus(Request $request)
    {
        $userId     =   $this->getUserId();

        $bonusId    =   $request->input('bonus_id','');
        //时间判断
        $receiveStatus =   ReceiveBonusLogic::isCanReceiveBonus($userId,$bonusId);

        if( $receiveStatus['status'] ==false ){

            return $receiveStatus;
        }

        return  ReceiveBonusLogic::doReceiveBonus($userId,$bonusId);

    }
    
}