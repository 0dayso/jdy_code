<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 16:26
 */
namespace App\Http\Logics\Current;

use App\Http\Dbs\Current\CreditDetailDb;
use App\Http\Logics\Logic;
use App\Http\Models\Current\CreditModel;
use App\Tools\ToolMoney;
use App\Http\Dbs\Current\CreditDb;
use Excel;
use Log;

class CreditLogic extends Logic{


    /**
     * @return array
     * 获取零钱计划债权的还款方式
     */
    public static function getRefundType(){
        
        return CreditModel::refundType();
    }


    /**
     * @param array $condition
     * @return mixed
     * 根据条件获取零钱计划债权列表
     */
    public static function getList($condition = []){

        return CreditDb::getList($condition);
    }

    /**
     * @param array $condition
     * @return mixed
     * 根据条件获取零钱计划债权列表
     */
    public static function getDetailList($id){

        return CreditDetailDb::getList($id);
    }

    /**
     * 表头：0-姓名 1-身份证号 2-借款金额 3-借款日期 4-借款到期日期 5-所在地
     * @param $path
     * @return array
     * 获取附件中的债权人信息
     */
    public static function loadExcel($path){

        $reader = Excel::load($path,'GB2312');
        $results = $reader->getSheet(0)->toArray();
        $results = array_slice($results,1);

        if(empty($results)){

            return fasle;
        }
        $totalAmount = 0;

        foreach($results as $result){

            //金额转化为分
            $cash = ToolMoney::formatDbCashAdd($result[2]);

            $item[] = array(
                'name'          => $result[0], //姓名
                'id_card'       => $result[1],  //身份证号
                'amount'        => $cash,       //借款金额
                'usable_amount' => $cash,
                'time'          => date('Y-m-d',strtotime($result[3])),  //借款日期
                //'end_time'      => $result[4], //到期日期
                'address'       => $result[5], //所在地

            );

            $totalAmount += $cash;
        }
        return [
            'list'          => $item,
            'total_amount'  => (int)$totalAmount
        ];
    }


    /**
     * @param $data
     * @return array
     * 添加零钱计划债权
     */
    public static function doCreate($data){

        try{

            //用户提交的总金额
            $totalAmount = $data['total_amount'];
            //债权人信息解析
            $result = self::parseFile($totalAmount,$data['credit_file']);
            //出现错误
            if($result['status'] === false){

                return self::callError($result['msg']);
            }
            //债权信息
            $attributes = [

                "name"          => $data['name'],
                "total_amount"  => $totalAmount,
                "refund_type"   => $data['refund_type'],
                "invest_time"   => $data['invest_time'],
                "end_time"      => $data['end_time'],
                "percentage"    => $data['percentage'],
                "usable_amount" => $totalAmount,
                "contract_no"   => $data['contract_no'],
                "create_by"     => $data['manage_id']
                
            ];

            self::beginTransaction();

            //添加债权信息
            $creditId = CreditModel::doCreateCredit($attributes);
            //添加债权详情信息
            $result   = CreditModel::doCreateDetail($creditId,$result['list']);

            self::commit();

        }catch (\Exception $e){

            self::rollback();
            return self::callError($e->getMessage());

        }

        return self::callSuccess();

    }

    /**
     * @param $totalAmount
     * @param $file
     * @return array
     * 解析债权附件并比对债权金额
     */
    private static function parseFile($totalAmount, $file){

        $path = $file->getRealPath();
        //文件解析
        $result = self::loadExcel($path);

        //债权人无法解析
        if($result === false){
            return self::callError('文件无法解析');
        }

        //债权总金额
        $creditAmount = $result['total_amount'];

        //债权金额与附件的总金额不一致
        if($creditAmount !== $totalAmount){

            return self::callError('金额不匹配');
        }

        $result['status'] = true;
        return $result;
    }

