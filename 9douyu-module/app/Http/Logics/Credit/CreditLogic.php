<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Credit;

use App\Http\Logics\Logic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Tools\ToolTime;
use Log;
use Cache;
use Redis;
use App\Http\Models\Project\ProjectModel;
use App\Http\Dbs\Credit\CreditDb;

/**
 * 债权逻辑
 * Class CreditLogic
 * @package App\Http\Logics\Credit
 */
abstract class CreditLogic extends Logic
{

    const
        PER_WAN     = 10000, // 万元
        PER_HUNDRED = 100,   // 百元

        BASE_NUM   = 4,     //分散项目的基础分散个数
        BASE_AMOUNT = 8000, //分散项目的基础金额

        CUT_HUNDRED = 100, //分散为100元一份
        CUT_ONE     = 1,  //分散为1元一份

        KEY_HUNDRED_CREDIT = 'CUT_CREDIT_HUNDRED', //分割债权100元一份的缓存key
        KEY_ONE_CREDIT     = 'CUT_CREDIT_ONE', //分割债权1元一份的缓存key

    END = true;

    /**
     * 获取要存储的金额【元】
     * @param int $loanAmount
     * @return int
     */
    public static function getSaveAmounts($loanAmount = 0){
        if(empty($loanAmount)){
            return $loanAmount;
        }
        return $loanAmount * self::PER_WAN;
    }

    /**
     * 获取来源
     * @return array
     */
    public static function getSource(){
        return CreditModel::getSource();
    }

    /**
     * 获取类型
     * @return array
     */
    public static function getType(){
        return CreditModel::getType();
    }

    /**
     * 获取产品线
     * @return array
     */
    public static function getProductLine(){
        return ProjectModel::getProductLine();
    }

    /**
     * 获取还款类型
     * @return array
     */
    public static function getRefundType(){
        return CreditModel::refundType();
    }

    /**
     * 获取还款类型
     * @return array
     */
    public static function getRefundTypeForOperation(){
        return CreditModel::refundTypeForOperation();
    }

    /**
     * 获取星级
     * @return array
     */
    public static function getStar(){
        return CreditModel::getStar();
    }

    /**
     * 获取回款类型对应期限【单位】
     * @return array
     */
    public static function getLoanDeadlineDayOrMonth(){
        return CreditModel::loanDeadlineDayOrMonth();
    }

    /**
     * 获取性别数组
     * @return array
     */
    public static function getSexData(){
        return CreditModel::getOwnerSexData();
    }


    /**
     * 获取 riskcale 数组
     * @return array
     */
    public static function getRiskcalcLevel(){
        return CreditModel::getRiskcalcLevel();
    }

    /**
     * 获取 已方债权人信息 数组
     * @return array
     */
    public static function getCreditor(){
        return CreditModel::getCreditor();
    }

    /**
     * @desc 获取债权来源类型
     * @param $sourceNote string
     * @return int
     */
    public static function getSouceByText( $sourceNote )
    {
        $source = 0;

        $sourceList = CreditLogic::getSource();

        foreach( $sourceList as $key => $name )
        {
            if( $sourceNote == $name )
                $source  =  $key;
        }

        return $source;
    }

    /**
     * @desc 获取债权的类型
     * @param $typeNote string
     * @return int
     */
    public static function getTypeByText( $typeNote )
    {
        $type = 0;

        $typeList = CreditLogic::getType();

        foreach( $typeList as $key => $name )
        {
            if( $typeNote == $name )
                $type = $key;
        }

        return $type;
    }

    /**
     * @desc 获取债权标签
     * @param $tagNote string
     * @return int
     */
    public static function getCreditTagByText( $tagNote )
    {
        $tag = 0;

        $tagList = CreditLogic::getProductLine();

        foreach( $tagList as $key => $name )
        {
            if( $tagNote == $name )
                $tag = $key;
        }
        return  $tag;
    }


