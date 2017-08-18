<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/11
 * Time: 20:32
 */

namespace App\Http\Controllers\Pc\Invest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\Invest\CurrentLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Requests\Invest\CurrentRequest;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;
use App\Http\Logics\Project\CurrentLogic as CurrentProjectLogic;
use Illuminate\Support\Facades\Redirect;

use Cache;

class CurrentController extends UserController{

    /**
     * @param CurrentRequest $request
     * @return mixed
     * 零钱计划投资
     */

    public function doInvest(CurrentRequest $request){

        $data               = $request->all();

        $cash               = (float)$request->input('cash',0);
        $data['user_id']    = $this->getUserId();
        $data['from']       = RequestSourceLogic::getSource();
        $data['cash']       = ToolMoney::formatDbCashAdd($cash);    //金额处理成分
        $data['bonus_id']   = $request->input('bonus_id',0);

        $logic              = new CurrentLogic();
        $result             = $logic->doInvest($data);

        //页面套完后,删除该行
        //return self::returnJson($result);

        
        
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

            session(['bonus_id'=>$data['bonus_id']]);
            session(['cash'=>$cash]);
            return Redirect::to("/invest/current/confirm")->with('msg',$result['msg'])->withInput();

        }


    }

    /**
     * @return mixed
     * pc端零钱计划投资成功
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
        $from       = RequestSourceLogic::getSourceKey('pc');
        $result     = $logic->getInvestData($bonusId,$cash,$userId,$from);

        return view('pc.invest.current.success',$result);
    }

    /**
     * @param CurrentRequest $request
     * 零钱计划转入确认页面
     */
    public function confirm(Request $request){

        $userId     = $this->getUserId();

        $bonusId    = (int)$request->input('bonus_id',0);
        $bonusId    = empty($bonusId)? (int)session('bonus_id') : $bonusId;
        $cash       = (int)$request->input('cash',0);
        $cash       = empty($cash) ? (int)session('cash') : $cash;
        
        $cash       = ToolMoney::formatDbCashAdd($cash);
        
        $logic      = new CurrentLogic();

        $checkUserInvest = $logic->checkAjax($userId,$cash);

        if($checkUserInvest['status'] == false){

            return Redirect::to("/project/current/detail")->with('msg',$checkUserInvest['msg'])->withInput();

        }

        $from       = RequestSourceLogic::getSourceKey('pc');
        $result     = $logic->getInvestData($bonusId,$cash,$userId,$from);

        $userLogic  = new UserLogic();
        $userInfo   = $userLogic->getUserInfoById($userId);

        $result['balance'] = $userInfo['balance'];

        return  view('pc.invest.current.confirm',$result);
    }


    /**
     * @param Request $request
     * 零钱计划转出页面
     */
    public function doInvestOut(Request $request){
        
        $data               = $request->all();
        $data['user_id']    = $this->getUserId();
        $data['from']       = RequestSourceLogic::getSource();

        $cash               = (float)$request->input('cash',0);
        $data['cash']       = ToolMoney::formatDbCashAdd($cash);

        $logic              = new CurrentLogic();
        $result             = $logic->doInvestOut($data);

        //页面套完后,删除该行
        return self::returnJson($result);
        
    }
    
}