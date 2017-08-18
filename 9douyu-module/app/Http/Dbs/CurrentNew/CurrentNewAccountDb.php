<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/24
 * Time: 下午4:16
 */

namespace App\Http\Dbs\CurrentNew;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class CurrentNewAccountDb extends JdyDb
{
    protected $table = 'current_new_account';

    public function getObj($id)
    {

        return $this->find($id);

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户信息
     */
    public function getInfoByUserId($userId)
    {

        return $this->where('user_id', $userId)
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @return array
     * @desc 通过用户id获取用户零钱计划数据
     */
    public function getCurrentInfoByUserId( $userId )
    {

        return $this -> where( 'user_id' , $userId )
            -> select ('cash','interest','yesterday_interest')
            -> first();

    }

    public function getCurrentAccountByUserId( $userId ){

        $result = $this->getCurrentInfoByUserId($userId);

        $result = $this->dbToArray($result);

        return empty($result)?0:$result['cash'];

    }


    /**
     * @param $userId
     * @param array $data
     * @return mixed
     * @desc 插入记录
     */
    public function doAdd($userId, $cash)
    {

        $this->user_id = $userId;

        $this->cash = abs($cash);

        $this->save();

        return $this->id;

    }

    /**
     * @param $id
     * @param $cash
     * @return mixed
     * @desc 更新账户金额
     */
    public function doUpdateCash($userId, $cash)
    {

        if( $cash < 0 ){

            return self::where('user_id',$userId)
                ->where('cash', '>=', abs($cash))
                ->update(array('cash' => \DB::raw(sprintf('`cash`+%.2f', $cash))));

        }

        return self::where('user_id',$userId)
            ->update(array('cash' => \DB::raw(sprintf('`cash`+%.2f', $cash))));

    }

    /**
     * @param $id
     * @param $interest
     * @return mixed
     * @desc 更新利息
     */
    public function doUpdateInterest($userId, $interest)
    {

        $interest = abs($interest);

        return self::where('user_id', $userId)
            ->where('interested_at','<',date('Y-m-d'))
            ->update(array(
                'cash'                  => \DB::raw(sprintf('`cash`+%.2f', $interest)),
                'interest'              => \DB::raw(sprintf('`interest`+%.2f', $interest)),
                'yesterday_interest'    => $interest,
                'interested_at'         => ToolTime::dbNow()
            ));

    }

    /**
     * @param $userId
     * @param $interest
     * @return mixed
     * 更新加息券利息数据
     */
    public function doUpdateBonusInterest($userId,$interest){

        $interest = abs($interest);

        return self::where('user_id', $userId)
            ->where('interested_at','>',date('Y-m-d'))
            ->update(array(
                'cash'                  => \DB::raw(sprintf('`cash`+%.2f', $interest)),
                'interest'              => \DB::raw(sprintf('`interest`+%.2f', $interest)),
                'yesterday_interest'    => \DB::raw(sprintf('`yesterday_interest`+%.2f', $interest)),
            ));
    }

    /**
     * @return mixed
     * @desc 获取总数
     */
    public function getTotal()
    {

        return self::count('id');

    }

    /**
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 获取列表
     */
    public function getList($page=1, $size=1000)
    {

        $start = ( max(0, $page -1) ) * $size;

        return self::skip($start)
            ->take($size)
            ->orderBy('id')
            ->get()
            ->toArray();

    }

    /**
     * @param $data
     * @return mixed
     * @desc 批量插入
     */
    public function addBatch($data)
    {

        return \DB::table('current_account')
            ->insert($data);

    }


    public function getUserInfo($userId){

        return self::where('user_id',$userId)
            ->first();
    }

    /**
     * @param $userIds
     * @return mixed
     * 获取多个用户的零钱计划账户信息
     */
    public function getByUserIds($userIds){

        return self::select('user_id','cash','yesterday_interest','interested_at')
            ->whereIn('user_id',$userIds)
            ->get()
            ->toArray();
    }

    /**
     * @param $userId
     * @return mixed
     * 统计指定用户的活期总金额
     */
    public function getTotalCashByUserIds($userId){

        return self::whereIn('user_id',$userId)
            ->sum('cash');
    }

    /**
     * @return mixed
     * 获取零钱计划帐户总人数
     */
    public function getCount(){

        return self::count();
    }

    /**
     * @return mixed
     * 获取零钱计划总收益
     */
    public function getTotalInterest(){

        return self::sum('interest');
    }

    /**
     * @return mixed
     * @desc 清除未计息用户的昨日收益字段的值
     */
    public function clearUserYesterdayInterest()
    {

        return self::where('interested_at', '<', ToolTime::dbDate())
            ->update(array(
                'yesterday_interest'    => 0
            ));

    }

    /**
     * @desc    获取活期总投资金额，总收益
     * @date    2016年11月23日
     * @author  @llper
     */
    public function getCurrentFundStatistics(){

        $result = self::select(\DB::raw(' sum(cash) as current_cash ,  sum(interest) as current_interest ,sum(yesterday_interest) as yesterday_interest'))
            ->first();

        return self::dbToArray($result);

    }

    /**
     * @return mixed
     * 获取零钱计划总金额
     */
    public function getUseAmount()
    {
        return $this->sum('cash');
    }
}
