<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 16:31
 * Desc: 零钱计划债权db
 */

namespace App\Http\Dbs\Current;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class CreditDb extends JdyDb{

    protected $table = 'current_credit';

    //还款方式
    const
        REFUND_TYPE_WITH_BASE           = 10,       //等额本息
        REFUND_TYPE_ONLY_INTEREST       = 20,       //按月付息，到期还本
        REFUND_TYPE_BASE_INTEREST       = 30;       //到期还本息



    /**
     * 创建记录
     * @param array $attributes
     * @return static
     */
    public static function addRecord($attributes = []){

        $model = new static($attributes, array_keys($attributes));

        $model->save();

        return $model->id;
    }

    /**
     * @param $condition
     * @return mixed
     * 根据条件获取债权列表
     */
    public static function getList($condition){

        return self::where($condition)->orderBy('id', 'desc')->paginate(2)->toArray();
    }


    /**
     * 获取指定ID所有字段的记录
     * @param $id
     * @return mixed
     */
    public static function findById($id){
        
        return static::find($id);
    }


    /**
     * 更新指定ID债权
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public static function doEdit($id = 0, $data = []){

        return static::where('id', $id)->update($data);

    }

    /**
     * 零钱计划债权回收
     */
    public static function recovery(){

        $dbPrefix = env('DB_PREFIX');

        $sql = "update ".$dbPrefix."current_credit set usable_amount=total_amount";

        return \DB::statement($sql);


    }

    /**
     * @return mixed
     * 获取剩余可匹配的零钱计划债权总金额
     */
    public static function getUsableAmount(){

        $date = ToolTime::dbDate();

        return self::select(\DB::raw('SUM(usable_amount) as total_amount'))
            ->where('end_time','>',$date)
            ->first()
            ->toArray();
    }

    /**
     * @return mixed
     * 获取剩余可匹配的债权列表
     */
    public static function getUsableList(){

        $date = ToolTime::dbDate();

        return self::select('id')
            ->where('end_time','>',$date)
            ->get()
            ->toArray();
    }

    /**
     * @param $id
     * @param $amount
     * 更新零钱计划债权的可使用金额
     */
    public static function editUsableAmount($id,$amount){

        return self::where('id', '=', $id)
            ->update(['usable_amount' => \DB::raw(sprintf('`usable_amount`-%d', $amount))]);
    }
}


