<?php

/** ****************************** 加币活动的LOGIC层 ******************************
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/02/10
 * Time: 上午10:01
 */
namespace  App\Http\Logics\Activity;

use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Logics\Logic;
use App\Http\Logics\Activity\Common\ActivityLogic;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use App\Http\Logics\Activity\Common\AnalysisConfigLogic;
use Cache;

class GradeLotteryLogic extends ActivityLogic
{
    const
            GRADE_LOTTERY_LIST  =   'GRADE_LOTTERY_LIST_CACHE' ,
            DEFAULT_DRAW_NUMBER =   3
            ; //奖品的配置

    public function getActivityTime()
    {
        $config  =   self::config();

        return  $this->getTime( $config['START_TIME'] , $config['END_TIME'] );
    }
    /**
     * @return array
     * @desc 活动时间的判断
     */
    public function getTimeCondition( $userId=0 )
    {
        $config     =   self::config();

        return $this->isCanJoinActivity( $config['START_TIME'] , $config['END_TIME'] , self::getEventId() , $userId );
    }

    /**
     * @param int $userId
     * @param int $gradeLevel
     * @return array
     * @desc 返回用户抽奖的条件
     */
    public function getUserCondition( $userId=0 , $gradeLevel = 1 )
    {
        $validNumber       =    self::getLotteryTotalByUserId( $userId ) ;

        if( $validNumber <= 0 || empty($validNumber) ) {

            return self::callError( '您在当前奖池！暂无抽奖机会' );
        }

        $userLotteryMessage =    self::setLotteryMessage( $userId );

        $gradeMessage       =   $userLotteryMessage['grade'];

        if( empty($userLotteryMessage['lottery']) ) {

            return self::callError( '十分抱歉，您当前没有抽奖的机会!' );
        }
        if( $gradeLevel > $gradeMessage['grade_level'] ) {

            return self::callError( '您的等级最高在!' . $gradeMessage['grade_name'] . '奖池!' );
        }

        $lotteryTotal       =   $userLotteryMessage['lottery'][$gradeLevel]['lottery'];

        $investTotal        =   $userLotteryMessage['lottery'][$gradeLevel]['invest'];

        $drawNumber         =   $userLotteryMessage['lottery'][$gradeLevel]['draw_number'];

        if( $investTotal ==0 ) {

            return self::callError("十分抱歉！您在当前奖池没有抽奖机会");
        }

        if( $investTotal - $lotteryTotal <=0 || $drawNumber == 0 || empty($drawNumber) ) {

            return self::callError( '您在当前奖池抽奖次数已达到上限，<br/>请到其他奖池参与活动' );
        }

        return self::callSuccess( $userLotteryMessage['config'][$gradeLevel] );
    }

    /**
     * @param int | user_id
     * @return can lottery number
     * @desc 用户还抽奖的次数
     */
    public function getLotteryTotalByUserId($userId=0)
    {
        $config         =   self::config();

        $timeArr        =   $this->setReceiveBetweenTime( $config['START_TIME'] , $config['END_TIME'] );

        $startTime      =   strtotime( $timeArr['start'] );

        $endTime        =   strtotime( $timeArr['end'] );

        $lotteryNumber  =   $this->getUserLotteryInfo( $startTime , $endTime , self::getEventId() , $userId );

        $gradeConfig    =   self::setGradeLotteryConfig();

        $baseCash       =   isset($gradeConfig['1']) ? $gradeConfig['1']['min_invest'] : '20000';

        $investNumber   =   $this->getUserSatisfyInvestNumber( $startTime , $endTime , $baseCash , $userId , [], self::setUseBonusStatus() );

        return $investNumber - $lotteryNumber;
    }

    /**
     * @return array()
     * @desc 中奖记录
     */
    public function getLotteryRecord()
    {
        $config     =   self::config();

        return $this->setCouponWinningList( $config['START_TIME'] , $config['END_TIME'] , self::getEventId() );
    }

