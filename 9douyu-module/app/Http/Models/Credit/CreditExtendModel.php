<?php
/**
 * Created by Vim_anywhere.
 * User: linguanghui
 * Date: 17/5/16
 * Time: 6:14PM
 * Desc: 债权扩展表后model层
 */

namespace App\Http\Models\Credit;

use App\Http\Dbs\Credit\CreditExtendDb;

class CreditExtendModel extends CreditModel
{
    public static $codeArr = [
        'doCreate' => 1,
        'doUpdate' => 2,
        'doAddBatchExtra' => 3,
        ];

    /**
     * @desc 添加扩展程序
     * @param $attributes array
     * @return array
     */
    public static  function doCreate( $attributes )
    {
        if( empty( $attributes ) )
            throw new \Exception( '添加的债权扩展内容为空', self::getFinalCode('doCreate') );

        $result = CreditExtendDb::add( $attributes );

        if( !$result )
            throw new \Exception( '添加债权扩展信息失败', self::getFinalCode('doCreate') );

        return $result;
    }


    /**
     * @desc 批量添加债权扩展数据
     * @param $extendInfo array
     * @return mixed
     */
    public static function doAddBatchExtra( $extendInfo )
    {
        if( empty( $extendInfo ) )
            throw new \Exception( '添加的债权扩展内容为空', self::getFinalCode('doAddBatchExtra') );

        $result  = CreditExtendDb::insert( $extendInfo );

        if( !$result )
            throw new \Exception( '添加债权扩展信息失败', self::getFinalCode('doAddBatchExtra') );

        return $result;
    }

    /**
     * @desc 编辑扩展债权信息
     * @param $creditId int
     * @param $attributes
     * @return bool
     */
    public static function doUpdateExtra( $creditId, $attributes )
    {
        if( empty( $creditId ) || empty( $attributes ) )
            throw new \Exception('债权Id或更新内容不能为空', self::getFinalCode('doUpdate'));

        $result = CreditExtendDb::doUpdateExtra( $creditId, $attributes );

        if(!$result)
            throw new \Exception('更新债权扩展信息失败  ', self::getFinalCode('doUpdateExtra'));

        return $result;
    }

    /**
     * @desc 获取债权扩展信息
     * @param $creditId int
     * @return array
     */
    public static function getExtraByCreditId( $creditId )
    {
        if( empty($creditId) )
            return [];

        $return = CreditExtendDb::getExtraByCreditId( $creditId );

        $extra  = isset($return['extra']) && $return['extra'] ? $return["extra"] :"";
        if($extra){
            return json_decode($return['extra'], true);
        }else{
            return "";
        }

    }
}
