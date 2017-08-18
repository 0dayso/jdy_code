<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/4/27
 * Time: 下午3:03
 */

namespace App\Http\Dbs;

class ProjectRefundPlanDb extends JdyDb{

    protected $table = 'project_refund_plan';

    /**
     * @param $projectId
     * @return mixed
     * @desc 通过项目id获取项目还款计划
     */
    public function getObjByProjectId($projectId)
    {

        return self::where('project_id',$projectId)
            ->orderBy('refund_time')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 获取项目还款计划
     */
    public function getPlanListByProjectIds($projectIds)
    {

        return self::whereIn('project_id', $projectIds)
            ->get()
            ->toArray();

    }


}