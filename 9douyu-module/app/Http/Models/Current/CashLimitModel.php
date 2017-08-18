<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/22
 * Time: 上午11:14
 */

namespace App\Http\Models\Current;


use App\Http\Dbs\Current\CashLimitDb;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use EasyWeChat\Core\Exception;

class CashLimitModel extends Model
{

    public static $codeArr = [
        'addLimitCode'             => 1,
        'editLimitCode'            => 2,
        'deleteLimitCode'          => 3,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_CURRENT_LIMIT;

    /**
     * @param $id
     * @return mixed
     * @desc 通过ID获取
     */
    public static function getById( $id )
    {
        $db         =   new CashLimitDb();

        $return     =   $db->getById($id);

        if( isset($return['user_id']) && $return ){

            $user   =   UserModel::getCoreApiUserInfo($return['user_id']);

            $return['phone']=$user['phone'];
        }

        return $return;
    }

    public static function getLimitByUserId( $userId )
    {
        $db     =   new CashLimitDb();
        return $db->getByUserId($userId);
    }
    /**
     * @param $page
     * @param $size
     * @return array|mixed
     */
    public function getList( $page, $size)
    {
        $db     =   new CashLimitDb();

        $return =   $db->getLimitList( $page, $size);

        if(empty($return) ) return [];

        return $return;
    }

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 添加个人
     */
    public static function doAdd( $data )
    {
        $db     =   new CashLimitDb();
        
        $return =   $db->doAdd($data);
        
        if( empty($return) ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_CASH_LIMIT_ADD_FAILED'), self::getFinalCode('addLimitCode'));

        }

        return $return;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function doEdit( $id, $data )
    {
        $db     =   new CashLimitDb();

        $return =   $db->doEdit($id,$data);

        if( empty($return) ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_CASH_LIMIT_EDIT_FAILED'), self::getFinalCode('editLimitCode'));
        }

        return $return;
    }
}