    /**
     * @return mixed
     * @desc  返回奖品的列表
     */
    public function getLotteryList()
    {
        $cacheKey       =   self::GRADE_LOTTERY_LIST;

        $gradeLottery   =   Cache::get( $cacheKey );

        if( !empty($gradeLottery)) {

            return  json_decode($gradeLottery , true);
        }

        $gradeLotteryList['config'] =   self::setGradeLotteryConfig();

        if( empty( $gradeLotteryList['config']) ) {

            return [];
        }


        $lotteryLogic       =   new LotteryConfigLogic();

        foreach ( $gradeLotteryList['config'] as $key  => $value ) {

            $gradeLotteryList['list'][$key]   =   $lotteryLogic->getLotteryByGroup( $value['lottery_group'] );
        }

        Cache::put($cacheKey , json_encode($gradeLotteryList) , 60);

        return $gradeLotteryList;
    }
    /**
     * @param int $userId
     * @param int $groupId
     * @desc 执行抽奖的程序
     */
    public function doLuckDraw( $userId = 0 , $groupId = 0 )
    {
        $lotteryLogic           =   new LotteryLogic();

        $lotteryParam           =[
                'activity_id'   =>  self::getEventId() ,
                'group_id'      =>  $groupId ,
                'user_id'       =>  $userId ,
            ];

        return $lotteryLogic->doLuckDrawWithRate( $lotteryParam );
    }

    /**
     * @param (int) $userId
     * @return array() lotteryMessage
     * @desc 用户默认对应的奖池信息
     */
    public function setLotteryMessage( $userId = 0 )
    {
        $returnLottery   =   [
            'userStatus'    =>  true,
            'status'        =>  false ,
            'account'       =>  0 ,
            'config'        =>   $this->setGradeLotteryConfig(),
            'lottery'       =>  [] ,
            'number'        =>  0,
        ];

        $config                 =   $this->config();

        if( $userId ==0 || empty($userId) ) {

            $returnLottery['userStatus']  =   false;

            return $returnLottery;
        }

        $returnLottery['account']   =   $this->getUserRechargeTotal( $config['START_TIME'] , $config['END_TIME'] , $userId );

        $validLottery               =   self::setUserValidLottery( $userId , $returnLottery['account'] );

        $returnLottery['grade']     =   $validLottery['grade'];

        $returnLottery['lottery']   =   $validLottery['Lottery_info'];

        $investSumTotal             =   isset( $validLottery['Lottery_info']['1']['invest']) ? $validLottery['Lottery_info']['1']['invest'] : 0;

        $lotterySumTotal            =   array_sum( array_column( $validLottery['Lottery_info'] , 'lottery' ) );

        if( $investSumTotal > 0 && $lotterySumTotal < $this->getMaxLotteryNumber() * count(self::setGradeLotteryConfig() ) ) {

            $returnLottery['status'] =   true;

            $returnLottery['number'] =   $investSumTotal-$lotterySumTotal;
        }

        //需要加一个有效的投资次数:
        return $returnLottery;
    }

    /**
     * @param int $userId
     * @param int $userAccount
     * @return array
     */
    private function setUserValidLottery( $userId = 0 , $userAccount=0 )
    {
        $gradeConfig    =   self::setGradeLotteryConfig();

        $validLotteryResult    =   [];

        $userGrade      =   $gradeConfig[1];

        foreach( $gradeConfig as $key => $value ) {

            if( $userAccount >= $value['grade_money'] ) {

                $userGrade  =   $value;

                $investNumber   =  self::setUserInvestAmount( $userId , $value['min_invest'] );

                $lotteryTotal   =  self::setUserLotteryTotal( $value['lottery_group'] , $userId );

                $validLotteryResult[$key] =[
                    'invest'    =>  $investNumber ,
                    'lottery'   =>  $lotteryTotal ,
                    'draw_number'=> self::validUserLottery( $investNumber , $lotteryTotal ) ,
                ];
            }
        }

        return [
            'grade'         =>  $userGrade ,
            'Lottery_info'  =>  $validLotteryResult ,
        ];
    }

    /**
     * @param $investNumber
     * @param $lotteryNumber
     * @return int|mixed
     * @desc 判断当前用户对于的等级使用还可以进行抽奖
     */
    protected function validUserLottery( $investNumber , $lotteryNumber )
    {
        $maxLotteryNumber   =   $this->getMaxLotteryNumber();

        if( $lotteryNumber  >= $maxLotteryNumber || $lotteryNumber >= $investNumber ) {

            return 0;
        }

        if( $maxLotteryNumber >= $lotteryNumber && $investNumber >= $maxLotteryNumber) {

            return $maxLotteryNumber - $lotteryNumber;
        }
        if( $maxLotteryNumber >= $investNumber ) {

            return $investNumber;
        }

        return  $maxLotteryNumber;
    }

