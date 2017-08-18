<?php
/**
 * 普付宝申请质押冻结及解冻的订单
 * User: bihua
 * Date: 16/8/19
 * Time: 15:09
 */
namespace App\Http\Dbs\Pfb;

use App\Http\Dbs\JdyDb;

class InvestPfbDb extends JdyDb
{
    protected $table = 'invest_pfb';

    const

        STATUS_FREEZE   = 100,  //冻结

        STATUS_UNFREEZE = 200;  //解冻


    /**
     * @param $investId
     * @param $cash
     * @param $userId
     * @return mixed
     * @desc 添加质押订单记录
     */
    public function addInfo($investId,$cash,$userId){

        $data = array(
            'invest_id' => $investId,
            'user_id'   => $userId,
            'cash'      => $cash,
            'status'    => self::STATUS_FREEZE
        );

        return self::insert($data);
    }

    /**
     * @param $investId
     * @param $status
     * @return mixed
     * @desc 更新记录状态
     */
    public function editStatus($investId,$status){

        return self::where('invest_id',$investId)->update(['status' => $status]);
    }

    /**
     * 通过订单ID获取信息
     * @param $investId
     * @return mixed
     */
    public function getOrderByInvestId($investId){

        return self::where('invest_id',$investId)->first();
    }

    /**
     * 获取订单状态
     * @param $status
     * @return mixed
     */
    public function getStatus($status){

        $arr = array(

            'freeze'    => self::STATUS_FREEZE,
            'unfreeze'  => self::STATUS_UNFREEZE
        );

        return $arr[$status];
    }

    /**
     * 获取订单状态
     * @param $status
     * @return mixed
     */
    public function getStatusShowName($status){

        $arr = array(

            self::STATUS_FREEZE   => '抵押中',
            self::STATUS_UNFREEZE  =>'可抵押'
        );

        return $arr[$status];
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户冻结订单总金额
     */
    public function getFreezeCash($userId){

        return self::where('user_id',$userId)
                    ->where('status',self::STATUS_FREEZE)
                    ->sum('cash');

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户的冻结订单ID
     */
    public function getFreezeInvestIds($userId){

        return self::select('invest_id')
                    ->where('user_id',$userId)
                    ->where('status',self::STATUS_FREEZE)
                    ->get()
                    ->toArray();
    }
}