    /**
     * @desc 定时脚本，项目完结债权回收
     */
    public function creditRecovery()
    {

        $time = ToolTime::dbDate();

        //获取今日已经完结的项目
        $projectIds = \App\Http\Models\Common\CoreApi\ProjectModel::getFinishedProjectIds($time);

        if( !empty($projectIds) ){

            $projectLogic = new ProjectLogic();

            $log = '';

            foreach( $projectIds as $projectId ){

                try{

                    $projectLogic->recoverCreditInfo($projectId);

                }catch (\Exception $e){

                    $log[] = [
                        'code'          => $e->getCode(),
                        'msg'           => $e->getMessage(),
                        'project_id'    => $projectId
                    ];

                }

            }

            if( $log ){

                Log::Error('creditRecoveryError', $log);

            }

        }




    }


    /**
     * 通过条件获取所有列表
     *
     * @param array $condition
     * @return mixed
     */
    public static function geAllLists($condition = []){
        $creditModel = new CreditModel;
        return $creditModel->getAllList($condition);
    }


    /**
     * 获取符合条件的债权Id集合
     *
     * @param array $condition
     * @return mixed
     */
    public static function geAllCreditIds($condition = []){
        $creditModel = new CreditModel;
        return $creditModel->geAllCreditIds($condition);
    }

    /**
     * 获取债权项目关联 code
     *
     * @param $item
     */
    public static function getProjectLinksCode($item){
        if($item['type'] == CreditDb::TYPE_BASE){
            $code = $item['source'];
        }else{
            $code = $item['type'];
        }
        return $code;
    }

    /**
     * 获取债权使用记录
     *
     * @param $items
     * @return mixed
     */
    public static function getProjectLinks($items){
        if($items){
            foreach($items as $k => $creditObj){
                $creditObj->project_link_code = CreditLogic::getProjectLinksCode($creditObj);
                $like                         = '"credit_id":'. $creditObj->credit_id . ',"type":' . $creditObj->project_link_code .',';
                $likeOr                       = '"credit_id":"'. $creditObj->credit_id . '","type":"' . $creditObj->project_link_code .'",';
                $condition                    = [];
                $condition []                 = ['credit_info', 'like', '%'.$like.'%'];
                $conditionOr                  = [];
                $conditionOr []               = ['credit_info', 'like', '%'.$likeOr.'%'];

                $projectLink                  = ProjectLinkCreditModel::getListsByCredit($condition, $conditionOr);
                $creditObj->projectLinks      = $projectLink;
            }
        }

        return $items;
    }


    /*##########################[分散投资债权匹配操作]####################################*/

    /**
     * @desc  执行分散投资债权的操作
     * @param $amount
     * @param $creditArr
     * @return array
     */
    public static function doDiversificationInvest($amount, &$creditArr){
        if(empty($creditArr)){
            return [];
        }
        $investResult = [];
        $success = false;

        //获取分散项目的个数
        $targetProjectNum  = self::getTargetProjectNum($amount);
        //分散的平均金额
        $avgAmount = floor( $amount/ $targetProjectNum);

        foreach($creditArr as $key => $value) {

            if (empty($value)) {
                unset($creditArr[$key]);
                continue;
            }

            if ($avgAmount >= $value['usable_amount']) {
                $investAmount = $value['usable_amount'];
            } else {
                $investAmount = $avgAmount;
            }
            $creditArr[$key]['invest'] = 1;
            $investResult[$key]['id']         = $creditArr[$key]['id'];
            if ($amount - $investAmount >= 0) {
                $creditArr[$key]['usable_amount'] -= $investAmount;

                $investResult[$key]['invest_amount'] = $investAmount;
                $amount -= $investAmount;

            } else {//投资超出
                $success = true;
                $creditArr[$key]['usable_amount'] -= $amount;

                $investResult[$key]['invest_amount'] = $amount;
                $amount = 0;
                break;
            }
            if(empty($creditArr[$key]['usable_amount'])){ $creditArr[$key]['status'] = CreditDb::STATUS_CODE_ACTIVE; }

            if (empty($creditArr[$key])) {
                unset($creditArr[$key]);
            }

            if (empty($amount)) {
                $success = true;
                break;
            }
        }
        //每个项目投资金额全部使用
        if(!$success){
            $investResult = self::doLeftAmountDiverInvest($amount, $creditArr,$investResult);
        }

        return $investResult;
    }

