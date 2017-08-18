<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/9
 * Time: 上午11:25
 */

namespace App\Http\Models\Order;


use App\Http\Dbs\Order\CheckOrderRecordDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class CheckOrderRecordModel extends Model
{
    public static $codeArr            = [
        'doAddCheck'    =>  1,
        'doEditCheck'   =>  2,
        'doDeleteCheck' =>  3,

    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_ORDER_CHECK;

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 增加数据记录
     */
    public function doAdd( $data )
    {
        $db     =   new CheckOrderRecordDb();

        $return =   $db->doAdd($data);

        if( empty($return) ){

            throw new \Exception(LangModel::getLang('ERROR_CHECK_ORDER_ADD_FAILED'),self::getFinalCode('doAddCheck'));
        }

        return $return;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 更新数据记录
     */
    public function doUpdate( $id , $data )
    {
        $db     =   new CheckOrderRecordDb();

        $return =   $db->doUpdate($id,$data);

        if( empty($return) ){

            throw new \Exception(LangModel::getLang('ERROR_CHECK_ORDER_EDIT_FAILED'),self::getFinalCode('doEditCheck'));
        }

        return $return;
    }
    
}