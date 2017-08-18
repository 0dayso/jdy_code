<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 上午11:22
 */

namespace App\Http\Logics\Fund;

use App\Http\Logics\AppLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\RequestSourceLogic;

use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Fund\FundHistoryModel;

use App\Tools\ToolMoney;
/**
 * 资金 记录/流水
 * Class FundHistoryLogic
 * @package App\Http\Logics\Fund
 */
class FundHistoryLogic extends Logic
{

    protected $fundHistoryModel = null;

    public function __construct(){
        $this->fundHistoryModel = new FundHistoryModel;
    }
    /**
     * 获取资金记录
     * @param array $data
     * @return array
     */
    protected static function getList($data = []){
        $coreRequestData = [];

        $coreRequestData['userId']     = isset($data['userId']) ? $data['userId'] : null;         // 用户ID
        $coreRequestData['typeCode']   = isset($data['typeCode']) ? $data['typeCode'] : null;     // 事件类型标示
        $coreRequestData['page']       = isset($data['page']) ? $data['page'] : 1;                // 当前页码
        $coreRequestData['size']       = isset($data['size']) ? $data['size'] : 20;               // 每页数量

        $coreRequestData['start_time'] = isset($data['start_time']) ? $data['start_time'] : null; // 时间
        $coreRequestData['end_time']   = isset($data['end_time']) ? $data['end_time'] : null;     // 时间
        return self::getCoreFundHistoryList($coreRequestData, new FundHistoryModel);

    }
    /**
     * 获取内核资金历史数据
     *
     * @param array $coreRequestData
     * @return array
     */
    public static function getCoreFundHistoryList($coreRequestData = [], FundHistoryModel $fundHistoryModel){
        return $fundHistoryModel->getCoreHistoryData($coreRequestData);
    }

    /**
     * 设置搜索选项类型
     * @return array
     */
    public static function setTransactionType()
    {
        return [
            'all'                => '全部',                 //'全部',
            'recharge'           => '充值记录',             //'充值',
            'withdraw'           => '提现记录',             //'提现',
            'invest'             => '投资记录',             //'投资',
            'refund'             => '投资回款',             //'本息回款',
            'current'            => '零钱计划记录',             //'零钱计划',
            'reward'             => '活动奖励',             //'活动奖励',

            'rechargeWithdraw'   => '充值提现',             //'充值提现',
        ];
    }

    /**
     * 设置App4.0搜索选项类型
     * @return array
     */
    public static function setTransaction4Type()
    {
        return [
            'all'           => '全部',
            'reward'        => '活动奖励',
            'recharge'      => '充值',
            'withdraw'      => '提现',
            'invest'        => '定期投资',
            'refund'        => '定期回款',
            'investCurrent' => '零钱计划买入',
            'outCurrent'    => '零钱计划卖出'
        ];
    }

    /**
     * 根据交易类型获取列表
     * @param string $type
     * @return string
     */
    protected static function getTransactionType($type = ''){
        $transactionType = [
            'getAllEventId'                => 'all',             //'全部',
            'getRechargeEventId'           => 'recharge',        //'充值',
            'getWithdrawEventId'           => 'withdraw',        //'提现',
            'getInvestEventId'             => 'invest',          //'投资',
            'getRefundEventId'             => 'refund',          //'本息回款',
            'getCurrentEventId'            => 'current',         //'零钱计划',
            'getRewardEventId'             => 'reward',           //'活动奖励',

            'getInvestCurrentId'           => 'investCurrent',    //'零钱转入',
            'getOutCurrentId'              => 'outCurrent',       //'零钱转出',

            'getWithdrawAndRechargeEventId'=> 'rechargeWithdraw',//'充值提现',
        ];

        $key =  array_search($type, $transactionType);

        return $key;
    }


    /**
     * 获取指定交易类型数据
     *
     * @param array $data
     * @return array
     */
    public static function getListByType($data = []){

        $type             = isset($data['type']) ? $data['type'] : 'all';

        $method           = self::getTransactionType($type);

        $data['typeCode'] = $method;

        if( isset($data['phone']) ){
            //通过手机号码获取用户的信息
            $userInfo      = UserModel::getBaseUserInfo($data['phone']);

            $data['userId']= $userInfo ? $userInfo['id'] : 0;

        }else{
            //格式用户的id
            $data['userId']   = isset($data['user_id']) ? $data['user_id'] : 0; // todo 获取用户ID
        }

        $data             = self::getList($data);

        if(!empty($data) && $data['total'] > 0){
            $record = $data['data'];
            if(!empty($record)){
                $data['data'] = self::formatList($record);
            }
        }else{
            $data['total']    = 0;
            $data['totalCash']= 0;
        }
        return self::callSuccess($data);
    }

    /**
     * 格式化数据
     */
    public static function formatList($record){

        /*
        foreach($record as $k => $value){
            $record[$k]['event_id_label'] = self::getTransactionTypeLabel($value['event_id']);
        }
        */
        //输出格式化 钱
        $record = self::formatOutputMoney($record);

        return $record;
    }


//    /**
//     * 获取交易类型
//     */
//    public static function getTransactionTypeLabel($event_id){
//        return FundHistoryModel::getLable($event_id);
//    }

