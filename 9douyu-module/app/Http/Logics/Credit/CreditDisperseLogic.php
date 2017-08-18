<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/3/21
 * Time: Pm 05:57
 */

namespace App\Http\Logics\Credit;

use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Credit\CreditDisperseModel;
use App\Http\Models\Credit\UserCreditModel;

use App\Http\Dbs\Credit\CreditDb;
use App\Http\Dbs\Credit\CreditDisperseDb;
use Log;
use Cache;
use App\Tools\ToolMoney;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Mockery\Exception;
use Predis\Client;

/**
 * @desc 添加分散债权
 * Class CreditDisperseLogic
 * @package App\Http\Logics\Credit
 */

class CreditDisperseLogic extends CreditLogic
{

    private static $redis = null;

    public function __construct()
    {

        self::getInstance();

    }

    private static function getInstance(){

        if(self::$redis === null){
            //redis初始化
            self::$redis = new \Redis();

            self::$redis->connect( env('REDIS_HOST'), env('REDIS_PORT') );

            self::$redis->auth( env('REDIS_PASSWORD') );
        }


    }

    /**
     * @desc 创建分散债权信息
     * @author linguanghui
     * @param $data
     * @return array
     */
    public function doCreate( $data )
    {

        $attributes = [
            'credit_name'     => $data['credit_name'],
            'amounts'  => $data['amounts'],
            'usable_amount' => $data['amounts'],
            'interest_rate'   => $data['interest_rate'],
            'loan_deadline'   => $data['loan_deadline'],
            'status'          => CreditDb::STATUS_CODE_UNUSED,
            'start_time'      => $data['start_time'],
            'end_time'        => $data['end_time'],
            'loan_realname'   => empty($data['loan_realname']) ? '' : $data['loan_realname'],
            'loan_idcard'     => empty($data['loan_idcard']) ? '' : $data['loan_idcard'],
            'contract_no'     => $data['contract_no'],
            ];

        try {

            $return = CreditDisperseModel::doCreate( $attributes );

        }catch ( \Exception $e ) {
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError( $e->getMessage() );
        }

        return self::callSuccess( [$return] );
    }

    /**
     * @desc 批量添加债权信息
     * @author linguanghui
     * @param $creditInfo array 债权信息
     * @return array
     */
    public function doBatchImport( $creditInfo )
    {

        if(empty( $creditInfo ))
            return self::callError( '批量上传数据为空' );

        $formatCreditInfo = $this->formatImportCredit( $creditInfo );

        try {

            $return = CreditDisperseModel::doBatchCreate( $formatCreditInfo );

        }catch ( \Exception $e ) {
            $attributes['data']           = $formatCreditInfo;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error( __METHOD__.'Error', $formatCreditInfo );

            return self::callError( $e->getMessage() );
        }

        return self::callSuccess( [$return] );
    }

    /**
     * @desc 格式化导入债权的信息
     * @author linganghui
     * @param $creditInfo array
     * @return array
     */
    public function formatImportCredit( $creditInfo )
    {
        $formatCreditInfo = [] ;

        if(empty( $creditInfo ))
            return [];

        foreach( $creditInfo as $key => $value )
        {
            $formatCreditInfo[ $key ] = [
                'credit_name'     => empty( $value['credit_name'] ) ? '快金' : $value['credit_name'] ,
                'amounts'         =>  $value['amounts'] ,
                'usable_amount'   =>  $value['amounts'] ,
                'interest_rate'   => empty( $value['interest_rate'] ) ? '0' : $value['interest_rate'] ,
                'loan_deadline'   => empty( $value['loan_deadline'] ) ? ToolTime::getDayDiff( $value['start_time'], $value['end_time'] )  : $value['loan_deadline'] ,
                'status'          => CreditDb::STATUS_CODE_UNUSED,
                'start_time'      => $value['start_time'],
                'end_time'        => $value['end_time'],
                'loan_realname'   => empty($value['loan_realname']) ? '' : $value['loan_realname'],
                'loan_idcard'     => empty($value['loan_idcard']) ? '' : $value['loan_idcard'],
                'contract_no'     => empty( $value['contract_no'] ) ? '' : $value['contract_no'] ,
                ];

        }

        return $formatCreditInfo ;
    }

    /**
     * @desc 获取新债权信息列表
     * @author linguanghui
     * @param $condition array
     * @return array
     */
    public function getCreditDisperseList( $status, $size )
    {

        $creditDisperseModel  = new CreditDisperseModel();

        return $creditDisperseModel->getCreditDisperseListByStatus($status, $size);

    }

    /**
     * @desc 发布债权为可匹配状态
     * @param $ids array
     * return bool
     */
    public function doCreditOnline( $ids )
    {

        $creditDisperseModel  = new CreditDisperseModel();

        try{

            if( !is_array( $ids ) )
            {
                $ids = [ $ids ];
            }

            $return = $creditDisperseModel->doCreditOnline ( $ids );

            CreditDisperseModel::setCreditAbleAmountCache();

        }catch( \Exception $e ){

            \Log::error(__CLASS__.__Method__, ['msg' => $e->getMessage(), 'data' => $id]);

            return self::callError( $e->getMessage() );
        }

        return self::callSuccess( $return );
    }

