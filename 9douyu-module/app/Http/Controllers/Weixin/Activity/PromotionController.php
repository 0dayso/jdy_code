<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/10/11
 * Time: 下午2:14
 * Desc: 推广相关
 */

namespace App\Http\Controllers\Weixin\Activity;

use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Controllers\Weixin\WeixinController;
use App\Http\Logics\Activity\PromotionLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use Illuminate\Http\Request;
use App\Http\Logics\Media\ChannelLogic;
use Redirect;

class PromotionController extends WeixinController{


    public function index( Request $request )
    {

        if( $this->checkLogin() ){

            return Redirect::to('/user');

        }

        $logic = new ChannelLogic();

        $promotionLogic =   new PromotionLogic();

        $channel = $request->input('channel','');

        $timeArr    =   $promotionLogic->getTime();

        $data['channel'] = $channel;

        $data['package'] = $logic->getPackage($channel);//推广包名

        $configArr = SystemConfigLogic::getConfig('PROMOTION');

        $data['registerWord'] = isset($configArr['register_word']) ? $configArr['register_word'] : '提交注册';

        //$statistics  =   $promotionLogic->getStatistics();

        //$data   =   array_merge($data,$statistics);

        if($timeArr['start'] > time() ){
            return view('wap/activity/novice/extension', $data);
        }
        return view('wap/activity/novice/extension1', $data);

    }

    public function success( Request $request )
    {
        /*
        if( $this->checkLogin() ){

            return Redirect::to('/user');

        }
        */
        $data['phone'] = $request->input('phone');

        $logic = new ChannelLogic();

        $channel = $request->input('channel','');

        $data['package'] = $logic->getPackage($channel);//推广包名

        $configArr = SystemConfigLogic::getConfig('PROMOTION');

        $data['awardWord'] = isset($configArr['award_word']) ? explode('|', $configArr['award_word']) : '';

        //$data['channel_url'] = '/zt/appguide?channel='.$request->input('channel', '');
        $promotionLogic =   new PromotionLogic();

        $timeArr        =   $promotionLogic->getTime();
        if($timeArr['start'] > time() ){
            return view('wap/activity/novice/arrival', $data);
        }
       return view('wap.activity.novice.arrival1',$data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 理财列表页
     */
    public function noviceProjectList(Request $request)
    {

        $client     =   RequestSourceLogic::getSource();

        $token      =   $request->input('token');

        $logic      =   new PromotionLogic();

        $project    =   $logic->getFormatProject();

        $viewData   =   [
            'creditProject' => $project['show'],
            'moreProject'   => $project['more'],
            'currentProject'=> $project['current'],
            'client'    =>  $client,
        ];

        return view('wap/activity/novice/product',$viewData);
    }


    // 新手狂欢

    public function introduce(Request $request)
    {
        $client         =   RequestSourceLogic::getSource();

        $token          =   $request->input('token');

        $userId         =   $this->getUserId();

        if($client == 'android' && $userId){

            $partnerLogic   =   new PartnerLogic();

            $partnerLogic->setCookieAndroid($token, $client);
        }

        $promotionLogic =   new PromotionLogic();

        //$statistics     =   $promotionLogic->getStatistics();

        $activityTime   =   $promotionLogic->getTime();

        $viewData       =   [
            //'data'           =>  $statistics,
            'activityTime'   =>  $activityTime,
            'client'         =>  $client,
            'userStatus'     => (!empty($userId)||$userId!=0) ? true : false,
            ];

        return view('wap.activity.novice.introduce',$viewData);
    }

    // ROI注册落地页
    public function roiIndex( Request $request )
    {

        $data['isLogin'] = $this->checkLogin() ? 1 : '';

        $logic = new ChannelLogic();

        $promotionLogic = new PromotionLogic();

        $projectLogic = new ProjectLogic();

        $project = $projectLogic->getPfbProjectDetail();
        $data['project'] = $project['data'];

        $channel = $request->input('channel', '');

        $data['channel'] = $channel;

        $data['package'] = $logic->getPackage($channel);//推广包名

        //$statistics = $promotionLogic->getStatistics();

        //$data = array_merge($data, $statistics);

        $roiProjectId = !empty($project['data']['id']) ? $project['data']['id'] : '';
        \Session::put("roiProjectId", $roiProjectId);

        return view('wap.activity.novice.another', $data);
    }
}
