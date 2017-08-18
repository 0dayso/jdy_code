<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/29
 * Time: 下午8:16
 * Desc: 项目扩展表
 */

namespace App\Http\Dbs\Project;

use App\Http\Dbs\JdyDb;

class ProjectExtendDb extends JdyDb
{

    const   TYPE_NEW_COMER  = 1, //新手标志
            TYPE_RECOMMEND_CHOICE  = 2, //优选推荐
            TYPE_INVEST_PK       =  3,  //投资PK

            STATUS_WAIT     = 100,  //待发布
            STATUS_COMMON   = 200,  //已发布

        END                = 0;


    protected $table = "project_extend";


    /**
     * @param $data
     * @return bool
     * @desc 执行添加
     */
    public function doAdd($data)
    {

        $this->project_id = $data['project_id'];

        $this->type = $data['type'];

        return $this->save();

    }

    /**
     * @desc 获取项目活动标示的列表
     * @return array
     */
    public function getActivitySignList( )
    {

        return $this->select('project_id', 'type')
            ->get()
            ->toArray();
    }

    /**
     * @param $type
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 获取列表信息
     */
    public function getListByType($type, $page=1, $size=2){

        $total = $this->where('type', $type)
            ->where('status', self::STATUS_COMMON)
            ->count();

        $offset = $this->getLimitStart($page, $size);

        $list = $this::where('type', $type)
            ->where('status', self::STATUS_COMMON)
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

        return [
            'total' => $total,
            'list'  => $list
        ];

    }

}
