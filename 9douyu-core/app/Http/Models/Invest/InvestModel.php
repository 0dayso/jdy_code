<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午1:43
 * Desc: 投资记录
 */

namespace App\Http\Models\Invest;


use App\Http\Dbs\InvestDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class InvestModel extends Model
{


    public static $codeArr = [
        'add'                    => 1,
        'getById'                => 2,
        'getByProjectIdAndUserId'=> 3,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_INVEST;

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 插入投资记录
     */
    public function add($projectId, $userId, $cash,$investType = InvestDb::INVEST_TYPE,$assignProjectId = 0)
    {

        $data = [
            'project_id'    => $projectId,
            'user_id'       => $userId,
            'cash'          => $cash,
            'invest_type'   => $investType,
            'assign_project_id' => $assignProjectId
        ];
        $db = new InvestDb();

        $res = $db->add($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_INVEST_RECORD'), self::getFinalCode('add'));

        }

        return $res;

    }

    /**
     * @param $investId
     * 根据投资ID获取对应的数据
     */
    public function getById($investId){

        $db = new InvestDb();

        $res = $db->getObj($investId);

        if(!$res){

            throw new \Exception(LangModel::getLang('ERROR_EMPTY_RECORD'), self::getFinalCode('getById'));
        }
        return $res;

    }

    /**
     * @param $projectId
     * @param $userId
     * @return mixed
     * 获取用户投资某个项目指定金额的所有记录
     */
    public function getByProjectIdAndUserId($projectId,$userId,$cash){
        
        $db = new InvestDb();
        
        $result = $db->getByProjectIdAndUserId($projectId,$userId,$cash);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_EMPTY_RECORD'), self::getFinalCode('getByProjectIdAndUserId'));

        }

        return $result;
    }


}