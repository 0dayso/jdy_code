<?php

/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/18
 * Time: 下午3:23
 */
namespace App\Http\Dbs\Micro;

use App\Http\Dbs\JdyDb;

/**
 * Class MicroJournalDb
 * @package App\Http\Dbs\Micro
 * @月刊的后台管理数据方式
 */
class MicroJournalDb extends JdyDb
{
    protected $table    =   'micro_journal';
    /**
     * 定义一些常用的常量
     */
    const
            RELEASE_STATUS_IS_OPEN     =   200,    //已发布
            RELEASE_STATUS_IS_CLOSED   =   100;    //未发布

    /**
     * @param $id
     * @return array
     * @desc 查询单条数据
     */
    public function getById( $id )
    {
        $result =   self::find( $id );

        return $this->dbToArray($result);
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
     * @param $data
     * @return bool
     * @desc 添加数据
     */
    public function doAdd( $data )
    {
        $this->date         =   $data['date'];

        $this->picture_id   =   $data['picture_id'];

        $this->params       =   $data['params'];

        $this->status       =   $data['status'];


        return $this->save();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 更新数据
     */
    public function doEdit( $id,$data )
    {
        return $this->where('id',$id)
                    ->update($data);
    }

    /**
     * @param $id
     * @return mixed
     * @desc 删除数据
     */
    public function doDelete( $id )
    {
        return $this->where('id',$id)->delete();
    }
    /**
     * @param $where
     * @return mixed
     * @desc 数据查询条件
     */
    public function getNewMicroInfo( $where )
    {
        $status     =   $where['status'];

        $size       =   $where['size'];

        $date       =   $where['date'];

        $obj        =   $this->select();

        //状态
        if( $status ){

            $obj    =   $obj->where('status',$status);
        }
        //读取数量
        if( $size ) {

            $obj    =   $obj->paginate($size);
        }
        //微刊刊号
        if( $date ){

            $obj    =   $obj->where('date',$date);
        }
        
        $return     =   $obj->orderBy('created_at','desc')
                            ->get()
                            ->toArray();

        return $return;

    }

    /**
     * @return array
     * @desc 获取最新一期的微刊,通过微刊的刊号
     */
    public function getLastMicroByDate()
    {
        $result =    $this->orderBy('date','desc')
                            ->take(1)
                            ->first();
        
        return $this->dbToArray($result);
    }
}