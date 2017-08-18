<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/7
 * Time: 下午3:35
 */

namespace App\Http\Dbs\Bonus;

use App\Http\Dbs\Bonus\BonusDb;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class UserBonusDb extends JdyDb
{

    protected $table = "user_bonus";

    const
        FROM_TYPE_USER              = 0,        //用户获得
        FROM_TYPE_ADMIN             = 1,        //后台发送

        LOCK_TRUE                   = 1,        //已锁定
        LOCK_FALSE                  = 0,        //未锁定

        USED_TIME                   = '0000-00-00 00:00:00',

        END=true;

    /**
     * @param $data
     * @return mixed
     * @desc 发送优惠券
     */
    public function add($data){

        return $this->insert($data);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过Id锁定某条记录
     */
    public function addLock($id){

        return $this->where('id', $id)
            ->update(['lock' => self::LOCK_TRUE]);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过Id解锁某条记录
     */
    public function delLock($id){

        return $this->where('id', $id)
            ->update(['lock' => self::LOCK_FALSE]);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过id获取不锁定的记录
     */
    public function getUnLockById($id){
        $result = $this->where('id', $id)
                ->where('lock',self::LOCK_FALSE)
                ->first();
        return $this -> dbToArray($result);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过id获取锁定的记录
     */
    public function getLockById($id){
        $return =  $this->where('id', $id)
            ->where('lock',self::LOCK_TRUE)
            ->first();

        return $this -> dbToArray($return);
    }

    /**
     * @param $id
     * @return mixed
     * @desc 通过id获取记录
     */
    public function getById($id){
        $return = $this->where('id', $id)
            ->first();
        return $this -> dbToArray($return);

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 已使用列表红包 加息券 活动期加息券
     */
    public function getUsedListByUserId($userId){

        return $this->where('user_id',$userId)
            ->where('used_time','!=',self::USED_TIME)
            ->get()->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 未使用列表红包 加息券 活动期加息券
     */
    public function getAbleUseListByUserId($userId){

        return $this->where('user_id',$userId)
            ->where('used_time', self::USED_TIME)
            ->where('use_end_time','>',ToolTime::dbNow())
            ->get()->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 已过期列表红包 加息券 活动期加息券
     */
    public function getExpireListByUserId($userId){

        return $this->where('user_id',$userId)
            ->where('used_time', self::USED_TIME)
            ->where('use_end_time','<=',ToolTime::dbNow())
            ->get()->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 定期可用优惠券
     */
    public function getRegularAbleBonusList($userId){

        if($userId <= 0){
            return [];
        }

        return $this->join('bonus', 'bonus.id', '=', $this->table.'.bonus_id')
            ->where($this->table.'.user_id', '=', $userId)
            ->where($this->table.'.used_time', self::USED_TIME)
            ->where($this->table.'.use_end_time','>',ToolTime::dbNow())
            ->where($this->table.'.use_start_time', '<=', ToolTime::dbNow())
            ->whereIn('bonus.type', [BonusDb::TYPE_CASH,BonusDb::TYPE_COUPON_INTEREST])
            ->select($this->table.'.*','bonus.client_type','bonus.project_type','bonus.type','bonus.type as bonus_type','bonus.name','bonus.min_money','bonus.effect_type','bonus.rate','bonus.money','bonus.effect_start_date','bonus.effect_end_date')
            ->orderBy($this->table.'.use_end_time')
            ->get()->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 零钱计划可用优惠券
     */
    public function getCurrentAbleBonusList($userId){

        if($userId <= 0){
            return [];
        }

        return $this->join('bonus', 'bonus.id', '=', $this->table.'.bonus_id')
            ->where($this->table.'.user_id', '=', $userId)
            ->where($this->table.'.used_time',self::USED_TIME)
            ->where($this->table.'.use_end_time','>',ToolTime::dbNow())
            ->where($this->table.'.use_start_time', '<=', ToolTime::dbNow())
            ->where('bonus.type', '=', BonusDb::TYPE_COUPON_CURRENT)
            ->select($this->table.'.*','bonus.client_type','bonus.project_type','bonus.name','bonus.rate','bonus.current_day','bonus.using_desc','bonus.min_money','bonus.type as bonus_type','bonus.effect_type','bonus.effect_start_date','bonus.effect_end_date')
            ->orderBy($this->table.'.use_end_time')
            ->orderBy('bonus.rate', 'desc')
            ->get()->toArray();

    }

    /**
     * @param $id
     * @param $investId
     * @return mixed
     * @desc 定期使用优惠券
     */
    public function doRegularUsedBonus($id, $investId=0){
        return $this->where('id', $id)
            ->update(['used_time' => ToolTime::dbNow(),'foreign_id'=>$investId]);

    }

    /**
     * @param $id
     * @param $currentDay
     * @param $investId
     * @return mixed
     * @desc 零钱计划使用优惠券
     */
    public function doCurrentUsedBonus($id, $currentDay, $investId=0){

        return $this->where('id', $id)
            ->update(['used_time' => ToolTime::dbNow(), 'rate_used_time' => ToolTime::getAddDateByDays($currentDay,ToolTime::dbNow()), 'foreign_id'=>$investId]);

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 活动正在使用的加息券
     */
    public function getCurrentIngUsedBonus($userId){


            $return = $this -> join('bonus', 'bonus.id', '=', $this->table.'.bonus_id')
            -> where($this->table.'.user_id', $userId)
            -> where($this->table.'.used_time', '<=', ToolTime::dbNow())
            -> where($this->table.'.used_time','<>','0000-00-00 00:00:00')
            -> where($this->table.'.rate_used_time', '>', ToolTime::dbNow())
            -> select($this->table.'.*','bonus.*')
            -> first();

        return $this -> dbToArray($return);

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 用户可用优惠券总数
     */
    public function getTotalBonusByUserId($userId){

        return $this -> where ('user_id', $userId)
            -> where ('used_time', self::USED_TIME)
            -> where ('get_time', '<=',ToolTime::dbNow())
            -> where ('use_end_time', '>',ToolTime::dbNow())
            -> where ('lock', self::LOCK_FALSE)
            -> count();
            
    }


    /**
     * @return mixed
     * 获取昨日使用零钱计划加息券的用户列表
     */
    public static function getYesterdayBonusUserList($page,$size){

        //select * from sf_current_user_bonus where rate_used_time > '2016-06-08' and used_time <= '2016-06-08'

        $date = ToolTime::dbDate();

        $page = max(($page - 1),0);//页码处理

         return self::select('bonus_id','user_id','id')
            ->where('rate_used_time','>=',$date)
            ->where('used_time','<=',$date)
            ->orderBy('id','desc')
            ->skip($page * $size)
            ->take($size)
            ->get()
            ->toArray();


    }

    /**
     * @return mixed
     * 获取昨日使用零钱计划加息券的用户总数
     */
    public static function getYesterdayBonusUserNum()
    {

        $date = ToolTime::dbDate();

        return self::select('bonus_id', 'user_id', 'id')
            ->where('rate_used_time', '>=', $date)
            ->where('used_time', '<=', $date)
            ->count();
    }

    /**
     * @param $page
     * @param $size
     * @param $userId
     * @param $order
     * @return array
     * @desc 分页列表可用优惠券数据
     */
    public function getAbleBonusByUserId( $userId, $page, $size, $order='desc' ){

        $start = $this->getLimitStart($page, $size);

        $total = $this->where('user_id', $userId)
            ->where('used_time', self::USED_TIME)
            ->where('use_end_time','>',ToolTime::dbNow())
            ->count('id');

        $list = $this->where('user_id', $userId)
            ->where('used_time', self::USED_TIME)
            ->where('use_end_time','>',ToolTime::dbNow())
            ->skip($start)
            ->take($size)
            ->orderBy('use_end_time', $order)
            ->orderBy('id', $order)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }

    /**
     * @param $page
     * @param $size
     * @param $userId
     * @param $order
     * @return array
     * @desc 分页列表过期优惠券数据
     */
    public function getExpireBonusByUserId( $userId, $page, $size, $order='desc' ){

        $start = $this->getLimitStart($page, $size);

        $total = $this->where('user_id', $userId)
            ->where(function($query){
                $query->where('used_time', '!=',  self::USED_TIME)
                        ->orWhere(function($query){
                            $query->where('used_time', self::USED_TIME)
                                ->where('use_end_time','<=',ToolTime::dbNow());
                    });
            })
            ->count('id');

        $list = $this->where('user_id', $userId)
            ->where(function($query){
                $query->where('used_time', '!=',  self::USED_TIME)
                    ->orWhere(function($query){
                        $query->where('used_time', self::USED_TIME)
                            ->where('use_end_time','<=',ToolTime::dbNow());
                    });
            })
            ->skip($start)
            ->take($size)
            ->orderBy('used_time', $order)
            ->orderBy('use_end_time', $order)
            ->orderBy('id', $order)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];


    }

    /**
     * @param $page
     * @param $size
     * @param $userId
     * @return array
     * @desc 4.0分页列表未使用优惠券数据
     */
    public function getBonusList( $userId, $page, $size ){

        if($userId <= 0){
            return [];
        }

        $start = $this->getLimitStart($page, $size);

        $list = $this->join('bonus', 'bonus.id', '=', $this->table.'.bonus_id')
            ->where($this->table.'.user_id', '=', $userId)
            ->where($this->table.'.used_time',self::USED_TIME)
            ->where($this->table.'.use_end_time','>',ToolTime::dbNow())
            ->orderBy('use_start_time')
            ->skip($start)
            ->take($size)
            ->orderBy('use_end_time')
            ->select($this->table.'.*','bonus.client_type','bonus.project_type','bonus.name','bonus.rate','bonus.current_day','bonus.using_desc','bonus.min_money','bonus.type as bonus_type','bonus.effect_type','bonus.effect_start_date','bonus.effect_end_date','bonus.money')
            ->get()
            ->toArray();

        return $list;

    }

    /**
     * @param $page
     * @param $size
     * @param $userId
     * @param $order
     * @return array
     * @desc 4.0分页列表已使用优惠券数据
     */
    public function getUsedBonusByUserId( $userId, $page, $size, $order='desc' ){

        $start = $this->getLimitStart($page, $size);

        $list = $this->join('bonus', 'bonus.id', '=', $this->table.'.bonus_id')
            ->where($this->table.'.user_id', '=', $userId)
            ->where($this->table.'.used_time', '!=', self::USED_TIME)
            ->skip($start)
            ->take($size)
            ->orderBy('used_time', $order)
            ->select($this->table.'.*','bonus.client_type','bonus.project_type','bonus.name','bonus.rate','bonus.current_day','bonus.using_desc','bonus.min_money','bonus.type as bonus_type','bonus.effect_type','bonus.effect_start_date','bonus.effect_end_date','bonus.money')
            ->get()
            ->toArray();

        return $list;

    }

    /**
     * @param $page
     * @param $size
     * @param $userId
     * @return array
     * @desc 4.0分页列表已过期优惠券数据
     */
    public function getOutTimeBonusByUserId( $userId, $page, $size, $order='desc' ){

        $start = $this->getLimitStart($page, $size);

        $list = $this->join('bonus', 'bonus.id', '=', $this->table.'.bonus_id')
            ->where($this->table.'.user_id', '=', $userId)
            ->where($this->table.'.used_time',self::USED_TIME)
            ->where($this->table.'.use_end_time','<=',ToolTime::dbNow())
            ->skip($start)
            ->take($size)
            ->orderBy('use_end_time', $order)
            ->select($this->table.'.*','bonus.client_type','bonus.project_type','bonus.name','bonus.rate','bonus.current_day','bonus.using_desc','bonus.min_money','bonus.type as bonus_type','bonus.effect_type','bonus.effect_start_date','bonus.effect_end_date','bonus.money')
            ->get()
            ->toArray();

        return $list;

    }

    /**
     * @desc 更改用户红包的信息
     * @param $id
     * @param $data
     * @return mixed
     */
    public static function doUpdate($id, $data){

        return self::where('id', $id)->Update($data);

    }

    /**
     * @param $bonusId
     * @return mixed
     * @desc 通过bonus获取用户bonus信息
     */
    public static function getInfoByUserIdBonusId($userId, $bonusId)
    {

        return self::where('user_id', $userId)
            ->where('bonus_id', $bonusId)
            ->first();

    }

    /**
     * @param $parameter
     * @return mixed
     * @desc 红包的状态
     */
    public static function getUserBonusUsedSituation( $parameter ,$isUsed = false)
    {
        $startTime      =   $parameter['start_time'];

        $endTime        =   $parameter['end_time'];

        $bonusObj       =   self::join('bonus',"bonus.id","=","user_bonus.bonus_id")
                            ->select("user_bonus.bonus_id as bonus_id","bonus.name","bonus.type","bonus.rate","bonus.money", \DB::raw('count(*) as total_number'),\DB::raw('sum(money) as total_cash'));
        //红包获取时间
        if( !empty($startTime) && $isUsed == false){

            $bonusObj   =   $bonusObj->where("get_time",">=",$startTime );
        }

        if( !empty($endTime) && $isUsed == false){

            $bonusObj   =   $bonusObj->where('get_time',"<=" , $endTime);
        }

        //红包的使用使用
        if( !empty($startTime) && $isUsed == true){

            $bonusObj   =   $bonusObj->where("used_time",">=",$startTime );
        }

        if( !empty($endTime) && $isUsed == true){

            $bonusObj   =   $bonusObj->where('used_time',"<=" , $endTime);
        }

        if( $isUsed == true ){

            $bonusObj   =   $bonusObj->where('used_time',"<>" , self::USED_TIME)->where("foreign_id", "<>" , "0");
        }

        $result         =   $bonusObj->groupBy('bonus_id')
                                    ->get()
                                    ->toArray();

        return $result;
    }

    /**
     * @param string $startTime
     * @param string $endTime
     * @param array $bonusIds
     * @param int $userId
     * @param bool $isUsed
     * @return mixed
     * @desc 获取指定条件红包的数据量
     */
    public static function getUserBonusUsedTotal($startTime='',$endTime='',$bonusIds = [],$userId = 0, $isUsed = false)
    {
        $bonusObj   =   self::select('bonus_id',\DB::raw('count(id) as total'));

        //红包获取时间
        if( !empty($startTime) ){
            $bonusObj   =   $bonusObj->where("get_time",">=",$startTime );
        }

        if( !empty($endTime) ){
            $bonusObj   =   $bonusObj->where('get_time',"<=" , $endTime);
        }

        if( $userId !=0 ){
            $bonusObj= $bonusObj->where('user_id',$userId);
        }

        if( !empty($bonusIds) ){
            $bonusObj=  $bonusObj->whereIn('bonus_id',$bonusIds);
        }

        if( $isUsed == true ){
            $bonusObj   =   $bonusObj->where('used_time',"<>" , self::USED_TIME)->where("foreign_id", "<>" , "0");
        }

        $result    =    $bonusObj->groupBy('bonus_id')->get()->toArray();

        return $result;
    }


    /**
     * @param   $userId
     * @param   $bonusId
     * @return  mixed
     * @desc    用户-红包信息
     * 考虑用户可能获取，多个相同红包，所以获取多条数据
     */
    public function getReceivedBonusWithUser($userId, $bonusId){

        $return = $this->where ('user_id', $userId)
                ->where('bonus_id', $bonusId)
                ->get();

        return $this -> dbToArray($return);
    }
}