    /**
     * @desc 按照金额计算分散投资项目的个数
     * @param $amount
     * @return int
     */
    public static function getTargetProjectNum($amount){

        $baseNum = self::BASE_NUM;

        $baseAmount = self::BASE_AMOUNT;

        if($amount <= $baseAmount){
            return $baseNum;
        }else{
            return $baseNum + floor($amount / $baseAmount);
        }

    }

    /**
     * @desc 将剩余投资分散投资完成
     * @param $amount
     * @param $creditArr
     * @param $investResult
     * @return array
     */
    public static function doLeftAmountDiverInvest($amount, &$creditArr, $investResult){

        foreach($creditArr as $key => $value){
            if(empty($value)){
                unset($creditArr[$key]);
                continue;
            }

            if(!isset($investResult[$key])){
                $investResult[$key] = 0;
            }
            $creditArr[$key]['invest'] = 1;

            if($amount >= $value['usable_amount']){
                $creditArr[$key]['usable_amount'] -= $value['usable_amount'];

                $investResult[$key]['invest_amount'] += $value['usable_amount'];

                $amount -= $value['usable_amount'];
            }else{
                $creditArr[$key]['usable_amount'] -= $amount;
                $investResult[$key]['invest_amount'] += $amount;
                break;
            }

            if(empty($creditArr[$key]['usable_amount'])){ $creditArr[$key]['status'] = CreditDb::STATUS_CODE_ACTIVE; }

            if(empty($creditArr[$key])){
                unset($creditArr[$key]);
            }
            if (empty($amount)) {
                break;
            }
        }
        return $investResult;
    }


    /** ############################ [债权匹配新方案匹配处理-Start] ##########################################**/

    /**
     * @desc 分割可用债权为100 and 1 元的债权数据
     * @author linguanghui
     * @param $ableCreditData  array 可用债权列表数据
     * @return array
     */
    public static function doCutAbleCreditData( $ableCreditData )
    {
        $cutCreditResult  =  [];

        if( empty( $ableCreditData ) )

            return [];

        foreach( $ableCreditData as $key => $value ){

            $hundreds  = floor( $value['usable_amount'] / self::CUT_HUNDRED ); //债权百元分散的份数

            $ones      = floor( ( $value['usable_amount'] % self::CUT_HUNDRED ) / self::CUT_ONE ); //债权1元分散分数

            //组装100的数据

            if( $hundreds > 0 )
            {
                for( $m = 0; $m < $hundreds; $m++ )
                {

                    $cutCreditResult['hundred'][] = [
                        'id'  => $value['id'],
                        'usable_amount' => self::CUT_HUNDRED,
                        ];
                }
            }

            //组装1元的数据
            for ( $n = 0; $n < $ones ; $n++ )
            {
                $cutCreditResult['one'][] = [
                    'id'  => $value['id'],
                    'usable_amount' => self::CUT_ONE,

                ];
            }

        }

        //数组打乱

        if( !empty( $cutCreditResult['hundred'] ) )
        {
            shuffle( $cutCreditResult['hundred'] );
        }

        if( !empty( $cutCreditResult['one'] ) )
        {
            shuffle( $cutCreditResult['one'] );
        }

        Log::info('Cut-Credit-Result:债权拆分结果', $cutCreditResult );

        return $cutCreditResult;
    }


