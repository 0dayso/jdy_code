<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/3
 * Time: 下午11:04
 */

namespace App\Http\Models\Contract;


use App\Http\Dbs\Contract\ContractDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Lang\LangModel;

class ContractModel extends Model
{

    private $db;

    public static $codeArr            = [
        'doAddInfo'  => 1,
        'checkBonusId' => 2,

    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CONTRACT;

    public function __construct()
    {

        $this -> db = new ContractDb();
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 执行添加合同信息
     */
    public function doAddInfo($data){

        $result = $this->db->add($data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_RECORD_ADD_FAIL'), self::getFinalCode('doAddInfo'));
        }

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 执行更新合同信息
     */
    public function doUpdate( $data ){

        $result = $this->db->doUpdate($data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_RECORD_UPDATE_FAIL'), self::getFinalCode('doUpdate'));
        }

        return $result;

    }

    /**
     * 调用合同服务
     * @param $parameter
     * @return mixed
     */
    public function contractService($parameter)
    {
        $server = array();
        $res = HttpQuery::serverPost('/contract/index', $parameter);
        if ($res['code'] == Logic::CODE_SUCCESS) {
            $server = $res['data'];
        }
        return $server;
    }

}