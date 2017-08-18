<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/7
 * Time: 18:07
 */
namespace App\Http\Logics\Pay;
use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Dbs\OrderDb;
use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\ThirdApi\PfbLogic;
use App\Http\Models\Pay\WithdrawModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Bank\CardModel;
use App\Http\Logics\Logic;
use App\Http\Models\Common\NationalModel;
use App\Http\Models\Common\TradingPasswordModel;
use App\Http\Models\Common\CoreApi\BankCardModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\UserModel;
use App\Lang\AppLang;
use App\Tools\ToolArray;
use App\Tools\ToolOrder;
use App\Tools\ToolMoney;
use App\Lang\LangModel;
use App\Tools\ToolTime;
use Validator;

class WithdrawLogic extends Logic{

    /**
     * 获取当前用户提现银行卡
     * @param $userId
     * @return array
     */
    public function getWithdrawCard($userId){
        $withdrawModel = new WithdrawModel();
        $result = $withdrawModel->getWithdrawCard($userId);
        return $result;
    }

    /**
     * 验证数据
     * @param $data
     * @return array
     */
    public function vaildData($data){
        try {
            //数据验证
            ValidateModel::isUserId($data['userId']);
            ValidateModel::isDecimalCash($data['cash']);
            ValidateModel::isBankCard($data['card_no']);

            $userInfo = UserModel::getUserInfo($data['userId']);

            //判断用户是否实名 + 设置交易密码
            UserModel::checkUserAuthStatus($userInfo);

            $minWithdraw = $this->getMinWithdraw();
            $balance = $userInfo['balance'];
            //检测金额
            WithdrawModel::checkCash($data,$minWithdraw,$balance);
            //检测交易密码
            if(empty($userInfo['trading_password'])){

                return self::callError(LangModel::getLang('ERROR_EMPTY_TRADING_PASSWORD'));
            }
            TradingPasswordModel::checkPassword($data['trading_password'],$userInfo['trading_password']);

            \Log::info(__METHOD__ .'检测普付宝润徐提现额度:', [$data['cash'], $data]);

            PfbLogic::checkPledgeAmount($data['cash']);

        } catch (\Exception $e) {

            return self::callError($e->getMessage());
        }
        return self::callSuccess();
    }

    /**
     * 创建提现订单
     * @param array $data
     * @return int
     */
    public function createOrder($data){
        $fee = $this->getCommission($data['userId']);
        $param['order_id'] = ToolOrder::generateOrder();
        $param['user_id'] = $data['userId'];
        $param['handing_fee'] = $fee;
        $param['cash'] = $data['cash']-$fee;
        $param['bank_id'] = $data['bank_id'];
        $param['card_no'] = $data['card_no'];
        $param['type'] = OrderDb::RECHARGE_WITHDROW_TYPE;
        $param['from'] = RequestSourceLogic::getSource();
        $param['version'] = empty($data['version'])?'':$data['version'];
        $withdrawModel = new WithdrawModel();
        $result = $withdrawModel->createOrder($param);

        if( $result['status'] ){

            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_ORDER_WITHDRAW_CREATE);

            $msg = sprintf($msgTpl, ToolTime::dbNow(), $data['cash']);

            $event['notice'] = [
                'title'     => NoticeDb::TYPE_ORDER_WITHDRAW_CREATE,
                'user_id'   => $data['userId'],
                'message'   => $msg,
                'type'      => NoticeDb::TYPE_ORDER_WITHDRAW_CREATE
            ];

            \Event::fire(new \App\Events\Order\WithdrawCreateSuccessEvent($event));

        }

