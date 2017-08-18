<?php
/**
 * 账户中心
 * User: bihua
 * Date: 16/7/26
 * Time: 10:44
 */
namespace App\Http\Controllers\Weixin\User;

use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\AppButton\AppButtonLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Fund\FundHistoryLogic;
use App\Http\Logics\Order\OrderListsLogic;
use App\Http\Logics\User\UserLogic;

class IndexController extends UserController
{

    public function appendConstruct(){
        \Debugbar::disable();
    }

    protected $perPageSize = 10;

    function index(){

        $userId = $this->getUserId();

        $userLogic = new UserLogic();

        $user = $userLogic->getAppUserInfo($userId);

        //菜单
        $appButtonLogic = new AppButtonLogic();

        $menu = $appButtonLogic->getUserCenterMenu($userId);
        $assign = [
            'user'        => $user['data']['items'],
            'menuList'    => $menu['data'],
            'userActive'  => 'active',
        ];

        return view('wap.user.index.user',$assign);
    }


    /**
     * 交易明细
     */
    public function accountBalance()
    {
        $user        = $this->getUser();
        $accountLog  = $this->getLogList();

        $assign = [
            'title'         => '交易明细',
            'balance'       => $user['balance'],
            'totalRecharge' => isset($accountLog['data']['Summary']['recharge_summary']) ? $accountLog['data']['Summary']['recharge_summary'] : 0.00,
            'withdraw'      => isset($accountLog['data']['Summary']['withdraw_summary']) ? $accountLog['data']['Summary']['withdraw_summary'] : 0.00,
            'LogList'       => isset($accountLog['data']) ? $accountLog['data'] : [],
        ];

        return view('wap.user.AccountBalance/balance', $assign);
    }


    /**
     * 交易明细
     *
     * @return array|string
     */
    public function getLogList(){
        $request         = app('request');
        $type            = $request->input('t', 1);

        $data['page']    = $request->input('p', 1);
        $data['size']    = $this->perPageSize;
        $data['user_id'] = $this->getUserId();

        if($type == 1) {
            $return = FundHistoryLogic::getListByType($data);
        }else{
            $return = OrderListsLogic::formatGetListOutput($data);
        }
        $return['data']['size'] = $data['size'];

        $ajax = [];
        if($request->ajax()){
            $assign          = ['LogList' => $return['data']];
            $content         = view('wap.user.AccountBalance/_balance_child', $assign)->render();
            $ajax['content'] = $content;
            $ajax['type']    = $type;
            $ajax['page']    = $data['page'];
            return json_encode($ajax);
        }
        return $return;

    }

    /**
     * @desc 用户可用优惠券列表
     * @author lgh-dev
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ableBonusList(){

        $userId = $this->getUserId();

        $userBonusLogic = new UserBonusLogic();

        $ableBonusList = $userBonusLogic->getBonus($userId);

        $assign['ableBonus']  =  $ableBonusList;

        return view('wap.user.bonus.index',$assign);
    }

    /**
     * @desc 用户已过期的优惠券列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unableBonusList(){

        $userId = $this->getUserId();

        $userBonusLogic = new UserBonusLogic();

        $unableBonusList = $userBonusLogic->getExpireListByUserId($userId);

        $assign['unableBonus']  =  $unableBonusList;

        return view('wap.user.bonus.unable', $assign);
    }

}