    /**
     * @param $data
     * @return array
     * 编辑零钱计划债权
     */
    public static function doEdit($data){

        try{
            //用户提交的总金额
            $totalAmount = $data['total_amount'];
            //债权ID
            $creditId       = $data['id'];
            //是否上传附件初始值
            $isAttachment   = false;
            //上传债权人附件
            if(isset($data['credit_file']) && is_object($data['credit_file'])){

                $result     = self::parseFile($totalAmount,$data['credit_file']);

                $isAttachment   = true;

            }else{
                //未上传债权信息,获取相应债权人的所有金额
                $result = self::compareCrditAmount($totalAmount,$creditId);

            }

            if($result['status'] === false){

                return self::callError($result['msg']);
            }

            //债权信息
            $attributes = [
                "name"          => $data['name'],
                "total_amount"  => $totalAmount,
                "invest_time"   => $data['invest_time'],
                "end_time"      => $data['end_time'],
                "percentage"    => $data['percentage'],
                "contract_no"   => $data['contract_no'],
                "usable_amount" => $totalAmount,
                "create_by"     => $data['manage_id'],

            ];

            self::beginTransaction();

            //编辑债权信息
            CreditModel::doEditCredit($creditId,$attributes);

            if($isAttachment === true){

                //删除原有的债权详情
                CreditModel::deleteCreateDetail($creditId);
                //添加债权详情信息
                CreditModel::doCreateDetail($creditId,$result['list']);
                
            }

            self::commit();

        }catch (\Exception $e){

            self::rollback();
            return self::callError($e->getMessage());

        }

        return self::callSuccess();

    }

    /**
     * @param $totalAmount
     * @param $creditId
     * 比对传递的债权总金额是否与数据库所有借款人的总金额一致
     */
    private static function compareCrditAmount($totalAmount,$creditId){


        $creditData     = CreditDetailDb::getAmountByCreditId($creditId);

        $creditAmount   =   (int)$creditData[0]['total_amount'];

        if($totalAmount !== $creditAmount){

            return self::callError('金额不匹配');

        }
    }



    /**
     * @param $totalAmount
     * @return int
     * 将万元转化成分
     */
    public static function formatCash($totalAmount){

        //单位转化成元
        $tmpAmount = ToolMoney::formatDbCashAddTenThousand($totalAmount);
        //金额再转化成分
        $amount    = (int)ToolMoney::formatDbCashAdd($tmpAmount);

        return $amount;

    }


    /**
     * @param $id
     * @return array
     * 根据主键ID获取债权信息
     */
    public static function findById($id){
        
        $obj = CreditDb::findById($id);

        if($obj){
            return self::callSuccess([ 'obj' => $obj]);
        }

        return self::callError('债权不存在');
    }


    /**
     * 债权回收
     */
    public static function recovery(){


        try{

            self::beginTransaction();

            //恢复零钱计划债权
            CreditModel::creditRecovery();
            //恢复零钱计划债权人信息
            CreditModel::creditDetailRecovery();

            //清空用户匹配的零钱计划债权信息
            CreditModel::userCreditClear();

            self::commit();

        }catch(\Exception $e){

            self::rollback();

            $data['msg']    = $e->getMessage();
            $data['code']   = $e->getCode();
            $data['desc']   = '零钱计划债权恢复失败';

            Log::error(__METHOD__.'Error', $data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([]);
    }


    /**
     * @param $userId
     * @return array
     * 给用户匹配零钱计划债权
     */
    public static function viewCredit($userId){

        //判断是否已分配过债权,若分配过,直接返回
        $creditList = CreditModel::checkCreditAssigned($userId);
        if($creditList){
            return self::callSuccess(['creditList' => $creditList]);
        }

        try{
            //获取用户需要匹配债权的金额
            $cash = CreditModel::getUserCurrentAmount($userId);
            //匹配的债权结果
            $data = CreditModel::assignedCredit($userId,$cash);

            self::beginTransaction();

            //更新债权相关的数据
            //1.添加匹配记录
            CreditModel::addUserCreditRecord($userId,$cash,$data['user_credit']);
            //2.更新债权可用金额
            CreditModel::editCreditUsableAmount($data['credit']);
            //3.更新借款人可用金额
            CreditModel::editDetailUsableAmount($data['credit_detail']);

            self::commit();

        }catch(\Exception $e){

            self::rollback();

            return self::callError($e->getMessage());
        }

        return self::callSuccess(['creditList' => $data['user_credit']]);

    }
}
