<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/2
 * Time: 下午8:49
 * Desc: 项目债权关系模型
 */

namespace App\Http\Models\Project;

use App\Http\Dbs\Current\CreditDb;
use App\Http\Dbs\Credit\CreditDb as RegularCreditDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;
use App\Http\Dbs\Project\ProjectLinkCreditDb;
use App\Http\Dbs\Project\ProjectLinkCreditNewDb;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use Log;

use Config;

use App\Tools\ToolCurl;

use App\Tools\ToolArray;

use App\Http\Models\Common\CoreApi\SystemConfigModel;

use App\Http\Models\Common\CoreApi\ProjectModel as CoreApiProjectModel;

class ProjectLinkCreditModel extends Model
{

    public static $codeArr            = [
        'createProjectLinkCredit' => 1,
        'getCoreProjectDetail'    => 2,
        'updateProjectLinkCredit' => 3,
        'getByProjectId'          => 4,
        'checkCreditTotal'        => 5,
        'getByProjectIds'         => 6,
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_PROJECT_LINK_CREDIT;

    /**
     * 项目债权关系数据实例
     * @var null
     */
    protected $ProjectLinkCreditDb = null;


    public function __construct(){
        $this->ProjectLinkCreditDb = new ProjectLinkCreditDb;
    }

    /**
     * 根据项目ID查询内容
     * @param $id
     */
    public function getByProjectId($id = 0){
        $recordObj = $this->ProjectLinkCreditDb->where(['project_id'=>$id])->first();
        $data      = is_object($recordObj) ? $recordObj->getAttributes() : $recordObj;
        if(empty($data))
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_DETAIL_GET_FAIL'), self::getFinalCode('getByProjectId'));
        return  $data;
    }


    /**
     * 批量获取项目债权关联数据
     *
     * @param array $ids
     * @return mixed
     * @throws \Exception
     */
    public function getByProjectIds($ids = []){
        $data = $this->ProjectLinkCreditDb->whereIn('project_id', $ids)->get()->toArray();
        //if(empty($data))
            //throw new \Exception(LangModel::getLang('ERROR_PROJECT_LINK_GET_FAIL'), self::getFinalCode('getByProjectIds'));
        return  $data;
    }


    /**
     * 获取核心接口项目详情
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function getCoreProjectDetail($id){
        try {

            $data = CoreApiProjectModel::getProjectDetail($id);

        }catch (\Exception $e){

            $data['id']             = $id;
            $data['msg']            = $e->getMessage();
            $data['code']           = $e->getCode();

            Log::error(__METHOD__.'curl-Error', $data);

            return [];
        }

        return $data;
    }

    /**
     * @param $projectId
     * @return array
     * @desc 获取项目的还款计划
     */
    public  function  getCoreProjectRefundPlan($projectId){

        $api  = Config::get('coreApi.moduleProject.refundPlan');

        $return = HttpQuery::corePost($api, ['project_id'=>$projectId]);

        if( $return['code'] == Logic::CODE_SUCCESS){

            return $return['data'];

        }

        return [];
    }

    /**
     * @param $projectId
     * @param $creditIds
     * @param $creditInfo
     * @return mixed
     * @throws \Exception
     * @desc 项目添加债权
     */
    public function createProjectLinkCredit($projectId, $creditIds, $creditInfo){

        \Log::info(__CLASS__.__METHOD__.'info',[$creditIds,$creditInfo]);

        $credit['project_id'] = $projectId;

        foreach($creditIds as $key => $id){

            $creditArr[] =  [
                'credit_id'     => $id,
                'type'          => empty($creditInfo[$id]["type"])?'':$creditInfo[$id]["type"],
                'credit_cash'   => $creditInfo[$id]['cash'],
            ];

            $credit['product_line'] = $creditInfo[$id]['product_line'];

            $credit['credit_info']  = json_encode($creditArr);

        }

        \Log::info(__CLASS__.__METHOD__.'error',[$credit]);

        $db = new ProjectLinkCreditDb();

        $return = $db->add($credit);

        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_LINK_CREDIT_CREATE_FAIL'), self::getFinalCode('createProjectLinkCredit'));

        return $return;

    }

    /**
     * @param $projectId
     * @param $creditIds
     * @param $creditInfo
     * @return mixed
     * @throws \Exception
     * @desc 更新项目关联债权信息
     */
    public function updateProjectLinkCredit($projectId, $creditIds, $creditInfo){

        \Log::info(__CLASS__.__METHOD__.'info',[$creditIds,$creditInfo]);

        $credit['project_id'] = $projectId;

        foreach($creditIds as $key => $id){

            $creditArr[] =  [
                'credit_id'     => $id,
                'type'          => $creditInfo[$id]["type"],
                'credit_cash'   => $creditInfo[$id]['cash'],
            ];

            $credit['product_line'] = $creditInfo[$id]['product_line'];

            $credit['credit_info']  = json_encode($creditArr);

        }

        \Log::info(__CLASS__.__METHOD__.'info',[$credit]);

        $db = new ProjectLinkCreditDb();

        $return = $db->edit($projectId, $credit);

        if(!$return){
            \Log::error(__CLASS__.__METHOD__.'error',[$return]);
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_LINK_CREDIT_EDIT_FAIL'), self::getFinalCode('updateProjectLinkCredit'));
        }


        return $return;

    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @desc 根据项目id查询关联债权信息
     */
    public function getCreditByProjectId($id = 0){

        $result = $this->ProjectLinkCreditDb->where(['project_id'=>$id])->get()->toArray();

        if(! $result){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_LINK_CREDIT'), self::getFinalCode('getCreditByProjectId'));

        }

        return json_decode($result[0]['credit_info'],true);

    }

    /**
     * 获取债权使用记录
     *
     * @param array $condition
     * @return mixed
     */
    public static function getListsByCredit($condition = [],$conditionOr = []){
        $result = ProjectLinkCreditDb::where($condition)->orWhere($conditionOr)->get()->toArray();

        return $result;
    }


    /**
     * 获取项目类型
     *
     * @param array $projectLinkCredit
     * @return bool|int
     */
    public static function getProjectWay($projectLinkCredit = []){
        if(empty($projectLinkCredit)) {
            return false;
        }
        $creditLinkInfo = isset($projectLinkCredit['credit_info']) ? $projectLinkCredit['credit_info'] : $projectLinkCredit;
        $creditLinkInfoArray = json_decode($creditLinkInfo, true);
        $project_way = false;
        if (count($creditLinkInfoArray) > 1) {//关联的债权大于1个 视为项目集
            $project_way = RegularCreditDb::TYPE_PROJECT_GROUP;
        } else {
            $creditLinkInfoArray = $creditLinkInfoArray[0];
            if (isset($creditLinkInfoArray['type'])) {
                $project_way  = $creditLinkInfoArray['type'];
            }
        }
        return $project_way;
    }

    /**
     * @param $projectTotalAmount
     * @param $creditArr
     * @return bool
     * @throws \Exception
     * @desc 债权与项目金额不匹配
     */
    public static function checkCreditTotal($projectTotalAmount, $creditArr){

        if(empty($creditArr)){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_TOTAL_AMOUNT_CREDIT'), self::getFinalCode('checkCreditTotal'));
        }

        $creditCash = 0;

        foreach($creditArr as $value){
            $creditCash += $value['cash'];
        }

        if($projectTotalAmount != $creditCash){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_TOTAL_AMOUNT_CREDIT'), self::getFinalCode('checkCreditTotal'));
        }

        return true;
    }


    /**
     * 获取老系统的projectWay【与新系统对应】
     * 后面数字为老系统编码 重构app端后 此方法可移除
     * @param $code
     */
    public static function getOldProjectWay($code = 10){
        $data = [
            RegularCreditDb::SOURCE_FACTORING         => 20,//保理
            RegularCreditDb::SOURCE_CREDIT_LOAN       => 10,//信贷
            RegularCreditDb::SOURCE_HOUSING_MORTGAGE  => 50,//房抵
            RegularCreditDb::TYPE_PROJECT_GROUP       => 60,//项目集
            RegularCreditDb::TYPE_NINE_CREDIT         => 30,//九省心

            RegularCreditDb::SOURCE_THIRD_CREDIT      => 30,//RegularCreditDb::SOURCE_THIRD_CREDIT,//第三方
        ];
        if(isset($data[$code]))
            return $data[$code];
        \Log::error('project_way错误 没有对应老系统状态码' . $code);
        return $code;
    }

    /**
     * @desc    通过债权获取项目信息
     *
     **/
    public static function getProjectByCredit($creditInfoArr=[]){
        $projectIdArr = [];
        if(is_array($creditInfoArr)){

            $projectIdArr =  ToolArray::arrayToIds( ProjectLinkCreditNewDb::getProjectByCredit($creditInfoArr), 'project_id' );

           // foreach ($creditInfoArr as $creditInfo){

           //     //$creditProject =  ProjectLinkCreditDb::getProjectByCredit($creditInfo);
           //     if($creditProject){
           //         $projectIdArr[] = $creditProject["project_id"];
           //     }
           // }
        }

        return $projectIdArr;

    }
}
