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

class ProjectLinkCreditNewDb extends JdyDb{

    protected $table   = 'project_link_credit_new';
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
     * @desc 通过在项目获取关联的债权ID
     * @param $projectId int
     * @return object
     */
    public function getByProjectId( $projectId )
    {
        $result = self::select('credit_id')
            ->where( 'project_id', $projectId )
            ->first();

        return $result;
    }

    /**
     * @desc 通过债权ID获取项目ID
     * @param $credit_id
     * @return array
     */
    public static function getProjectByCredit( $credit_id )
    {
        return self::select( 'project_id' )
            ->whereIn( 'credit_id', $credit_id )
            ->get()
            ->toArray();
    }


    /**
     * @param $projectId
     * @return mixed
     * @desc 删除项目关联信息
     */
    public function delByProjectId ( $projectId )
    {

        return $this->where('project_id', $projectId)
            ->delete();

    }

}
