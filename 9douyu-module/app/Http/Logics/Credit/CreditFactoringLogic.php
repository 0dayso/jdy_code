<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Credit;

use App\Http\Models\Credit\CreditFactoringModel;

use App\Http\Models\Credit\CreditModel;
use App\Tools\ToolMoney;

use Log;

/**
 * 保理债权逻辑
 * Class CreditLogic
 * @package App\Http\Logics\Credit
 */
class CreditFactoringLogic extends CreditLogic
{
    /**
     * 添加耀盛保理常规债权
     * @param array $data
     * @return array
     */
    public static function doCreate($data = []){

        //dd($data);

        $attributes = [
            'source'                        => $data['source'],
            'type'                          => $data['type'],
            'credit_tag'                    => $data['credit_tag'],
            'company_name'                  => $data['company_name'],
            'loan_amounts'                  => self::getSaveAmounts($data['loan_amounts']),
            'can_use_amounts'               => self::getSaveAmounts($data['loan_amounts']),
            'interest_rate'                 => $data['interest_rate'],
            'repayment_method'              => $data['repayment_method'],
            'expiration_date'               => $data['expiration_date'],
            'loan_deadline'                 => $data['loan_deadline'],
            'contract_no'                   => $data['contract_no'],
            'loan_username'                 => empty($data['loan_username']) ? null : json_encode($data['loan_username']),
            'loan_user_identity'            => empty($data['loan_user_identity']) ? null : json_encode($data['loan_user_identity']),

            'riskcalc_level'                => $data['riskcalc_level'],

            'company_level'                 => $data['company_level'],
            'downstream_level'              => $data['downstream_level'],
            'profit_level'                  => $data['profit_level'],
            'downstream_refund_level'       => $data['downstream_refund_level'],
            'liability_level'               => $data['liability_level'],
            'guarantee_level'               => $data['guarantee_level'],

            'company_level_value'           => $data['company_level_value'],
            'downstream_level_value'        => $data['downstream_level_value'],
            'profit_level_value'            => $data['profit_level_value'],
            'downstream_refund_level_value' => $data['downstream_refund_level_value'],
            'liability_level_value'         => $data['liability_level_value'],
            'guarantee_level_value'         => $data['guarantee_level_value'],

            'keywords'                      => empty($data['keywords']) ? null : json_encode($data['keywords']),
            'credit_desc'                   => $data['credit_desc'],
            'factor_summarize'              => $data['factor_summarize'],
            'repayment_source'              => $data['repayment_source'],
            'factoring_opinion'             => $data['factoring_opinion'],
            'business_background'           => $data['business_background'],
            'introduce'                     => $data['introduce'],
            'risk_control_measure'          => $data['risk_control_measure'],
            'transactional_data'            => $data['transactional_data'],
            'traffic_data'                  => $data['traffic_data'],
        ];

        try {

            $return = CreditFactoringModel::doCreate($attributes);

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
        $classObj = new CreditFactoringModel;
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

            $obj = CreditFactoringModel::findById($id);

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
     * 执行编辑
     * @param array $data
     * @return array
     */
    public static function doUpdate($data = []){
        $attributes = [
            'credit_tag'                    => $data['credit_tag'],
            'company_name'                  => $data['company_name'],
            'loan_amounts'                  => self::getSaveAmounts($data['loan_amounts']),
            'can_use_amounts'               => self::getSaveAmounts($data['can_use_amounts']),
            'interest_rate'                 => $data['interest_rate'],
            'repayment_method'              => $data['repayment_method'],
            'expiration_date'               => $data['expiration_date'],
            'loan_deadline'                 => $data['loan_deadline'],
            'contract_no'                   => $data['contract_no'],
            'loan_username'                 => empty($data['loan_username']) ? null : json_encode($data['loan_username']),
            'loan_user_identity'            => empty($data['loan_user_identity']) ? null : json_encode($data['loan_user_identity']),

            'riskcalc_level'                => $data['riskcalc_level'],

            'company_level'                 => $data['company_level'],
            'downstream_level'              => $data['downstream_level'],
            'profit_level'                  => $data['profit_level'],
            'downstream_refund_level'       => $data['downstream_refund_level'],
            'liability_level'               => $data['liability_level'],
            'guarantee_level'               => $data['guarantee_level'],

            'company_level_value'           => $data['company_level_value'],
            'downstream_level_value'        => $data['downstream_level_value'],
            'profit_level_value'            => $data['profit_level_value'],
            'downstream_refund_level_value' => $data['downstream_refund_level_value'],
            'liability_level_value'         => $data['liability_level_value'],
            'guarantee_level_value'         => $data['guarantee_level_value'],

            'keywords'                      => empty($data['keywords']) ? null : json_encode($data['keywords']),
            'credit_desc'                   => $data['credit_desc'],
            'factor_summarize'              => $data['factor_summarize'],
            'repayment_source'              => $data['repayment_source'],
            'factoring_opinion'             => $data['factoring_opinion'],
            'business_background'           => $data['business_background'],
            'introduce'                     => $data['introduce'],
            'risk_control_measure'          => $data['risk_control_measure'],
            'transactional_data'            => $data['transactional_data'],
            'traffic_data'                  => $data['traffic_data'],
        ];

        try {

            //验证可用金额
            CreditModel::compareCash($data['loan_amounts'], $data['can_use_amounts']);

            $return = CreditFactoringModel::doUpdate($data['id'], $attributes);

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