    /**
     * 格式化金额数据 [分 => 元]
     */
    public static function formatOutputMoney($record){

        foreach($record as $k => $value){
            $record[$k]['balance']        = ToolMoney::formatDbCashDelete($value['balance']);
            $record[$k]['balance_before'] = ToolMoney::formatDbCashDelete($value['balance_before']);
            $record[$k]['balance_change'] = ToolMoney::formatDbCashDelete($value['balance_change']);
        }
        return $record;
    }


    /**
     * @param $userId
     * @param $page
     * @return array
     * 分页获取用户零钱计划投资记录
     */
    public function getCurrentInvestList($userId,$page,$size){

        $list = \App\Http\Models\Common\CoreApi\FundHistoryModel::getCurrentFundList($userId,$page,$size);

        $result = [
            'total' => 0,
            'list'  => [[]]
        ];

        if(!empty($list)){

            $data = $list['data'];
            if(!empty($data)){
                foreach($data as $k=>$val){

                    $historyList[] = [
                        'id'                => $val['id'],
                        'cash_change'       => abs(ToolMoney::formatDbCashDelete($val['balance_change'])),
                        'create_time'       => $val['created_at'],
                        'note'              => FundHistoryModel::formatCurrentEventId($val['event_id'])==1?'转入':'转出',
                        'type'              => FundHistoryModel::formatCurrentEventId($val['event_id']),
                    ];
                }

                $result['list']     = $historyList;
            }

            $result['total']    = $list['total'];
        }

        return self::callSuccess($result);
    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * 获取客户端交易明细
     */
    public function getAppList($userId,$page,$size,$client){

        $data = [
            'type'      => 'all',
            'user_id'   => $userId,
            'page'      => $page,
            'size'      => $size,
        ];

        $client = strtolower($client);
        $list = $this->getListByType($data);

        $result = [
            'total' => 0,
            'list'  => ($client === 'ios') ? [] : [[]]
        ];


        if(!empty($list['data'])) {

            $result['total'] = $list['data']['total'];

            if (!empty($list['data']['data'])) {

                $data = $list['data']['data'];

                foreach ($data as $k => $val) {

                    $month = date('Y-m', strtotime($val['created_at']));
                    if($client === 'android'){
                        $fundList[] = [
                            'time' => $val['created_at'],
                            'cash' => $val['balance_change']>0?'+'.$val['balance_change']:$val['balance_change'],
                            'balance' => $val['balance'],
                            'type' => $val['note'],
                            'date' => $month
                        ];
                    }else{

                        $fundList[$month][] = [
                            'time' => $val['created_at'],
                            'cash' => $val['balance_change']>0?'+'.$val['balance_change']:$val['balance_change'],
                            'balance' => $val['balance'],
                            'type' => $val['note'],
                            'date' => $month
                        ];
                    }


                }

                $result['list'] = $fundList;

            }
        }

        return self::callSuccess($result);
    }

    /**
     * @param $data
     * @return array
     * 获取APP4.0客户端交易明细
     */
    public function getApp4List($data){

        $client  = RequestSourceLogic::getSource();
        $param = [
            'type' => !empty($data['type']) ? $data['type'] : 'all',
            'user_id' => $data['user_id'],
            'page' => !empty($data['page']) ? $data['page'] : 1,
            'size' => !empty($data['size']) ? $data['size'] : 5,
            'start_time' => !empty($data['start_time']) ? $data['start_time'] : null,
            'end_time' => !empty($data['end_time']) ? date('Y-m-d',strtotime($data['end_time'])+60*60*24) : null,
        ];

        $list = $this->getListByType($param);

        $data = $this->formatData($list,$client);

        if($param['type'] == 'all' || $param['type'] == ''){
            unset($data['totalCash']);
        }

        return self::callSuccess($data);
    }

    /**
     * @param $list
     * @param $client
     * @return array
     * 根据客户端,格式化数据
     */
    public function formatData($list,$client){

        $client = strtolower($client);

        $typeList = $this->setTransaction4Type();

        $type   = [];
        foreach($typeList as $key=>$value){
            $type[] = [
                'type' => $key,
                'text' => $value
            ];
        }

        $result = [
            'total'     => 0,
            'totalCash' => 0,
            'tag'       => $type,
            'list'      => [[]]
        ];

        if(!empty($list['data'])) {

            $result['total'] = $list['data']['total'];
            $result['totalCash'] = abs($list['data']['totalCash']);

            if (!empty($list['data']['data'])) {

                $data = $list['data']['data'];

                foreach ($data as $k => $val) {

                    $month = date('Y-m', strtotime($val['created_at']));
                    $fundList[] = [
                        'time' => $val['created_at'],
                        'cash' => $val['balance_change']>0?'+'.$val['balance_change']:$val['balance_change'],
                        'balance' => $val['balance'],
                        'type' => empty($val['note']) ? $val['event_id_label'] : $val['note'],
                        'date' => $month
                    ];

                }

                $result['list'] = $fundList;

            }
        }

        return $result;
    }

    /**
     * @return array
     * @desc fundHistory的事件解释
     */
    public static function getEventIdToExplain()
    {
        return FundHistoryModel::getEventIdToExplain();
    }
}
