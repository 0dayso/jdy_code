<?php
/**
 * @desc    快金-我要借款
 * @date    2017-03-02
 *
 */

namespace App\Http\Dbs\TimeCash;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class TimeCashLoanDb extends JdyDb
{

    protected $table = "timecash_loan";



    /**
     * @param   $data
     * @return  boolean
     * @desc    添加数据
     */
    public function addLoan($data)
    {
        return $this->insert($data);
        
    }


    /**
     * @param   $startTime
     * @param   $endTime
     * @return  mixed
     * @desc    获取借款信息
     */
    public function getLoanRecord($startTime, $endTime)
    {
        return $this->whereBetween('created_at',[$startTime,$endTime])
            ->get()
            ->toArray();

    }

    /**
     * @desc    获取当日 手机号申请次数
     * @param   $phone
     *
     */
    public  function getLoanCountByPhone($phone,$statTime){
        return $this->where('phone',$phone)
            ->where('created_at','>=',$statTime)
            ->count();
    }

}