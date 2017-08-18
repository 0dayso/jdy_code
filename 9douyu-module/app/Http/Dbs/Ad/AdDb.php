<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午4:47
 * Desc: 广告管理
 */

namespace App\Http\Dbs\Ad;

use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class AdDb extends JdyDb
{

    protected $table = "ad";

    const   STATUS_COMMON       = 1,    //正常
            STATUS_OFFLINE      = 2,    //下线

            SHOW_TYPE_PIC       = 1,    //图片
            SHOW_TYPE_WORD      = 2,    //文字

            JUMP_TO_URL         = 1,    //跳转链接
            JUMP_TO_PROJECT     = 2,    //跳转项目列表
            JUMP_TO_USERINFO    = 3,    //跳转个人中心
            JUMP_TO_BONUS       = 4,    //跳转优惠券

            END = 0;

    /**
     * @param $type
     * @param $page
     * @param $size
     * @return mixed
     * @desc 根据广告位,获取广告列表
     */
    public function getListByPositionId($positionId, $page, $size)
    {

        $total = $this->where('position_id', $positionId)->count();

        $offset = $this->getLimitStart($page, $size);

        $list = $this->where('position_id', $positionId)
            ->skip($offset)
            ->take($size)
            ->orderBy('end_at', 'desc')
            ->orderBy('id','desc')
            ->get()
            ->toArray();

        return ['total' => $total, 'list' => $list];

    }

    /**
     * @param $positionId
     * @param $page
     * @param $size
     * @return array
     * @desc 获取可用户的
     */
    public function getUseAbleListByPositionId($positionId)
    {

        $paramTime = ToolTime::dbNow();

        return $this->where('position_id', $positionId)
            ->where('publish_at', '<=', $paramTime)
            ->where('end_at', '>=', $paramTime)
            ->orderBy('group_sort')
            ->orderBy('sort','asc')
            ->orderBy('id','desc')
            ->get()
            ->toArray();

    }

    /**
     * @param $ids
     * @return mixed
     * @desc 通过ids,更新广告为下线状态
     */
    public function updateStatusOfflineByIds($ids)
    {

        return $this->whereIn('id', $ids)
            ->where('status', self::STATUS_COMMON)
            ->update(array('status' => self::STATUS_OFFLINE));

    }

    /**
     * @param $data
     * @return bool
     * @desc 添加数据
     */
    public function addInfo($data)
    {

        $this->title = $data['title'];

        $this->position_id = $data['position_id'];

        $this->manage_id = $data['manage_id'];

        $this->param = $data['param'];

        $this->publish_at = $data['publish_at'];

        $this->end_at = $data['end_at'];

        $this->sort   = $data['sort'];

        $this->group_sort   = $data['group_sort'];

        return $this->save();

    }


    /**
     * @param $positionIds
     * @return mixed
     * @desc 通过广告位ids获取广告数量
     */
    public function getNumByPositionIds($positionIds)
    {

        return $this->select(\DB::raw('count(id) as total, position_id'))
            ->whereIn('position_id', $positionIds)
            ->groupBy('position_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $id
     * @return mixed
     * @desc 删除广告
     */
    public function delInfo($id)
    {

        return $this->where('id', $id)
            ->delete();

    }

    /**
     * @param $positionId
     * @param $limit
     * @return mixed
     * @desc 当前广告位要显示的广告列表
     */
    public function getAdsByPositionId($positionId, $limit)
    {

        $paramTime = ToolTime::dbNow();

        return $this->where('position_id', $positionId)
            ->where('publish_at', '<=', $paramTime)
            ->where('end_at', '>=', $paramTime)
            ->orderBy('sort','asc')
            ->orderBy('id','desc')
            ->take($limit)
            ->get()
            ->toArray();

    }

    /**
     * @param $id
     * @return array
     * 根据主键ID获取广告信息
     */
    public function getById($id){

        $obj =  $this->where('id',$id)
            ->first();

        return $this->dbToArray($obj);
    }


    /**
     * @param $id
     * @param $data
     * @return mixed
     * 编辑广告
     */
    public function editById($id,$data){

        return $this->where('id',$id)
            ->update($data);
    }
    /**
     * @param $positionId
     * @param $page
     * @param $size
     * @return array
     * @desc 获取可用户的
     */
    public function getUseAbleListByAdId( $id=array() )
    {

        $paramTime = ToolTime::dbNow();

        return $this->whereIn('id', $id)
                    ->where('position_id' , AdPositionDb::P_LEAD_PAGE)
                    ->where('publish_at', '<=', $paramTime)
                    ->where('end_at', '>=', $paramTime)
                    ->orderBy( 'sort' , 'asc' )
                    ->get()
                    ->toArray();
    }

}
