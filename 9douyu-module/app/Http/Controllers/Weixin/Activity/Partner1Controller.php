<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午8:16
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\Invite\InviteRatesLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use Illuminate\Http\Request;
use Redirect;

class Partner1Controller extends UserController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 合伙人首页数据
     */
    public function partner1( Request $request ){



        /*$a = Array
        (
            'share_title' => '点击领取收益!',
            'share_desc' => '我在九斗鱼赚了好多钱，邀请你一起来，注册后即可赚钱！',
            'share_url' => 'http://testwx2.9douyu.com/activity/partner1?from=app',
            'invite_url' => '1',
            'purl' => 'http://testwx2.9douyu.com/static/images/partner_share.png',
            'share_img' => 'http://testwx2.9douyu.com/static/images/partner_share.png',
            'share_type' => 1
);

        echo serialize($a);die;*/

        $userId = $this->getUserId();

        $partnerLogic = new PartnerLogic();

        if( $request->input('client') == 'android' && $userId ){

            $token = strtolower($request->input('token'));

            $partnerLogic->setCookieAndroid($token, $request->input('client'));

        }

        $partnerInfo = $partnerLogic->getUserIndexData($userId);

        $userInfo = $partnerLogic->getPartnerInviteData($userId);

        if( !empty($partnerInfo) ){

            $data = array_merge($partnerInfo, $userInfo);

        }else{

            $data = $userInfo;

        }

        $data = $this->getShareConfig($userId, $data);

        return view('wap.activity.partner3.partner1', $data);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 收益排行
     */
    public function partner2(){

        $userId = $this->getUserId();

        $partnerLogic = new PartnerLogic();

        $partnerInfo = $partnerLogic->getUserIndexData($userId);

        $sortList = $partnerLogic->getInterestCashSort();

        $userInfo = $partnerLogic->getUserInfo($userId);

        $data = [
            'user_info'     => $userInfo,
            'partner_info'  => $partnerInfo,
            'list'          => $sortList
        ];

        $data = $this->getShareConfig($userId, $data);

        return view('wap.activity.partner3.partner2', $data);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 邀请列表页面
     */
    public function partner3(Request $request){

        $userId = $this->getUserId();

        $logic = new PartnerLogic();

        $page = $request->input('page', 1);

        $size = $request->input('size', 20);

        $data['partner_info'] = $logic->getUserIndexData($userId);

        $data['user_info'] = $logic->getUserInfo($userId);

        $data['invite_list'] = $logic->getInviteListByUserId($userId, $page, $size);

        $data = $this->getShareConfig($userId, $data);

        return view('wap.activity.partner3.partner3', $data);

    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 执行使用
     */
    public function doUseRate( Request $request ){

        $logic = new InviteRatesLogic();

        $userId = $this->getUserId();

        $id = $request->input('id');

        $result = $logic->doUse($id, $userId);

        if( $result['status'] ){

            return Redirect::to("/activity/partner1")->with('message', "使用成功");

        }else{

            return Redirect::to("/activity/partner1")->with('message', $result['msg']);

        }

    }


    private function getShareConfig($userId, $data){

        //获取合伙人活动配置
        $data['shareConfig'] = SystemConfigLogic::getConfig('WX_ACTIVE_INVITE');
        $data['shareConfig']['line_link'] = !empty($data['shareConfig']['line_link']) ? $data['shareConfig']['line_link'] : '';
        if( $userId ){

            $data['shareConfig']['line_link'] = $data['shareConfig']['line_link'].'?inviteId='.$userId;

        }

        $wechat = app('wechat');

        $data['js'] = $wechat->js;

        return $data;

    }




}
