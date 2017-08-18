<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/23
 * Time: 下午4:36
 */

namespace App\Http\Logics\Activity;


use App\Http\Dbs\Activity\LotteryConfigDb;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Activity\LotteryConfigModel;
use App\Tools\AdminUser;
use Log;

class LotteryConfigLogic extends Logic
{

    /**
     * @param $id
     * @return array
     * @desc 获取单个信息
     */
    public function getById( $id )
    {
        $db     =   new LotteryConfigDb();

        return $db->getById($id);
    }

    /**
     * @param $page
     * @param $pageSize
     * @return mixed
     * @desc 奖品列表
     */
    public function getConfigList( $page,$pageSize)
    {
        $db     =   new LotteryConfigDb();

        return $db->getList($page,$pageSize);
    }

    /**
     * @return array
     * @desc  奖品类型
     */
    public function getLotteryType()
    {
        $db     =   new LotteryConfigDb();

        return $db->setLotteryType();
    }

    public function getBonusList($type = '')
    {
        if( empty($type) ){

            $type   =   LotteryConfigDb::LOTTERY_TYPE_ENVELOPE;
        }
        
        $bonusType  =   self::fitterBonusType($type);
        
        if ( !empty($bonusType) ){
            
            $bonusLogic =   new BonusLogic();

            $bonusList  =   $bonusLogic->getCanSendListByType($bonusType);

            return $bonusList;
        }

        return [];

    }
    /**
     * @param $data
     * @return array
     * @desc 添加奖品设置
     */
    public function doAdd( $data )
    {

        $insertParam   =   $this->doFormatInsertParam($data);

        try{
            self::beginTransaction();

            $model     =   new LotteryConfigModel();

            $model->doAdd($insertParam);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess();
    }

    public function doEdit($id, $data)
    {
        $updateParam   =   $this->doFormatInsertParam($data);

        try{
            self::beginTransaction();

            $model     =   new LotteryConfigModel();

            $model->doEdit($id , $updateParam);

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess();
    }
    /**
     * @param $params
     * @return array
     * @desc  格式化添加的数据
     */
    protected function doFormatInsertParam( $params )
    {
        $formatParam    =   [
            'name'      =>  isset($params['name']) ? (string)$params['name'] : '',
            'number'    =>  isset($params['number']) ? $params['number'] : 0,
            'rate'      =>  isset($params['rate']) ? (int)$params['rate'] : 0,
            'type'      =>  isset($params['type']) ? (int)$params['type']  : LotteryConfigDb::LOTTERY_TYPE_ENTITY,
            'order_num' =>  isset($params['order_num']) ? $params['order_num'] : "0",
            'group'     =>  isset($params['group']) ? $params['group'] : LotteryConfigDb::LOTTERY_DEFAULT_GROUP,
            'admin_id'  =>  AdminUser::getAdminUserId(),
            'status'    =>  isset($params['status']) ? $params['status'] : LotteryConfigDb::LOTTERY_STATUS_SURE,
        ];

        if( $params['type'] !=LotteryConfigDb::LOTTERY_TYPE_ENTITY){

            $formatParam['foreign_id'] = $params['ticket_id'];

        }else{

            $formatParam['foreign_id'] = 0;
        }

        return $formatParam;
    }

    /**
     * @param $groupId
     * @return mixed
     * 获取奖品组
     */
    public static function getLotteryByGroup( $groupId )
    {
        $db     =   new LotteryConfigDb();

        return   $db->getByGroup($groupId);
    }

    /**
     * @param int $groupId
     * @return array
     * @desc 通过id获取中奖信息
     */
    public static function getLotteryListByGroupId( $groupId = 0)
    {
        $return     =   self::getLotteryByGroup($groupId);

        if( $groupId == 0 || empty($return) ){

            return self::callError('分组信息不存在');
        }

        return  self::callSuccess($return);
    }
    /**
     * @param $lotteryList
     * @return array|string
     * @desc  格式化数据
     */
    public static function doGetFormatLottery( $lotteryList )
    {
        if( empty($lotteryList) ) return [];

        $returnList     =   '';

        foreach ( $lotteryList as $key  =>  $lottery ) {

            $returnList[$lottery['id']] =   $lottery;

        }

        return $returnList;
    }
    /**
     * @param $type
     * @return int|string
     * @desc 格式化类型
     */
    protected static function fitterBonusType( $type )
    {
        switch ($type){

            case LotteryConfigDb::LOTTERY_TYPE_ENVELOPE:

                $bonusType  =   BonusDb::TYPE_CASH;   //红包

                break;
            case LotteryConfigDb::LOTTERY_TYPE_CURRENT:  //零钱计划加息券

                $bonusType  =   BonusDb::TYPE_COUPON_CURRENT;

                break;
            case LotteryConfigDb::LOTTERY_TYPE_TICKET:  //加息券

                $bonusType  =   BonusDb::TYPE_COUPON_INTEREST;

                break;
            default:

                $bonusType  =   "";

                break;

        }
        return $bonusType;
    }

}