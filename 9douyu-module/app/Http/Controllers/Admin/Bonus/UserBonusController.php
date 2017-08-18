<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/7
 * Time: 下午3:14
 * Desc: 用户优惠券控制器
 */

namespace App\Http\Controllers\Admin\Bonus;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\AdminUser;
use Illuminate\Http\Request;

class UserBonusController extends AdminController
{

    protected $homeName = '优惠管理';

    /**
     * @desc 发送红包/加息券
     */
    public function sendBonus(){

        $bonusDb = new BonusDb();

        $viewData = [
            'home'          => $this -> homeName,
            'title'         => '发放优惠券',
            'bonus_list'    => $bonusDb->getCanSendList(),//给包列表
        ];

        return view('admin.bonus.send', $viewData);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc  执行发送红包
     */
    public function doSendBonus(Request $request){

        $data = $request -> all();

        $data['from_type']      = UserBonusDb::FROM_TYPE_ADMIN;

        $data['send_user_id']   = AdminUser::getAdminUserId();  // 发送人id

        $logic = new UserBonusLogic();

        $result = $logic -> doSendBonus($data);

        if($result['status']){
            return redirect('/admin/bonus/send')->with('message', '优惠券发送成功');
        }else {
            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }

    }

    /**
     * @param $userId
     */
    public function getUserBonus($userId){

        //可用
        //已使用
        //已过期

    }

    public function getUserBonusByProject($productLine,$type,$userId){

        //未过期,当前产品线可使用的红包例表

    }

    /**
     * @desc 红包延期页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bonusDelay(Request $request){
        $userBonusLogic = new UserBonusLogic();
        $phone = $request->input('phone');
        $assign['phone'] = $phone;
        if($phone){
            $userInfo  = UserModel::getBaseUserInfo($phone);
            $userId = $userInfo['id'];
            //获取用户未使用的红包
            $assign['userBonusList'] = $userBonusLogic->getNoUsedUserBonus($userId);
        }
        return view('admin.bonus.delay', $assign);
    }

    /**
     * @desc 红包延期处理
     * @param Request $request
     * @return string
     */
    public function doBonusDelay(Request $request){
        $userBonusLogic = new UserBonusLogic();

        $userBonusId   = $request->input('userBonusId');
        $useEndTime   = $request->input('userEndTime');

        //用户红包延期处理
        $result = $userBonusLogic->doDelayUserBonus($userBonusId, $useEndTime);
        return self::ajaxJson($result);

    }

    /**
     * @param Request $request
     * @desc  读取红包数据
     */
    public function getUserBonusStatus( Request $request)
    {
        $startTime      =   $request->input('start_time');

        $endTime        =   $request->input("end_time");

        $params         =  ['start_time'=>$startTime , 'end_time'=>$endTime];

        if( empty($startTime) && empty($endTime) ){

            $viewData   =   [ 'bonusList'  =>  [], 'params' =>  $params, "bonus_total"=>0, 'rate_interest'=>0 ];

            return view('admin.bonus.usedStatus',$viewData);

        }

        $searchDiff     =   UserBonusLogic::setDiffSearchTime($startTime,$endTime);

        if( $searchDiff['status'] == false){

            return redirect()->back()->withInput($request->input())->with('message', $searchDiff['msg']);
        }

        $bonusList      =   UserBonusLogic::getUserBonusStatus($startTime,$endTime);

        $viewData       =   [
            'bonusList'     =>  $bonusList['list'],
            'params'        =>  $params,
            'bonus_total'   =>  $bonusList['total_money'],
            'rate_interest' =>  0,
        ];

        return view('admin.bonus.usedStatus',$viewData);


    }
}