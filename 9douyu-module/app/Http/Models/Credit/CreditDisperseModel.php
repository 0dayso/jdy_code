<?php
/**
 * Created by Vim_anywhere.
 * User: linguanghui
 * Date: 17/3/21
 * Time: 4:14PM
 */

namespace App\Http\Models\Credit;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Http\Dbs\Credit\CreditDisperseDb;
use Log;
use Cache;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
/**
 * 新合规的分散匹配债权模型
 * Class CreditProjectGroupModel
 * @package App\Http\Models\Credit
 */

class CreditDisperseModel extends CreditModel{


    const
        ABLE_MATCH    = 200, //可匹配的
        UNABLED_MATCH = 300, //匹配的

        CREDIT_ABLE_AMOUNT_KEY = 'CREDIT_ABLE_AMOUNTS', //可匹配债权金额总和的缓存的KEY
    END =true;

    public static $codeArr            = [
        'doCreate' => 1,
        'findById' => 2,
        'doUpdate' => 3,
        'getAbleCreditDisperseList' =>4,
        'initCreditData'  => 5
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CREDIT_DISPERSE;

    /**
     * @desc 录入新的债权
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function doCreate($data){

        $return = CreditDisperseDb::add($data);

        if(!$return)
            throw new \Exception( LangModel::getLang('ERROR_CREDIT_CREATE_DISPERSE'), self::getFinalCode('doCreate') );

        //日志
        \App\Tools\AdminUser::userLog('credit_disperse',[$data, $return]);

        return $return;

    }

    /**
     * @desc 批量录入新的债权
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function doBatchCreate( $data )
    {

        $return = CreditDisperseDb::insert( $data );

        if(!$return)
            throw new \Exception( LangModel::getLang('ERROR_CREDIT_CREATE_DISPERSE'), self::getFinalCode('doBatchCreate') );

        //日志
        \App\Tools\AdminUser::userLog('batch_credit_disperse',[$data, $return]);

        return $return;

    }

    /**
     * @desc 分散债权列表
     * @param $condition array
     * @return $throws\Exception| array
     */
    public function getCreditDisperseListByStatus($status, $size=100){

        $creditDisperseDb  = new CreditDisperseDb();

        $creditList = $creditDisperseDb->getCreditListByStatus($status, $size);

        return $creditList;
    }

    /**
     * @desc 发布债权为可匹配状态
     * @param $ids array
     * @return $throws\Exception| array
     */
    public function doCreditOnline( $ids )
    {
        if( empty( $ids ))
        {
            throw new \Exception( '债权Id不能为空', self::getFinalCode( 'doCreditOnline' ) );
        }

        $creditDisperseDb = new CreditDisperseDb();

        $return = $creditDisperseDb->doCreditOnline( $ids );

        if( !$return )
        {
            throw new \Exception( '发布债权失败', self::getFinalCode( 'doCreditOnline' ) );
        }

        return $return;
    }

    /**
     * @desc 设置可匹配的债权金额总和缓存
     * @author linguanghui
     * @return bool
     */
    public static function setCreditAbleAmountCache( )
    {
        $cacheKey  = self::CREDIT_ABLE_AMOUNT_KEY;

        $expires   =  24*60;

        $creditDisperseDb = new CreditDisperseDb();

        $creditAbleAmounts = $creditDisperseDb->getCreditAbleAmount();

        if( ! $creditAbleAmounts )
        {
            throw new \Exception( '获取可用债权总额失败', self::getFinalCode( 'setCreditAbleAmountCache' ) );
        }

        return Cache::put( $cacheKey, $creditAbleAmounts->total_amount, $expires );

    }


    /**
     * @desc 获取可匹配债权金额总和的缓存
     * @author linguanghui
     * @return float
     */
    public static function getCreditAbleAmountCache( )
    {
        $cacheKey  = self::CREDIT_ABLE_AMOUNT_KEY;

        $creditAbleAmounts = Cache::get( $cacheKey );

        if( !empty( $creditAbleAmounts ) )
        {
            return $creditAbleAmounts;
        }else{

            self::setCreditAbleAmountCache();

            return Cache::get( $cacheKey );

        }
    }

    /**
     * @desc 获取可匹配的债权列表
     * @author linguanghui
     * @return array
     */
    public function getAbleCreditDisperseList(){

        $creditDisperseDb = new CreditDisperseDb();

        $return = $creditDisperseDb->getAbleCreditList();

        if(!$return)
            throw new \Exception('获取可用债权失败', self::getFinalCode('getAbleCreditDisperseList'));

        return $return;
    }

    /**
     * @desc 设置到期的债权为到期状态
     * @author linguanghui
     * @return Exception|mixed
     */
    public function setCreditExpireStatus( )
    {
        $creditDisperseDb = new CreditDisperseDb();

        $result = $creditDisperseDb->setCreditExpireStatus();

        if( !$result )
            throw new \Exception('设置到期债权为到期状态失败', self::getFinalCode('setCreditExpireStatus'));

        return true;
    }

    /**
     * @desc 债权数据初始化
     * @author lgh-dev
     * @return Exception|mixed
     */
    public function initCreditData(){

        $creditDisperseDb = new CreditDisperseDb();

        $result  = $creditDisperseDb->initCreditData();

        if(!$result)
            throw new \Exception('初始化债权失败', self::getFinalCode('initCreditData'));

        return $result;

    }

    /**
     * @desc 检测债权匹配的用户信息
     * @param $account array 投资用户信息
     * @return bool|Exception
     */
    public static function checkMatchAccount($account){

        if( empty($account) ){

            throw new \Exception('用户信息不能为空', self::getFinalCode('checkMatchAccount'));
        }
        return true;
    }

    /**
     * @desc 检测要匹配的信息
     * @param $creditArr array 可用债权
     * @return bool|Exception
     */
    public static function checkMatchCredit($creditArr){

        if( empty($creditArr) ){

            throw new \Exception('债权匹配数据不能为空', self::getFinalCode('checkMatchAccount'));
        }
        return true;
    }


    /**
     * @desc 更新匹配后的债权数据
     * @param $afterCredit array 参与匹配的债权列表
     * @return mixe|Exception
     */
    public static function updateCreditAfterMatch($afterCredit){
        if(empty($afterCredit)){
            throw new \Exception('匹配后的债权不能为空', self::getFinalCode('updateCreditAfterMatch'));
        }

        $ids = ToolArray::arrayToStr(ToolArray::arrayToIds($afterCredit));

        //格式化批量更新的sql
        $dbPrefix = env('DB_PREFIX');

        $sql = 'update '.$dbPrefix.'credit_disperse set usable_amount = case id ';

        foreach($afterCredit as $value1){

            $sql .= sprintf('when %d then %f ', $value1['id'], $value1['usable_amount']);
        }

        $sql .= 'end, status = case id ';

        foreach($afterCredit as $value2){

            $sql .= sprintf('when %d then %d ', $value2['id'], $value2['status']);
        }

        $sql .= 'end where id in ('.$ids.')';

        $return  =  \DB::statement($sql);

        if(!$return){
            Log::error('批量更新债权失败');
            throw new \Exception('批量更新匹配后的债权失败', self::getFinalCode('updateCreditAfterMatch'));
        }
        return $return;
    }

}
