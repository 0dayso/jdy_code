<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/9
 * Time: 上午11:40
 */

namespace App\Http\Logics\Recharge;


use App\Http\Dbs\Order\CheckOrderRecordDb;
use App\Http\Logics\Logic;
use \App\Http\Models\User\UserModel;
use App\Http\Models\Order\CheckOrderRecordModel;
use App\Tools\ToolArray;
use Log;

class CheckOrderRecordLogic extends Logic
{

    /**
     * @param $page
     * @param $size
     * @param int $status
     * @return mixed
     * @desc 对账的列表
     */
    public function getList( $page , $size ,$status='')
    {
        $db         =   new CheckOrderRecordDb();

        $status     =   self::formatCheckStatus($status);
        
        $result     =    $db->getList($page,$size,$status);

        $userIds    =   array_column($result['list'],"user_id");

        $userInfo   =   $this->doGetUserInfoByIds($userIds);

        $result['list'] =   $this->doFormatUserInfo($result['list'],$userInfo);

        return $result;
    }
    
    /**
     * @param $status
     * @return array
     * @desc 格式化状态
     */
    protected static function formatCheckStatus( $status )
    {
        if( empty($status) ){

            return [
                CheckOrderRecordDb::CHECK_STATUS_SUCCESS,
                CheckOrderRecordDb::CHECK_STATUS_PENDING,
            ];
        }

        if(is_array($status) ){

            return $status;
        }

        return [$status];
    }
    /**
     * @param $userIds
     * @return array
     * @desc 获取用户信息
     */
    public function doGetUserInfoByIds( $userIds )
    {
        if( empty($userIds) ) return [];

        $returnUser =   [];

        $userInfo   =   UserModel::getCoreUserListByIds($userIds);

        if( empty($userInfo) ) return [];

        foreach ($userInfo as $key  => $user ){

            $returnUser[$user['id']]    =   $user;

        }

        return $returnUser;

    }

    /**
     * @param $userList
     * @param $userInfo
     * @return mixed
     * @desc 格式化数据
     */
    protected function doFormatUserInfo( $checkList,$userInfo)
    {
        if( empty($checkList) || empty($userInfo) ){

            return $checkList;
        }

        foreach ($checkList as $key  => $user ){

            $checkList[$key]['info'] =   isset($userInfo[$user['user_id']]) ? $userInfo[$user['user_id']] : "";
        }

        return $checkList ;
    }
    /**
     * @return mixed
     * @desc 未处理的订单数据
     */
    public function getNotCheckRecordTotal()
    {
        $db     =   new CheckOrderRecordDb();

        return $db->getNotCheckRecordTotal();
    }

    /**
     * @param $data
     * @return array
     * @desc 记录数据
     */
    public function doAdd( $data )
    {
        self::beginTransaction();

        try{

            $data       =   self::filterAddParams($data);

            $model      =   new CheckOrderRecordModel();

            $model->doAdd($data);

            self::commit();

        }catch (\Exception $e) {

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $id
     * @param $data
     * @return array
     * @desc 更新数据
     */
    public function doUpdate( $id , $data )
    {
        $model          =   new CheckOrderRecordModel();

        self::beginTransaction();

        try{
            $data       =   self::filterParams($data);

            $model->doUpdate($id,$data);

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
     * @desc 零钱计划订单id
     */
    public function getCheckOrderRecordByParam( $params)
    {
        if( empty($params['order_id'])){

            return [];
        }
        //$params     =   self::filterParams($params,true);
        
        $db         =   new CheckOrderRecordDb();
        
        $checkList  =   $db->getCheckOrderRecordByParam($params);

        $checkList  =   ToolArray::arrayToKey($checkList,'order_id');

        return self::doFormatOrderCheckStatus($params['order_id'],$checkList);
    }

    /**
     * @param $orderIds
     * @param $orderList
     * @return array
     * @desc 返回对账处理的结果
     */
    protected static function doFormatOrderCheckStatus( $orderIds,$orderList )
    {
        $returnList =   [];

        foreach ( $orderIds as $key=> $orderId ){

            if( isset($orderList[$orderId]) && $orderList[$orderId]){

                $returnList[$orderId]   =  CheckOrderRecordDb::CHECK_ORDER_FAILED;

            }else{

                $returnList[$orderId]   =  CheckOrderRecordDb::CHECK_ORDER_SUCCESS;
            }
        }

        return $returnList;
    }
    /**
     * @param $params
     * @return array
     * @desc 格式化数据
     */
    protected static function filterParams( $params ,$type=false)
    {
        $attributes =   [
            'is_check'      =>  isset($params['is_check']) ? $params['is_check'] : CheckOrderRecordDb::CHECK_STATUS_PENDING,
            'admin_id'      =>  isset($params['admin_id']) ? $params['admin_id'] : "0",
        ];
        //订单号
        if( isset($params['order_id']) && !empty($params['order_id'])){

            $attributes['order_id'] =   $params['order_id'];
        }
        //渠道号
        if( isset($params['pay_channel']) && !empty($params['pay_channel']) ){

            $attributes['pay_channel']  =   $params['pay_channel'];
        }
        //金额
        if( isset($params['cash']) && !empty($params['cash']) ){

            $attributes['cash']     =   $params['cash'];
        }

        //异常数据说明
        if( isset($params['note']) && !empty($params['note']) ){

            $attributes['note']     =   $params['note'];
        }
        //处理说明
        if( isset($params['tackle_note']) ){

            $attributes['tackle_note']= $params['tackle_note'];
        }

        //订单的用户id
        if( isset($params['user_id']) ){

            $attributes['user_id']  =   $params['user_id'];
        }

//        if(isset($attributes['order_id']) && !is_array($attributes['order_id']) && $type ==true){
//
//            $attributes['order_id'] =   [$attributes['order_id']];
//        }

        return $attributes;
    }

    /**
     * @param array $params
     * @return array
     * @desc 格式化对账订单中的数据
     */
    protected static function filterAddParams($params = array())
    {

        $attributes =   [
            'order_id'      =>  isset($params['order_id']) ? $params['order_id'] : "",
            'pay_channel'   =>  isset($params['pay_channel']) ? $params['pay_channel']: '',
            'cash'          =>  isset($params['cash']) ?$params['cash'] :  '0.00',
            'is_check'      =>  isset($params['is_check']) ? $params['is_check'] : CheckOrderRecordDb::CHECK_STATUS_PENDING,
            'note'          =>  isset($params['note']) ? $params['note'] : '',
            'admin_id'      =>  isset($params['admin_id']) ? $params['admin_id'] : "0",
            'user_id'       =>  isset($params['user_id']) ? $params['user_id'] : '0',
        ];

        return $attributes;
    }
}