<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/22
 * Time: 10:49Am
 * Desc: 借款人体系项目债权关系模型
 */

namespace App\Http\Models\Project;

use App\Http\Dbs\Credit\CreditAllDb;
use App\Http\Dbs\Current\CreditDb;
use App\Http\Dbs\Credit\CreditDb as RegularCreditDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;
use App\Http\Dbs\Project\ProjectLinkCreditNewDb;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Tools\ToolArray;
use Log;

use Config;

use App\Tools\ToolCurl;

use App\Http\Models\Common\CoreApi\SystemConfigModel;

use App\Http\Models\Common\CoreApi\ProjectModel as CoreApiProjectModel;

class ProjectLinkCreditNewModel extends Model
{

    public static $codeArr            = [
        'createProjectLinkCredit' => 1,
        'updateProjectCreditId'   => 2,
        'getByProjectId'          => 3,
        'createProjectLinkCreditIds'    => 4,
        'delByProjectId'          =>5,
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_PROJECT_LINK_CREDIT;

    /**
     * 项目债权关系数据实例
     * @var null
     */
    protected $ProjectLinkCreditNewDb = null;


    public function __construct(){
        $this->ProjectLinkCreditNewDb = new ProjectLinkCreditNewDb;
    }

    /**
     * @param $projectId
     * @param $creditId
     * @return mixed
     * @throws \Exception
     * @desc 创建债权和项目关联关系
     */
    public function createProjectLinkCredit( $projectId, $creditId )
    {
        if( empty( $projectId) || empty( $creditId )  )
        {
            throw new \Exception( '项目ID或债权ID不能为空', self::getFinalCode('createProjectLinkCredit'));
        }

        $creditProjectData = [
            'project_id'  => $projectId,
            'credit_id'   => $creditId,
            ];

        $result  = $this->ProjectLinkCreditNewDb->add( $creditProjectData );

        if( !$result )
        {
            throw new \Exception( '创建债权失败', self::getFinalCode('createProjectLinkCredit'));
        }

        return $result;
    }

    /**
     * @param $projectId
     * @return mixed
     * @throws \Exception
     * 删除项目债权的关联关系
     */
    public function delByProjectId( $projectId ){


        if( empty( $projectId) )
        {
            throw new \Exception( '项目ID不能为空', self::getFinalCode('delByProjectId'));
        }

        $result = $this->ProjectLinkCreditNewDb->delByProjectId( $projectId );

        if( !$result ){
            throw new \Exception( '删除项目债权关联关系失败', self::getFinalCode('delByProjectId'));
        }

        return $result;

    }

    /**
     * @desc 更新项目债权关联表的债权id
     * @param $projectId int 项目ID
     * @param $creditId  int 债权ID
     * @return bool
     * @throws \Exception
     */
    public function updateProjectCreditId( $projectId , $creditId )
    {
        if( empty( $projectId) || empty( $creditId )  )
        {
            throw new \Exception( '项目ID或债权ID不能为空', self::getFinalCode('updateProjectCreditId'));
        }

        $updateArr = [
            'credit_id' => $creditId,
            ];

        $result  = $this->ProjectLinkCreditNewDb->edit( $projectId, $updateArr );

        if( !$result )
        {
            throw new \Exception( '更新关联表债权id失败', self::getFinalCode('updateProjectCreditId'));
        }

        return $result;
    }

    /**
     * @desc 根据项目ID查询债权ID
     * @param $projectId
     * @return null|int
     */
    public function getByProjectId($projectId = 0){

        $creditId = $this->ProjectLinkCreditNewDb->getByProjectId( $projectId );

        if( !$creditId )
        {
            return '';

        }
        return  $creditId->credit_id;
    }

    /**
     * 获取债权使用记录
     *
     * @param int $creditId
     * @return mixed
     */
    public static function getListsByCredit($creditId){
        $result = ProjectLinkCreditNewDb::where('credit_id','=',$creditId)->get()->toArray();

        return $result;
    }

    /**
     * @param $projectId
     * @param $creditIds
     * @return mixed
     * @throws \Exception
     * @desc 创建债权和项目关联关系
     */
    public static function createProjectLinkCreditIds( $projectId, $creditIds )
    {
        if( empty( $projectId) || empty( $creditIds )  )
        {
            throw new \Exception( '项目ID或债权ID不能为空', self::getFinalCode('createProjectLinkCredit'));
        }

        if(is_array( $creditIds )){

            foreach($creditIds  as $creditId){

                $creditProjectData[] = [
                    'project_id'  => $projectId,
                    'credit_id'   => $creditId,
                ];
            }

        }else{

            $creditProjectData = [
                'project_id'  => $projectId,
                'credit_id'   => $creditIds,
            ];

        }

        $db = new ProjectLinkCreditNewDb();

        $result  = $db->add( $creditProjectData );

        if( !$result )
        {
            throw new \Exception( '创建债权关联关系失败', self::getFinalCode('createProjectLinkCredit'));
        }

        return $result;
    }

    /**
     * @desc 根据项目ID查询债权ID
     * @param $projectId
     * @return null|int
     */
    public function getCreditListByProjectId($projectId = 0){

        $return = [];

        $db = new ProjectLinkCreditNewDb();

        $result = $db->where('project_id', $projectId)
                    ->select('credit_id')
                    ->get()
                    ->toArray();

        if( !empty($result) ){

            $return = ToolArray::arrayToIds($result, 'credit_id');
            
        }

        return $return;
    }

    /**
     * 批量获取项目债权关联数据
     *
     * @param array $projectIds
     * @return array
     */
    public function getByProjectIds($projectIds = []){

        $db = new ProjectLinkCreditNewDb();

        $data = $db->whereIn('project_id', $projectIds)->get()->toArray();

        return  $data;
    }

    /**
     * 获取项目类型
     *
     * @param array $creditId
     * @return bool|int
     */
    public static function getProjectWay($creditId){

        if(empty($creditId)) {
            return false;
        }

        $db = new CreditAllDb();

        $project_way = $db -> getCreditTypeById($creditId);

        if( !empty($project_way) && $project_way['type'] == CreditAllDb::COMMON_CREDIT ){
            $return = $project_way['source'];
        }else{
            $return = $project_way['type'];
        }

        return $return;
    }
}
