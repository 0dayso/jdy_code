<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/13
 * Time: 下午3:50
 */

namespace App\Http\Dbs;


use App\Tools\ToolTime;
use DB;

class FundHistoryDb extends JdyDb
{

    protected $table = 'fund_history';
    public $timestamps = false;

    const   PROJECT_REFUND          = 200,  //回款
            INVEST_PROJECT          = 300,  //定期
            INVEST_CURRENT          = 400,  //零钱计划
            INVEST_OUT_CURRENT      = 401,  //零钱计划转出
            INVEST_CURRENT_AUTO     = 402,  //回款自动转零钱计划
            INVEST_CREDIT_PROJECT   = 500,  //债转
            RECHARGE_ORDER          = 600,  //充值
            WITHDRAW_ORDER          = 700,  //提现
            WITHDRAW_ORDER_FAILED   = 701,  //提现失败
            WITHDRAW_ORDER_CANCEL   = 702,  //取消提现
            ACTIVITY_AWARD          = 800,  //活动奖励
            CREDIT_ASSIGN_PROJECT   = 900,  //出让
            INVEST_CREDIT_ASSIGN    = 901,  //投资
            CHARGE_BALANCE          = 1000, //扣费
            INVEST_CURRENT_NEW      = 1100, //新活期投资
            INVEST_OUT_CURRENT_NEW  = 1101, //新活期转出

            END=true;


    /**
     * @param $data
     * @return bool
     * @desc 插入记录
     */
    public function add($data)
    {

        if( $data['balance']<0 ){

            return false;

        }

        $this->user_id = $data['user_id'];

        $this->balance_before = $data['balance_before'];

        $this->balance_change = $data['balance_change'];

        $this->balance = $data['balance'];

        $this->event_id = $data['event_id'];

        $this->note = isset($data['note']) ? $data['note'] : '';

        $this->save();

        return $this->id;

        //return $res;


    }

    /**
     * @param $userId
     * @param $cash
     * @param $eventId
     * @param $note
     * @return array
     * @desc 获取用户的资金
     */
    public function getFundData($userId, $cash, $eventId, $note)
    {
        $userDb = new UserDb();

        $userInfo = $userDb->getInfoById($userId);

        $data = [
            'user_id'           => $userId,
            'balance_before'    => round($userInfo['balance']-$cash, 2),
            'balance_change'    => $cash,
            'balance'           => round($userInfo['balance'], 2),
            'event_id'          => $eventId,
            'note'              => $note
        ];

        return $data;

    }

