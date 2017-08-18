<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/11/15
 * Time: 10:30
 */

namespace App\Http\Dbs\Credit;


class CreditThirdDetailDb extends CreditDb{

    protected $table = 'credit_third_detail';


    /**
     * @desc 获取可使用的债权人的详情列表
     * @param $creditId
     * @return mixed
     */
    public function getAbleCreditDetailList($creditId){

        return self::select('id','usable_amount')
            ->where('credit_third_id',$creditId)
            ->where('status', CreditDb::STATUS_CODE_UNUSED)
            ->get()
            ->toArray();
    }

    /**
     * @desc 通过多个Id获取信息
     * @param $creditIds
     * @return mixed
     */
    public function getListByIds($creditIds){

        return self::whereIn('id', $creditIds)
            ->get()
            ->toArray();
    }

    /**
     * @param $creditId
     * @return mixed
     */
    public function getCreditListByThirdId($creditId){

        return self::where('credit_third_id',$creditId)
            ->get()
            ->toArray();
    }

    /**
     * @return mixed
     * @desc 发布出去的三方债券总数
     */
    public function getCreditTotal()
    {
        return $this->count();
    }

    public static function delDetailByCreditId( $creditId )
    {
        return self::where( 'credit_third_id', $creditId)
            ->delete();
    }

    /**
     * @desc    未到期的第三方债权个数
     **/
    public function getCreditThirdIngNum(){
        $currentDate    = date("Y-m-d");
        $result = self::select(\DB::raw(" COUNT( distinct id_card ) AS thirdCreditNum "))
            ->where( 'refund_time','>', $currentDate)
            ->first();
        return self::dbToArray($result);
    }
}
