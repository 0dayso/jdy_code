<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/31
 * Time: 下午6:13
 */
namespace App\Http\Models\Credit\Lable;

use App\Http\Dbs\Credit\CreditDb;

use App\Http\Models\Model;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use Log;
/**
 * 债权标签抽象类
 * Class CreditLable
 * @package App\Http\Models\Credit\Lable
 */
abstract class CreditLableModel extends Model
{

    public static $codeArr            = [
        'returnCanUseAmount'              => 1,
        'returnCanUseAmountStatus'        => 2,
        'returnCanUseAmountClass'         => 3,
        'occupationCanUseAmountStatus'    => 4,
        'occupationCanUseAmountClass'     => 5,
        'occupationCanUseAmount'          => 6,
    ];


    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CREDIT_UPDATE_STATUE;

    /**
     * 根据条件获取债权集
     * @param array $field
     * @param array $condition
     * @return array
     */
    public function get($field = [], $condition = []){
        $data        = [];
        $field[]     = 'can_use_amounts';
        $fieldNine   = $baseField  = $field;
        $fieldNine[] = 'plan_name as credit_name';
        $baseField[] = 'company_name as credit_name';
//->where('can_use_amounts','>', 0)
        $data[] = \App\Http\Dbs\Credit\CreditFactoringDb::select($baseField)->where($condition)
            ->orderBy('id', 'desc')->get()->toArray();
        $data[] = \App\Http\Dbs\Credit\CreditGroupDb::select($baseField)->where($condition)
            ->orderBy('id', 'desc')->get()->toArray();
        $data[] = \App\Http\Dbs\Credit\CreditHousingDb::select($baseField)->where($condition)
            ->orderBy('id', 'desc')->get()->toArray();
        $data[] = \App\Http\Dbs\Credit\CreditLoanDb::select($baseField)->where($condition)
            ->orderBy('id', 'desc')->get()->toArray();
        $data[] = \App\Http\Dbs\Credit\CreditNineDb::select($fieldNine)->where($condition)
            ->orderBy('id', 'desc')->get()->toArray();
        $data[] = \App\Http\Dbs\Credit\CreditThirdDb::select($baseField)->where($condition)
            ->orderBy('id', 'desc')->get()->toArray();
        return $data;
    }

    /**
     * 格式化数组
     * @param array $data
     * @return array
     */
    public function formatData($data = []){
        $result = [];
        $nowDate         = date('Y-m-d');
        $nowStrtotime    = strtotime($nowDate);
        if(!empty($data)){
            foreach($data as $creditData){
                if(!empty($creditData)) {
                    foreach ($creditData as $item) {
                        $strtotime = strtotime($item['expiration_date']);
                        //剩余天数
                        $item['remaining_day'] = null;
                        if($nowStrtotime < $strtotime){
                            $item['remaining_day'] = ($strtotime - $nowStrtotime) / 86400;
                        }

                        if($item['type'] == CreditDb::TYPE_BASE){
                            $item['update_status_identifier'] = $item['source'];
                        }else{
                            $item['update_status_identifier'] = $item['type'];
                        }
                        $result[] = $item;
                    }
                }
            }
        }
        return $result;
    }


    /**
     *  更新状态、可用金额占用 【回退+】 //cash 分
     * @param array $data [
     *                  ['id'=> 1, 'update_status_identifier'=10, status_code=100, cash=>10],
     *                  ['id'=> 3, 'update_status_identifier'=10, status_code=200, cash=>10],
     *                  ['id'=> 2, 'update_status_identifier'=70, status_code=300, cash=>10],
     *                    ]
     * @throws \Exception
     * @return bool
     */
    public static function returnCanUseAmount($data){
        if(empty($data))
            return false;
        $status      = \App\Http\Models\Credit\CreditModel::getStatusCode();

        foreach($data as $key => $item){
            $id          = $item['id'];
            $code        = $item['update_status_identifier'];
            $status_code = $item['status_code'];
            $cash        = $item['cash'];

            if(!isset($status[$status_code])){
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_UPDATE_STATUE'), self::getFinalCode('returnCanUseAmountStatus'));
            }

            $class =\App\Http\Models\Credit\CreditModel::getClass($code);

            if($class === null || !class_exists($class)){
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_UPDATE_STATUE'), self::getFinalCode('returnCanUseAmountClass'));
            }

            $result = $class::where('id', $id)
                ->where('loan_amounts', '>=', \DB::raw(sprintf('`can_use_amounts`+%d', $cash)))
                ->update(['status_code' => $status_code, 'can_use_amounts'=>\DB::raw(sprintf('`can_use_amounts`+%d', $cash))]);

            Log::info(app('db')->getQueryLog());

            if(!$result) {
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_UPDATE_STATUE'), self::getFinalCode('returnCanUseAmount'));
            }
        }
        return true;
    }
    /**
     *  更新状态、可用金额占用 【占用 减】 //cash 分
     * @param array $data [
     *                  ['id'=> 1, 'update_status_identifier'=10, status_code=100, cash=>10],
     *                  ['id'=> 3, 'update_status_identifier'=10, status_code=200, cash=>10],
     *                  ['id'=> 2, 'update_status_identifier'=70, status_code=300, cash=>10],
     *                    ]
     * @throws \Exception
     * @return bool
     */
    public static function occupationCanUseAmount($data){
        if(empty($data))
            return false;
        $status      = \App\Http\Models\Credit\CreditModel::getStatusCode();

        foreach($data as $key => $item){
            $id          = $item['id'];
            $code        = $item['update_status_identifier'];
            $status_code = $item['status_code'];
            $cash        = $item['cash'];

            if(!isset($status[$status_code])){
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_UPDATE_STATUE'), self::getFinalCode('occupationCanUseAmountStatus'));
            }

            $class =\App\Http\Models\Credit\CreditModel::getClass($code);

            if($class === null || !class_exists($class)){
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_UPDATE_STATUE'), self::getFinalCode('occupationCanUseAmountClass'));
            }

            $result = $class::where('id', $id)
                ->where(\DB::raw(sprintf('`can_use_amounts`-%d', $cash)), '>=', 0)
                ->update(
                    [
                        'status_code' => $status_code,
                        'can_use_amounts'=>\DB::raw(sprintf('`can_use_amounts`-%d', $cash))
                    ]);

            Log::info(app('db')->getQueryLog());

            if(!$result) {
                throw new \Exception(LangModel::getLang('ERROR_CREDIT_UPDATE_STATUE'), self::getFinalCode('occupationCanUseAmount'));
            }
        }
        return true;
    }
}