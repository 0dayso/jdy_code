<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Credit;

use App\Http\Models\Credit\CreditCreditLoanModel;

use App\Http\Models\Credit\CreditModel;
use Log;

use App\Tools\ToolMoney;
/**
 * 耀盛信贷债权逻辑
 * Class CreditLogic
 * @package App\Http\Logics\Credit
 */
class CreditLoanLogic extends CreditLogic
{
    /**
     * 添加耀盛信贷常规债权
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
            'profit_level'                  => $data['profit_level'],
            'liability_level'               => $data['liability_level'],
            'guarantee_level'               => $data['guarantee_level'],

            'company_level_value'           => $data['company_level_value'],
            'profit_level_value'            => $data['profit_level_value'],
            'liability_level_value'         => $data['liability_level_value'],
            'guarantee_level_value'         => $data['guarantee_level_value'],

            'keywords'                      => empty($data['keywords']) ? null : json_encode($data['keywords']),
            'credit_desc'                   => $data['credit_desc'],

            'financing_company'             => $data['financing_company'],
            'founded_time'                  => $data['founded_time'],
            'program_area_location'         => $data['program_area_location'],
            'registered_capital'            => $data['registered_capital'],
            'annual_income'                 => $data['annual_income'],
            'loan_use'                      => $data['loan_use'],
            'repayment_source'              => $data['repayment_source'],
            'background'                    => $data['background'],
            'financial'                     => $data['financial'],

            'sex'                           => $data['sex'],
            'age'                           => $data['age'],
            'family_register'               => $data['family_register'],
            'residence'                     => $data['residence'],
            'home_stability'                => $data['home_stability'],
            'esteemn'                       => $data['esteemn'],
            'credibility'                   => $data['credibility'],
            'involved_appeal'               => $data['involved_appeal'],
            'submit_data'                   => empty($data['submit_data']) ? null : json_encode($data['submit_data']),
            'risk_control_message'          => $data['risk_control_message'],
            'risk_control_security'         => $data['risk_control_security'],
            'creditor_info'                 => !empty($data['creditor_info'])? $data['creditor_info'] : '',

        ];

        try {

            $return = CreditCreditLoanModel::doCreate($attributes);

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
        $classObj = new CreditCreditLoanModel;
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

            $obj = CreditCreditLoanModel::findById($id);

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
     * 编辑耀盛信贷常规债权
     * @param array $data
     * @return array
     */
    public static function doUpdate($data = []){

        //dd($data);

        $attributes = [
            'source'                        => $data['source'],
            'type'                          => $data['type'],
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
            'profit_level'                  => $data['profit_level'],
            'liability_level'               => $data['liability_level'],
            'guarantee_level'               => $data['guarantee_level'],

            'company_level_value'           => $data['company_level_value'],
            'profit_level_value'            => $data['profit_level_value'],
            'liability_level_value'         => $data['liability_level_value'],
            'guarantee_level_value'         => $data['guarantee_level_value'],

            'keywords'                      => empty($data['keywords']) ? null : json_encode($data['keywords']),
            'credit_desc'                   => $data['credit_desc'],

            'financing_company'             => $data['financing_company'],
            'founded_time'                  => $data['founded_time'],
            'program_area_location'         => $data['program_area_location'],
            'registered_capital'            => $data['registered_capital'],
            'annual_income'                 => $data['annual_income'],
            'loan_use'                      => $data['loan_use'],
            'repayment_source'              => $data['repayment_source'],
            'background'                    => $data['background'],
            'financial'                     => $data['financial'],

            'sex'                           => $data['sex'],
            'age'                           => $data['age'],
            'family_register'               => $data['family_register'],
            'residence'                     => $data['residence'],
            'home_stability'                => $data['home_stability'],
            'esteemn'                       => $data['esteemn'],
            'credibility'                   => $data['credibility'],
            'involved_appeal'               => $data['involved_appeal'],
            'submit_data'                   => empty($data['submit_data']) ? null : json_encode($data['submit_data']),
            'risk_control_message'          => $data['risk_control_message'],
            'risk_control_security'         => $data['risk_control_security'],
            'creditor_info'                 => !empty($data['creditor_info'])? $data['creditor_info'] : '',

        ];

        try {

            //验证可用金额
            CreditModel::compareCash($data['loan_amounts'], $data['can_use_amounts']);

            $return = CreditCreditLoanModel::doUpdate($data['id'], $attributes);

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