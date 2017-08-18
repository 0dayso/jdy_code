<?php
/**
 * User: caelyn
 * Date: 16/5/20
 * Time: 16:26
 * Desc: 银行卡列表db
 */

namespace App\Http\Dbs\Bank;

use App\Http\Dbs\JdyDb;

class BankListDb extends JdyDb{

    Const
        RECHARGE_ONLINE_BANKING = 1000,

        STATUS_SHOW = 1,    //前端 显示
        STATUS_HIDDEN = 0,  //前端隐藏

        END=TRUE;


    public $timestamps = false;

    protected $table = "bank_list";

    /**
     * 获取银行列表
     * @param $type
     * @return array
     */
    public function getBankList($type){
        return self::where('type',$type)
            ->where('status',1)
            ->get();
    }

    /**
     * 获取alias
     * @param $type
     * @param $bank_id
     * @return array
     */
    public function getAlias($type,$bank_id){
        return self::where('type',$type)
            ->where('bank_id',$bank_id)
            ->where('status',1)
            ->first();
    }


    /**
     * @param $type
     * @return mixed
     * 根据支付通道获取对应的网银编码信息
     */
    public function getBankListByType($type){

        return self::where('type',$type)
            ->get()
            ->toArray();
    }


    /**
     * @param $bankId
     * @param $type
     * @param $data
     * @return mixed
     * 编辑网银银行的显示状态
     */
    public function doEdit($bankId,$type,$data){

        return self::where('bank_id',$bankId)
            ->where('type',$type)
            ->update($data);
    }


}