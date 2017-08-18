<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/13
 * Time: 下午3:32
 */

namespace App\Http\Dbs;
use App\Tools\ToolMoney;
use Monolog\Logger;

/**
 * 用户数据
 * Class UserDb
 * @package App\Http\Dbs
 */
class UserDb extends JdyDb
{
    const
        STATUS_INACTIVE             = 100,  // 注册未激活
        STATUS_ACTIVE               = 200,  // 注册已经激活
        STATUS_BLOCK                = 300,  // 禁用
        STATUS_FROZEN               = 400,  // 实网冻结
        FROZEN_STR                  = 'f_'  // 冻结标识
    ;

    protected $table = 'user';
    public $timestamps = false;

    /**
     * 白名单场景扩展【创建输入字段精确控制存储、在主模型定义存储白名单利于日后维护】
     * @param string $scene
     * @return mixed
     */
    public static function setFillable($scene = 'create')
    {
        $fillable = [
            //'create' => ['phone', 'password_hash', 'real_name', 'identity_card'], //存储     // todo 【重构 API】 测试后移除本行注释
            'create' => ['id', 'phone', 'password_hash', 'real_name', 'identity_card'], //存储 // todo 【重构 API】 测试后移除本行
        ];
        if (isset($fillable[$scene]))
            return $fillable[$scene];
        else
            return null;
    }

    public function getById($id)
    {

        return $this->where('id',$id);

    }

    public function getByPhone($phone)
    {

        return $this->where('phone', $phone);

    }

    /**
     * @desc 通过多个手机号获取用户信息
     * @param $phones
     * @return mixed
     */
    public function getByPhones($phones)
    {

        return $this->whereIn('phone', $phones)->get()->toArray();

    }

    public function getInfoById($id)
    {

        return $this->getById($id)->first();

    }


    /**
     * @param $id
     * @param $cash
     * @return mixed
     * @desc 更新账户余额
     */
    public function updateBalance($id, $cash)
    {

        if( $cash < 0 ){

            return self::where('id', '=', $id)
                ->where('balance', '>=', abs($cash))
                ->update(['balance' => \DB::raw(sprintf('`balance`+%.2f', $cash))]);

        }

        return self::where('id', '=', $id)->update(['balance' => \DB::raw(sprintf('`balance`+%.2f', $cash))]);

    }

    public function getObj($id)
    {

        return $this->find($id);

    }

    public function getList($page, $size)
    {

        $start = ( max(0, $page -1) ) * $size;

        return self::skip($start)
            ->take($size)
            ->get()
            ->toArray();
    }
    /**
     * @param $userId
     * @param $name
     * @param $idCard
     * 实名验证成功，修改用户相关信息
     */
    public function verify($userId,$name,$idCard){

        $user   = $this->getObj($userId);

        $user->real_name        = $name;
        $user->identity_card    = strtoupper($idCard);

        return $user->save();

    }

    /**
     * @param $userIds
     * 获取多个用户的信息
     */
    public function getUserListByUserIds($userIds){

        return self::whereIn('id',$userIds)
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @param $password
     * @return mixed
     * @desc 更新用户密码
     */
    public function doModifyPassword($userId, $password)
    {

        return self::where('id', $userId)
            ->update(['password_hash' => $password]);

    }

    /**
     * @param $userId
     * @param $password
     * @return mixed
     * @desc 更新用户交易密码
     */
    public function doModifyTradingPassword($userId, $tradingPassword)
    {

        return self::where('id', $userId)
            ->update(['trading_password' => $tradingPassword]);

    }

    /**
     * @param $idCard
     * @return mixed
     * 根据身份证查询用户信息
     */
    public function getByIdCard($idCard){

        return self::where('identity_card',$idCard)
            ->first();
    }

    /**
     * @param $idCards
     * @return mixed
     * 通过多个身份证号获取用户数据
     */
    public function getUserByIdCards($idCards){

        return self::whereIn('identity_card',$idCards)
            ->get()
            ->toArray();
    }

    /**
     * @desc 获取用户的列表[管理后台]
     * @author lgh
     * @param $pageSize
     * @param $condition
     * @return mixed
     */
    public function getAdminUserListAll($pageSize, $condition){

        return self::where($condition)
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据开始结束日期获取用户注册数
     */
    public function getUserAmountByDate($start = '',$end = ''){

        $res = $this->select(\DB::raw('count(id) as total') ,\DB::raw('DATE_FORMAT(created_at,\'%Y%m%d\') as date'));

        if(!empty($start) && !empty($end)){

            $end   = date('Y-m-d',strtotime($end)+86400);

            $res = $res->whereBetween('created_at',[$start,$end]);
        }

        $res  = $res->groupBy('date')
                    ->orderBy('date','desc')
                    ->get()
                    ->toArray();

        return $res;

    }

    /**
     * @return mixed
     * @desc 获取总注册数
     */
    public function getUserTotal(){

        $res = $this->count();

        return $res;
    }

    /**
     * @desc 更改用户账户为锁定状态
     * @param $userId
     * @param $status
     * @return mixed
     */
    public function modifyStatusBlock($userId, $status){

        return self::where('id', $userId)
            ->update(['status_code' => $status]);
    }

    /**
     * @desc 获取当日生日的用户
     * @return mixed
     */
    public function getBirthdayUser(){

        return self::select('id','phone','real_name',\DB::raw('if(length(`identity_card`) = 18, SUBSTRING(`identity_card`,7,8), SUBSTRING(`identity_card`,5,8)) as birthday'),\DB::raw('concat(\'\t\',identity_card) as identity_card'),'created_at')
            ->where(\DB::raw('if(length(`identity_card`) = 18, SUBSTRING(`identity_card`,11,4), SUBSTRING(`identity_card`,9,4))'),\DB::raw('DATE_FORMAT(now(),\'%m%d\')'))
            ->where('status_code', self::STATUS_ACTIVE)
            ->get()
            ->toArray();
        //return $this->count();
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 实网冻结用户账户
     */
    public function userFrozen( $userId ){

        return self::where('id', $userId)
            ->where('status_code', '!=', self::STATUS_FROZEN)
            ->update(
                [
                    'status_code'=>self::STATUS_FROZEN,
                    'phone'=>\DB::raw("concat('".self::FROZEN_STR."',phone,'.' ,'".rand(100,999)."')"),
                    'real_name'=>\DB::raw("if(real_name!='',concat('".self::FROZEN_STR."', real_name,'.','".rand(100,999)."'),real_name )"),
                    'identity_card'=>\DB::raw("if(identity_card!='',concat('".self::FROZEN_STR."', identity_card,'.', '".rand(100,999)."'),identity_card )")]
            );

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 实网用户解冻
     */
    public function userUnFrozen( $userId ){

        return self::where('id', $userId)
            ->where('status_code', self::STATUS_FROZEN)
            ->update(
                [
                    'status_code'=>self::STATUS_ACTIVE,
                    'phone'=>\DB::raw("SUBSTRING_INDEX(REPLACE(phone, '".self::FROZEN_STR."' ,'') ,'.', 1)"),
                    'real_name'=>\DB::raw("SUBSTRING_INDEX(REPLACE(real_name, '".self::FROZEN_STR."' ,'') ,'.', 1)"),
                    'identity_card'=>\DB::raw("SUBSTRING_INDEX(REPLACE(identity_card, '".self::FROZEN_STR."' ,'') ,'.', 1)")
                ]
            );

    }

    /**
     * @desc    获取用户表 所有用户总余额
     * @date    2016年11月23日
     * @author  @llper
     */
    public function getFundStatisticsTotalBalance(){

        return self::sum('balance');

    }

}