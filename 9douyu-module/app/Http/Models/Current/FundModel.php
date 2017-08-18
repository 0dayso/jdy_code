<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/15
 * Time: 11:05
 */
namespace App\Http\Models\Current;

use App\Http\Dbs\Current\FundStatisticsDb;
use App\Http\Dbs\Current\RateDb;
use App\Http\Dbs\Fund\FundHistoryDb;
use App\Http\Models\Common\CoreApi\CurrentModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Tools\ToolTime;
use App\Lang\LangModel;

class FundModel extends Model{

    public static $codeArr = [
        'getFundBaseData'             => 1,

    ];

    const BASE_RATE             = 7.0,
        BASE_INTEREST_TYPE      = 1,
        BONUS_INTEREST_TYPE     = 2;



    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_CURRENT_FUND_STATISTICS;

    /**
     * 获取前天的汇总数据
     */
    public function getFundBaseData(){
        
        $db = new FundStatisticsDb();
        
        $date = ToolTime::getDateBeforeCurrent(2);
            
        $data = $db->getByDate($date);

        if(empty($data)){

            throw new \Exception(sprintf(LangModel::getLang('ERROR_CURRENT_FUND_STATISTICS_NOT_FOUND'),$date), self::getFinalCode('getFundBaseData'));

        }

        return $data;
        
    }

    /**
     * @desc 按照统计日期获取获取零钱计划汇总信息
     * @author Liguanh
     * @param $date
     * @return array|mixed
     */
    public function getFundByDate($date)
    {
        if (empty($date)){
            return [];
        }
        $db = new FundStatisticsDb();

        $fundData = $db->getByDate($date);

        return $fundData;
    }

    
    public function updateFundInterest(){

        $fundData = $this->getFundBaseData();

        $interest = $fundData['interest'];

        $data           = CurrentModel::getYesterdayInterest();

        $cost           = 0;
        $dayInterest    = 0;

        $rateDb = new RateDb();
        $rateInfo = $rateDb->getYesterdayRate();
        $rate = $rateInfo['rate'];


        if(!empty($data)){

            $baseType   = self::BASE_INTEREST_TYPE;
            $bonusType  = self::BONUS_INTEREST_TYPE;

            if(isset($data[$bonusType])){

                $bonusInterest = $data[$bonusType]['total_interest'];

                $cost           += $bonusInterest;
                $dayInterest    += $bonusInterest;
                $interest       += $bonusInterest;
            }


            if(isset($data[$baseType])){

                $baseTotalInterest = $data[$baseType]['total_interest'];

                $interest  += $baseTotalInterest;
                $dayInterest += $baseTotalInterest;

                if($rate > self::BASE_RATE){

                    $cost += round($baseTotalInterest * (($rate - self::BASE_RATE) / $rate),2);
                }
            }
        }


        $data = [
            //'cash'           => $fundData['cash'] + $interest,
            'day_interest'   => $dayInterest,
            'interest'       => $interest,
            'rate'           => $rate,
            'cost'           => $cost,
        ];

        $db = new FundStatisticsDb();

        $date = ToolTime::getDateBeforeCurrent();

        $db->updateRecord($date,$data);


    }

    /**
     * @param $data
     * 以前天的数据来生成昨日的资金记录,并作更新
     */
    public function addFundRecord($data){

        $yesterdayFund = CurrentModel::getYesterdayFund();
        //零钱计划的资金数据
        $currentAccount= CurrentModel::getCurrentAccountAmount();

        $cash               = $data['cash'];
        $investIn           = 0;
        $investOut          = 0;
        $totalInvestIn      = $data['total_invest_in'];
        $totalInvestOut     = $data['total_invest_out'];
        $yesterdayInterest  = $data['day_interest'];

        if($yesterdayFund){

            $investCurrentEvent = FundHistoryDb::INVEST_CURRENT;
            $investOutEvent     = FundHistoryDb::INVEST_OUT_CURRENT;
            $investAutoEvent    = FundHistoryDb::INVEST_CURRENT_AUTO;

            if(isset($yesterdayFund[$investCurrentEvent])){

                $investCash     = $yesterdayFund[$investCurrentEvent]['balance_change'];

                $cash           += $investCash;

                $investIn       += $investCash;

                $totalInvestIn  += $investCash;
            }


            if(isset($yesterdayFund[$investAutoEvent])){

                $investCash     = $yesterdayFund[$investAutoEvent]['balance_change'];

                $cash           += $investCash;

                $investIn       += $investCash;

                $totalInvestIn  += $investCash;
            }

            if(isset($yesterdayFund[$investOutEvent])){

                $investOutCash     = $yesterdayFund[$investOutEvent]['balance_change'];

                $cash               -= $investOutCash;

                $investOut          += $investOutCash;

                $totalInvestOut     += $investOutCash;
            }

        }

        $list = [
            'cash'                  => isset($currentAccount['current_cash']) ? $currentAccount['current_cash'] : $cash + $yesterdayInterest,
            'invest_in'             => $investIn,
            'invest_out'            => $investOut,
            'total_invest_in'       => $totalInvestIn,
            'total_invest_out'      => $totalInvestOut,
            'date'                  => ToolTime::getDateBeforeCurrent(),
        ];

        $db = new FundStatisticsDb();

        $db->addRecord($list);
    }
    
}