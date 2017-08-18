<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Credit;

use App\Http\Dbs\Project\ProjectDb;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Credit\CreditThirdCreditModel;

use App\Http\Models\Credit\CreditThirdDetailModel;
use Log;

use App\Tools\ToolMoney;
/**
 * 债权第三方逻辑
 * Class CreditLogic
 * @package App\Http\Logics\Credit
 */
class CreditThirdLogic extends CreditLogic
{
    /**
     * 添加第三方债权
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
            'project_desc'                  => $data['project_desc'],
            'risk_control'                  => $data['risk_control'],
        ];

        if(!empty($data['credit_list']))
            $attributes['credit_list'] = $data['credit_list'];

        try {

            $return = CreditThirdCreditModel::doCreate($attributes);

        }catch (\Exception $e){
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }
        //九省心一月期执行添加债权人信息
        if(!empty($data['credit_list'])){
            //添加第三方债权人信息数据组装
            $data = [
                'credit_id'   => $return,
                'credit_list' => $attributes['credit_list']
            ];

            \Event::fire(new \App\Events\Admin\Credit\CreditThirdDetailEvent(
                ['data'=> $data]
            ));

        }
        return self::callSuccess([$return]);
    }


    /**
     * 获取相应的债权列表
     * @param array $condition
     * @return array
     */
    public static function getList($condition = []){
        $classObj = new CreditThirdCreditModel;
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

            $obj = CreditThirdCreditModel::findById($id);

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
     * 编辑第三方债权
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
            'project_desc'                  => $data['project_desc'],
            'risk_control'                  => $data['risk_control'],
        ];

        if(!empty($data['credit_list']))
            $attributes['credit_list'] = $data['credit_list'];

        try {

            //验证可用金额
            CreditModel::compareCash($data['loan_amounts'], $data['can_use_amounts']);

            $return = CreditThirdCreditModel::doUpdate($data['id'], $attributes);

        }catch (\Exception $e){
            $attributes['id']             = $data['id'];
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }
        //判断债权详情是否上传过  上传过就直接跳过时间机制
        $creditDetailModel = new CreditThirdDetailModel();

        $isImport = $creditDetailModel->checkCreditThirdIsImport($data['id']);

        //九省心一月期编辑倒入债权人信息
        if(!empty($data['credit_list']) ){
            //添加第三方债权人信息数据组装
            $data = [
                'credit_id'   => $data['id'],
                'credit_list' => $attributes['credit_list']
            ];

            \Event::fire(new \App\Events\Admin\Credit\CreditThirdDetailEvent(
                ['data'=> $data]
            ));

        }

        return self::callSuccess([$return]);
    }

}