    /**
     * @desc 分割的债权数据存储到redis的list中
     * @author linguanghu
     * @param $cutCreditResult array 分割的数据内容
     * @return array
     */
    public static  function pushTheCutCreditCache( $cutCreditResult, $isDelKey = false )
    {
        if( empty( $cutCreditResult ) )
            return false;

        $hundred  = self::KEY_HUNDRED_CREDIT;

        $one  = self::KEY_ONE_CREDIT;

        //redis初始化
        $redis = new Redis();

        $redis->connect( env('REDIS_HOST'), env('REDIS_PORT') );

        $redis->auth( env('REDIS_PASSWORD') );

        Log::info( 'Disperse-Cut_data:分割债权的数据存入缓存队列', $cutCreditResult );

        //判断是否删除以缓存key
        if( $isDelKey ){

            if($redis->exists( $hundred ))
                $redis->del( $hundred );

            if( $redis->exists( $one ) )
                $redis->del( $one );

        }

        $pushHundredData = $pushOneData = [];
        //数据推入redis list
        if( !empty( $cutCreditResult['hundred'] ) )
        {

            foreach( $cutCreditResult['hundred'] as $value )
            {
                $redis->lPush( $hundred, json_encode( $value ) );
            }

        }

        if( !empty( $cutCreditResult['one'] ) )
        {

            foreach( $cutCreditResult['one'] as $value )
            {
                $redis->lPush( $one, json_encode( $value ) );
            }

        }


        return true;
    }


    /**
     * @desc 获取分割债权缓存数据
     * @author linguanghui
     * @param $key string
     * @return array
     */
    public static function getCutCreditCache( $key ='' )
    {

        if( empty( $key ) )
            return '';

        //redis初始化
        $redis = new Redis();

        $redis->connect( env('REDIS_HOST'), env('REDIS_PORT') );

        $redis->auth( env('REDIS_PASSWORD') );

        if( $redis->exists( $key ))
        {
            return $redis->lRange( $key, 0, -1 );
        }

        return '';
    }

    /**
     * @desc 执行新的债权分割后的摘取匹配结果
     * @author linguanghi
     * @param $amounts float 用户金额
     * @return array
     */
    public static function doCutCreditMatch( $amounts )
    {

        if( empty( $amounts ) )

            return [];

        $investResult = $investHundredResult = $investOneResult =  [];


        //处理百元的数据
        if( floor( $amounts / self::CUT_HUNDRED ) > 0 )
        {
            $hundredAmount  = self::CUT_HUNDRED * floor( $amounts / self::CUT_HUNDRED );

            $investHundredResult  = self::doHundredCacheData( $hundredAmount );

        }

        //处理1元的数据
        if( ( $amounts % self::CUT_HUNDRED ) >0 )
        {
            $oneAmount  = $amounts % self::CUT_HUNDRED;

            $investOneResult  = self::doOneCacheData( $oneAmount );

        }


        $investResult = self::investResultMerge( array_merge_recursive( $investHundredResult, $investOneResult ) );

        return $investResult;
    }

    /**
     * @desc 配合100元债权集合里的数据
     * @author linguanghui
     * @param $hundredAmounts 分割数据
     * @return array
     */
    public static function doHundredCacheData( $hundredAmounts )
    {
        $investResult = [];

        $redis = new Redis();

        $redis->connect( env('REDIS_HOST'), env('REDIS_PORT') );

        $redis->auth( env('REDIS_PASSWORD') );

        $hundred  = self::KEY_HUNDRED_CREDIT;

        $hundredCredit = self::getCutCreditCache( $hundred );

        $one  = self::KEY_ONE_CREDIT;

        $oneCredit = self::getCutCreditCache( $one );

        if(empty( $hundredCredit ))
            return [] ;

        //循环分割百元的债权缓存
        foreach( $hundredCredit as $key => $value )
        {
            $creditArr = json_decode( $value, true );

            #$investResult = self::getMatchInvestData( $creditArr, $investResult );

            self::getMatchInvestData( $creditArr, $investResult );

            $hundredAmounts -=  $creditArr['usable_amount'];

            $redis->lPop( $hundred );

            //金额匹配完成退出循环
            if( $hundredAmounts <= 0 )
            {
                break;
            }
        }

        //用户金额
        if( $hundredAmounts > 0 ){

            $investResult1  = self::doOneCacheData( $hundredAmounts );

            $investResult   = self::investResultMerge( array_merge_recursive( $investResult, $investResult1 ) );
        }

        return $investResult;
    }