        return $result;

    }


    /**
     * 计算提现手续费
     * @param $userId 用户Id
     * @return int
     */
    public function getCommission($userId){
        $commission = 0;
        $withDrawConfig = SystemConfigModel::getConfig("NEW_USER_WITHDRAW_CONFIG");
        if(!empty($withDrawConfig)){
            //当月可以免费提现次数
            $freeNum = $withDrawConfig['MAX_FREE_NUM'];
            //本月已提现次数
            $withdrawModel = new WithdrawModel();
            $withDrawNum = $withdrawModel->getWithDrawNum($userId);
            //计算手续费
            if($withDrawNum['total']>=$freeNum) {
                $commission = $withDrawConfig['HANDING_FREE'];
            }
        }
        return $commission;
    }

    /**
     * 获取最小提现金额
     * @return int
     */
    public function getMinWithdraw(){
        $minMoney = 0;
        $withDrawConfig = SystemConfigModel::getConfig("NEW_USER_WITHDRAW_CONFIG");
        if(!empty($withDrawConfig)){
            $minMoney = ToolMoney::formatDbCashDelete($withDrawConfig['MIN_MONEY']);
        }
        return $minMoney;
    }
    /**
     * 获取每月可免手续费提现次数
     * @author lin.guanghui
     * @return int
     */
     public function getMaxFreeNum(){
         $maxFreeNum =0;
         $withDrawConfig = SystemConfigModel::getConfig("NEW_USER_WITHDRAW_CONFIG");
         if(!empty($withDrawConfig)){
             $maxFreeNum = $withDrawConfig['MAX_FREE_NUM'];
         }
         return $maxFreeNum;
     }
    /**
     * 获取用户超过提现次数后的手续费
     * @return array
     */
    public function getHandlingFree(){
        $handlingFree = 5;
        $withDrawConfig = SystemConfigModel::getConfig("NEW_USER_WITHDRAW_CONFIG");
        if(!empty($withDrawConfig)){
            $handlingFree = $withDrawConfig['HANDING_FREE'];
        }
        return $handlingFree;
    }
    /**
     * APP用户提现卡返回数据
     * @param  int $userId 
     * @return array
     */
    public function getWithdrawCardForApp($userId) {

        $withdrawModel = new WithdrawModel();

        $result        = $withdrawModel->getWithdrawCard($userId);

        $userInfo      = $this->getUser($userId);

        $minMoney      = $this->getMinWithdraw();


        $data = [
            'user_name'             =>  isset($userInfo['real_name']) ? '*' . substr(trim($userInfo['real_name']), 3) : '',
            'user_cash'             =>  isset($userInfo['balance']) ? $userInfo['balance'] : '',
            'min_money'             =>  $minMoney,
            'type'                  =>  !empty($result) ? 'authcard' : '',
            'withdraw_message'      =>  AppLang::APP_WITHDRAW_MESSAGE,
            'list'                  => [],
            'desc_url'              => env('APP_URL_WX').'/article/withdrawIntro',
        ];

        $banks  = ToolArray::arrayToKey(CardModel::getBanks(), 'id');

        if($result){
            foreach($result as $res){

                $bankId = $res['bank_id'];

                //$banks  = CardModel::getBanks();

                $data['list'][] = [
                    'id'           =>   $res['id'],
                    'bank_id'      =>   $bankId,
                    //'name'         =>   $banks[$bankId]['name'],
                    'name'         =>   isset($banks[$bankId]) ? $banks[$bankId]['name'] : '',
                    'card_number'  =>   substr($res['card_no'],-4),
                    'crad_number_web' => substr($res['card_no'],0,4).'******'.substr($res['card_no'],-4),
                    'image'        =>   ToolOrder::getBankImage($bankId)
                ];

            }
        }else{

            $data['list'][] = [];
        }


        return self::callSuccess($data);
    }

    /**
     * APP提现卡列表返回数据
     * @param  int $userId 
     * @return array
     */
    public function getWithdrawBanksForApp(){

        $withdrawModel = new WithdrawModel();

        $result        = $withdrawModel->getWithdrawCards();

        if(empty($result))  return self::callError(AppLang::APP_NO_WITHDRAW_CARDS);

        $banks         = CardModel::getBanks();

        foreach ($result as $key => $value) {
            
            $bankId  =  $value['bank_id'];

            $data[] = [
                'id'     =>   $bankId,
                'name'   =>   $banks[$bankId]['name'],
                'image'  =>   ToolOrder::getBankImage($bankId)
            ];
        }

        return self::callSuccess($data);
    }

    /**
     * 用户绑定提现银行卡
     * @param  int       $userId 
     * @param  int       $bankId 
     * @param  string    $cardNo
     * @return array        
     */
    public function bindWithdrawCard($userId,$bankId,$cardNo){

        try{

            ValidateModel::isBankCard($cardNo);

            if(\App::environment("production")) {
                $bankId = CardModel::getCardInfo($cardNo);
            }

            $res = BankCardModel::doCreateWithdrawCard($userId,$bankId,$cardNo);

            if ($res['status']) {

                $bank_card_id = $res['data']['bank_card_id'];

                return self::callSuccess(['id'=>$bank_card_id]);

            } else {

                return self::callError($res['msg']);

            }

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }



    }

}
