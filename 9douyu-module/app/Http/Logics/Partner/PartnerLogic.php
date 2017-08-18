<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午8:19
 */

namespace App\Http\Logics\Partner;


use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\DbKvdb\DbKvdbDb;
use App\Http\Dbs\User\InviteDb;
use App\Http\Dbs\User\OAuthAccessTokenDb;
use App\Http\Dbs\User\PartnerDb;
use App\Http\Dbs\User\PartnerPrincipalDb;
use App\Http\Logics\Invite\InviteRatesLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\User\SessionLogic;
use App\Http\Logics\User\TokenLogic;
use App\Http\Models\Activity\ActivityFundHistoryModel;
use App\Http\Models\Common\CoreApi\RefundModel;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\PasswordModel;
use App\Http\Models\Common\TradingPasswordModel;
use App\Http\Models\Common\DbKvdbModel;
use App\Http\Models\Invite\InviteRatesModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Http\Models\User\InviteModel;
use App\Http\Models\User\PartnerModel;
use App\Http\Models\User\UserInfoModel;
use App\Jobs\Partner\BackInterestJob;
use App\Jobs\Partner\RefundInterestJob;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolDomainCookie;
use App\Tools\ToolQrCode;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Cache;
use Log;
use App\Tools\ExportFile;
use Config;
use App\Http\Models\Common\ServiceApi\EmailModel;

class PartnerLogic extends Logic
{


