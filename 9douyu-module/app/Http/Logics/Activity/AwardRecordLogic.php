<?php
/** ****************************** 额外加息的LOGIC层 ******************************
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/8/30
 * Time: 下午1:57
 */
namespace App\Http\Logics\Activity;
use App\Http\Dbs\Activity\AwardRecordDb;
use App\Http\Dbs\Fund\FundHistoryDb;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\ActivityFundHistoryModel;
use App\Http\Models\Activity\AwardRecordModel;
use App\Http\Models\Common\CoreApi\RefundModel;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Log;
use Cache;
use Mockery\Exception;
use App\Jobs\Job;

class AwardRecordLogic extends Logic
{
    /**
     * @param $data
     * @return array
     * @desc 增加记录
     */
    public function doInsert( $data )
    {
        try {
            self::beginTransaction();
            $data       =   self::filterAddAttributes($data);
            $return     =   AwardRecordModel::doInsert($data);
            self::commit();
        }catch (\Exception $e){
            self::rollback();
            $attributes['data']           = $data;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            \Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$return]);
    }

    /*
     * 更新奖励的记录
     * @param   $userId
     * @param   $data
     * @return  array
     * @desc    更新记录
     */
    public function doUpdate( $data ,$type='id' )
    {

        try{
            self::beginTransaction();
            $attribute  =   self::filterAttributes($data[$type]);
            unset($data[$type]);
            $return     =   AwardRecordModel::doUpdate($data, $attribute,$type);
            self::commit();
        } catch (\Exception $e){
            self::rollback();
            $attributes['data']           = $data;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }
        return self::callSuccess([$return]);
    }

    /*
     * 根据结算的项目d 获取当前未结算的手机
     */
    public function getPendingList($projectIds)
    {
        $projectIds =   self::filterAttributes($projectIds);
        $db         =   new AwardRecordDb();
        return $db->getPendingList($projectIds);
    }

    /**
     * 把奖励的金额放入到用户的账户余额的操作
     * @author linguanghui <lin.guanghui@9douyu.com>
     * @param $userId 用户ID
     * @param $cash   奖励金额
     * @param $id 加息奖励记录ID
     * @return string
     */
    public function refundAwardToBalance($userId, $cash, $id ,$eventId = ''){

        if(empty($userId) || empty($cash) || $cash == '0.00'){
            return false;
        }
        try{
            self::beginTransaction();

            $activityNote   =   ActivityFundHistoryModel::getActivityEventNote();
            
            $data = [
                'status'  =>  AwardRecordDb::STATUS_GIVE,
            ];
            //更新奖励记录为已奖励状态
            $attribute  =   self::filterAttributes($id);
//            AwardRecordModel::doUpdate($data,$attribute);
//            $note       =   '活动奖励';
            $userInfo   =   UserModel::getCoreApiUserInfo($userId);
//            if($userInfo){
//                UserModel::doIncBalance($userId,$cash,$userInfo['trading_password'],$note);
//            }

            $params = [
                'event_name'        => 'App\Events\Activity\IncreaseTransferEvent',
                'event_desc'        => '活动奖励结算事件',
                'note'              => isset($activityNote[$eventId]) ? $activityNote[$eventId] : '活动奖励',
                'event_id'          =>  $eventId,                   //结算标示
                'attribute'         =>  $attribute,                 //结算的参数
                'data'              =>  $data,                      //结算参数
                'user_id'           =>  $userId,                     //结算的用户ID
                'cash'              =>  $cash,                       //结算的金额
                'trading_password'  =>  $userInfo['trading_password'], //验证用户密码
                'ticket_id'         =>  ToolStr::getRandTicket()
            ];

            Log::info(__METHOD__,$params);

            \Event::fire(new \App\Events\Activity\IncreaseTransferEvent($params));

            //写日志
            Log::info(__CLASS__.'success', [$userId,$cash]);
            
            self::commit();
            
        } catch (\Exception $e){

            self::rollback();

            Log::error(__CLASS__, [$e->getMessage()]);

            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }

    /*
     * 获取指定时间内的回款项目
     */
    public function getRefundProjectByTime($times)
    {
        $times  =   $times ? $times : date('Y-m-d');
        $list   =   RefundModel::getRefundProjectByTime($times);
        return $list;
    }

    /**
     * 保证查询条件
     * @param $attributes
     * @return array $attributes
     */
    private function filterAttributes( $attributes )
    {
        if( is_array($attributes) ){
            return $attributes;
        }
        return [$attributes];
    }

    /*
     * 检测添加数据的有效性
     */
    private function filterAddAttributes($data)
    {
        $returnAttributes    =   [
            "project_id"   =>   isset($data['project_id']) ? $data['project_id'] : "0",
            "invest_id"    =>   isset($data['invest_id']) ? $data['invest_id']  :  "0",
            "event_type"   =>   isset($data['event_type']) ? $data['event_type'] : FundHistoryDb::ACTIVITY_AWARD,
            "user_id"      =>   isset($data['user_id']) ? $data['user_id'] : '0',
            "principal"    =>   isset($data['principal']) ? $data['principal'] : '0.00',
            "percentage"   =>   isset($data['percentage']) ? $data['percentage'] : AwardRecordDb::DEFAULT_PERCENTAGE,
            "cash"         =>   $data['cash'],
            "comment"      =>   $data['comment'],
            "status"       =>   isset($data['status']) ? $data['status'] : AwardRecordDb::STATUS_FOR_GIVE,
            "created_at"   =>   isset($data['created_at']) ? $data['created_at'] : ToolTime::dbNow(),
            "updated_at"   =>   isset($data['updated_at']) ? $data['created_at'] : ToolTime::dbNow(),
        ];
        return $returnAttributes;
    }
}