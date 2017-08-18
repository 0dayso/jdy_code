<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/25
 * Time: 下午4:35
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\Activity\LotteryConfigDb;
use App\Http\Dbs\Activity\LotteryRecordDb;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityConfigModel;
use App\Http\Models\Activity\ActivityFundHistoryModel;
use App\Http\Models\Activity\ActivityStatisticsModel;
use App\Http\Models\Activity\LotteryRecordModel;
use App\Http\Models\Bonus\BonusModel;
use App\Http\Models\Bonus\UserBonusModel;
use App\Http\Models\User\UserModel;
use App\Tools\ExportFile;
use App\Tools\ToolTime;
use Log;
use Event;

class LotteryRecordLogic extends Logic
{

    private $eventKey = '';
    /**
     * @param $id
     * @return array
     * @desc  通过id读取信息
     */
    public static function getById( $id )
    {
        $db     =   new LotteryRecordDb();
        
        return $db->getById($id);
    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc  列表数据
     */
    public function getRecordList( $page , $size ,$phone = '',$aid = '')
    {
        $db     =   new LotteryRecordDb();

        $result =   $db->getRecordList($page , $size, $phone ,$aid );

        return $result;
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     * @添加中奖记录
     */
    public function doAdd( $data )
    {

        $model      =   new LotteryRecordModel();

        try{

            self::beginTransaction();

            //检测奖品配置
            $lottery    =   $model->doVerifyLottery($data['prizes_id']);

            //检测用户是否存在
            $user       =   UserModel::getUserInfo($data['user_id']);

            //格式化数据
            $attributes =   self::doFormatInsertRecord( $data ,$lottery,$user);

            $model->doAddRecord($attributes);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        \Event::fire(new \App\Events\Activity\LotteryEvent(['prizes_id'=>$data['prizes_id'],'phone'=>$attributes['phone']]));

        return self::callSuccess();
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     * @添加中奖记录
     */
    public function doAddUseStatistics( $data )
    {

        $model      =   new LotteryRecordModel();

        try{

            self::beginTransaction();

            //检测奖品配置
            $lottery    =   $model->doVerifyLottery($data['prizes_id']);

            //检测用户是否存在
            $user       =   UserModel::getUserInfo($data['user_id']);

            //格式化数据
            $attributes =   self::doFormatInsertRecord( $data ,$lottery,$user);

            $model->doAddRecord($attributes);

            ActivityStatisticsModel::doUpdateRecordUsed($data['statics_id'] ) ;

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        \Event::fire(new \App\Events\Activity\LotteryEvent(['prizes_id'=>$data['prizes_id'],'phone'=>$attributes['phone']]));

        return self::callSuccess();
    }


    /**
     * @param $data
     * @return array
     * @throws \Exception
     * @desc 后台添加中奖记录
     */
    public function doAdminAdd( $data )
    {
        $model      =   new LotteryRecordModel();

        try{

            self::beginTransaction();

            //检测奖品配置
            $lottery    =   $model->doVerifyLottery($data['prizes_id']);

            //检测用户是否存在
            $user       =   UserModel::getCoreApiBaseUserInfo($data['phone']);

            //格式化数据
            $attributes =   self::doFormatAttributes( $data ,$lottery,$user );

            $model->doAddRecord($attributes);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => '用户'.$data['phone'].':记录中奖信息失败','code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $data
     * @return array
     * @throws \Exception
     * @desc 后台更新中奖
     */
    public function doEdit( $data )
    {
        $model      =   new LotteryRecordModel();

        try{

            self::beginTransaction();

            $id         =   $data['id'];
            //检测奖品配置
            $lottery    =   $model->doVerifyLottery($data['prizes_id']);

            //检测用户是否存在
            $user       =   UserModel::getCoreApiBaseUserInfo($data['phone']);

            //格式化数据
            $attributes =   self::doFormatAttributes( $data ,$lottery,$user);

            $model->doUpdate($id,$attributes);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => '用户'.$data['phone'].':记录中奖信息失败','code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $data
     * @return array
     * @desc 使用投资数据的中奖记录
     */
    public function doAddVirtualUseStatistics( $data )
    {
        $model      =   new LotteryRecordModel();

        $bonusModel =   new UserBonusModel();

        $bonusLogic =   new UserBonusLogic();

        try{

            self::beginTransaction();

            //检测奖品配置
            $lottery    =   $model->doVerifyLottery($data['prizes_id']);

            //检测红包ID的合法性
            $bonusModel->checkBonusId($data['bonus_id']);

            //检测红包的可用性
            $bonusInfo[] =   BonusModel::checkBonus($data['bonus_id']);

            //检测用户是否存在
            $user       =   UserModel::getUserInfo($data['user_id']);

            //格式红包,加息券数据
            $bonusInfo  =   $bonusLogic->formatMultiBonusInfo($bonusInfo,$data['user_id']);

            //发送红包数据
            $bonusModel->doSendBonus($bonusInfo);

            //格式化数据
            $attributes =   self::doFormatInsertRecord( $data ,$lottery,$user);

            $model->doAddRecord($attributes);

            ActivityStatisticsModel::doUpdateRecordUsed($data['statics_id'] ) ;

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        \Event::fire(new \App\Events\Activity\LotteryEvent(['prizes_id'=>$data['prizes_id'],'phone'=>$attributes['phone']]));
        return self::callSuccess();
    }
    /**
     * @param $data
     * @return array
     * @desc 添加虚拟奖品记录
     */
    public function doAddVirtual( $data )
    {
        $model      =   new LotteryRecordModel();

        $bonusModel =   new UserBonusModel();

        $bonusLogic =   new UserBonusLogic();
        
        try{

            self::beginTransaction();

            //检测奖品配置
            $lottery    =   $model->doVerifyLottery($data['prizes_id']);

            //检测红包ID的合法性
            $bonusModel->checkBonusId($data['bonus_id']);

            //检测红包的可用性
            $bonusInfo[] =   BonusModel::checkBonus($data['bonus_id']);

            //检测用户是否存在
            $user       =   UserModel::getUserInfo($data['user_id']);

            //格式红包,加息券数据
            $bonusInfo  =   $bonusLogic->formatMultiBonusInfo($bonusInfo,$data['user_id']);

            //发送红包数据
            $bonusModel->doSendBonus($bonusInfo);

            //格式化数据
            $attributes =   self::doFormatInsertRecord( $data ,$lottery,$user);

            $model->doAddRecord($attributes);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        \Event::fire(new \App\Events\Activity\LotteryEvent(['prizes_id'=>$data['prizes_id'],'phone'=>$attributes['phone']]));
        return self::callSuccess();
    }
    /**
     * @param $data
     * @param $lottery
     * @param string $user
     * @return array
     * @desc 格式化数据
     */
    protected static function doFormatInsertRecord( $data ,$lottery,$user = '')
    {
        $attributes     =   [
            'award_name'    =>  isset($lottery['name']) ? $lottery['name']  : '',
            'type'          =>  isset($lottery['type']) ? $lottery['type']  : '',
            'prizes_id'     =>  isset($lottery['id'])   ? $lottery['id']    : 0,
            'activity_id'   =>  isset($data['activity_id']) ? $data['activity_id'] : '0',
            'user_id'       =>  isset($data['user_id']) ? $data['user_id'] : 0,
            'phone'         =>  isset($user['phone']) ? $user['phone'] : 0,
            'user_name'     =>  isset($user['real_name']) ? $user['real_name']  :" ",
        ];

        //中奖状态
        if( $lottery['type'] != LotteryConfigDb::LOTTERY_TYPE_ENTITY ){

            $attributes['status']   =   LotteryRecordDb::LOTTERY_STATUS_SUCCESS;

        }else{

            $attributes['status']   =   LotteryRecordDb::LOTTERY_STATUS_NOT_AUDITED;
        }

        return $attributes;
    }

    /**
     * @param $data
     * @param $lottery
     * @param string $user
     * @return array
     * @desc 格式化数据
     */
    protected static function doFormatAttributes( $data ,$lottery,$user = '')
    {
        $attributes     =   [
            'award_name'    =>  isset($lottery['name']) ? $lottery['name']  : '',
            'type'          =>  isset($lottery['type']) ? $lottery['type']  : '',
            'prizes_id'     =>  isset($lottery['id'])   ? $lottery['id']    : 0,
            'activity_id'   =>  isset($data['activity_id']) ? $data['activity_id'] : '0',
            'user_id'       =>  isset($user['id']) ? $user['id'] : 0,
            'phone'         =>  isset($user['phone']) ? $user['phone'] : 0,
            'user_name'     =>  isset($user['real_name']) ? $user['real_name']  :" ",
            'note'          =>  isset($data['note']) ? $data['note'] : '',
            'created_at'    =>  isset($data['lottery_time']) ?$data['lottery_time'] :ToolTime::dbNow(),
            'status'        =>  isset($data['status']) ? $data['status'] :LotteryRecordDb::LOTTERY_STATUS_NOT_AUDITED,
        ];

        return $attributes;
    }
    /**
     * @param $statistics
     * @return mixed
     * @desc 统计根据条件统计数据
     */
    public function getRecordByConnection($statistics)
    {
        $db             =   new LotteryRecordDb();

        $fitterParam    =   $this->doFitterParams($statistics);

        return $db->getRecordByConnection($fitterParam);

    }

    /**
     * @param $statistics
     * @return array
     * @desc 格式化查询的的条件
     */
    protected function doFitterParams($statistics)
    {
        return [
            'start_time'    =>  isset($statistics['start_time']) ? $statistics['start_time'] : "",
            'end_time'      =>  isset($statistics['end_time']) ? $statistics['end_time'] : "",
            'user_id'       =>  isset($statistics['user_id']) ? $statistics['user_id'] : null,
            'activity_id'   =>  isset($statistics['activity_id']) ? $statistics['activity_id'] : null,
            'status'        =>  isset($statistics['status']) ? $statistics['status'] : null,
            'limit'         =>  isset($statistics['limit']) ? $statistics['limit'] : '',
            'prizes_id'     =>  isset($statistics['prizes_id']) ? $statistics['prizes_id'] : '',
        ];
    }

    /**
     * @return array
     * @desc 活动的note
     */
    public static function getActivityNote()
    {
        return ActivityFundHistoryModel::getActivityEventNote();
    }

    /**
     * @return array
     * @desc 带有抽奖活动EventId
     */
    public static function getLotteryActivityEventNote()
    {
        $lotteryEventConfig =   ActivityConfigModel::getConfig('ACTIVITY_LOTTERY_EVENT_NOTE');
        
        if( empty($lotteryEventConfig) ){

            return ActivityFundHistoryModel::getLotteryActivityEventNote();
        }
        
        $lotteryEventNote   =   [];
        
        foreach ($lotteryEventConfig as $eventKey => $item ){

            $lotteryEventNote[$eventKey]=self::doFormatCheckConfig($item);
        }

        ksort($lotteryEventNote);

        return $lotteryEventNote;
    }

    /**
     * @param string $payChannelStr
     * @return array
     * @desc  解析数值
     */
    protected static function doFormatCheckConfig( $lotteryEvent = '')
    {

        if( empty($lotteryEvent)) return [];

        $lotteryEventArr   =   explode("|",$lotteryEvent);

        if( empty($lotteryEventArr) ) return [];

        $returnConfig   =   [];

        foreach ($lotteryEventArr as $key =>  $item ){

            $config     =   explode("=",$item);

            $returnConfig[$config[0]]=$config[1];
        }

        return $returnConfig;

    }

    public static function doAdminExport( $list )
    {
        $formatList       =   self::doFormatRecordList($list['list']);

        $exportTitle[]    =   ['用户id','姓名','手机号码','奖品类型','奖品名词','活动类型','中奖时间'];

        $formatList         =   array_merge($exportTitle,$formatList);

        ExportFile::csv($formatList,'lottery_record_'.ToolTime::dbDate());

    }

    /**
     * @param array $list
     * @return array
     * @desc 格式化数据
     */
    protected static function doFormatRecordList($list = array())
    {
        if( empty($list) ){

            return [];
        }
        $formatList =   [];

        $acNote     =   self::getActivityNote();

        $configLogic    =   new LotteryConfigLogic();

        $typeList       =   $configLogic->getLotteryType();

        foreach ($list  as $key => $record ){

            $formatList[$key]   =   [
                'user_id'       =>  $record['user_id'],
                'user_name'     =>  $record['user_name'],
                'phone'         =>  $record['phone'],
                'prize_type'    =>  isset($typeList[$record['type']]) ? $typeList[$record['type']] :'普通奖品',
                'award_name'    =>  $record['award_name'],
                'activity_note' =>  isset($acNote[$record['activity_id']]) ? $acNote[$record['activity_id']] : '活动',
                'created_at'    =>  $record['created_at'],
            ];
        }

        return $formatList;
    }
}