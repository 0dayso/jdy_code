<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/18
 * Time: Pm 06:35
 * Desc: 债权扩展表的逻辑层处理
 */

namespace App\Http\Logics\Credit;

use App\Http\Models\Credit\CreditExtendModel;
use App\Http\Dbs\Credit\CreditExtendDb;
use Log;
use App\Tools\ToolArray;

class CreditExtendLogic extends CreditLogic
{

    /**
     * @desc 创建合并后的债权扩展数据
     * @param $data
     * @return array
     */
    public function doCreate( $data )
    {

        $attributes = [
            'credit_id'            => $data['credit_id'],
            'extra'                 => isset($data['extra']) ? $data['extra'] : '',
            ];

        try{
            //执行债权的创建
            $result = CreditExtendModel::doCreate( $attributes );

        }catch( \Exception $e ){
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError( $e->getMessage() );
        }

        return self::callSuccess( [$return] );
    }

    /**
     * @desc 更新债权的扩展信息
     * @param $creditId
     * @param $data
     * @return array
     */
    public function doUpdate( $data )
    {
        $creditId = $data['id'];
        unset( $data['id'] );
        $extra = json_encode( $data, true );
        try{

            $result = CreditExtendModel::doUpdateExtra( $creditId, $extra );

        }catch( \Exception $e ){

            $attributes['data']           = $data;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError( $e->getMessage() );
        }

        if( isset( $data['credit_list']) && !empty($data['credit_list'])){
            //添加第三方债权人信息数据组装
            $data = [
                'credit_id'   => $creditId,
                'credit_list' => $data['credit_list']
            ];

            \Event::fire(new \App\Events\Admin\Credit\CreditThirdDetailEvent(
                ['data'=> $data]
            ));

        }
        return self::callSuccess();

    }
    /**
     * @param $creditId | int
     * @return $result | array
     * @desc 获取债券的扩展信息
     */
    public static function getCreditExtendByCreditId( $creditId = 0 )
    {
        if( empty( $creditId ) ) {

            return  [] ;
        }

        $creditExtendDb =   new CreditExtendDb();

        return self::doFormatCreditExtendMessage( $creditExtendDb->getCreditExtendByCreditId( $creditId ) );
    }
    /**
     * @param $creditInfo  | array
     * @return creditExtednInfo | array
     * @desc 格式化债券的扩展信息
     */
    protected static function doFormatCreditExtendMessage( $creditExtendInfo = array() )
    {
        if( empty( $creditExtendInfo ) || empty($creditExtendInfo['extra']) ) {

            return [];
        }

        return json_decode($creditExtendInfo['extra'], true) ;
    }
}
