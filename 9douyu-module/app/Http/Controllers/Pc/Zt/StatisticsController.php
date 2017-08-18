<?php
/**
 * create by Phpstorm
 * User lgh-dev
 * Date 16/10/11 Time 13:54
 * Desc 平台数据统计
 */

namespace App\Http\Controllers\Pc\Zt;

use App\Http\Controllers\Pc\PcController;
use App\Http\Logics\Statistics\StatLogic;
use App\Http\Models\Common\CoreApi\StatisticsModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;

class StatisticsController extends PcController{

    public function index(){
        $statisticsData  = StatisticsModel::getStatistics(false);

        //融资总额
        $assign['totalAmount'] = (int)$statisticsData['projectInvestAmount'] + (int)$statisticsData['currentInvestAmount']+(int)$statisticsData['creditAssignInvestAmount'];
        //直投项目
        $assign['projectInvestAmount'] = (int)$statisticsData['projectInvestAmount'];
        //债转投资总额
        $assign['creditAssignInvestAmount'] = (int)$statisticsData['creditAssignInvestAmount'];
        //零钱计划
        $assign['currentInvestAmount'] = (int)$statisticsData['currentInvestAmount'];
        //累计预期收益
        $assign['totalInterest'] = (int)($statisticsData['totalInterest']);
        //安全归还用户本息
        $assign['refundAmount'] = (int)($statisticsData['refundAmount']);
        //注册人数
        $assign['userCount'] = $statisticsData['userCount'];
        //当前时间
        $assign['nowDay']   = date('Y-m-d H:i:s',ToolTime::getUnixTime( ToolTime::dbDate() )-1) ;
        //代收数据
        $assign['collect']  =   $statisticsData['collect'];
        //
        $assign['currentCashTotal'] =   isset($statisticsData['currentCashTotal']) ? $statisticsData['currentCashTotal'] : '0';
        //项目满标时间
        $assign['hundred']  =   $statisticsData['projectTotalHundredPercent'];
        
        $borrowStat         =   StatLogic::getBorrowingData();

        $assign['borrow']   =   [
            'investTotal'   =>  $borrowStat['investTotal'], //总出借笔数
            'investNumber'  =>  $borrowStat['investNumber'], //总出款人数
            'borrowTotal'   =>  $borrowStat['thirdTotal'] + $statisticsData['projectTotal'],
            'borrowNumber'  =>  round(($borrowStat['thirdTotal'] + $statisticsData['projectTotal']) * 90/100 ),
        ];
        //return $assign;
        return view('pc.zt.information' , $assign);
        //return view('pc.zt.statistics', $assign);
    }
}