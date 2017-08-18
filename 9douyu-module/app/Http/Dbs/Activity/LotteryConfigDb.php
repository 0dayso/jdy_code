<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/23
 * Time: 下午4:03
 */

namespace App\Http\Dbs\Activity;


use App\Http\Dbs\JdyDb;

class LotteryConfigDb extends JdyDb
{

    protected $table    =   'lottery_config';


    const

        LOTTERY_DEFAULT_GROUP   = 0,    //默认组

        LOTTERY_TYPE_ENVELOPE   = 1,    //红包
        LOTTERY_TYPE_TICKET     = 2,    //加息券
        LOTTERY_TYPE_ENTITY     = 3,    //实物奖品
        LOTTERY_TYPE_CURRENT    = 4,    //零钱计划加息券
        LOTTERY_TYPE_EMPTY      = 5;    //谢谢惠顾奖

    const
        LOTTERY_STATUS_SURE     =   10, //可以使用
        LOTTERY_STATUS_FAILED   =   20; //不可以使用


    /**
     * @param $id
     * @return array
     * @desc 通过id 获取
     */
    public function getById( $id )
    {
        $result     =   self::find($id);

        return self::dbToArray($result);
    }

    /**
     * @param $data
     * @return bool
     * @desc 添加奖品配置
     */
    public function doAdd( $data )
    {
        $this->name     =   $data['name'] ;

        $this->number   =   $data['number'];

        $this->rate     =   $data['rate'];

        $this->type     =   $data['type'];

        $this->foreign_id=  $data['foreign_id'];

        $this->order_num =  $data['order_num'];

        $this->group    =   $data['group'];

        $this->admin_id =   $data['admin_id'];

        return $this->save();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 编辑奖品
     */
    public function doEdit( $id, $data )
    {
        return $this->where('id',$id)->update($data);
    }

    /**
     * @param $page
     * @param $pageSize
     * @return mixed
     * @desc 返回列表
     */
    public function getList( $page, $pageSize)
    {
        $start  = $this->getLimitStart($page, $pageSize);

        $total  = $this->count('id');

        $list   = $this->orderBy('id', 'desc')
            ->skip($start)
            ->take($pageSize)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }

    /**
     * @param $group
     * @return mixed
     * @desc 根据奖品分组获取奖品信息
     */
    public function getByGroup( $group )
    {
        return self::where('group',$group)
                ->where('status',self::LOTTERY_STATUS_SURE)
                ->orderBy('order_num','asc')
                ->get()
                ->toArray();
    }

    /**
     * @return array
     * @desc 奖品类型
     */
    public function setLotteryType()
    {
        return [
            self::LOTTERY_TYPE_ENVELOPE   => '红包',
            self::LOTTERY_TYPE_TICKET     => '加息券',
            self::LOTTERY_TYPE_ENTITY     => '实物奖品',
            self::LOTTERY_TYPE_CURRENT    => '零钱计划加息券',
            self::LOTTERY_TYPE_EMPTY      => '谢谢参与奖',
        ];
    }

    /**
     * @return array
     * @desc 设置虚拟的奖品类型
     */
    public static function setVirtualType()
    {
        return [
            self::LOTTERY_TYPE_ENVELOPE,
            self::LOTTERY_TYPE_TICKET  ,
            self::LOTTERY_TYPE_CURRENT ,
        ];
    }
}