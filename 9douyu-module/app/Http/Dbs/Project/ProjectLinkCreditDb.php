<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/23
 * Time: 上午11:25
 * 项目债权关联类
 */
namespace App\Http\Dbs\Project;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class ProjectLinkCreditDb extends JdyDb{

    protected $table   = 'project_link_credit';
    public $timestamps = false;

    /**
     * @param $data
     * @return mixed
     * @desc 批量添加数据
     */
    public function add($data){

        return \DB::table($this->table)->insert($data);

    }

    /**
     * @param $data
     * @param $projectId
     * @return mixed
     * @desc 修改数据
     */
    public function edit($projectId, $data){

        $data['updated_at'] = ToolTime::dbNow();

        return $this->where("project_id",$projectId)->update($data);

    }


    /**
     * @desc    模糊查询获取获取债权对应项目信息
     **/
    public static function getProjectByCredit($creditInfo){

        $result = self::select("project_id")
                ->where("credit_info", "like", '%'.$creditInfo.'%')
                ->first();

        return is_null($result) ? $result : $result->toArray();

    }

}