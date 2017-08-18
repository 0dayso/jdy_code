<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午8:16
 */

namespace App\Http\Controllers\Weixin\Activity;


use App\Http\Controllers\Weixin\UserController;
use App\Http\Dbs\User\InviteDb;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\User\PasswordLogic;
use App\Http\Logics\User\UserLogic;
use App\Tools\ToolQrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Session;

class UserPartnerController extends UserController
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 合伙人账户信息页
     */
    public function index(){

        $this->redirect('/activity/partner1');


        //昨日佣金收益，累计佣金收益
        $userId               = $this->getUserId();
        $logic                = new PartnerLogic();

        $userInfo             = $logic->isPartnerByUserId($userId);
        if(!$userInfo){
            $this->redirect('/activity/y2015partner');
        }

        //邀请合伙人数
        $inviteCount           = $logic->getInviteUserCount($userId);
        //被邀请人待收本金
        $refundInfo            = $logic->getPrincipalByInvestIds($userId);
        //佣金率
        $rate                  = $logic->getProfitByCash($refundInfo['total_cash'])*100;
        //累计佣金排名
        //$sort                  = $logic->getOneSort($userInfo['interest']);
        //是否已进行实名认证
        $checkTradingPassword  = $logic->getUserAuthStatus($userId);
        //公告链接
        $partnerConfig         = $logic->getDefineConfig();
        //用户邀请码
        $inviteCode            = $logic->getInviteCodeByUserId($userId);

        $userLogic      = new UserLogic();

        $user          = $userLogic->getUserInfoById($userId);

        $userStatus    = UserLogic::getUserAuthStatus($user);

        $view = [
            'inviteCount'           => $inviteCount,
            'userInfo'              => $userInfo,
            'refundInfo'            => $refundInfo,
            'rate'                  => $rate,
            'url'                   => $partnerConfig['ANNOUNCEMENT_URL'],
            'inviteCode'            => $inviteCode,
            //'sort'                  => $sort,
            'checkTradingPassword'  => $checkTradingPassword,
            'untoken'               => md5(time()),
            'status'                => 3,
            'isTradePassword'       => $userStatus['password_checked']=='on' ? true : false,
        ];

        return view('wap.activity.partner.partner-information', $view);

    }

    /**
     * @desc 参与合伙人活动
     * @return mixed
     */
    public function createPartner()
    {
        $this->redirect('/activity/partner1');

        $userId = $this->getUserId();

        $logic = new PartnerLogic();

        $isPartner = $logic->isPartnerByUserId($userId);

        if (!$isPartner) {

            $result = $logic->create($userId);

            if (!$result['status']) {
                return Redirect::to('/Activity/y2015partner');
            } else {
                return Redirect::to('/ActivityPartner/');
            }

        }

        return Redirect::to('/ActivityPartner/');

    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 转出收益
     */
    public function doWithdraw(Request $request){

        //给用户3秒的转出弹窗时间
        //作为工程师,我他么一定要选择不执行产品这种shabi需求,说是有了这三秒能提高用户的刺激性。
        //每天赚1毛钱有啥个刺激的?如果每次直接给用户10000元加币,就算没有页面也刺激
        //sleep(3);

        $token             = $request->input('csrf_token');

        $sToken            = Session::get('csrf_token');

        if( empty($token) ){

            return Redirect::to("/activity/partner1");

        }

        if( $token == $sToken ){

            return Redirect::to("/activity/partner1")->with('message', "请勿重复提交");

        }

        Session::put('csrf_token',$token);

        $cash               = (int)$request->input('cash');

        $userId             = $this->getUserId();

        if( $cash < 1 ){

            return Redirect::to("/activity/partner1")->with('message', "转出金额不能少于1元");

        }

        $logic = new PartnerLogic();

        $data = array(
            'user_id'           => $userId,
            'cash'              => $cash,
        );

        $res            = $logic->doInvestOut($data);

        if($res['status']) {

            //\Cache::put($userId.'_I_O_C', $cash, 2);

            return Redirect::to("/activity/partner1")->with('message', '佣金转出成功');

            /*Session::put('partner_type','invest_out');
            Session::put('partner_res','success');
            Session::put('partner_cash',$cash);
            return Redirect::to("/ActivityPartner/turnOutSuccess");*/
        } else {
            return Redirect::to("/activity/partner1")->with('message', $res['msg']);
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 转出成功页
     */
    public function turnOutSuccess(){

        return Redirect::to("/activity/partner1");

        $cash = Session::get('partner_cash');

        if(empty($cash)){

            return Redirect::to("/activity/partner1");

        }

        Session::forget('partner_cash');

        $view = [
            'turnCash' => $cash,
        ];

        return view('wap.activity.partner.turnout', $view);
    }

    /**
     * @author gyl-dev
     * 扫码
     */
    public function scanCode(){

        $userId = $this->getUserId();
        if( empty($userId) ){
            return Redirect::to("/ActivityPartner");
        }

        $Logic = new PartnerLogic();
        $config = $Logic->getDefineConfig();
        $startTime   = $config['PARTNER_START_TIME'];
        $endTime     = $config['PARTNER_END_TIME'];
        $source = InviteDb::TYPE_PARTNER;
        $url = env('APP_URL_WX')."/register?inviteId=".$userId."&type=".$source;
        if( date('Y-m-d H:i:s',time()) > $startTime && date('Y-m-d H:i:s',time()) < $endTime ){
            $data['qrCodePath']   = ToolQrCode::createCode($url, true);
            return view('wap.activity.partner.scanCode', $data);
        }else{
            return Redirect::to("/ActivityPartner");
        }


    }

}