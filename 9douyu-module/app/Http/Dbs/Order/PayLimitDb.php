<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 16:27
 * Desc: 支付限额Db
 */

namespace App\Http\Dbs\Order;
use App\Http\Dbs\OrderDb;

use App\Http\Dbs\JdyDb;

class PayLimitDb extends JdyDb{


    protected $table = "pay_limit";

    /*
     * 网银支付标识码 1000-1100
     * 快捷支付      1101-1200
     * 代扣          1201-1300
     * 提现          2000
     */
    const

        STATUS_NORMAL           = 1, //启用
        STATUS_FORBIDDEN        = 0, //禁用

        PAY_LIMIT = 100000000;//充值无限额的默认金额(单位：分)

    private $currentTime ; //当前时间
    private $dbPrefix ;    //表前缀

    public function __construct(){

        $this->currentTime = date('Y-m-d H:i:s');
        $this->dbPrefix = env('DB_PREFIX');

    }



    /**
     * @param $bankId
     * @return mixed
     * 获取某个银行可用的支付通道及限额信息(绑卡情况)
     * SQL: select pay_type,limit,day_limit,bank_id from core_pay_limit
     *  where status = 1 and bank_id = 6 and
     * (start_time > '2016-04-15 10:19:54' or end_time < '2016-04-15 10:19:54')
     * 查询不在维护期、启用状态、固定银行的可用支付通道列表
     */
    public function getLimitByBank($bankId){


        $sql = "select pay_type,`limit`,day_limit,bank_id,version from ".$this->dbPrefix."pay_limit
                where bank_id = {$bankId} and
                status = ".self::STATUS_NORMAL." and
                (start_time > '".$this->currentTime."' or end_time < '".$this->currentTime."')";

        return app('db')->select($sql);



    }


    /**
     * @param $bankId
     * @return mixed
     * 获取某个银行可用的支付通道及限额信息（未绑卡情况，不支持翼支付）
     * SQL: select pay_type,limit,day_limit,bank_id from core_pay_limit
     *  where status = 1 and bank_id = 6 and
     * pay_type <> 1203 and
     * (start_time > '2016-04-15 10:19:54' or end_time < '2016-04-15 10:19:54')
     * 查询不在维护期、启用状态、不为翼支付、固定银行的可用支付通道列表
     *
     */
    public function getUnbindLimitByBank($bankId){


        $sql = "select pay_type,`limit`,day_limit,bank_id,version from ".$this->dbPrefix."pay_limit
                where bank_id = {$bankId} and
                status = ".self::STATUS_NORMAL." and
                (start_time > '".$this->currentTime."' or end_time < '".$this->currentTime."')";

        return app('db')->select($sql);
    }


    /**
     * @return mixed
     * 获取所有银行可用的支付限额列表
     * SQL : select pay_type,`limit`,day_limit,bank_id from core_pay_limit
     *       where status = 1 and
     *       pay_type <> 1203 and
     *   (start_time > '2016-04-15 10:19:54' or end_time < '2016-04-15 10:19:54')
     *  查询不在维护期内且状态为启用，不为翼支付的通道列表
     */
    public function getAllBankLimit(){


        $sql = "select pay_type,`limit`,day_limit,bank_id,version from ".$this->dbPrefix."pay_limit
                where status = ".self::STATUS_NORMAL." and
                (start_time > '".$this->currentTime."' or end_time < '".$this->currentTime."')";

        return app('db')->select($sql);
    }


    public function getLimitByType($type,$bankId){

        $typeArr = is_array($type) ? $type : [$type];

        if($bankId){

            return self::whereIn('pay_type',$typeArr)
                ->where('bank_id',$bankId)
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->toArray();

        }else{

            return self::whereIn('pay_type',$typeArr)->orderBy('id', 'desc')->paginate(10)->toArray();

        }


    }


    public function getListByType($type){

        return self::where('pay_type',$type)
            ->get()
            ->toArray();
    }

    public function doCreate($data){

        $data['start_time']= $data['end_time'] = date('Y-m-d H:i:00');

        return self::insert($data);
    }


    public function getById($id){

        return self::where('id',$id)->first()->toArray();
    }


    public function doEdit($id,$data){

        return self::where('id',$id)->update($data);
        
    }
}