    /**
     * @desc 循环匹配缓存里1元的数据
     * @author linguanghui
     * @param $oneAmounts 分割数据
     * @return array
     */
    public static function doOneCacheData( $oneAmounts )
    {
        $investResult = [];

        $redis = new Redis();
        $redis->connect( env('REDIS_HOST'), env('REDIS_PORT') );

        $redis->auth( env('REDIS_PASSWORD') );

        $one  = self::KEY_ONE_CREDIT;

        $oneCredit = self::getCutCreditCache( $one );

        $hundred  = self::KEY_HUNDRED_CREDIT;

        $hundredCredit = self::getCutCreditCache( $hundred );

        if( empty( $oneCredit ))
        {
            if( !empty( $hundredCredit ) )
            {
            $investResult1 = self::doLeftOneAmount( $oneAmounts );

            $investResult = self::investResultMerge( $investResult1 );

            return $investResult;
            }
            return [];
        }
        foreach( $oneCredit as $key => $val )
        {
            $creditArr = json_decode( $val, true );

            //$investResult = self::getMatchInvestData( $creditArr );

            self::getMatchInvestData( $creditArr, $investResult );

            $oneAmounts -= $creditArr['usable_amount'];

            //弹出1元的key
            $redis->lPop( $one );

            //1元金额已分配完
            if( $oneAmounts <= 0)
            {
                break;
            }
        }

        if( $oneAmounts >0 )
        {
            $investResult1 = self::doLeftOneAmount( $oneAmounts );

            $investResult = self::investResultMerge( array_merge_recursive( $investResult, $investResult1 ) );
        }


        return $investResult;
    }

    /**
     * @desc 处理债权匹配中用户投资的数据
     * @author linguanghui
     * @param $creditArr array 债权数据
     */
    public static function getMatchInvestData( $creditArr, &$investResult )
    {

        //投资记录
        $investResult[$creditArr['id']]['id'] = $creditArr['id'] ;

        if( isset( $investResult[$creditArr['id']]['usable_amount'] ))
        {
            $investResult[$creditArr['id']]['usable_amount'] += $creditArr['usable_amount'] ;
        }else{

            $investResult[$creditArr['id']]['usable_amount'] = $creditArr['usable_amount'] ;
        }

        #return $investResult;

    }

    /**
     * @desc 处理如果用户金额分配两个缓存池的投资数据
     * @author linguanghui
     * @param $investMergeResult 投资处理合并数据
     * @return array
     */
    public static function investResultMerge( $investMergeResult )
    {
        if(empty( $investMergeResult ))
        {
            return [];
        }


		foreach ($investMergeResult as $key => $value) {

            $investResult[$value['id']]['id'] = $value['id'] ;

			if( isset( $investResult[$value['id']]['usable_amount']) )
			{
				$investResult[$value['id']]['usable_amount']  +=   $value['usable_amount'];
			}else{
				$investResult[$value['id']]['usable_amount']  =    $value['usable_amount'];
			}
		}

        return $investResult;
    }

    /**
     * @desc 处理用户剩余的1元金额集合
     * @param $leftOneAmount float
     * @return array
     */
    public static function doLeftOneAmount( $leftOneAmount )
    {
        $investResult = [];

        $redis = new Redis();

        $redis->connect( env('REDIS_HOST'), env('REDIS_PORT') );

        $redis->auth( env('REDIS_PASSWORD') );

        $popHundredData  = json_decode( $redis->lPop( self::KEY_HUNDRED_CREDIT ), true );

        $investResult[] = [
            'id'  => $popHundredData['id'],
            'usable_amount' => $leftOneAmount,
            ];

        //处理匹配后的金额
        $leftHundredAmount =  $investResult;


        $leftHundredAmount[0]['usable_amount'] = $popHundredData['usable_amount'] - $leftOneAmount;


        $cutOneAmount  = self::doCutAbleCreditData( $leftHundredAmount );

        self::pushTheCutCreditCache( $cutOneAmount );


        return $investResult;
    }

    /** ############################ [债权匹配新方案匹配处理-End] ##########################################**/
}
