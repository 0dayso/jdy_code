<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Credit;

use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Credit\CreditNineCreditModel;

use Log;

use App\Tools\ToolMoney;
/**
 * 债权九省心逻辑
 * Class CreditLogic
 * @package App\Http\Logics\Credit
 */
class CreditNineLogic extends CreditLogic
{

    /**
     * 添加九省心债权
     * @param array $data
     * @return array
     */
    public static function doCreate($data = []){

        //dd($data);

        $attributes = [
            'source'                        => $data['source'],
            'type'                          => $data['type'],
            'credit_tag'                    => $data['credit_tag'],
            'plan_name'                     => $data['plan_name'],
            'loan_amounts'                  => self::getSaveAmounts($data['loan_amounts']),
            'can_use_amounts'               => self::getSaveAmounts($data['loan_amounts']),
            'interest_rate'                 => $data['interest_rate'],
            'repayment_method'              => $data['repayment_method'],
            'expiration_date'               => $data['expiration_date'],
            'loan_deadline'                 => $data['loan_deadline'],
            'contract_no'                   => $data['contract_no'],

            'program_no'                    => $data['program_no'],
            'file'                          => $data['file'],
        ];

        if(!empty($data['credit_info']))
            $attributes['credit_info'] = $data['credit_info'];

        try {
            $return = CreditNineCreditModel::doCreate($attributes);

        }catch (\Exception $e){
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$return]);
    }


    /**
     * 获取相应的债权列表
     * @param array $condition
     * @return array
     */
    public static function getList($condition = []){
        $classObj = new CreditNineCreditModel;
        if(method_exists($classObj, 'getAdminList') && method_exists($classObj, 'formatAdminList')){
            return self::formatAdminList($classObj->formatAdminList($classObj->getAdminList($condition)));
        }
        return [];
    }

    /**
     * 格式化保理债权列表
     * @param array $listData
     * @return array
     */
    protected static function formatAdminList($listData = []){
        if($listData){
            foreach($listData as $list){
                $list->loan_amounts = ToolMoney::formatDbCashDeleteTenThousand($list->loan_amounts);
            }
        }
        return $listData;
    }

    /**
     * 获取指定债权
     * @param int $id
     * @return array
     */
    public static function findById($id = 0){
        try{

            $obj = CreditNineCreditModel::findById($id);

        }catch (\Exception $e){
            $data['id']             = $id;
            $data['msg']            = $e->getMessage();
            $data['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([ 'obj' => $obj]);
    }

    /**
     * 编辑九省心债权
     * @param array $data
     * @return array
     */
    public static function doUpdate($data = []){

        //dd($data);

        $attributes = [
            'source'                        => $data['source'],
            'type'                          => $data['type'],
            'credit_tag'                    => $data['credit_tag'],
            'plan_name'                     => $data['plan_name'],
            'loan_amounts'                  => self::getSaveAmounts($data['loan_amounts']),
            'can_use_amounts'               => self::getSaveAmounts($data['can_use_amounts']),
            'interest_rate'                 => $data['interest_rate'],
            'repayment_method'              => $data['repayment_method'],
            'expiration_date'               => $data['expiration_date'],
            'loan_deadline'                 => $data['loan_deadline'],
            'contract_no'                   => $data['contract_no'],

            'program_no'                    => $data['program_no'],
            'file'                          => $data['file'],
        ];

        if(!empty($data['credit_info']))
            $attributes['credit_info'] = $data['credit_info'];

        try {
            //验证可用金额
            CreditModel::compareCash($data['loan_amounts'], $data['can_use_amounts']);

            $return = CreditNineCreditModel::doUpdate($data['id'], $attributes);

        }catch (\Exception $e){
            $attributes['id']             = $data['id'];
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$return]);
    }
}