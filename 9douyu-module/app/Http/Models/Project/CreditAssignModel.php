<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/27
 * Time: 13:27
 */

namespace App\Http\Models\Project;

use App\Http\Models\Common\CoreApi\CreditAssignProjectModel;
use App\Http\Models\Model;
use App\Http\Models\Common\ExceptionCodeModel;
use Cache;
use App\Lang\LangModel;

class CreditAssignModel extends Model{

    public static $codeArr = [

        'doCreateProject'       => 1,
        'getUsableInvestId'     => 2,
        'cancel'                => 3,
        'addLock'               => 4,
    ];

    public static $defaultNameSpace = ExceptionCodeModel::EXP_MODEL_CREDIT_ASSIGN_PROJECT;


    /**
     * @param $investId
     * @param $cash
     * 创建债转项目
     */
    public function doCreateProject($investId,$cash){

        $result = CreditAssignProjectModel::doCreateProject($investId,$cash);

        if(!$result['status']){

            throw new \Exception($result['msg'],self::getFinalCode('doCreateProject'));

        }
        return true;


    }


    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return mixed
     * @throws \Exception
     * 获取匹配到的可用投资ID
     */
    public function getUsableInvestId($projectId,$userId,$cash){

        $result = CreditAssignProjectModel::getUsableInvestId($projectId,$userId,$cash);

        if(!$result['status']){

            throw new \Exception($result['msg'],self::getFinalCode('getUsabledInvestId'));
        }

        return $result['data']['invest_id'];
    }

    /**
     * @param $projectId
     * @return mixed
     * @throws \Exception
     * 取消债转项目
     */
    public function cancel($creditAssignProjectId,$userId){

        $result = CreditAssignProjectModel::doCancel($creditAssignProjectId, $userId);

        if(!$result['status']){

            throw new \Exception($result['msg'],self::getFinalCode('cancel'));
        }

        return true;
        
    }

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return bool
     * @throws \Exception
     * 购买债权转让项目
     */
    public function doInvest($projectId,$userId,$cash){

        $result = CreditAssignProjectModel::doInvest($projectId,$userId,$cash);

        if(!$result['status']){

            throw new \Exception($result['msg'],self::getFinalCode('cancel'));

        }else if($result['status'] && !empty($result['data'])){

            return $result['data'];
        }

        return true;
        
    }

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return bool
     * @throws \Exception
     * 购买债权转让项目
     */
    public function doInvestByCurrent($projectId,$userId,$cash){

        $result = CreditAssignProjectModel::doInvestBycurrent($projectId,$userId,$cash);

        if(!$result['status']){

            throw new \Exception($result['msg'],self::getFinalCode('cancel'));

        }else if($result['status'] && !empty($result['data'])){

            return $result['data'];
        }

        return true;

    }

    /**
     * @param $projectId
     * 零钱计划项目投资加锁
     */
    public function addLock($projectId){

        $lockKey = $this->getLockKey($projectId);

        if(Cache::has($lockKey)){
            throw new \Exception(LangModel::getLang('ERROR_CREDIT_ASSIGN_INVEST_FAILED'), self::getFinalCode('addLock'));
        }

        //债转投资加锁,锁定30秒
        Cache::put($lockKey,1,0.5);

    }


    /**
     * @param $projectId
     * @return mixed
     * 获取锁定的key
     */
    private function getLockKey($projectId){

        //投资债转项目加锁
        $baseKey = 'm_invest_assign_lock_%s';

        $lockKey    = sprintf($baseKey,$projectId);

        return $lockKey;
    }


    /**
     * @param $projectId
     * 解锁
     */
    public function releaseLock($projectId){

        $lockKey = $this->getLockKey($projectId);

        Cache::forget($lockKey);

    }
}