    /**
     * 格式化新债权列表
     * @param array $listData
     * @return array
     */
    protected static function formatAdminList( $listData = [] )
    {

        if($listData){
            foreach($listData as $list){
                $list->credit_amounts = $list->amounts;
                $list->useable_amounts = $list->usable_amount;
                if($list->status == CreditDb::STATUS_CODE_UNUSED){
                    //未使用状态
                    $list->status_note  = '可使用';
                }else{
                    $list->status_note  = '不可用';
                }
            }
        }
        return $listData;
    }

    /*###########################［新债权匹配] ##############################*/

    /**
     * @desc 获取可用的债权列表
     * @author linguanghui
     * @return array
     */
    public function getAbleCreditList()
    {
        $creditDisperseModel = new CreditDisperseModel();

        try{
            $result = $creditDisperseModel->getAbleCreditDisperseList();
        }catch(\Exception $e){
            $attributes['data']           = [];
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error( __METHOD__.'Error', $attributes );

            return self::callError( $e->getMessage() );

        }

        return self::callSuccess( $result );
    }

    /**
     * @desc 通过多个ID获取债权信息
     * @author linguanghui
     * @param $creditIds string
     * @return array
     */
    public function getCreditListByIds( $creditIds )
    {
        if(empty($creditIds)){
            return [];
        }

        $creditDisperseDb = new CreditDisperseDb();

        $creditList  = $creditDisperseDb->getCreditListByIds( $creditIds );

        return $creditList;
    }

