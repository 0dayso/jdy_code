<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Credit;

use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Credit\CreditProjectGroupModel;

use Log;

use App\Tools\ToolMoney;
/**
 * 债权项目集逻辑
 * Class CreditGroupLogic
 * @package App\Http\Logics\Credit
 */
class CreditGroupLogic extends CreditLogic
{

    /**
     * 添加项目集债权
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


            'financing_company'             => $data['financing_company'],
            'program_area_location'         => $data['program_area_location'],
            'loan_use'                      => $data['loan_use'],
            'repayment_source'              => $data['repayment_source'],
            'loan_contract'                 => $data['loan_contract'],
        ];

        try {

            $return = CreditProjectGroupModel::doCreate($attributes);

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
        $classObj = new CreditProjectGroupModel;
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

            $obj = CreditProjectGroupModel::findById($id);

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
     * 编辑项目集债权
     * @param array $data
     * @return array
     */
    public static function doUpdate($data = []){

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


            'financing_company'             => $data['financing_company'],
            'program_area_location'         => $data['program_area_location'],
            'loan_use'                      => $data['loan_use'],
            'repayment_source'              => $data['repayment_source'],
            'loan_contract'                 => $data['loan_contract'],
        ];

        try {

            //验证可用金额
            CreditModel::compareCash($data['loan_amounts'], $data['can_use_amounts']);

            $return = CreditProjectGroupModel::doUpdate($data['id'], $attributes);

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