    /**
     * @param string $sTime
     * @param string $eTime
     * @return mixed
     * @desc 获取零钱计划资金变动的集合
     */
    public function getCurrentEventAssemble($userIds, $sTime='', $eTime='')
    {

        $sTime = $sTime ? $sTime : ToolTime::dbDate();

        return DB::table('fund_history')
            ->select(DB::raw('user_id, sum(balance_change) as balance_change'))
            ->where('created_at', '>', $sTime)
            ->whereIn('event_id', $this->getCurrentEventList())
            ->whereIn('user_id',$userIds)
            ->groupBy('user_id')
            ->get();

    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户今日零钱计划帐户变化情况
     */
    public function getTodayUserInvestCurrentAmount($userId){

        $date = ToolTime::dbDate();

        return DB::table('fund_history')
            ->select(DB::raw('user_id, sum(balance_change) as balance_change'))
            ->where('created_at', '>', $date)
            ->whereIn('event_id', $this->getCurrentEventList())
            ->where('user_id',$userId)
            ->first();
    }


    /**
     * @return mixed
     * 获取零钱计划用户今日转出总金额
     */
    public function getTodayCurrentInvestOutAmount(){

        $date = ToolTime::dbDate();

        return self::where('event_id',self::INVEST_OUT_CURRENT)
            ->where('created_at', '>', $date)
            ->sum('balance_change');
    }

    /**
     * @return mixed
     * 获取零钱计划用户今日转入总金额
     */
    public function getTodayCurrentInvestAmount(){

        $date = ToolTime::dbDate();

        return self::where('created_at', '>', $date)
            ->whereIn('event_id',[self::INVEST_CURRENT_AUTO,self::INVEST_CURRENT])
            ->sum('balance_change');
    }
    /**
     * @param $userIds
     * @return mixed
     * 获取多个用户今日零钱计划变化情况
     */
    public function getCurrentCashChangeByUserIds($userIds){

        $date = ToolTime::dbDate();

         return DB::table('fund_history')
            ->select(DB::raw('user_id, sum(balance_change) as balance_change'))
            ->where('created_at', '>', $date)
            ->whereIn('event_id',$this->getCurrentEventList())
            ->whereIn('user_id',$userIds)
            ->groupBy('user_id')
            ->get();

    }


    /**
     * @param $userId
     * @return mixed
     * 获取用户今日的自动回款记录
     */
    public static function getTodayAutoInvestRecord($userId){

        $date = ToolTime::dbDate();

        return  self::where('user_id',$userId)
            ->where('event_id',self::INVEST_CURRENT_AUTO)
            ->where('created_at','>',$date)
            ->count();
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户当日自动转入金额,目前只是回款自动转入的金额;
     * 
     */
    public static function getTodayAutoInvestCurrentTotalByUserId($userId){

        $date = ToolTime::dbDate();

        return  self::where('user_id',$userId)
            ->where('event_id',self::INVEST_CURRENT_AUTO)
            ->where('created_at','>',$date)
            ->sum('balance_change');
    }

    /**
     * @param string $date
     * @return mixed
     * @desc 根据时间获取平台自动转入零钱计划金额总数
     */
    public static function getTodayAutoInvestCurrentTotal($date=''){

        $date = $date ? $date : ToolTime::dbDate();

        return  self::where('event_id',self::INVEST_CURRENT_AUTO)
            ->where('created_at','>',$date)
            ->sum('balance_change');
    }
    
    
    

    /**
     * @param $userId
     * @return mixed
     * 获取零钱计划用户今日转出总金额
     */
    public function getTodayInvestOutAmount($userId){

        $date   = ToolTime::dbDate();

        return DB::table('fund_history')
            ->where('created_at', '>', $date)
            ->where('event_id',self::INVEST_OUT_CURRENT)
            ->where('user_id',$userId)
            ->sum('balance_change');
    }

    /**
     * @return mixed
     * 获取零钱计划总的转入金额
     */
    public function getInvestAmount(){

        return DB::table('fund_history')
            ->whereIn('event_id',[self::INVEST_CURRENT,self::INVEST_CURRENT_AUTO])
            ->sum('balance_change');
    }

    /**
     * @return mixed
     * 获取零钱计划总的转入金额
     */
    public function getInvestAmountWithoutAuto(){

        return DB::table('fund_history')
            ->where('event_id',self::INVEST_CURRENT )
            ->sum('balance_change');
    }
    /**
     * @param $userId
     * @return mixed
     * 获取用户零钱计划转入\转出总记录条数
     */
    public function getCurrentRecordNum($userId){

        $eventIds = $this->getCurrentEventList();

        return  self::whereIn('event_id',$eventIds)
            ->where('user_id',$userId)
            ->count();
    }


    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return mixed
     * 分页获取零钱计划转入 转出记录
     */
    public function getCurrentListByPage($userId,$page,$size){

        $start = ( max(0, $page -1) ) * $size;

        $eventIds = $this->getCurrentEventList();

        return self::whereIn('event_id',$eventIds)
            ->where('user_id',$userId)
            ->skip($start)
            ->take($size)
            //->orderBy('id','desc')
            ->orderBy('created_at','desc')
            ->get()
            ->toArray();
    }

    /**
     * @return array
     * 获取零钱计划事件列表
     */
    private function getCurrentEventList(){

        $eventIds = [
            self::INVEST_CURRENT,
            self::INVEST_OUT_CURRENT,
            self::INVEST_CURRENT_AUTO,
        ];

        return $eventIds;
    }

    /**
     * 获取今日有充值\提现\投资的用户列表
     */
    public function getTodayFundList(){

        $date = ToolTime::dbDate();
        $eventIds = [
            self::INVEST_PROJECT,   //定期投资
            self::RECHARGE_ORDER,   //充值
            self::WITHDRAW_ORDER,   //提现
            self::INVEST_CURRENT_AUTO //自动回款进零钱计划
        ];

        return self::select('user_id')
                ->whereIn('event_id',$eventIds)
                ->where('created_at','>=',$date)
                ->groupBy('user_id')
                ->get()
                ->toArray();
    }


    /**
     * @return mixed
     * 获取昨日零钱计划转入转出资金总额
     */
    public function getYesterdayCurrentFundData(){

        $today      = ToolTime::dbDate();
        $yesterday  = ToolTime::getDateBeforeCurrent();

        return self::select(DB::raw('event_id, sum(balance_change) as balance_change'))
            ->where('created_at', '>=', $yesterday)
            ->where('created_at', '<', $today)
            ->whereIn('event_id', $this->getCurrentEventList())
            ->groupBy('event_id')
            ->get()
            ->toArray();
    }

    /**
     * 获取用户今日活期账户变更金额
     */
    public function getTodayChangeCash(){

        $date = ToolTime::dbDate();

        return self::select(DB::raw('user_id, sum(balance_change) as balance_change'))
            ->where('created_at','>=',$date)
            ->whereIn('event_id', $this->getCurrentEventList())->groupBy('user_id')
            ->get()
            ->toArray();
    }

    /**
     * @return array
     * 获取未投资事件列表
     */
    private function getNoInvestEventList(){

        $eventIds = [
            self::INVEST_PROJECT,
            self::INVEST_CURRENT,
        ];

        return $eventIds;
    }

    /**
     * @param string $balance  用户余额高于$balance
     * @param string $days     用户在$days天内没有进行投资行为
     * @return array
     * @desc       获取余额大于N元并且在某段时间内没有进行过投资行为的用户ID
     */
    public function getNoInvestIds($balance, $days, $start=false, $size=false){

        $obj = self::select('user_id',DB::raw("max(created_at) as ctime"))
            ->whereIn('event_id',$this->getNoInvestEventList())
            ->whereIn('user_id',function($query) use($balance) {
                $query->select('id')
                    ->from('user')
                    ->where('balance','>',$balance);
            })
            ->groupBy('user_id')
            ->having('ctime','<',DB::raw("DATE_ADD(CURRENT_DATE, INTERVAL -{$days} day)"));

        if($start !== false && $size){
            $return = $obj->skip($start)
                ->take($size)
                ->get()
                ->toArray();
        }else{
            $return = $obj->get()
                ->toArray();
        }

        return $return;
    }

    /**
     * @param string $date
     * @return mixed
     * @desc 根据事件类型分组进行数据统计
     *       提现失败的数数据 从2016-9-22变更成银行自动处理,之前的为错标志
     */
    public static function getChangeCashGroupByEventId( $date = '')
    {
        $obj    =   self::select("event_id as type",DB::raw(' sum(balance_change) as balance_change'));

        if( $date ){

            $obj=   $obj->where("created_at","<=",$date);
        }

        $return =   $obj->groupBy('event_id')
                        //->where("event_id","<>",self::WITHDRAW_ORDER_FAILED)
                        ->get()
                        ->toArray();

        return $return;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return mixed
     * @desc 根据时间获取用户自动投资活期的列表信息
     */
    public static function getAutoInvestCurrentListByDate($startDate, $endDate){

        return self::select('user_id', 'event_id as type', 'created_at', 'created_at as updated_at', DB::raw('-sum(`balance_change`) as cash'))
            ->whereIn('event_id', [self::INVEST_CURRENT_AUTO])
            ->where('created_at', '>', $startDate)
            ->where('created_at', '<', $endDate)
            ->groupBy('user_id')
            ->get()
            ->toArray();

    }



}