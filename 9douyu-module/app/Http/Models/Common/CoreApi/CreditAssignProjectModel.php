<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/9/27
 * Time: 上午10:26
 */

namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use Config;
use App\Http\Models\Common\HttpQuery;

class CreditAssignProjectModel extends CoreApiModel
{

    /**
     * @param $userId       用户ID    必填
     * @return array
     * @desc 用户中心债权转让列表
     */
    public static function getUserCreditAssign($userId){

        $api  = Config::get('coreApi.moduleCreditAssign.getUserCreditAssign');

        $params = [
            'user_id'   => $userId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 变现宝专区债权转让列表
     */
    public static function getCreditAssignList($page, $size){

        $api  = Config::get('coreApi.moduleCreditAssign.getCreditAssignList');

        $params = [
            'page' => $page,
            'size' => $size,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }

    /**
     * @param $investId
     * @param $cash
     * @return null|void
     * 创建债转项目
     */
    public static function doCreateProject($investId,$cash){

        $params = [
            'invest_id' => $investId,
            'amount'    => (int)$cash,
        ];

        $api = Config::get('coreApi.moduleCreditAssign.doCreateProject');

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return null|void
     * @desc 通过项目ID,用户,金额匹配可债转的投资
     */
    public static function getUsableInvestId($projectId,$userId,$cash){

        $params = [
            'project_id' => $projectId,
            'user_id'  =>  $userId,
            'cash'    => $cash,
        ];

        $api = Config::get('coreApi.moduleCreditAssign.getUsableInvestId');

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }

    /**
     * @param $projectId
     * @param $userId
     * @return null|void
     * @desc 取消债转
     */
    public static function doCancel($creditAssignProjectId,$userId){

        $params = [
            'project_id' => $creditAssignProjectId,
            'user_id'    => $userId
        ];

        $api = Config::get('coreApi.moduleCreditAssign.doCancel');

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return null|void
     * @desc 购买债转
     */
    public static function doInvest($projectId,$userId,$cash){

        $params = [
            'project_id' => $projectId,
            'user_id'    => $userId,
            'cash'       => $cash,
        ];

        $api = Config::get('coreApi.moduleCreditAssign.doInvest');

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }

    /**
     * @param $projectId
     * @param $userId
     * @param $cash
     * @return null|void
     * @desc 购买债转
     */
    public static function doInvestByCurrent($projectId,$userId,$cash){

        $params = [
            'project_id' => $projectId,
            'user_id'    => $userId,
            'cash'       => $cash,
        ];

        $api = Config::get('coreApi.moduleCreditAssign.doInvestByCurrent');

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }

    /**
     * @param $projectId
     * @return array
     * @desc 变现宝项目详情
     */
    public static function getCreditAssignDetail($projectId){

        $api  = Config::get('coreApi.moduleCreditAssign.getCreditAssignDetail');

        $params = [
            'project_id' => $projectId,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户已转让的投资Id数组
     */
    public static function getUserCreditAssignInvestIds($userId){

        $api  = Config::get('coreApi.moduleCreditAssign.getUserCreditAssignInvestIds');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];
        }

    }

    /**
     * @return array
     * @desc 获取可投的债转项目总数
     */
    public static function getInvestingCount(){

        $api  = Config::get('coreApi.moduleCreditAssign.getInvestingCount');

        $params = [];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];
        }

    }

    /**
     * @param $investId
     * @return array
     * @desc
     */
    public static function userPreCreditAssign($investId){

        $api  = Config::get('coreApi.moduleCreditAssign.userPreCreditAssign');

        $params = [
            'invest_id' => $investId,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];
        }

    }

    /**
     * @param $investId
     * @return array
     * @desc
     */
    public static function getCreditAssignByInvestId($investId){

        $api  = Config::get('coreApi.moduleCreditAssign.getCreditAssignByInvestId');

        $params = [
            'invest_id' => $investId,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];
        }

    }
}