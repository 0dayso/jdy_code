<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 19:00
 */
namespace App\Http\Dbs\Current;


use App\Http\Dbs\JdyDb;

class CreditDetailDb extends JdyDb{

    const STATUS_NORMAL = 200, //正常
          STAUTS_DELETE = 400;

    protected $table = 'current_credit_detail';


    /**
     * @param $data
     * @return mixed
     * @desc 创建债权详情信息
     */
    public static function addRecord($data)
    {

        return self::insert(
            $data
        );

    }

    /**
     * @param $creditId
     * 根据债权ID,获取总的借款金额
     */
    public static function getAmountByCreditId($creditId){

        return self::select(\DB::raw('SUM(amount) as total_amount'))
                    ->where('credit_id',$creditId)
                    ->where('status',self::STATUS_NORMAL)
                    ->get()
                    ->toArray();
    }

    /**
     * @param $creditId
     * @return mixed
     * 删除原有债权
     */
    public static function doDelete($creditId){

        return self::where('credit_id',$creditId)
                    ->update(['status' => self::STAUTS_DELETE]);
    }


    /**
     * @param $condition
     * @return mixed
     * 根据条件获取债权列表
     */
    public static function getList($id){


        return self::where('credit_id',$id)
            ->where('status',self::STATUS_NORMAL)
            ->orderBy('id', 'desc')
            ->paginate(20)
            ->toArray();
    }


    /**
     * 债权人恢复
     */
    public static function recovery(){

        $dbPrefix = env('DB_PREFIX');
        
        $sql = "update ".$dbPrefix."current_credit_detail set usable_amount=amount";

        return \DB::statement($sql);
    }

    /**
     * @param $creditIds
     * 根据债权ID获取借款人信息
     */
    public static function getListByCreditIds($creditIds){

        return self::select('id','credit_id','name','id_card','usable_amount')
            ->whereIn('credit_id',$creditIds)
            ->where('status',self::STATUS_NORMAL)
            ->orderBy('created_at','asc')
            ->get()
            ->toArray();

    }

    /**
     * @param $creditId
     * @param $amount
     * @return mixed
     * 更新借款人可用金额
     */
    public static function editUsableAmount($id,$amount){

        return self::where('id', '=', $id)
            ->update(['usable_amount' => $amount]);

    }
}