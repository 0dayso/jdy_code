<?php
/**
 * User: zhangshuang
 * Date: 16/4/14
 * Time: 16:41
 * Desc: 用户成功充值记录Db
 */

namespace App\Http\Dbs;

use App\Tools\ToolTime;

class UserPayListDb extends JdyDb{

    protected $table = "user_pay_list";

    /**
     * @param $userId
     * @param $bankId
     * 根据用户ID及银行获取对应的充值成功记录
     */
    public function getUserPayListByBankId($userId,$bankId){

        return self::where('user_id',$userId)
            ->where('bank_id',$bankId)
            ->get()
            ->toArray();
    }


    /**
     * @param $userId
     * @param $bankId
     * @param int $payType
     * 获取用户成功的充值列表
     */
    public function getUserPayList($userId,$bankId,$payType = 0){

        return self::where('user_id',$userId)
            ->where('bank_id', $bankId)
            ->where('pay_type',$payType)
            ->first();

    }


    /**
     * @param $userId
     * @param $bankId
     * @param $payType
     * @param $cash
     * 添加充值成功记录
     */
    public function addRecord($userId,$bankId,$payType,$cash){

        $this->user_id = $userId;
        $this->bank_id = $bankId;
        $this->pay_type = $payType;
        $this->day_cash = $cash;
        $this->month_cash = $cash;

        $this->save();
    }


    /**
     * @param $userId
     * @param $bankId
     * @param $payType
     * @param $cash
     * @return mixed
     * 更新记录
     */
    public function updateRecord($userId,$bankId,$payType,$cash){

        $data = [
            'month_cash' => \DB::raw(sprintf('`month_cash`+%d', $cash)),
            'day_cash'   => \DB::raw(sprintf('`day_cash`+%d', $cash))
        ];

        return self::where('user_id', $userId)
            ->where('bank_id', $bankId)
            ->where('pay_type' ,$payType)
            ->update($data);
    }


    /**
     * @return mixed
     * 昨日充值记录清零
     */
    public function clearDayCash(){

        $dbPrefix = env('DB_PREFIX');//表前缀

        $sql = "update ".$dbPrefix."user_pay_list set day_cash = 0,updated_at = '".ToolTime::dbNow()."'";

        return app('db')->update($sql);
    }


    /**
     * @return mixed
     * 上个月的充值记录清零
     */
    public function clearMonthCash(){

        $dbPrefix = env('DB_PREFIX'); //表前缀

        $sql = "update ".$dbPrefix."user_pay_list set day_cash = 0,month_cash = 0,updated_at = '".ToolTime::dbNow()."'";

        return app('db')->update($sql);
    }


}