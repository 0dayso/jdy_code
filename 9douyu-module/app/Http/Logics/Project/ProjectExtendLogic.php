<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/29
 * Time: 下午8:35
 * Desc: 项目相关扩展信息
 */

namespace App\Http\Logics\Project;

use App\Http\Dbs\Project\ProjectExtendDb;
use App\Http\Logics\Logic;
use App\Http\Models\Project\ProjectExtendModel;
use App\Http\Models\Project\ProjectModel;
use App\Tools\ToolArray;
use Log;

class ProjectExtendLogic extends Logic{

    /**
     * @param int $page
     * @param int $size
     * @return array
     * @desc 获取新手专享项目列表
     */
    public function getNewComerProjectList($page=1, $size=2)
    {

        $db = new ProjectExtendDb();

        $newComer = $db->getListByType($db::TYPE_NEW_COMER, $page, $size);

        if( isset($newComer['list']) && !empty($newComer['list']) ){

            $projectIds = ToolArray::arrayToIds($newComer['list'], 'project_id');

            $projectList = ProjectModel::getProjectListByIds($projectIds);

            return $projectList;

        }

        return [];

    }

    /**
     * @param $data
     * @desc 执行添加
     */
    public function doAdd($data){

        if( isset($data['newcomer']) && $data['newcomer'] ){

            $data['type'] = $data['newcomer'];
            //$data['type'] = ProjectExtendDb::TYPE_NEW_COMER;

        }

        $model = new ProjectExtendModel();

        try{

            $model->doAdd($data);

        }catch (\Exception $e){

            Log::Error('ProjectExtendAddError', [ 'data' => $data, 'msg' => $e->getMessage()]);

        }

    }

}
