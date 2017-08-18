<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/12
 * Time: 13:30
 */

namespace App\Http\Models\Project;

use App\Http\Dbs\Current\ProjectDb;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;

class CurrentModel extends Model{

    public static $codeArr = [

        'create'                                    => 1,
        'editProjectInvestAmount'                   => 2,
        'checkCanInvestProjectNotExist'             => 3,
        'checkCanInvestCashLeftAmountNotEnough'     => 4,
    ];

    public static $defaultNameSpace = ExceptionCodeModel::EXP_MODEL_CURRENT_PROJECT;

    /**
     * @param $projectName
     * @param $cash
     * @param $admin
     * 创建零钱计划项目
     */
    public function create($projectName,$cash,$publishAt,$admin){

        $db = new ProjectDb();

        $result = $db->add($projectName,$cash,$publishAt,$admin);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_PROJECT_CREATE_FAILED'), self::getFinalCode('create'));

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

        $leftAmount = $info['total_amount'] - $info['invested_amount'];

        if( $leftAmount < abs($cash) ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_LEFT_AMOUNT_NOT_ENOUGH'), self::getFinalCode('checkCanInvestCashLeftAmountNotEnough'));

        }

        $info['left_amount'] = $leftAmount;

        return $info;

    }
}