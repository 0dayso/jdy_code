<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/6
 * Time: 下午8:35
 */


namespace App\Http\Dbs\Bonus;

use App\Http\Dbs\JdyDb;

use App\Http\Dbs\Project\ProjectDb;
use App\Tools\ToolTime;

/**
 * 红包\加息券 db
 * Class BonusDb
 * @package App\Http\Dbs
 */
class BonusDb extends JdyDb
{

    const
        TYPE_COUPON_INTEREST = 100, // 定期加息券
        TYPE_COUPON_CURRENT  = 200, // 零钱计划加息券
        TYPE_CASH            = 300, // 红包

        TYPE_USE_INVEST      = 100, // 投资使用

        //红包使用客户端类型限制
        CLIENT_TYPE_ALL      = 9,   // 所有类型
        CLIENT_TYPE_WEB      = 1,   // PC投资
        CLIENT_TYPE_WAP      = 2,   // WAP投资
        CLIENT_TYPE_APP      = 3,   // APP投资

        STATUS_UNPUBLIC      = 100, // 未发布
        STATUS_PUBLIC        = 200, // 已发布
        STATUS_LOCK          = 300, // 锁定

        ASSIGNMENT_ON        = 200, // 允许转让
        ASSIGNMENT_OFF       = 300, // 不允许转让

        EFFECT_NOW           = 100,   // 即时生效
        EFFECT_TIME          = 200,   // 按时间生效
    END = true;

    /**
     * @param $bonusId
     * @return mixed
     * @desc 通过优惠id 获取可用优惠券信息
     */
    public function getCanSendById($bonusId){

        $result =   $this->where('id', $bonusId)
            ->where('status', self::STATUS_PUBLIC)
            ->where('send_start_date','<=',ToolTime::dbNow())
//            ->where('send_end_date','>=',ToolTime::dbNow())
            ->first();
        return $this->dbToArray($result);

    }

    /**
     * @return mixed
     * @desc 获取可用优惠券例表
     */
    public function getCanSendList(){

        return $this->where('status', self::STATUS_PUBLIC)
            ->where('send_start_date','<=',ToolTime::dbNow())
            ->where('send_end_date','>=',ToolTime::dbNow())
            ->get()->toArray();
    }

    /**
     * @return mixed
     * @desc 获取可用优惠券例表
     */
    public function getCanSendListByType($type){

        return $this->where('status', self::STATUS_PUBLIC)
                    ->where('send_start_date','<=',ToolTime::dbNow())
                    ->where('send_end_date','>=',ToolTime::dbNow())
                    ->where('type',$type)
                    ->get()->toArray();
    }

    /**
     * 创建记录
     * @param array $attributes
     * @return static
     */
    public static function addRecord($attributes = []){
        $attributes = self::filterAttributes($attributes);
        $model = new static($attributes, array_keys($attributes));
        return $model->save();
    }

    /**
     * 获取指定ID所有字段的记录
     * @param $id
     * @return mixed
     */
    public static function findById($id)
    {
        return static::find($id);
    }

    /**
     * 属性保存白名单
     * @param $attributes
     */
    private static function filterAttributes($attributes){

        // 零钱计划加息券
        if($attributes['type'] == BonusDb::TYPE_COUPON_CURRENT){
            $attributes['money'] = 0;
            $attributes['project_type'] = '';
        }
        // 红包
        if($attributes['type'] == BonusDb::TYPE_CASH){
            $attributes['rate'] = '';
        }else{
            $attributes['money'] = 0;
        }
        return $attributes;
    }

    /**
     * 更新指定ID红包或加息券
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public static function doUpdate($id = 0, $data = []){
        $data = self::filterAttributes($data);
        return static::where('id', $id)->Update($data);
    }

    /**
     * 获取指定ID所有字段的记录
     * @param $id
     * @return mixed
     */
    public function getById($id){
        return $this->dbToArray(static::find($id));
    }

    /**
     * @desc 获取多个红包信息集合
     * @param $ids
     * @return mixed
     */
    public static function getByIds($ids){

        return self::whereIn('id',$ids)
            ->get()
            ->toArray();
    }

    /**
     * @param $id
     * @return mixed
     * 红包或加息券发布
     */
    public static function publishBonusById($id){

        $data = [
            'status' => self::STATUS_PUBLIC
        ];

        return self::where('id',$id)->update($data);
    }

    /**
     * @param $bonusType
     * @param $refundType
     * @return string
     * @desc 还款方式
     */
    public static function projectRefundType($bonusType){

        $data = [
            self::TYPE_COUPON_INTEREST => [
                ProjectDb::REFUND_TYPE_ONLY_INTEREST,
                ProjectDb::REFUND_TYPE_BASE_INTEREST,
            ]
        ];

        return empty($data[$bonusType])?'':$data[$bonusType];

    }

}