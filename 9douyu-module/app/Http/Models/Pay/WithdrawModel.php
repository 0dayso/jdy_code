<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/7
 * Time: 上午11:09
 */
namespace App\Http\Models\Pay;

use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\BankCardModel;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Dbs\Bank\BankListDb;
use App\Http\Dbs\OrderDb;
use Config;
use Validator;

class WithdrawModel extends Model{

    /**
     * 获取用户提现卡信息
     * @param int $userId
     * @return array
     */
    public function getWithdrawCard($userId){

        $withdrawCard = BankCardModel::getUserWithdrawCard($userId);
        return $withdrawCard;

        /*
        $withdrawCard = array();
        if(empty($userId)) return $withdrawCard;
        $api = Config::get('coreApi.moduleBankCard.getUserWithdrawCard');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId));
        if($res['code']==Logic::CODE_SUCCESS){
            $withdrawCard = $res['data'];
        }
        return $withdrawCard;
        */
    }


    /**
     * 获取用户当月已提现次数
     * $param int $userId
     * @return int
     */
    public function getWithDrawNum($userId){

        $withdrawData = OrderModel::getUserMonthWithdrawNum($userId);

        return $withdrawData;
        /*
        $withdrawNum = 0;
        $api = Config::get('coreApi.moduleOrder.getUserMonthWithdrawNum');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId));
        if($res['code']==Logic::CODE_SUCCESS){
            $withdrawNum = $res['data'];
        }
        return $withdrawNum;
        */

    }


    /**
     * 创建订单
     * @param array $data
     * @return array
     */
    public function createOrder($data){

        $result = OrderModel::doCreateWithdrawOrder($data);

        return $result;

        /*

        $order = array();
        $api = Config::get('coreApi.moduleOrder.doCreateWithdrawOrder');
        $res = HttpQuery::corePost($api,$data);
        if($res['code']==Logic::CODE_SUCCESS){
            $order = $res['data'];
        }
        return $order;
        */
    }

    /**
     * 获取提现卡列表
     * @return array
     */
    public function getWithdrawCards() {

        $type = OrderDb::RECHARGE_WITHDROW_TYPE;

        $bankListDb = new BankListDb();

        $result = $bankListDb->getBankList($type)->toArray();

        return $result;
    }

    /**
     * 提现检测金额
     * @param  array $data 
     * @return bool
     */
    public static function checkCash($data,$minWithdraw,$balance){

        $min = $minWithdraw;

        $max = $balance;

        $rules = [
            'cash' => 'required|numeric|between:'.$min.','.$max,
        ];

        $validator = Validator::make($data,$rules);

        if ($validator->fails()){
            
            throw new \Exception($validator->messages()->first());
        }

        return true;
    }

    /**
     * [提现config]
     * @return [type] [description]
     */
    public static function getWithdrawConfig(){

        $withDrawConfig = SystemConfigModel::getConfig("NEW_USER_WITHDRAW_CONFIG");

        $res = [
            'maxFreeNum' => $withDrawConfig['MAX_FREE_NUM'],
            'handingFree' => $withDrawConfig['HANDING_FREE'],
            'closeAccountDays' => $withDrawConfig['CLOSE_ACCOUNT_DAYS']
        ];

        return $res;
    }

}