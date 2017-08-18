<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 16:26
 */
namespace App\Http\Models\Current;

use App\Http\Dbs\Current\CreditDb;
use App\Http\Dbs\Current\CreditDetailDb;
use App\Http\Dbs\Current\UserCreditDb;
use App\Http\Models\Invest\CurrentModel;
use App\Http\Models\Model;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Http\Models\Common\HttpQuery;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;


class CreditModel extends Model
{


    public static $codeArr = [
        'doCreateCredit'             => 1,
        'doCreateDetail'             => 2,
        'doEditCredit'               => 3,
        'deleteCreateDetail'         => 4,
        'creditRecovery'             => 5,
        'creditDetailRecovery'       => 6,
        'getUserCurrentAmountIsZero' => 7,
        'getUserCurrentAmountFailed' => 8,
        'editCreditUsableAmount'     => 9,
        'editDetailUsableAmount'     => 10,
        'assignedCredit'             => 11


    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_CREDIT_CURRENT;

    /**
     * 获取还款类型
     * @return array
     */
    public static function refundType()
    {
        return [
            CreditDb::REFUND_TYPE_WITH_BASE     => '等额本息',
            CreditDb::REFUND_TYPE_ONLY_INTEREST => '先息后本',
            CreditDb::REFUND_TYPE_BASE_INTEREST => '到期还本',
        ];
    }


    /**
     * @param $data
     * @return static
     * @throws \Exception
     * 添加债权信息
     */
    public static function doCreateCredit($data)
    {

        $creditId = CreditDb::addRecord($data);

        if (!$creditId)
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_CREDIT_CREATE_FAILED'), self::getFinalCode('doCreateCredit'));

        return $creditId;
    }

    /**
     * @param $creditId
     * @param $list
     * 添加债权人数据
     */
    public static function doCreateDetail($creditId, $list)
    {

        foreach ($list as $key => $val) {

            $list[$key]['credit_id'] = $creditId;
        }

        $result = CreditDetailDb::addRecord($list);

        if (!$result) {

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_CREDIT_DETAIL_CREATE_FAILED'), self::getFinalCode('doCreateDetail'));
        }

        return $result;

    }


    /**
     * 编辑债权
     * @param $id
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function doEditCredit($id, $data)
    {

        $result = CreditDb::doEdit($id, $data);

        if (!$result)
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_CREDIT_EDIT_FAILED'), self::getFinalCode('doEditCredit'));

        return $result;
    }

    /**
     * @param $creditId
     * @throws \Exception
     * 根据债权ID删除对应的详情信息
     */
    public static function deleteCreateDetail($creditId)
    {

        $result = CreditDetailDb::doDelete($creditId);

        if (!$result) {
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_CREDIT_DETAIL_DELETE_FAILED'), self::getFinalCode('deleteCreateDetail'));

        }
    }

    /**
     * 零钱计划债权恢复
     */
    public static function creditRecovery()
    {

        $result = CreditDb::recovery();

        if (!$result) {
            throw new \Exception(LangModel::getLang('ERROR_RECOVERY_CURRENT_CREDIT_FAILED'), self::getFinalCode('creditRecovery'));

        }
    }

    /**
     * 零钱计划债权借款人恢复
     */
    public static function creditDetailRecovery()
    {

        $result = CreditDetailDb::recovery();

        if (!$result) {
            throw new \Exception(LangModel::getLang('ERROR_RECOVERY_CURRENT_CREDIT_DETAIL_FAILED'), self::getFinalCode('creditDetailRecovery'));

        }


    }

    /**
     * 清空用户匹配的零钱计划债权信息
     */
    public static function userCreditClear()
    {

        UserCreditDb::clear();
    }

    /**
     * @param $userId
     * 零钱计划用户今日0:00时的债权金额
     */
    public static function getUserCurrentAmount($userId)
    {

        /*
        $params = [
            'user_id' => $userId
        ];
        $result = HttpQuery::corePost('/current/getCreditAmount', $params);
        */

        $result = \App\Http\Models\Common\CoreApi\CurrentModel::getCurrentCreditAmount($userId);
        if ($result['status'] && !empty($result['data'])) {

            $cash   = $result['data']['cash'];
            //匹配债权的金额为0
            if ($cash === 0) {
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_ASSIGNED_AMOUNT_IS_ZERO'), self::getFinalCode('getUserCurrentAmountIsZero'));
            }
            return $cash;
        } else {

            throw new \Exception($result['msg'], self::getFinalCode('getUserCurrentAmountFailed'));
        }

    }

    /**
     * @param $userId
     * @return array
     * 检查用户是否已匹配过债权
     */
    public static function checkCreditAssigned($userId)
    {

        //判断用户是否已经匹配过债权
        $creditInfo = UserCreditDb::getByUserId($userId);

        $credits = [];
        if ($creditInfo) {
            foreach ($creditInfo as $credit) {
                $credits = array_merge($credits, json_decode($credit['credit'], true));
            }
            return $credits;
        }

        return [];
    }

    /**
     * @param $userId
     * @return array
     * 零钱计划用户匹配债权
     */
    public static function assignedCredit($userId, $cash)
    {

        //获取剩余可匹配的债权的金额
        $totalAmount = CreditDb::getUsableAmount();

        //未匹配的债权小于用户的金额
        if ($cash > $totalAmount) {

            //抛出异常,债权正在匹配中
            throw new \Exception(LangModel::getLang('ERROR_CURRENT_CREDIT_IS_ASSIGNING'), self::getFinalCode('assignedCredit'));

        }

        //获取可用的债权列表ID
        $list = CreditDb::getUsableList();
        $creditIds = ToolArray::arrayToIds($list, 'id');

        //获取多个债权的详细借款人信息
        $creditList = CreditDetailDb::getListByCreditIds($creditIds);

        $userCredit = [];  //用户匹配债权
        $creditDetailData = [];  //需要更新的债权人列表
        $creditData = [];   //需要更新的债权列表

        //一个用户的债权信息可能跨两个债权池
        foreach ($creditList as $v) {
            $detailId = $v['id'];
            $creditId = $v['credit_id'];

            $amount = $v['usable_amount'];
            if ($amount <= $cash) {
                //减去已匹配的债权金额
                $cash -= $amount;
                $freeAmount = 0;    //当前借款人剩余可匹配的金额
                $useCash = $amount;    //债权已使用的金额
            } else {
                $freeAmount = $amount - $cash;//当前借款人剩余可匹配的金额
                $v['usable_amount'] = $freeAmount;
                $useCash = $cash;       //债权已使用的金额
                $cash = 0;
            }

            $v['credit_cash'] = $useCash;

            $creditDetailData[$detailId] = $freeAmount;
            if (isset($creditData[$creditId])) {
                $creditData[$creditId] += $useCash;
            } else {
                $creditData[$creditId] = $useCash;
            }
            $userCredit[] = $v;
            if ($cash == 0) {
                break;
            }
        }

        return [
            'credit_detail' => $creditDetailData,
            'user_credit' => $userCredit,
            'credit' => $creditData,
        ];
    }

    /**
     * @param $userId
     * @param $cash
     * @param $data
     * 添加零钱计划用户债权匹配记录
     */
    public static function addUserCreditRecord($userId, $cash, $data)
    {

        $credit = json_encode($data);

        $attributes = [
            'user_id' => $userId,
            'cash' => $cash,
            'credit' => $credit,
        ];

        UserCreditDb::add($attributes);
    }

    /**
     * @param $list
     * 更新债权可用金额
     */
    public static function editCreditUsableAmount($list)
    {
        foreach ($list as $id=>$amount){
            $result = CreditDb::editUsableAmount($id,$amount);
            if(!$result){
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_USABLE_AMOUNT_UPDATE_FAILED'), self::getFinalCode('editCreditUsableAmount'));
            }
        }
    }

    /**
     * @param $list
     * @throws \Exception
     * 更新零钱计划债权人可用金额
     */
    public static function editDetailUsableAmount($list)
    {
        foreach ($list as $id=>$amount){

            $result = CreditDetailDb::editUsableAmount($id,$amount);

            if(!$result){
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_DETAIL_UPDATE_FAILED'), self::getFinalCode('editDetailUsableAmount'));
            }
        }

    }
}    