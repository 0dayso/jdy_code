<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/25
 * Time: 15:51
 */
namespace App\Http\Controllers\Weixin\Invest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\User\UserLogic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CurrentController extends UserController{

    public function __construct()
    {
        parent::__construct();
        $this->checkIdentity();
    }


    /**
     * @return mixed
     * 微信端零钱计划投资确认页
     */
    public function confirm(){

        $from           = RequestSourceLogic::getSourceKey('wap');
        $logic          = new CurrentLogic();
        $userId = $this->getUserId();

        $viewData       = $logic->projectDetail($userId,$from);

        $userInfo       = $this->getUser();

        //获取用户状态
        $userStatus     = UserLogic::getUserAuthStatus($userInfo);

        $assign             = $viewData['data'];
        $assign['showStatus'] = $userStatus;

        //测试页面
        return view('wap.invest.current.confirm',$assign);
    }


    /**
     * @param CurrentRequest $request
     * @return mixed
     * 零钱计划投资
     */

    public function doInvest(Request $request){

        $data               = $request->all();

        $cash               = (int)$request->input('cash',0);
        $data['user_id']    = $this->getUserId();
        $data['from']       = RequestSourceLogic::getSource();
        $data['cash']       = $cash;    //金额处理成分
        $data['bonus_id']   = $request->input('bonus_id',0);

        $logic              = new CurrentLogic();

        $checkUserInvest = $logic->checkAjax($data['user_id'],$cash);

        if($checkUserInvest['status'] == false){

            return Redirect::to("/invest/current/confirm")->with('errors',$checkUserInvest['msg'])->withInput();

        }

        $result             = $logic->doInvest($data);

        //投资成功跳转到指定页面
        if($result['status']){
            //设置session
            $data = [
                'investType'    => 1,
                'cash'          => $cash,
                'bonus_id'      => $data['bonus_id']
            ];
            session($data);
            return Redirect::to("/invest/current/investSuccess");

        }else{

            return Redirect::to("/invest/current/confirm")->with('errors',$result['msg'])->withInput();

        }

    }

    /**
     * @return mixed
     * 零钱计划转出成功页
     */
    public function investSuccess(){

        //获取session的数据
        $investType    = session('investType');
        $cash          = session('cash');
        $bonusId       = session('bonus_id');

        if(empty($investType)){

            return Redirect::to("/");
        }else{

            //设置session
            $data = [
                'investType'    => null,
                'cash'          => null,
                'bonus_id'      => null
            ];

            session($data);
        }

        $logic      = new CurrentLogic();
        $userId     = $this->getUserId();
        $from       = RequestSourceLogic::getSourceKey('wap');
        $result     = $logic->getInvestData($bonusId,$cash,$userId,$from);

        return view('wap.invest.current.investSuccess',$result);

    }


    /**
     * 微信端零钱计划转出前的页面
     */
    public function investOut(){

        $userId = $this->getUserId();

        $logic = new CurrentLogic();
        $data = $logic->getWapInvestData($userId);

        return view('wap.invest.current.investOut',$data);
    }


    /**
     * @param Request $request
     * 零钱计划转出页面
     */
    public function doInvestOut(Request $request){

        $data               = $request->all();
        $userId             = $this->getUserId();
        $data['user_id']    = $userId;
        $data['from']       = RequestSourceLogic::getSource();

        $cash               = (float)$request->input('cash',0);
        $data['cash']       = $cash;

        $logic              = new CurrentLogic();
        $result             = $logic->doInvestOut($data);

        //投资成功跳转到指定页面
        if($result['status']){

            //设置session
            $data = [
                'investOutType' => 1,
                'cash'          => $cash
            ];
            session($data);
            return Redirect::to("/invest/current/investOutSuccess");
        }else{

            return Redirect::to("/invest/current/investOut")->with('errors',$result['msg'])->withInput();

        }
    }

    /**
     * @return mixed
     * 零钱计划转出成功页
     */
    public function investOutSuccess(){

        //获取session的数据
        $investOutType = session('investOutType');
        $cash          = session('cash');

        if(empty($investOutType)){

            return Redirect::to("/");
        }else{

            $data = [
                'investOutType' => null,
                'cash'          => null
            ];
            session($data);
        }

        $userInfo = $this->getUser();
        $viewData['balance'] = $userInfo['balance'];
        $viewData['cash']    = $cash;

        return view('wap.invest.current.investOutSuccess',$viewData);
    }

}