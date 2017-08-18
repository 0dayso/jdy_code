<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/25
 * Time: 下午3:51
 */

namespace App\Http\Dbs\Activity;


use App\Http\Dbs\JdyDb;

class LotteryRecordDb extends JdyDb
{
    protected $table    =   'lottery_record'; //默认的数据表

    const

        LOTTERY_STATUS_SUCCESS      =   '20',   //审核通过
        LOTTERY_STATUS_NOT_AUDITED  =   '10',   //未审核
        LOTTERY_STATUS_FAILED       =   '30';   //失败

    public function getById( $id )
    {
        $result =   self::find($id);

        return $this->dbToArray($result);
    }
    /**
     * @param $attributes
     * @return mixed
     * @desc 记录中奖的记录
     */
    public static function doAdd($attributes)
    {
        $model = new static($attributes, array_keys($attributes));

        return $model->save();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 更新记录
     */
    public static function doUpdate( $id ,$data )
    {
        return self::where('id',$id)->Update($data);
    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc  后台列表
     */
    public function getRecordList($page , $size,$phone = '',$aid = '')
    {
        $startPage  =   $this->getLimitStart($page,$size);

        $obj        =   $this->select('*');
        //活动类型
        if( !empty($aid) ){
            $obj    =   $obj->where('activity_id',$aid);
        }
        //用户
        if( !empty($phone) ){
            $obj    =   $obj->where('phone',$phone);
        }
        $total      = $obj->count('id');

        $return     = $obj->orderBy('id', 'desc')
                            ->skip($startPage)
                            ->take($size)
                            ->get()
                            ->toArray();
        
        return ['list'  =>  $return,    'total' =>   $total];
    }

    /**
     * @param $connection
     * @return mixed
     * @desc 多条件查询
     */
    public function getRecordByConnection( $connection )
    {
        $obj        =   $this->select('*');

        //用户id
        if( !empty($connection['user_id']) ){
            $obj    =   $obj->where('user_id',$connection['user_id']);
        }

        //活动类型
        if( !empty($connection['activity_id']) ){
            $obj    =   $obj->where('activity_id',$connection['activity_id']);
        }

        //中奖的时间
        if( !empty($connection['start_time']) ){
            $obj    =   $obj->where('created_at' ,">=",$connection['start_time']);
        }
        if( !empty($connection['end_time']) ){
            $obj    =   $obj->where('created_at' ,"<=",$connection['end_time']);
        }

        //通过奖品id
        if( !empty($connection['prizes_id']) && !is_array($connection['prizes_id'])){
            $obj    =   $obj->where('prizes_id',$connection['prizes_id']);
        }elseif ( !empty($connection['prizes_id']) && is_array($connection['prizes_id']) ){
            $obj    =   $obj->whereIn('prizes_id',$connection['prizes_id']);
        }

        //通过状态
        if( !empty($connection['status']) ){
            $obj    =   $obj->where("status",$connection['status']);
        }

        if( !empty($connection['limit']) ){
            
            $obj    =   $obj->take($connection['limit']);
        }
        $result['list']     =   $obj->orderBy('id','desc')
                            ->get()
                            ->toArray();

        $result['lotteryNum'] =   $obj->count("*");

        return $result;
    }
        

}