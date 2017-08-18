<?php
/**
 * Created by PhpStorm.
 * User: liu qiu hui
 * Date: 17/03/21
 * Time: 13:30
 */

namespace App\Http\Models\CurrentNew;

use App\Http\Dbs\CurrentNew\ProjectNewDb as ProjectDb;
use App\Http\Dbs\CurrentNew\UserCurrentNewFundHistoryDb;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Tools\ToolTime;

class ProjectModel extends Model{

    public static $codeArr = [

        'create'                                    => 1,
        'editProjectInvestAmount'                   => 2,
        'checkCanInvestProjectNotExist'             => 3,
        'checkCanInvestCashLeftAmountNotEnough'     => 4,
        'checkCanInvest'                            => 5,
        'edit'                                      => 6,
    ];

    public static $defaultNameSpace = ExceptionCodeModel::EXP_MODEL_CURRENT_PROJECT;

    /**
     * @param $projectName
     * @param $cash
     * @param $publishAt
     * @param $admin
     * @param $status
     * @throws \Exception
     * 创建零钱计划项目
     */
    public function create($projectName,$cash,$publishAt,$admin,$status){

        $db = new ProjectDb();

        $result = $db->add($projectName,$cash,$publishAt,$admin,$status);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_PROJECT_CREATE_FAILED'), self::getFinalCode('create'));

        }

    }

    /**
     * @param $id
     * @param $projectName
     * @param $cash
     * @param $publishAt
     * @param $status
     * @throws \Exception
     * 修改零钱计划项目
     */
    public function edit($id, $projectName,$cash,$publishAt,$status){

        $db = new ProjectDb();

        $info = $db->getInfoById($id);

        if($info['invested_amount'] > 0 && $cash < $info['invested_amount']){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_PROJECT_EDIT_FAILED'), self::getFinalCode('edit'));

        }

        $result = $db->edit($id, $projectName,$cash,$publishAt,$status);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_PROJECT_EDIT_FAILED'), self::getFinalCode('edit'));

        }

    }

    /**
     * @return mixed
     * 获取零钱计划最新的一个项目
     */
    public function getProject(){

        $projectDb = new ProjectDb();
        $project = $projectDb->getShowProject();

        $project['name'] = '零钱计划';

        return $project;
    }



    /**
     * @param $id
     * @param $cash
     * @return mixed
     * @throws \Exception
     * 更新项目信息
     */
    public function editProjectInvestAmount($id, $cash)
    {

        $db = new ProjectDb();

        $res = $db->invest($id, $cash);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_AMOUNT_PROJECT_UPDATE_FAILED'), self::getFinalCode('editProjectInvestAmount'));

        }
        return $res;

    }

    /**
     * @param $projectId
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 检测项目可投
     */
    public function checkCanInvest($cash)
    {

        $projectDb = new ProjectDb();
        $info = $projectDb->getShowProject();

        if( empty($info) ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_PROJECT_NOT_EXIST'), self::getFinalCode('checkCanInvestProjectNotExist'));
        }

        if( $info['status'] == ProjectDB::STATUS_UN_PUBLISH || $info['publish_at'] > ToolTime::dbNow()){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_PROJECT_UN_PUBLISH'), self::getFinalCode('checkCanInvest'));
        }

        $leftAmount = $info['total_amount'] - $info['invested_amount'];

        if( $leftAmount < abs($cash) ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_LEFT_AMOUNT_NOT_ENOUGH'), self::getFinalCode('checkCanInvestCashLeftAmountNotEnough'));

        }

        $info['left_amount'] = $leftAmount;

        return $info;

    }

}