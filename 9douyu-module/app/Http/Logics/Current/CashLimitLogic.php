<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/22
 * Time: 下午2:02
 */

namespace App\Http\Logics\Current;


use App\Http\Dbs\Current\CashLimitDb;
use App\Http\Logics\Logic;
use App\Http\Models\Current\CashLimitModel;
use App\Http\Models\User\UserModel;
use Log;

class CashLimitLogic extends Logic
{

    /**
     * @param $id
     * @return mixed
     * @desc 获取数据
     */
    public function getById( $id )
    {
        $return  =   CashLimitModel::getById($id);
        
        return $return;
    }

    /**
     * @return array|mixed
     * @desc 获取文章
     */
    public function getList($page, $size,$phone = ''){

        $db = new CashLimitDb();

        if( $phone ){

            $userInfo   =   UserModel::getCoreApiBaseUserInfo($phone);
        }

        $userId     =   isset($userInfo['id']) ? $userInfo['id'] : null;

        $result     =   $db -> getLimitList($userId,$page, $size);

        $userIds    =   array_column($result['list'],"user_id");

        $userInfo   =   $this->doGetUserInfoByIds($userIds);

        $result['list'] =   $this->doFormatUserInfo($result['list'],$userInfo);

        return $result;

    }

    /**
     * @param $userList
     * @param $userInfo
     * @return mixed
     * @desc 格式化数据
     */
    protected function doFormatUserInfo( $userList,$userInfo)
    {
        if( empty($userList) || empty($userInfo) ){

            return $userList;
        }

        foreach ($userList as $key  => $user ){

            $userList[$key]['info'] =   isset($userInfo[$user['user_id']]) ? $userInfo[$user['user_id']] : "";
        }

        return $userList ;
    }
    /**
     * @param $userIds
     * @return array
     * @desc 获取用户信息
     */
    public function doGetUserInfoByIds( $userIds )
    {
        if( empty($userIds) ) return [];

        $userInfo   =   UserModel::getCoreUserListByIds($userIds);

        if( empty($userInfo) ) return [];

        foreach ($userInfo as $key  => $user ){

            $returnUser[$user['id']]    =   $user;

        }
        return $returnUser;

    }
    /**
     * @param $data
     * @return array
     * @desc 添加用户获取转出数据
     */
    public function doCreate( $data )
    {

        self::beginTransaction();

        try{
            if($data['phone'] ){

                $user       =   UserModel::getCoreApiBaseUserInfo($data['phone']);

                $data['user_id']=   $user['id'];
            }
            $data       =   self::filterParams($data);

            CashLimitModel::doAdd($data);

            self::commit();

        }catch(\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }


    public function doEdit( $id,$cash,$inCash,$status,$manageId)
    {
        self::beginTransaction();

        try{

            $data   =   self::filterEditParams($cash,$inCash,$status,$manageId);
                
            CashLimitModel::doEdit($id,$data);

            self::commit();

        }catch(\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $params
     * @return array
     * @throws \Exception
     * @格式化数据
     */
    private static function filterParams( $params )
    {
        $attributes = [
            'user_id'   =>  $params['user_id'],
            'cash'      =>  isset($params['cash']) ? $params['cash'] : CashLimitDb::DEFAULT_OUT_CASH,
            'in_cash'   =>  isset($params['in_cash']) ? $params['in_cash'] : CashLimitDb::DEFAULT_IN_CASH,
            'admin_id'  =>  isset($params['admin_id']) ? $params['admin_id'] : "1",
            'status'    =>  isset($params['status']) ? $params['status'] : CashLimitDb::STATUS_ACTIVATE,
        ];

        return $attributes;
    }

    /**
     * @param $cash
     * @param $status
     * @param int $adminId
     * @return array
     */
    protected static function filterEditParams( $cash , $inCash,$status ,$adminId = 1)
    {
        return [
            'cash'      =>  isset($cash) ? $cash : CashLimitDb::DEFAULT_OUT_CASH,
            'in_cash'   =>  isset($inCash) ? $inCash : CashLimitDb::DEFAULT_IN_CASH,
            'status'    =>  isset($status) ? $status : CashLimitDb::STATUS_ACTIVATE,
            'admin_id'  =>  $adminId,
        ];
    }
}