<?php
/**
 * Created by PhpStorm.
 * User: bihua
 * Date: 16/7/22
 * Time: 下午2:58
 * Desc: 定期项目投资
 */

namespace App\Http\Controllers\Weixin\Invest;

use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\Project\ProjectModel;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Cache;

class ProjectController extends UserController{

    /**
     * @param int $id
     * @desc 投资页
     */
    public function confirm( $id = 0){

        //项目信息
        $logic = new ProjectDetailLogic();

        $project     = $logic->get($id);

        if(empty($project))

            return Redirect::to("/project/index");

        //用户信息
        $userId = $this->getUserId();

        $userInfo = $this->getUser();

        //优惠券
        $userBonusLogic = new UserBonusLogic();
        $bonusInfo = $userBonusLogic->getAppUserUsableBonus($userId,$project['id'],'wap');

        $assign = [
            'project'       => $project,
            'investMinCash' => ProjectModel::getInvestMinCashByProductLine($project['product_line']),
            'balance'   => $userInfo['balance'],
            'userId'    => $userId,
            'bonus'     => $bonusInfo['data']['list'],
            'bonusNum'  => $bonusInfo['data']['count'],
            'msg'       => session('msg') ? session('msg') : ''
        ];

        return view('wap.invest.project.confirm',$assign);
    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 确认投资
     */
    public function doInvest( Request $request){

        $userId = $this->getUserId();

        $projectId = $request->input('project_id');

        $cash      = $request->input('cash');

        $bonusId   = $request->input('bonus_id');

        $tradePassword = $request->input('trade_password');
        
        $termLogic = new TermLogic();

        //$appRequest              = RequestSourceLogic::getSourceKey('wap');

        $result = $termLogic->doInvest($userId,$projectId,$cash,$tradePassword,$bonusId,'wap',self::getActToken());

        if($result['status']){

            return Redirect::to('/invest/project/success');
        }else{

            return redirect()->back()->withInput($request->input())->with('msg', $result['msg']);
        }

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投资成功
     */
    public function success(){

        $investId  = Cache::get('invest_id');

        if(empty($investId)){

            return Redirect::to("/project/lists");
        }

        Cache::forget('invest_id');

        $invest        = InvestModel::getInvestByInvestId($investId);

        $projectLogic  = new ProjectDetailLogic();

        //项目信息
        $project       = $projectLogic->get($invest['project_id'], true);

        $termLogic     = new TermLogic();

        $bonusLogic    = new BonusLogic();

        $bonus         = $bonusLogic->getBonusValueByType($invest['bonus_type'],$invest['bonus_value']);

        //预期收益 项目基本利率收益+加息券收益
        //$fee           =  $termLogic->getProfit($invest['project_id'],$invest['cash'],$bonus['rate']);


        //首次回款
        $refund        = $termLogic->getFirstRefund($invest['project_id'], $invest['cash'],$invest['created_at']);

        $assign    = [
            'cash'            => $invest['cash'],
            'project'         => $project,
            'refund_times'    => $refund['times'],
            'projectId'       => $invest['project_id'],
            'profit'          => $refund['interest'],
            'rate'            => $bonus['rate'],
        ];

        return view('wap.invest.project.success',$assign);
    }

    /**
     * @param $id
     * @desc 投资详情
     */
    public function detail($investId)
    {

        $userId = $this->getUserId();

        $termLogic = new TermLogic();

        $result = $termLogic->getInvestDetailByIdForApp($userId, $investId);

        print_r($result);

    }

    /**
     * @return bool
     * @desc 获取活动标示
     */
    protected static function getActToken()
    {
       return  \Session::get('ACT_TOKEN');
    }
}