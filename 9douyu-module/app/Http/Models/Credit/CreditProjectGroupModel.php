<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Models\Credit;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Http\Dbs\Credit\CreditGroupDb;

use Log;
/**
 * 债权集模型
 * Class CreditProjectGroupModel
 * @package App\Http\Models\Credit
 */
class CreditProjectGroupModel extends CreditModel implements CreditInterfaceModel
{


    public static $codeArr            = [
        'doCreate' => 1,
        'findById' => 2,
        'doUpdate' => 3,
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CREDIT_GROUT;


    /**
     * 创建保理债权
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function doCreate($data){

        $return = CreditGroupDb::addRecord($data);

        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_CREDIT_CREATE_GROUP'), self::getFinalCode('doCreate'));

        //日志
        \App\Tools\AdminUser::userLog('credit',[$data, $return]);

        return $return;
    }


    /**
     * 获取债权列表
     * @param array $condition
     * @return mixed
     */
    public function getAdminList($condition = []){
        $size  = $this->getAdminListPageSize();
        $field = [
            'id',
            'source',
            'type',
            'credit_tag',
            'company_name',
            'loan_amounts',
            'interest_rate',
            'repayment_method',
            'expiration_date',
            'loan_deadline',
            'contract_no',
        ];
        return CreditGroupDb::select($field)->where($condition)->orderBy('id', 'desc')->paginate($size);
    }


    /**
     * 格式化债权列表
     * @param array $listData
     * @return array
     */
    public function formatAdminList($listData = []){
        return $listData;
    }

    /**
     * 根据ID获取对象
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public static function findById($id){
        $obj = CreditGroupDb::findById($id);
        if(!$obj)
            throw new \Exception(LangModel::getLang('ERROR_CREDIT_CREATE_FIND_BY_ID'), self::getFinalCode('findById'));

        return $obj;
    }


    /**
     * 编辑债权
     * @param $id
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function doUpdate($id =0, $data = []){
        $return = CreditGroupDb::doUpdate($id, $data);
        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_CREDIT_UPDATE'), self::getFinalCode('doUpdate'));

        //日志
        \App\Tools\AdminUser::userLog('credit',[$id, $data, $return]);

        return $return;
    }

}