    /**
     * @return int|mixed
     * @desc 单奖池的最大抽奖次数
     */
    protected function getMaxLotteryNumber()
    {
        return $this->config()['DRAW_NUMBER'] ? $this->config()['DRAW_NUMBER'] : self::DEFAULT_DRAW_NUMBER;
    }
    /**
     * @param int $userId
     * @param int $baseCash
     * @return int
     * @desc
     */
    private function setUserLotteryTotal( $groupId  , $userId = 0 )
    {
        $lotteryTotal   =   0;

        if( $userId == 0 || empty($userId) ) {

            return $lotteryTotal;
        }

        $config             =   $this->config();

        $lotteryRecordLogic =   new LotteryRecordLogic();

        $lotteryLogic       =   new LotteryConfigLogic();

        $lotteryList        =   $lotteryLogic->getLotteryByGroup( $groupId );

        $statistics         =   [
            'user_id'       =>  $userId ,
            'activity_id'   =>  self::getEventId() ,
            'start_time'    =>  date('Y-m-d H:i:s' , $config['START_TIME']) ,
            'end_time'      =>  date('Y-m-d H:i:s' , $config['END_TIME']) ,
        ];

        if( !empty($lotteryList) ) {

            $statistics['prizes_id']     =   ToolArray::arrayToIds( $lotteryList , 'id' );
        }

        $lotteryRecordList  =   $lotteryRecordLogic->getRecordByConnection( $statistics );

        return (int)$lotteryRecordList['lotteryNum'];
    }

    /**
     * @param int $userId
     * @return int
     * @desc 抽奖的条件:投资金额的次数
     */
    private function setUserInvestAmount( $userId = 0  , $baseCash=0 )
    {
        if( $userId == 0 || empty($userId) ) {

            return 0;
        }

        $config             =   $this->config();

        $lotteryTime        =   $this->setReceiveBetweenTime( $config['START_TIME'] , $config['END_TIME'] );

        return (int)$this->getUserSatisfyInvestNumber( strtotime( $lotteryTime['start'] ) , strtotime( $lotteryTime['end'] ) , $baseCash , $userId , [] , self::setUseBonusStatus() );
    }
    /**
     * @param (int) $userId
     * @return float number
     * @desc 获取用户的待收（定期）
     */
    protected  function setUserAccount( $userId = 0 )
    {
        if($userId == 0 || empty($userId)) {

            return 0;
        }

        $userAccount     =    UserModel::getCoreApiUserInfoAccount( $userId );

        if( !isset( $userAccount['project']['product_line']) || empty($userAccount['project']['product_line']) ) {

            return 0;
        }

        $accountList    =   $userAccount['project']['product_line'];

        $principal      =   array_column( $accountList , 'principal' );

        $interest       =   array_column( $accountList , 'interest' );

        return array_sum($principal) + array_sum($interest);
    }

    /**
     * @return array
     * @desc 设置活动的奖池和奖品的信息
     */
    protected function setGradeLotteryConfig()
    {
        $config     =   self::config();

        $gradeArr   =   explode( "|" , $config['USER_GRADE_LOTTERY_GROUP'] );

        $gradeConfig=   [];

        if( empty($gradeArr) ) {

            return  $gradeConfig;
        }

        foreach ( $gradeArr as $key => $gradeStr ) {

            $explodeArr =   explode( "=>" , $gradeStr );

            $explodeMsg =   explode( "," , $explodeArr['1'] );

            $gradeConfig[$explodeArr[0]] = [
                'lottery_group' =>  $explodeMsg[0] ,  //奖品分组
                'grade_level'   =>  $explodeArr[0] ,  //奖池等级
                'grade_money'   =>  $explodeMsg[1] ,  //最小充值金额
                'min_invest'    =>  $explodeMsg[2] ,  //单笔最小投资金额
                'grade_name'    =>  $explodeMsg[3] ,  //奖池名称
            ];
        }

        ksort($gradeConfig);

        return  $gradeConfig;
    }
    /**
     * @return mixed
     * @desc 投资是否可以使用红包
     */
    protected function setUseBonusStatus()
    {
        $config     =   self::config();

        return $config['INVEST_WITH_BONUS'];
    }
    /**
     * @return int
     * @desc 活动的唯一标示
     */
    protected function getEventId()
    {
        return ActivityFundHistoryDb::SOURCE_ACTIVITY_GRADE_LOTTERY;
    }
    /**
     * @return array|mixed
     * @desc  加币活动的配置文件
     */
    public function config()
    {
        return AnalysisConfigLogic::make('GRADE_LOTTERY_CONFIG');
    }
}