    /**
     * @param $userId
     * @return array
     * @desc 参与合伙人活动
     */
    public function create ($userId){

        try{

            $model = new PartnerModel();

            $result = $model -> create( $userId );

        }catch (\Exception $e){

            Log::error(__CLASS__.__METHOD__,[$e->getMessage(),$e->getCode()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     * @param $userId
     * @return array|bool
     * @desc 检测是否已经参与活动
     */
    public function isPartnerByUserId( $userId ){

        $db = new PartnerDb();

        $result = $db -> getByUserId($userId);

        if(!$result){
            return false;
        }

        return $result;

    }

    /**
     * @return array|mixed
     * @desc 获取活动配置信息
     */
    public function getDefineConfig()
    {
        $defineConfig = array(
            'START_PROFIT_TIME'     => '2015-11-28',
            'END_PROFIT_TIME'       => '2016-11-27',
            'LIMIT_PER'             => 100,
            'LIMIT_INVITE_DAY'      => 30,
            'LIMIT_CASH'            => 500,
            'PARTNER_START_TIME'    => '2015-11-23',
            'PARTNER_END_TIME'      => '2016-11-23',
            'ANNOUNCEMENT_URL'      => '/',
            'LEVEL_PROFIT_1'        => 0.015,
            'LEVEL_CASH_1'          => 1000000,
            'LEVEL_PROFIT_2'        => 0.02,
            'LEVEL_CASH_2'          => 2000000,
            'LEVEL_PROFIT_0'        => 0.01,
        );
        $res = SystemConfigModel::getConfig('WX_ACTIVE_PARTNER');
        if(empty($res)){
            return $defineConfig;
        }else{
            return $res;
        }
    }

    /**
     * @param $startTime
     * @param $endTime
     * @param $userId
     * @return mixed
     * @desc 判断合伙人活动状态
     */
    public function getStatus($startTime, $endTime, $userId){

        $statusArr = array(
            'noStart'   => 1, //活动未开始
            'ending'    => 2, //活动已结束
            'doing'     => 3, //活动进行中 加入合伙人计划
            'isPartner' => 5, //查看佣金
        );
        $startTime   = strtotime($startTime);
        $endTime     = strtotime($endTime);
        $startTimeStr = date("Y年m月d日",$startTime);
        $endTimeStr   = date("Y年m月d日",$endTime);

        if($startTime > time()){
            $result['status']    = $statusArr['noStart'];
        }else if($endTime < time()){
            $result['status']    = $statusArr['ending'];
        }else{
            $result['status']    = $statusArr['doing'];
        }

        if($userId){
            //是否已成为合伙人
            $partnerInfo  = $this->isPartnerByUserId($userId);
            if($partnerInfo){
                $result['status'] = $statusArr['isPartner']; //查看佣金
            }
            $result['isLogin'] = 1;
        }else{
            $result['isLogin'] = 0;
        }

        $result['startTimeStr'] = $startTimeStr;
        $result['endTimeStr']   = $endTimeStr;

        return $result;

    }

    /**
     * 合伙人数排行榜
     */
    public function partnerInviteCountSortList($cache = 0){

        $cacheKey         = PartnerDb::PARTNER_INVITE_COUNT_TOP_5;
        $partnerSort      = Cache::get($cacheKey);

        if(!empty($partnerSort) && $cache != 1){
            $sortList = json_decode($partnerSort,true);
        }else{
            //获取所有合伙人列表
            $partnerList = PartnerDb::getPartnerList();
            $sortList    = $this->getCountInviteSortByUids($partnerList);
            $sortList    = $this->formatUserInfo($sortList);
            Cache::put($cacheKey,json_encode($sortList), 24*60);
        }
        return $sortList;
    }

    /**
     * @param $params
     * @param $page
     * @param $size
     * @return mixed
     * @desc 1.邀请合伙人详情（仅展示好友当前数据）
     */
    public function getPartnerInviteInfo($params, $page, $size){

        $inviteDb = new InviteDb();

        $userList = $inviteDb->getInviteListByUser($params, $page, $size);

        $inviteInfo['list'] = $this->formatUserInfoShow($userList['list']);

        $inviteInfo['total'] = $userList['total'];

        return $inviteInfo;

    }

    /**
     * @param $params
     * @param $page
     * @param $size
     * @return array
     * @desc 2.佣金收益记录
     */
    public function getPartnerCashList($params, $page, $size){

        $params['type'] = ActivityFundHistoryDb::TYPE_IN;
        $params['source'] = ActivityFundHistoryDb::SOURCE_PARTNER;

        $activityFundHistory = new ActivityFundHistoryDb();

        $list = $activityFundHistory->getActivityFundHistoryList($params, $page, $size);

        if($list['total'] > 0){

            foreach ($list['list'] as $key => $val){

                $interestInfo = explode('|',$val['note']);

                $val['principal'] = $interestInfo[0];

                if(count($interestInfo) > 1){

                    $val['rate'] = $interestInfo[1];
                }else{

                    $val['rate'] = '未记录';
                }

                $data[] = $val;
            }

            return $data;

        }else{

            return [];
        }

        return $list;

    }

    /**
     * @param $params
     * @param $page
     * @param $size
     * @return array
     * @desc 3.佣金转出记录
     */
    public function getPartnerTurnOutList($params, $page, $size){

        $params['type'] = ActivityFundHistoryDb::TYPE_OUT;
        $params['source'] = ActivityFundHistoryDb::SOURCE_PARTNER;

        $activityFundHistory = new ActivityFundHistoryDb();

        $list = $activityFundHistory->getActivityFundHistoryList($params, $page, $size);

        return $list;

    }

    /**
     * 获取合伙人数排行榜数据
     * @param $list
     * @return array|bool
     */
    public function getCountInviteSortByUids($list){
        $uIds = self::formatUids($list);
        if(!$uIds ){ return false; }
        $model = new InviteModel();
        $sortList = $model->getCountInviteSortByUids($uIds);
        return $sortList;
    }

    /**
     * 格式化获取目标用户
     * 参数为数组（用户的id）
     * 返回array
     */
    public static function formatUids($list, $keys='user_id')
    {
        if( empty($list) ){return false;}
        $uIds = array();
        foreach( $list as $key => $val ){
            if( !isset($val[$keys]) ){
                continue;
            }
            $uIds[] = $val[$keys];
        }
        return $uIds;
    }

    /**
     * @param $list
     * @return mixed
     * @desc 拼装用户数据
     */
    public function formatUserInfo($list)
    {
        if(empty($list)) return '';
        $list = ToolArray::arrayToKey($list, 'user_id');
        $userIds = $this->formatUids($list);
        $userList = $this->getUserPhoneByUserIds($userIds);

        foreach ($userList as $key => $val) {
            if (isset($list[$key])) {
                $list[$key]['phone']         = $val['phone'];
                $list[$key]['register_time'] = $val['created_at'];
            }
        }
        return $list;
    }

    /**
     * @param $list
     * @return mixed
     * @desc 拼装用户数据
     */
    public function formatUserInfoShow($list)
    {
        if(empty($list)) return '';
        $list = ToolArray::arrayToKey($list, 'other_user_id');
        $userIds = $this->formatUids($list, 'other_user_id');
        $userList = $this->getUserPhoneByUserIds($userIds, 1);

        $refundCashUserList =  RefundModel::getRefundingPrincipalListByUserIds( $userIds );

        $refundCashUserList =ToolArray::arrayToKey($refundCashUserList, 'user_id');

        foreach ($userList as $key => $val) {
            if (isset($list[$key])) {
                $list[$key]['phone']         = $val['phone'];
                $list[$key]['register_time'] = $val['created_at'];
            }
            //待收本金
            /*$refundCash = RefundModel::getCoreRefundTotalByUserIds($key);
            $list[$key]['refund_cash']   = empty($refundCash['total_cash'])?0:$refundCash['total_cash'];*/

            $list[$key]['refund_cash']   = isset($refundCashUserList[$key]) ? $refundCashUserList[$key]['total_cash'] : 0;
        }

        return $list;
    }

    /**
     * todo 一码付有用到此方法
     * @param array $userIds
     * @param int $isShow
     * @return array|bool
     * @desc 通过userids获取用户手机号列表
     */
    public function getUserPhoneByUserIds($userIds=array(),$isShow=0)
    {
        if( empty($userIds) ){return false;}
        $userIds = implode(',', $userIds);
        $UserLogic = new UserModel();
        $userList = $UserLogic->getUserListByIds($userIds);
        $data = array();
        if( $userList ){
            foreach( $userList as $key => $user ){
                if($isShow){
                    $phone = $user['phone'];
                }else{
                    $phone = substr($user['phone'],0,3).'****'.substr($user['phone'],-4);
                }
                $data[$user['id']] = array(
                    'phone'             => $phone,
                    'created_at'        => date('Y-m-d',strtotime($user['created_at'])),
                );
            }
        }
        return $data;
    }

    /**
     * @param int $cache
     * @return mixed
     * @desc 合伙人投资总额排行榜
     */
    public function partnerInviteInvestCountSortList($cache = 0){

        $cacheKey = PartnerDb::PARTNER_INVITE_INVEST_COUNT_TOP_5;
        $sortList = Cache::get($cacheKey);

        if(!empty($sortList) && $cache != 1 ){
            $historyData = json_decode($sortList,true);
        }else{
            $topFiveList = PartnerDb::getYesterdayCashTopList();
            $historyData = $this->formatUserInfo($topFiveList);
            Cache::put($cacheKey, json_encode($historyData));

        }
        return $historyData;
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 通过Id获取邀请总人数
     */
    public function getInviteUserCount( $userId ){

        $db = new InviteDb();

        $total = $db -> getInviteUserCountByUserId( $userId );

        return $total;

    }

    /**
     * @param $userId
     * @return int
     * @desc 被邀请人待收本金
     */
    public function getPrincipalByInvestIds( $userId ){

        $inviteDb = new InviteDb();
        $refundModel = new RefundModel();
        //邀请的合伙人用户id列表
        $inviteUerIds        = $inviteDb->getInviteListByUserId($userId);

        if(!$inviteUerIds){
            return 0;
        }
        $inviteUerIds        = $this->formatUids($inviteUerIds);
        $refundInfo          = $refundModel->getCoreRefundTotalByUserIds($inviteUerIds);
        return $refundInfo;

    }

    /**
     * 通过金额获取佣金收益率
     * cash
     * 返回佣金收益率
     *
     * @param int $cash
     * @return mixed
     */
    public function getProfitByCash($cash=0)
    {
        $config = $this->getDefineConfig();

        if( $cash >= $config['LEVEL_CASH_2'] ){
            return $config['LEVEL_PROFIT_2'];
        }elseif( $cash >= $config['LEVEL_CASH_1'] && $cash < $config['LEVEL_CASH_2'] ){
            return $config['LEVEL_PROFIT_1'];
        }else{
            return $config['LEVEL_PROFIT_0'];
        }
    }

    /**
     * @param $interestTotal
     * @return mixed
     * @desc 累计佣金排名
     */
    public function getOneSort( $interestTotal ){

        if( $interestTotal <= 0 ){
            return 0;
        }

        $db = new PartnerDb();

        $result = $db->getOneSort( $interestTotal );

        return $result;

    }

    /**
     * @param $userId
     * @return bool
     * @desc 验证用户是否已被设置交易密码
     */
    public function getUserAuthStatus( $userId ){

        $model = new UserModel();

        $userInfo = $model -> getCoreApiUserInfo( $userId );

        $checkInfo = \App\Http\Models\User\UserModel::getUserAuthStatus($userInfo);

        return $checkInfo['password_checked'];

    }

    /**
     * @param array $data
     * @return array
     * @desc 合伙人佣金转出
     */
    public function doInvestOut($data=array())
    {

        if( empty($data) || !isset($data['user_id']) || $data['user_id']<1 || !isset($data['cash']) || $data['cash']<1 ){

            return self::callError(LangModel::ERROR_PARTNER_DATA);

        }

        $userModel                 = new UserModel();

        $model                     = new PartnerModel();

        $activityFundModel         = new ActivityFundHistoryModel();

        //赎回逻辑

        try {

            self::beginTransaction();

            $userId     = $data['user_id'];

            $cash       = $data['cash'];

            $model->checkInvestOut($userId,$cash);

            //更新佣金金额
            $model->delCash($userId, $cash);

            //获取用户信息
            $userInfo = \App\Http\Models\User\UserModel::getUserInfo($userId);

            //创建核心资金记录，更改余额
            $userModel->doIncBalance($userId, $cash, $userInfo['trading_password'], LangModel::PARTNER_OUT, ToolStr::getRandTicket());

            //创建模块活动类资金流水,方便数据统计
            $fundData = [
                'user_id'           => $userId,
                'balance_change'    => $cash,
                'source'            => ActivityFundHistoryDb::SOURCE_PARTNER,
                'note'              => '收益转出'
            ];

            $activityFundModel->doDecrease($fundData);

            Logic::commit();

            \Log::info(__METHOD__.'success', $data);

            /*
            $params = [
                'event_name'        => 'App\Events\Award\PartnerCommissionTransferEvent',
                'event_desc'        => '合伙人佣金转出事件',
                'user_id'           => $userId,
                'cash'              => $cash,
                'trading_password'  => $userId,
                'ticket_id'         => ToolStr::getRandTicket()
            ];

            Log::info(__METHOD__,$params);

            \Event::fire(new \App\Events\Award\PartnerCommissionTransferEvent($params));

            //写日志
            Log::info(__CLASS__.__METHOD__.'success', [$userId,$cash]);

            self::commit();*/

        } catch(\Exception $e) {

            self::rollback();

            Log::error(__CLASS__.__METHOD__, [$e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }


    /**
     * @param $userId
     * @return string
     * @desc 获取用户邀请码
     */
    public function getInviteCodeByUserId( $userId ){

        if($userId < 1){ return ''; }

        $model = new UserInfoModel();

        return $model -> setUserCodeByUid($userId);

    }

    /**
     * @param array $data
     * @return bool
     * @desc 执行添加收益
     */
    public function doBackInterest( $data=[] )
    {

        if( empty($data) ){

            Log::Info('doBackInterestError', ['EmptyData']);

            return false;

        }

        $partnerModel = new PartnerModel();

        $activityFundHistoryModel = new ActivityFundHistoryModel();

        try{


            foreach ($data as $value){

                self::beginTransaction();

                //合伙人账户加钱
                $partnerModel->incCash($value['user_id'], $value['interest'], $value['principal']);

                if($value['interest'] > 0){

                    $fundData = [
                        'user_id'           => $value['user_id'],
                        'balance_change'    => $value['interest'],
                        'source'            => ActivityFundHistoryDb::SOURCE_PARTNER,
                        'note'              => $value['principal']
                    ];

                    //活动资金流水表
                    $activityFundHistoryModel->doIncrease($fundData);
                }

                self::commit();

            }


        }catch (\Exception $e){

            self::rollback();

            \Log::Info('doBackInterestError'.ToolTime::dbDate(), $data);

        }

    }

    /**
     * @return array
     * @desc 拆分合伙人数据,主要先计算用户邀请人的代收,根据代收规则计算佣金收益,进入队列等待执行;
     * @系统自动执行 doBackInterest 创建回款
     */
    public function splitPartner()
    {

        $partnerConfig = SystemConfigModel::getConfig('WX_ACTIVE_PARTNER');

        $now = ToolTime::dbDate();

        if( $now > $partnerConfig['END_PROFIT_TIME'] ){

            return false;

        }

        $partnerDb = new PartnerDb();

        //获取总数
        $partnerNum = $partnerDb->getUserTotal();

        //计算页数
        $pages = ceil($partnerNum / 1000);

        $inviteDb = new InviteDb();

        //循环查找被邀请人的代收金额
        for( $p = 1; $p <= $pages; $p++ ){

            $partnerList = $partnerDb->getPartnerListByPage($p, 1000);

            if( !empty($partnerList) ){

                $partnerUserIds = ToolArray::arrayToIds($partnerList, 'user_id');

                $inviteList = $inviteDb->getPartnerInviteOtherUserIdListByUserIds($partnerUserIds);

                $inviteList = $this->formatInviteList($inviteList);

                $data = [];


                if( !empty($inviteList) ){

                    foreach ($partnerList as $partner){

                        //计算过收益的不再计算
                        if( $partner['interest_time'] > ToolTime::dbDate() ){

                            continue;

                        }

                        $otherUserIds = isset($inviteList[$partner['user_id']]) ? $inviteList[$partner['user_id']] : 0;

                        if( empty($otherUserIds) ){

                            continue;

                        }

                        $refundCashArr = RefundModel::getCoreRefundTotalByUserIds($otherUserIds);

                        $interest = $this->getBackInterestByCash($refundCashArr['total_cash']);

                        if( $interest < 0.01 ){

                            continue ;

                        }

                        $data[] = [
                            'user_id'       => $partner['user_id'],
                            'principal'     => $refundCashArr['total_cash'],
                            'interest'      => money_format($interest, 2)
                        ];

                    }

                }

                if( !empty($data) ){

                    $res = \Queue::pushOn('doRefundPartnerInterest',new RefundInterestJob($data));

                    if( !$res ){

                        Log::Error('splitPartnerError', [$data]);

                    }

                }

            }

        }

        return [];

    }

    /**
     * @param array $data
     * @return array
     * @desc 格式化邀请列表
     */
    public function formatInviteList($data=[])
    {

        if( empty($data) ){

            return [];

        }

        $list = [];


        foreach( $data as $value ){

            $list[$value['user_id']][] = $value['other_user_id'];

        }

        return $list;

    }

    /**
     * @param int $principal
     * @return bool|float|int
     * @desc 通过本金获取收益
     */
    public function getBackInterestByCash( $principal=0 ){

        if( $principal < 1 ){

            return 0;

        }

        $partnerConfig = SystemConfigModel::getConfig('WX_ACTIVE_PARTNER');

        if( empty($partnerConfig) ){

            Log::Error('splitPartnerError', ['partnerConfig empty']);

            return false;

        }

        if( $principal <= $partnerConfig['LIMIT_CASH'] ){

            return 0;

        }

        if( $principal >= $partnerConfig['LEVEL_CASH_2'] ){

            return $this->calInterest($principal, $partnerConfig['LEVEL_PROFIT_2']);

        }elseif( $principal >= $partnerConfig['LEVEL_CASH_1'] && $principal < $partnerConfig['LEVEL_CASH_2'] ){

            return $this->calInterest($principal, $partnerConfig['LEVEL_PROFIT_1']);

        }else{

            return $this->calInterest($principal, $partnerConfig['LEVEL_PROFIT_0']);

        }

    }

    /**
     * @param $cash
     * @param $profit
     * @return float
     * @desc 计算收益
     */
    private function calInterest($cash, $profit){

        return money_format( ($cash * $profit) / 365 , 2 );

    }

    /**
     * @desc [管理后台]合伙人管理
     * @param $param
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getAdminPartnerInfo($param, $page, $pageSize){

        $partnerDb  = new PartnerDb();

        if(empty($param)){
            return [];
        }

        //格式化搜索条件
        $where = $this->formatAdminPartnerWhere($param);

        //通过条件搜索结果
        $partner = $partnerDb->getPartnerInfo($where, $page, $pageSize);

        if($partner['total'] == 0){

            return [];
        }
        //组装userID
        $list = ToolArray::arrayToKey($partner['list'], 'user_id');

        $userIds = implode(',', $this->formatUids($list));

        //多个用户ID请求核心数据
        $partnerInfo = UserModel::getUserListByIds($userIds);
        $partnerUser = ToolArray::arrayToKey($partnerInfo,'id');

        $data = [];
        foreach($partner['list'] as $val){

            $userId                 = $val['user_id'];
            $val['phone']           = $partnerUser[$userId]['phone'];
            $val['balance']         = $partnerUser[$userId]['balance'];
            $val['real_name']       = $partnerUser[$userId]['real_name'];
            $val['register_time']   = $partnerUser[$userId]['created_at'];

            $data[] = $val;

        }
        $result['total'] = $partner['total'];
        $result['list']   = $data;
        return $result;

    }

    /**
     * @desc 格式化后台合伙人管理搜索条件
     * @param $param
     * @return array
     */
    public function formatAdminPartnerWhere($param){
        $where  = [];

        if(!empty($param['userId'])){
            $where[] = ['user_id', '=', $param['userId']];
        }
        //时间区间
        if(!empty($param['startTime'])){
            $startTime = $param['startTime'];
            $where[]  = ['created_at','>=', $startTime];
        }
        if(!empty($param['endTime'])){
            $endTime = $param['endTime'];
            $where[]  = ['created_at','<=', $endTime." 23:59:59"];
        }

        return $where;
    }

    /**
     * @param $token
     * @param $client
     * @desc 设置安卓cookie
     */
    public function setCookieAndroid($token, $client){

        $tokenLogic               = new TokenLogic();
        $tokenRecord              = OAuthAccessTokenDb::getUserIdByToken($token);
        $expires                  = strtotime($tokenRecord['expires']);
        $tokenExpiresIn           = $expires - time();
        $data['token_expires_in'] = ($tokenExpiresIn > 0) ? $tokenExpiresIn : 0;
        $data['access_token']     = $token;
        Log::info('REMOTE_ADDR_ip:',[ $_SERVER['REMOTE_ADDR'] ]);
        $data['access_token_key'] = $tokenLogic->encryptToken($token,  $_SERVER['REMOTE_ADDR']);
        $data['client']           = $client;
        setcookie(
            env('COOKIE_NAME', 'JDY_COOKIES'),
            SessionLogic::encryptCookie($data),
            time() + (int)$data['token_expires_in'],
            '/',
            ToolDomainCookie::getDomain()
        );

    }

    /**
     * @desc    获取时间段内活动奖励-合伙人
     * @param   $startTime
     * @param   $endTime
     * @param   $type   活动资金类型  1、转入 2、转出
     * @param   $source 活动资金来源
     * @date    2016年11月22日
     * @author  @llper
     */
    public function activityRewardExport($startTime, $endTime, $type=1, $source=2){

        $activityFundHistoryModel = new ActivityFundHistoryModel();
        #活动资金记录
        $activityFundRecord   = $activityFundHistoryModel->activityFundHistoryStat($startTime,$endTime,$type,$source);

        if(empty($activityFundRecord)){
            return [];
        }
        #用户ID
        $userIds    =  ToolArray::arrayToIds($activityFundRecord, 'user_id');
        $userIds    = implode(',', $userIds);
        #用户信息
        $userList   = UserModel::getUserListByIds($userIds);

        $userList   = ToolArray::arrayToKey($userList, 'id');
        #组装数据
        $formatData = $this->formatSelectData($activityFundRecord, $userList);

        ExportFile::csv($formatData, 'activity-reward-record-'.ToolTime::dbDate());
        return true;
    }

    /**
     * @param array $activityFundRecord
     * @param array $userList
     * @return array
     */
    private function formatSelectData($activityFundRecord=[], $userList=[], $type="转入",$source="合伙人"){

        if( empty($activityFundRecord) ){
            return [];
        }

        $return[] = [
            '用户ID','用户姓名','用户手机','金额','备注'
        ];

        foreach( $activityFundRecord as $key => $value ){
            $reward     = $value["balance_change"];
            $user_id    = $value["user_id"];
            $username   = isset($userList[$user_id])?$userList[$user_id]["real_name"]:'';
            $phone      = isset($userList[$user_id])?$userList[$user_id]["phone"]:'';

            $return[] = [
                'user_id'   => $user_id,
                'username'  => $username,
                'phone'     => $phone,
                'reward'    => $reward,
                'source'    => $source
            ];

        }

        return $return;

    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户中心首页数据
     */
    public function getUserIndexData($userId){

        /*$cacheKey = $userId.'_I_P'.ToolTime::dbDate();

        $cacheData = Cache::get($cacheKey);

        //如果存在缓存数据,则直接返回
        if( $cacheData && json_decode($cacheData, true) ){

            //return json_decode($cacheData, true);

        }*/

        //如果用户未有计息记录,则返回空
        $partnerDb = new PartnerDb();

        $userInfo = $partnerDb->getByUserId($userId);

        if( empty($userInfo) ){

            return [];

        }

        //佣金排名
        $userInfo['interest_sort'] = $this->getOneSort($userInfo['interest']);

        //佣金加息券信息
        $inviteRateLogic = new InviteRatesLogic();

        $usingRate = $inviteRateLogic->getUsingRateByUserIds([$userId]);

        $userInfo['rate_interest'] = [];

        $userInfo['rate_list'] = [];

        if( empty($usingRate['data']) ){

            $rateList = $inviteRateLogic->getCanUseListByUserId($userId);

            $userInfo['rate_list'] = empty($rateList['data']) ? [] : $rateList['data'];

        }else{

            $userInfo['rate_interest'] = $usingRate['data'];

        }

        //Cache::put($cacheKey, json_encode($userInfo), 24 * 60);

        return $userInfo;

    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户合伙人的邀请链接
     */
    public function getPartnerInviteData($userId)
    {

        $cacheKey = 'PARTNER_USER_DATA_' . $userId;

        $cacheData = Cache::get($cacheKey);

        //如果存在缓存数据,则直接返回
        if ($cacheData && json_decode($cacheData, true)) {

            //return json_decode($cacheData, true);

        }

        $userInfo = UserModel::getCoreApiUserInfo($userId);

        $phone = isset($userInfo['phone']) ? $userInfo['phone'] : '';

        $data = [
            'user_id'       => $userId,
            'invite_code'   => $phone,
            'qr_code'       => ToolQrCode::createCode(env('APP_URL_WX').'/register?inviteId=' . $userId, true)
        ];

        Cache::put($cacheKey, json_encode($data), 10000);

        return $data;

    }

    /*
     * 拆分邀请人入队列
     */
    public function splitInviteUser(){

        //获取相关邀请配置
        $partnerConfig = $this->getInviteConfig();
        $now = ToolTime::dbDate();

        if( ($now > $partnerConfig['END_DATE']) || ($now < $partnerConfig['START_DATE'])){

            exit("合伙人活动不在活动期内!\n");

        }

        $db = new PartnerPrincipalDb();

        $result = $db->getInviteUserTotal();

        if($result['total'] == 0){

            //发送报警邮件

            $receiveEmails = Config::get('email.monitor.accessToken');
            $model = new EmailModel();
            try{

                $title = '【Warning】合伙人计息';

                $msg = "合伙人本金数据未生成,请及时处理";

                $model->sendHtmlEmail($receiveEmails,$title,$msg);

            }catch (\Exception $e){

                Log::Error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            }
            exit();
        }

        $size = 200;
        $pages = ceil($result['total'] / $size);

        for($page = 1;$page <= $pages;$page++){

            $data = $db->getInviteUserIdByPage($page,$size);

            $userIds = ToolArray::arrayToIds($data,'user_id');

            if( $userIds ){

                $res = \Queue::pushOn('doPartnerInterest',new BackInterestJob($userIds));

                if(!$res){

                    Log::Error('splitInviteUser', [$data]);

                }

            }
        }
    }


    /**
     * @return array|mixed
     * 获取相关配置
     */
    public function getInviteConfig(){

        //三期合伙人邀请配置
        $partnerConfig = SystemConfigModel::getConfig('WX_ACTIVE_INVITE');

        return $partnerConfig;

    }

    /**
     * @param $userIds
     * 活期计息
     */
    public function doCalInterest($userIds){

        $config = $this->getInviteConfig();

        $db             = new PartnerPrincipalDb();

        $fundDb         = new ActivityFundHistoryModel();
        $partnerModel   = new PartnerModel();

        //获取合伙人待收信息
        $result         = $db->getByUserIds($userIds,$config['BASE_CASH']);

        //获取指定用户ID的合伙人帐户信息
        $partnerDb      = new PartnerDb();
        $partnerUserList    = $partnerDb->getUserIds($userIds);

        //获取指定用户中正在使用加息券的用户
        $model = new InviteRatesModel();

        $rateList = $model->getUsingRateByUserIds($userIds);
        $rateData = ToolArray::arrayToKey($rateList,'user_id');


        $partnerUser = ToolArray::arrayToIds($partnerUserList,'user_id');

        if($result){

            foreach($result as $val){

                $principalData = (array)$val;

                $userId = $principalData['user_id'];

                //总待收 活期 + 定期
                $totalPrincipal = $principalData['total_current_principal'] + $principalData['total_term_principal'];
                //加息券利率
                $bonusRate = isset($rateData[$userId]) ? $rateData[$userId]['rate'] : 0;

                //用户基准利率 + 加息利率 * 有效邀请人数 + 加息券利率
                $rate = $config['BASE_RATE'] + $principalData['invite_total'] * $config['ADD_RATE'] + $bonusRate;
                //计息利率
                $calRate = min($rate,$config['MAX_RATE']);
                //计算利息
                $interest = $this->calInterest($totalPrincipal,$calRate / 100.0);

                try{

                    self::beginTransaction();

                    //合伙人帐户存在
                    if(in_array($userId,$partnerUser)){

                        //更新用户信息
                        $partnerModel->incCash($userId, $interest, $totalPrincipal,$principalData['total_num'],$calRate);
                    }else{

                        //添加合伙人帐户
                        $data = [

                            'user_id'               => $userId,
                            'cash'                  => $interest,
                            'interest'              => $interest,
                            'yesterday_interest'    => $interest,
                            'yesterday_cash'        => $totalPrincipal,
                            'interest_time'         => ToolTime::dbNow(),
                            'invite_num'            => $principalData['total_num'],
                            'rate'                  => $calRate,
                        ];
                        $partnerModel->addUser($data);

                    }


                    //创建资金流水
                    if($interest > 0){

                        $fundData = [
                            'user_id'           => $userId,
                            'balance_change'    => $interest,
                            'source'            => ActivityFundHistoryDb::SOURCE_PARTNER,
                            'note'              => $totalPrincipal."|".$calRate
                        ];

                        $fundDb->doIncrease($fundData);
                    }


                    self::commit();

                }catch(\Exception $e){

                    self::rollback();

                    $log = [
                        'user_id' => $userId,
                        'principal' => $totalPrincipal,
                        'interest'  => $interest,
                        'rate'      => $rate,
                        'msg'       => $e->getMessage()
                    ];
                    Log::error(__METHOD__.'Error',[$log]);
                }

            }
        }
    }

    /**
     * 重置未计息数据
     *
     */
    public function resetInterest(){

        $db = new PartnerDb();
        $num = $db->getTodayInterestUserNum();
        if($num == 0){

            $receiveEmails = Config::get('email.monitor.accessToken');
            $model = new EmailModel();
            try{

                $title = '【Warning】合伙人计息';

                $msg = "今日计息合伙人用户数为0,请及时处理";

                $model->sendHtmlEmail($receiveEmails,$title,$msg);

            }catch (\Exception $e){

                Log::Error(__METHOD__.'Error',['msg' => $e->getMessage()]);

            }
        }else{

            $config = $this->getInviteConfig();

            $baseRate = $config['BASE_RATE'];

            $db->resetInterest($baseRate);
        }
    }

    /**
     * @return mixed
     * @desc 佣金收益排行
     */
    public function getInterestCashSort($size=20){

        $cacheKey = 'I_P_C_S'.$size;

        $cacheData = Cache::get($cacheKey);

        //如果存在缓存数据,则直接返回
        if( $cacheData && json_decode($cacheData, true) ){

            return json_decode($cacheData, true);

        }

        //如果用户未有计息记录,则返回空
        $partnerDb = new PartnerDb();

        $list = $partnerDb::getInterestCashSort($size);

        $userIds = [];

        if( !empty($list) ){

            $userIds = ToolArray::arrayToIds($list, 'user_id');

            $userList = UserModel::getUserListByIds(implode(',', $userIds));

            $userList = ToolArray::arrayToKey($userList);

            foreach( $list as $key => $item ){

                if( isset($userList[$item['user_id']]) ){

                    $list[$key]['phone'] = ToolStr::hidePhone($userList[$item['user_id']]['phone']);

                }

            }

        }

        Cache::put($cacheKey, json_encode($list), 60);

        return $list;

    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户信息
     */
    public static function getUserInfo($userId){

        $cacheKey = $userId.'_I_L_'.ToolTime::dbDate();

        $cacheData = Cache::get($cacheKey);

        //如果存在缓存数据,则直接返回
        if( $cacheData && json_decode($cacheData, true) ){

            return json_decode($cacheData, true);

        }

        $userInfo = UserModel::getCoreApiUserInfo($userId);

        Cache::put($cacheKey, json_encode($userInfo), 24 * 60);

        return $userInfo;

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 通过userId 获取邀请人的列表
     */
    public function getInviteListByUserId($userId, $page=1, $size=20){

        $cacheKey = $userId.'_INVITE';

        $cacheData = Cache::get($cacheKey);

        //如果存在缓存数据,则直接返回
        if( $cacheData && json_decode($cacheData, true) ){

            //return json_decode($cacheData, true);

        }

        $db = new PartnerPrincipalDb();

        $list = $db->getListByUserId($userId, $page, $size);

        if( !empty($list) ){

            $otherUserIds = ToolArray::arrayToIds($list, 'invited_user_id');

            $otherUserIds = implode(',', $otherUserIds);

            $userList = UserModel::getUserListByIds($otherUserIds);

            if( !empty($userList) ){

                $userList = ToolArray::arrayToKey($userList);

            }

            foreach( $list as $key => $item ){

                if( isset($userList[$item['invited_user_id']]) ){

                    $userList[$item['invited_user_id']]['real_name']    =   trim($userList[$item['invited_user_id']]['real_name']);

                    $list[$key]['real_name'] = !empty($userList[$item['invited_user_id']]['real_name']) ? substr($userList[$item['invited_user_id']]['real_name'], 0, 3).'*' : '';

                    $list[$key]['phone'] = ToolStr::hidePhone($userList[$item['invited_user_id']]['phone']);

                    $list[$key]['register_at'] = date('Y.m.d', strtotime($userList[$item['invited_user_id']]['created_at']));

                }

            }

            Cache::put($cacheKey, json_encode($list), 120);

        }

        return $list;

    }

    public function getInviteByUserId($userId){

        $db = new InviteDb();
        return $db->getInviteListByUserId($userId);
    }

    /**
     * @param $userIds
     * @param $allUserIds
     * 获取被邀请人待收明细
     */
    public function getPartnerPrincipal($allUserIds,$list,$config){

        $allUserIds = implode(',',$allUserIds);

        $result = UserModel::getPartnerPrincipal($config['BASE_CASH'],$allUserIds);
        $baseRate = $config['BASE_RATE'];
        if($result){

            $return = $result['list'];
            foreach ($list as $userId => $val){
                if(isset($return[$userId])){
                    $val['refund_cash'] = $return[$userId];
                }
                $list[$userId] = $val;
            }

            $rate = min(($config['ADD_RATE'] * $result['inviteNum'] + $baseRate),$config['MAX_RATE']);
        }else{

            $rate = $baseRate;
        }

        return [
            'list'      => $list,
            'principal' => isset($result['principal']) ? $result['principal'] : 0,
            'rate'      => $rate
        ];
    }

    /**
     *
     * 添加合伙人统计数据
     */
    public function createFundStatistics(){

        $partnerDb = new PartnerDb();

        $result = $partnerDb->getStatics();

        $yesterday = ToolTime::getDateBeforeCurrent();

        $result['date'] = $yesterday;

        $model = new DbKvdbModel();

        $key = 'PARTNER_STATISTICS';

        try{

            $model->addData($key,$result);

        }catch (\Exception $e){

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage()]);
        }
    }

}