    /**
     * @desc 债权匹配数据处理
     * @author linguanghui
     * @return array
     */
    public function doCreditMatchData()
    {
        $creditDisperseModel  = new CreditDisperseModel();

        try{

            self::beginTransaction();

            //检测债权匹配数据
            //CreditDisperseModel::checkMatchCredit( $creditArr );

            //设置到期的债权为到期状态
            $creditDisperseModel->setCreditExpireStatus();

            //初始化债权数据
            $creditDisperseModel->initCreditData();

            //获取可匹配的债权数据
            $creditArr = $this->getAbleCreditList();

            //分割债权数据
            $cutCreditResult = CreditLogic::doCutAbleCreditData( $creditArr['data'] );

            //分割后的数据存入redis list
            $doCache  = CreditLogic::pushTheCutCreditCache( $cutCreditResult, True );

            self::commit();
        }catch(\Exception $e){
            self::rollback();

            $attributes['data']           = [];
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        if( $doCache ){
            //用户债权算法匹配
            \Event::fire( new \App\Events\Credit\AccountCreditMatchEvent(  ));
        }
    }

    /**
     * @desc 执行用户债权分散匹配操作
     * @author linguanghui
     * @param $account array 投资账户
     * @param $creditArr array 可匹配的债权列表
     */
    public function doAccountCreditMatch( $account ){

        if( empty($account) ){
            return self::callError('匹配原始数据为空');
        }

        $investResult = [];

        //执行分散匹配算法
        foreach($account as $key => $value){

            if( $value['after_balance'] <=0 )
            {
                continue;
            }
            $return = CreditLogic::doCutCreditMatch( (int)$value['after_balance'] );

            $investResult[$value['user_id']] = $return;
        }

        return $investResult;
    }

    /**
     * @desc 更新匹配后的债权数据
     * @author linguanghui
     * @param $creditArr array 匹配后的债权数据
     * @return bool
     */
    public function updateCreditAfterMatch($creditArr){

        if(empty($creditArr)){
            return self::callError('债权匹配后数据为空');
        }

        //获取更新的债权信息组装数组
        foreach($creditArr as $key =>$val){
            if(isset($val['invest'])){
                $creditResult[$key]['id'] = $val['id'];
                $creditResult[$key]['usable_amount'] = $val['usable_amount'];
                $creditResult[$key]['status'] = (isset($val['status']) ? CreditDb::STATUS_CODE_ACTIVE  : CreditDb::STATUS_CODE_UNUSED);
            }
        }
        return CreditDisperseModel::updateCreditAfterMatch($creditResult);
    }

    /**
     * @desc 格式化用户投资债权匹配的数据
     * @param $investResult array
     * @return array
     */
    public function formatMatchInvestData( $investResult ) {

        if (empty( $investResult )) {
            return self::callError('匹配数据为空');
        }

        $returnData = [] ;

        foreach( $investResult as $userId => $invests )
        {
            foreach( $invests as  $value )
            {
                $returnData[] = [
                    'user_id' => $userId,
                    'credit_id' => $value['id'],
                    'amount'    => $value['usable_amount']
                ];
            }

        }


        $userCreditModel = new UserCreditModel();

        try{

            $userCreditModel->doAdd($returnData);

             \Event::fire(new \App\Events\Admin\Credit\MatchSuccessEvent());

        }catch( \Exception $e ){

            \Log::error(__CLASS__.__Method__, ['msg' => $e->getMessage(), 'data' => $returnData]);

        }
    }

    /***********//***********//***********//**重置债权*********//***********//***********//***********/

    /**
     * @throws \Exception
     * @desc 重置剩余未匹配债权监听
     */
    public function resetUnMatchCredit(){

        $data = $this->getUnMatchList();

        $ids = $this->getIdsByFormatList($data);

        $waitUpdateList = $this->getWaitUpdateList($data);

        $model = new CreditDisperseModel();

        try{

            self::beginTransaction();

            //更新数据信息为已匹配,更改状态
            $model->resetHadMatchFullByIds($ids);

            //更新未匹配完成的债权id
            CreditDisperseModel::updateCreditAfterMatch($waitUpdateList);

            //设置可匹配债权金额总和
            CreditDisperseModel::setCreditAbleAmountCache();

            $this->clearAllRedis();

            self::commit();

            return self::callSuccess();


        }catch (Exception $e){

            self::rollback();

            $receiveEmails = \Config::get('email.monitor.accessToken');

            $emailModel = new EmailModel();

            $title = '【Warning】重置债权债权失败';

            $emailModel->sendHtmlEmail($receiveEmails, $title, $e->getMessage());

            \Log::Error(__CLASS__.__METHOD__.__FUNCTION__.'Error', ['ids' => $ids, 'waitUpdateList' => $waitUpdateList]);

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param array $data
     * @return array|bool
     * @desc 获取列表ids
     */
    public function getIdsByFormatList($data=[]){

        if( empty($data) ){

            return false;

        }

        $returnIds = [];

        if( isset($data['hundredList']) && !empty($data['hundredList']) ){

            foreach ($data['hundredList'] as $item){

                $item = json_decode($item, true);

                $returnIds[] = $item['id'];

            }

        }

        if( isset($data['oneList']) && !empty($data['oneList']) ){

            foreach ($data['oneList'] as $item){

                $item = json_decode($item, true);

                $returnIds[] = $item['id'];

            }

        }

        return array_unique($returnIds);

    }

    /**
     * @param array $data
     * @return array|bool
     * @desc 格式化待回执的债权列表
     */
    public function getWaitUpdateList($data=[]){

        if( empty($data) ){

            return false;

        }

        $resetList = [];

        if( isset($data['hundredList']) && !empty($data['hundredList']) ){

            foreach ($data['hundredList'] as $item){

                $item = json_decode($item, true);

                if( isset($resetList[$item['id']]) ){

                    $resetList[$item['id']]['usable_amount'] = $resetList[$item['id']]['usable_amount'] + $item['usable_amount'];

                    $resetList[$item['id']]['status'] = CreditDb::STATUS_CODE_ACTIVE;

                    continue;

                }

                $resetList[$item['id']] = ['id' => $item['id'], 'usable_amount' => $item['usable_amount'], 'status' => CreditDb::STATUS_CODE_ACTIVE];

            }

        }

        if( isset($data['oneList']) && !empty($data['oneList']) ){

            foreach ($data['oneList'] as $item){

                $item = json_decode($item, true);

                if( isset($resetList[$item['id']]) ){

                    $resetList[$item['id']]['usable_amount'] = $resetList[$item['id']]['usable_amount'] + $item['usable_amount'];

                    $resetList[$item['id']]['status'] = CreditDb::STATUS_CODE_ACTIVE;

                    continue;

                }

                $resetList[$item['id']] = ['id' => $item['id'], 'usable_amount' => $item['usable_amount'], 'status' => CreditDb::STATUS_CODE_ACTIVE];

            }

        }

        return $resetList;

    }


    /**
     * @return mixed
     * @desc 获取未匹配的列表
     */
    public function getUnMatchList(){

        $data['hundredList'] = $this->getUnMatchHundredList();

        $data['oneList'] = $this->getUnMatchOneList();

        return $data;

    }

    /**
     * @return mixed
     * @desc 获取未匹配的100元
     */
    public function getUnMatchHundredList(){

        return $this->getRedisAllListByKey( self::KEY_HUNDRED_CREDIT );

    }

    /**
     * @return mixed
     * @desc 获取未匹配一元的列表
     */
    public function getUnMatchOneList(){

        return $this->getRedisAllListByKey( self::KEY_ONE_CREDIT);

    }

    /**
     * @param $key
     * @return array
     * @desc 根据key获取列表
     */
    public function getRedisAllListByKey($key){

        return self::$redis->lrange($key, 0, -1);

    }

    /**
     * @param $key
     * @param $data
     * @desc 加入redis缓存
     */
    public function doPushRedisData($key, $data){

        //redis初始化
        return self::$redis->rpush($key, json_encode($data));

    }

    /**
     * @desc 清楚redis
     */
    public function clearAllRedis(){

        self::$redis->del(self::KEY_ONE_CREDIT);

        self::$redis->del(self::KEY_HUNDRED_CREDIT);

    }


}


