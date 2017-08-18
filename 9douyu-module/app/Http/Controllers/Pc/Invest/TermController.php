<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/8
 * Time: 下午2:33
 * Desc: 项目投资
 */

namespace App\Http\Controllers\Pc\Invest;

use App\Http\Controllers\Controller;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\SessionLogic;
use Illuminate\Http\Request;
use Redirect;

class TermController extends Controller
{

    /**
     * 确认投资页
     * @param Request $request
     * @return mixed
     */
    public function confirm( Request $request )
    {
        $assign['cash']         = $request->input('cash');
        $assign['userBonusId']  = $request->input('userBonusId');
        $assign['projectId']    = $request->input('projectId');

        $assign['bonusType']    = 100;    //使用类型,红包还是加息券
        $assign['bonusValue']   = '2%';    //值

        $assign['user'] = SessionLogic::getTokenSession();
        
        
        $termLogic = new TermLogic();
        $assign['fee'] = $termLogic->getProfit($assign['projectId'],$assign['cash'],0);
        //TODO 确认投资页
        return view('pc.invest.project.confirm',$assign);



    }

    /**
     * 提交投资数据
     * @param Request $request
     * @return mixed
     */
    public function submit(Request $request){

        $userId             = $this->getUserId();

        $projectId          = $request->input('projectId');

        $userBonusId        = $request->input('userBonusId');

        $cash               = $request->input('cash');
        
        $source             = $request->input('source');

        $tradingPassword    = $request->input('trading_password');

        $termLogic          = new TermLogic();
        //投资检测
        $res                = $termLogic->checkInvest($userId,$projectId,$cash,$userBonusId,$source,$tradingPassword);

        if(!$res['status']){
            return Redirect::to('project/detail/'.$projectId)->with('errors',$res['msg']);
        }
        //投资操作
        $invest = $termLogic->invest($userId,$projectId,$cash,$userBonusId,$source);

        if(!$invest['status']){
            return Redirect::to('invest/term/fail');
        }
        return Redirect::to('invest/term/success');
    }

    /**
     * 投资成功页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success(){
        $from = RequestSourceLogic::getSource();
        //TODO 定期投资成功模版数据
        return view($from.'.invest.success');
    }

    /**
     * 投资失败页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fail(){
        $from = RequestSourceLogic::getSource();
        //TODO 定期投资成功模版数据
        return view($from.'.invest.fail');
    }



}
