<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/7/12
 * Time: 下午2:58
 * Desc: 定期项目投资
 */

namespace App\Http\Controllers\Pc\Invest;

use App\Http\Controllers\Pc\PcController;
use App\Http\Controllers\Pc\UserController;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\Invest\ProjectLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Models\Invest\InvestModel;
use App\Tools\ToolTime;
use Illuminate\Http\Request;
use Cache;

class ProjectController extends UserController{


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @desc 投资
     */
    public function doInvest( Request $request )
    {

        $userId                  = $this->getUserId();

        if(empty($userId)){

            return redirect("/project/index");
        }

        $cash                    = $request->input('cash');

        $userBonusId             = $request->input('userBonusId');

        $balance                 = $request->input('balance');

        $project                 = $request->input('project');

        $bonusRate               = $request->input('bonus_profit',0);

        $bonusMoney              = $request->input('bonus_money',0);

        $project['project_line'] = $project['product_line'] + $project['type'];

        $termLogic               = new TermLogic();

        $result                  = $termLogic->checkInvest($userId, $project, $cash, $balance, $bonusMoney, $bonusRate);

        if( $result['status'] ){

            $totalCash           = $cash+$bonusMoney;

            $fee    = $termLogic->getProfit($project['id'],$totalCash,$bonusRate);

            $assign = array(
                'balance'       => $balance,
                'cash'          => $cash,
                'userBonusId'   => $userBonusId,
                'project'       => $project,
                'fee'           => $fee,
                'bonusType'     => $bonusMoney > 0 ? BonusDb::TYPE_CASH : BonusDb::TYPE_COUPON_INTEREST,
                'bonusValue'    => $bonusMoney > 0 ? $bonusMoney.'元' : $bonusRate.'%',
                'total_cash'    => $totalCash
            );

            return view('pc.invest.project.confirm',$assign);

        }else{

            return redirect()->back()->withInput($request->input())->with('msg', $result['msg']);

        }

    }

    /**
     * @return bool
     * @投资确认页捕获活动标示
     */
    protected static function getActToken()
    {
        return \Session::get('ACT_TOKEN');
    }
    /**
     * @param Request $request
     * @desc 确认执行投资
     */
    public function confirmInvest( Request $request )
    {

        $userId                  = $this->getUserId();

        $projectId               = $request->input('project_id');

        $cash                    = $request->input('cash');

        $bonusId                 = $request->input('bonus_id');

        $tradePassword           = $request->input('trade_password');

        $termLogic             = new TermLogic();

        $appRequest              = RequestSourceLogic::getSource();

        $result                  = $termLogic->doInvest($userId, $projectId, $cash, $tradePassword, $bonusId, $appRequest , self::getActToken());

        return self::returnJson($result);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @desc 投资成功页
     */
    public function success(){

        $investId  = Cache::get('invest_id');

        if(empty($investId)){

            return redirect("/project/index");
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
        $fee           =  $termLogic->getProfit($invest['project_id'],$invest['cash'],$bonus['rate']);

        //首次回款
        $refund        = $termLogic->getFirstRefund($invest['project_id'], $invest['cash'],$invest['created_at']);

        //投资成功广告位
        $adInfo = AdLogic::getUseAbleListByPositionId(18);

        if( !empty($adInfo) && isset($adInfo[0]) ){

            $adInfo = $adInfo[0];

        }

        $assign    = [
            'cash'            => $invest['cash'],
            'invest_time'     => $project['format_invest_time'],
            'unit'            => $project['invest_time_unit'],
            'profit'          => $project['profit_percentage'],
            'interest'        => $fee,
            'rate'            => $bonus['rate'],
            'refund_interest' => $refund['cash'],
            'refund_times'    => $refund['times'],
            'refund_type'     => $project['refund_type'],
            'end_time'        => $project['end_at'],
            'ad_info'         => $adInfo
        ];
        
        return view('pc.invest.project.success',$assign);

    }


}