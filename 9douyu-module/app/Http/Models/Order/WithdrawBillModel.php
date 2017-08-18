<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/4
 * Time: 上午11:57
 */
namespace App\Http\Models\Order;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;
use App\Lang\LangModel;
use DB;
use Config;
class WithdrawBillModel extends Model
{
    protected $table = 'withdraw_bill';

    const   BILL_TYPE_JDWY  = 1,    //京东网银
            BILL_TYPE_UCF   = 3,    //先锋代付
            BILL_TYPE_SUMA  = 2;    //丰付代付
    /**
     * 增加提现账单数据
     * @param $data
     * @return bool
     */
    public function createBill($data){
        try {
            DB::table($this->table)->insert($data);
        }catch (\Exception $e){
            throw new \Exception(LangModel::getLang('ERROR_WITHDRAW_UPLOAD'));
        }

    }

    /**
     * 未处理提现订单数据
     * @return array
     */
    public function getBills(){
        $res = DB::table($this->table)->select('id','order_id','bill_status as status','note')->where('cron_status',0)->get();
        //二维object转成数组
        $data = json_decode(json_encode($res), true);
        return $data;
    }

    /**
     * 获取提现对账处理状态
     * @param array $data
     * @return bool
     */
    public function getDoneWithdrawStatus($data){
        $core = false;
        $api = Config::get('coreApi.moduleOrder.doBatchWithdrawCheckAmount');
        $res = HttpQuery::corePost($api,['order_data'=>json_encode($data)]);
        if($res['code']==Logic::CODE_SUCCESS){
            $core = $res['status'];
        }
        return $core;
    }

    /**
     * 更新提现对账状态成功
     * @param array $ids
     */
    public function updateWithdrawBillSuccess($ids){
        try {
            DB::table($this->table)->where('cron_status',0)->whereIn('order_id',$ids)->update(array('cron_status'=>1,'order_status'=>200));
        }catch (\Exception $e){
            throw new \Exception('');
        }
    }

    /**
     * 回调更新提现对账状态失败
     * @param array $ids
     * @return array
     */
    public function updateWithdrawBillFail($ids){
        try {
            DB::table($this->table)->whereIn('order_id',$ids)->update(array('cron_status'=>1,'order_status'=>500));
            return ['status'=>true];
        }catch (\Exception $e){
            return ['status'=>false];
        }
    }

}