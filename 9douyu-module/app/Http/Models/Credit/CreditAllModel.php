<?php
/**
 * Created by Vim_anywhere.
 * User: linguanghui
 * Date: 17/5/16
 * Time: 4:14PM
 * Desc: 债权合并后model层
 */

namespace App\Http\Models\Credit;

use App\Http\Dbs\Credit\CreditAllDb;
use App\Http\Dbs\Credit\CreditExtendDb;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Log;
use App\Lang\LangModel;

class CreditAllModel extends CreditModel
{

    public static $codeArr = [
        'doCreate' => 1,
        'doUpdate' => 2,
        'doBatchCreate' => 3,
        'doUpdateCreditStatus' => 4,
        ];

    /**
     * @desc 债权合并后创建债权基础数据
     * @author linguanghui
     * @param $attributes
     * @return array|Exception
     */
    public static  function doCreate( $attributes )
    {
        if( empty( $attributes ) )
            throw new \Exception( '添加的债权内容为空', self::getFinalCode('doCreate') );


        $result = CreditAllDb::add( $attributes );

        if( !$result )
            throw new \Exception( '添加债权失败', self::getFinalCode('doCreate') );

        return $result;
    }

    /**
     * @desc 批量创建债权的数据
     * @param $creditInfo array
     * @return array
     */
    public static function doBatchCreate( $creditInfo ){

        if( empty(  $creditInfo ) )
            throw new \Exception( '债权数据为空', self::getFinalCode('doBatchCreate') );

        $return = CreditAllDb::insert( $creditInfo );

        if(!$return)
            throw new \Exception( '批量录入债权信息失败', self::getFinalCode('doBatchCreate') );
    }

    /**
     * @param $list
     * @return array
     * 格式化创建项目债权列表
     */
    public static function formatCreateProjectCreditList( $list )
    {

        $result = [];
        $nowDate = ToolTime::dbDate();
        $nowStrToTime = strtotime($nowDate);

        if (!empty($list) && is_array($list)) {

            foreach ($list as $key => $item) {

                if (!empty($item)) {
                    $strToTime = strtotime($item['expiration_date']);
                    //剩余天数
                    $item['remaining_day'] = null;
                    if ($nowStrToTime < $strToTime) {
                        $item['remaining_day'] = ($strToTime - $nowStrToTime) / 86400;
                    }
                    $result[] = $item;
                }

            }

        }

        return $result;
    }

    /**
     * @desc 编辑债权的基本数据
     * @param $id int
     * @param $attributes
     * @return bool|Exception
     */
    public static function doUpdate( $id, $attributes )
    {
        if( empty( $id ) || empty( $attributes ) )
            throw new \Exception('债权Id或更新内容不能为空', self::getFinalCode('doUpdate'));

        $result = CreditAllDb::doUpdate( $id, $attributes );

        if(!$result)
            throw new \Exception(LangModel::getLang('ERROR_CREDIT_UPDATE'), self::getFinalCode('doUpdate'));

        return $result;
    }

    /**
     * @desc 更新债权的状态[主要用于添加或编辑项目时使用]
     * @param $creitId array|int
     * @param $statusCode
     * @return bool|Exception
     */
    public static function updateCreditStatus( $creditId, $statusCode )
    {
        if( empty( $creditId ) || empty( $statusCode ) )
            throw new \Exception('债权Id或状态不能为空', self::getFinalCode('updateCreditStatus'));

        if( !is_array( $creditId ) )
            $creditId = [$creditId];

        $result = CreditAllDb::updateCreditStatus( $creditId, $statusCode );

        if( !$result )
            throw new \Exception('更新债权状态失败', self::getFinalCode('updateCreditStatus'));

        return $result;
    }

    /**
     * 获取所有数据
     * @param array $condition
     * @return mixed
     */
    public function getCreditLists($condition = [])
    {
        $size = $this->getAdminListPageSize();
        $lists = CreditAllDb::where($condition)->orderBy('created_at', 'desc')->paginate($size);
        //CreditViewAllDb::getSql();
        return $lists;
    }

    /**
     * @param $creditIds
     * @return mixed
     * 债权信息
     */
    public static function getCreditDetailById($creditIds)
    {

        if( !empty($creditIds) && !is_array($creditIds) ){
            $creditIds = [$creditIds];
        }

        //查询债权表
        $creditInfo = CreditAllDb::getCreditListByCreditIds( $creditIds );

        $creditExtendInfo = CreditExtendDb::getCreditDetailByCreditIds( $creditIds );

        $extendInfo = [];
        foreach($creditExtendInfo as $key=>$value){

            $extendInfo[$value['credit_id']] = json_decode( $value['extra'] ,true);

        }

        foreach($creditInfo as $key => $item){

            if(!empty($extendInfo[$item['id']])){

                $creditInfo[$key] = array_merge($item, $extendInfo[$item['id']]);

            }

        }

        return $creditInfo;

    }

    /**
     * @desc 中关村的接口获取债权信息
     * @parma $loanName
     * @return array
     */
    public static function getAllCreditByLoanName( $loanName )
    {
        $lists = CreditAllDb::where('loan_username', 'like', '%'.$loanName.'%')->get()->toArray();

        return $lists;
    }

    /**
     * @desc 获取指定条数的债权[中关村接口]
     * @param  $size
     * @return mixed
     */
    public static function getCreditBySize($size)
    {

        $lists = CreditAllDb::orderBy('created_at', 'desc')->take($size)->get()->toArray();

        return $lists;
    }
}
