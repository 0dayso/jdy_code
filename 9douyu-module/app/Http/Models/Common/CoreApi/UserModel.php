<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:40
 * Desc: 核心用户操作调用model
 */

namespace App\Http\Models\Common\CoreApi;

use App\Http\Models\Common\CoreApiModel;
use App\Lang\LangModel;
use Config;
use App\Tools\ToolMoney;
use App\Http\Models\Common\HttpQuery;


class UserModel extends CoreApiModel{


    /**
     * @param $phone        手机号     必填
     * @return array
     * 根据手机号获取用户信息
     */
    public static function getBaseUserInfo($phone){

        $api  = Config::get('coreApi.moduleUser.getCoreApiBaseUserInfo');

        $params = [
            'phone'   => $phone
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $return['data']['balance'] = ToolMoney::formatDbCashAdd($return['data']['balance']);

            return $return['data'];

        }else{

            return [];
        }

    }


    /**
     * @param $userId        用户ID     必填
     * @return array
     * 根据用户ID获取用户信息
     */
    public static function getCoreApiUserInfo($userId){

        $api  = Config::get('coreApi.moduleUser.getCoreApiUserInfo');

        $params = [
            'user_id'   => $userId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $return['data']['balance'] = ToolMoney::formatDbCashAdd($return['data']['balance']);

            return $return['data'];
        }else{

            return [];
        }

    }


    /**
     * @param $params        包括:未投资天数,最低余额,当前页数
     * @return array
     * 根据余额和未投资天数获取用户信息
     */
    public static function getNoInvestUser($params){

        $api  = Config::get('coreApi.moduleUser.getNoInvestUser');

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
        }else{

            return [];
        }

    }


    /**
     * @param $phone    手机号 必填
     * @param $password 密码  必填
     * @param null $realName 身份证 名
     * @param null $identityCard 身份证 号
     * @return array
     * 用户注册
     */
    public static function doCoreApiRegister($phone, $password, $realName = null, $identityCard= null){

        $api  = Config::get('coreApi.moduleUser.doCoreApiRegister');

        $params = [
            'phone'         => $phone,
            'password'      => $password,
            'real_name'     => $realName,
            'identity_card' => $identityCard
        ];
        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){
            return $return['data'];
        }else{
            return [];
        }
    }


    /**
     * @param $userId   用户ID    必填
     * @return array
     * 根据UID获取用户零钱计划定期收益
     */
    public static function getCoreApiUserInfoAccount($userId){

        $api  = Config::get('coreApi.moduleUser.getCoreApiUserInfoAccount');

        $params = [
            'user_id'     => $userId,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            //零钱计划资金
            $data['current']['cash']                 = ToolMoney::formatDbCashAdd($data['current']['cash']);
            $data['current']['interest']             = ToolMoney::formatDbCashAdd($data['current']['interest']);
            $data['current']['yesterday_interest']   = ToolMoney::formatDbCashAdd($data['current']['yesterday_interest']);

            $interestList = $data['current']['seven_interest'];
            //零钱计划近一周收益金额处理
            if(!empty($interestList)){

                foreach ($interestList as $k=>$val){
                    $interestList[$k]['interest']   = ToolMoney::formatDbCashAdd($val['interest']);
                    $interestList[$k]['principal']  = ToolMoney::formatDbCashAdd($val['principal']);
                }

                $data['current']['seven_interest'] = $interestList;
            }

            //定期收益金额处理
            $data['project']['refund_interest']     = ToolMoney::formatDbCashAdd($data['project']['refund_interest']);
            $data['project']['refund_principal']    = ToolMoney::formatDbCashAdd($data['project']['refund_principal']);

            $productLine = $data['project']['product_line'];

            if( !empty($productLine) ){

                foreach($productLine as $key => $value){

                    $productLine[$key]['interest'] = ToolMoney::formatDbCashAdd($value['interest']);
                    $productLine[$key]['principal'] = ToolMoney::formatDbCashAdd($value['principal']);

                }

            }

            $data['project']['product_line'] = $productLine;

            return $data;

        }else{
            return [];
        }

    }


    /**
     * @param $userIds 这个是字符串 String   多个用户ID,以,分隔    必填
     * @return array
     * 获取多个用户信息
     */
    public static function getUserListByIds($userIds){

        $api  = Config::get('coreApi.moduleUser.getUserListByIds');

        $params = [
            'user_ids'     => $userIds,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $userList = $return['data'];

            foreach ($userList as $k=>$val){

                $userList[$k]['balance'] = ToolMoney::formatDbCashAdd($val['balance']);
            }

            return $userList;

        }else{
            return [];
        }

    }


    /**
     * @param $userId   用户ID    必填
     * @return array
     * 用户激活
     */
    public static function doActivate($userId){

        $api  = Config::get('coreApi.moduleUser.doActivate');

        $params = [
            'user_id'     => $userId,
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $userId       用户ID    必填
     * @param $name         姓名      必填
     * @param $cardNo       银行卡号    必填
     * @param $bankId       银行ID      必填
     * @param $idCard       身份证号      必填
     * @return array
     * 实名 + 绑卡
     */
    public static function doVerify($userId,$name,$cardNo,$bankId,$idCard){

        $api  = Config::get('coreApi.moduleUser.doVerify');

        $params = [
            'user_id'     => $userId,
            'name'        => $name,
            'card_no'     => $cardNo,
            'bank_id'     => $bankId,
            'id_card'     => $idCard
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $userId       用户ID    必填
     * @param $name         姓名      必填
     * @param $cardNo       银行卡号    必填
     * @param $bankId       银行ID      必填
     * @param $idCard       身份证号      必填
     * @param $tradingPassword 交易密码 必填
     * @return array
     * 实名 + 绑卡 + 交易密码
     */
    public static function doVerifyTradingPassword($userId,$name,$cardNo,$bankId,$idCard, $tradingPassword){

        $api  = Config::get('coreApi.moduleUser.doVerifyTradingPassword');

        $params = [
            'user_id'     => $userId,
            'name'        => $name,
            'card_no'     => $cardNo,
            'bank_id'     => $bankId,
            'id_card'     => $idCard,
            'trading_password' => $tradingPassword,
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $userId       用户ID    必填
     * @param $name         姓名      必填
     * @param $idCard       身份证号      必填
     * @return array
     * 实名
     */
    public static function doRealName($userId,$name,$idCard){

        $api  = Config::get('coreApi.moduleUser.doRealName');

        $params = [
            'user_id'     => $userId,
            'name'        => $name,
            'id_card'     => $idCard
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $userId       用户ID        必填
     * @param $investId     投资ID        必填
     * @return array
     * 获取用户投资项目的回款计划
     */
    public static function getRefundDetail($userId,$investId){

        $api  = Config::get('coreApi.moduleUser.getRefundDetail');

        $params = [
            'user_id'     => $userId,
            'invest_id'   => $investId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];
            
        }else{

            return [];

        }


    }


    /**
     * @param $userId   用户ID    必填
     * @return array
     * 获取用户回款中的项目列表
     */
    public static function getRefundingList($userId)
    {

        $api = Config::get('coreApi.moduleUser.getRefundingList');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            foreach ($data as $k => $val) {

                $data[$k]['principal']  = ToolMoney::formatDbCashAdd($val['principal']);
                $data[$k]['total']      = ToolMoney::formatDbCashAdd($val['total']);
                $data[$k]['next_cash']  = ToolMoney::formatDbCashAdd($val['next_cash']);
            }


            return $data;
        } else {
            return [];
        }

    }


    /**
     * @param $userId       用户ID    必填
     * @return array
     * 获取用户已回款的项目列表
     */
    public static function getRefundedList($userId)
    {

        $api = Config::get('coreApi.moduleUser.getRefundedList');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            foreach ($data as $k => $val) {

                $data[$k]['principal']  = ToolMoney::formatDbCashAdd($val['principal']);
                $data[$k]['total']      = ToolMoney::formatDbCashAdd($val['total']);
            }

            return $data;
        } else {
            return [];
        }

    }

    /**
     * @param $userId       用户ID    必填
     * @return array
     * 获取用户投资中的项目列表
     */
    public static function getInvestingList($userId)
    {

        $api = Config::get('coreApi.moduleUser.getInvestingList');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            foreach ($data as $k => $val) {

                $data[$k]['invested_amount'] = ToolMoney::formatDbCashAdd($val['invested_amount']);
                $data[$k]['total_amount']    = ToolMoney::formatDbCashAdd($val['total_amount']);
            }

            return $data;
        } else {
            return [];
        }

    }

    /**
     * @param $userId
     * @param $password
     * @return bool
     * @throws \Exception
     * @desc 修改密码
     */
    public static function doPassword($userId,$password,$type='password'){

        $api = '';

        if($type=='password'){
            $api = Config::get('coreApi.moduleUser.doPassword');
        }

        if($type=='tradingPassword'){
            $api = Config::get('coreApi.moduleUser.doTradingPassword');
        }

        if(empty($api)){
            throw new \Exception(LangModel::getLang('MODEL_USER_PASSWORD_TYPE_ERROR'));
        }

        $return = HttpQuery::corePost($api, ['user_id'=>$userId,'password'=>$password]);

        if(!$return['status']){

            throw new \Exception($return['msg']);

        }

        return true;
    }


    /**
     * @param int $phone   手机号    必填
     * @param int $new_phone 新手机号
     * @return array
     * 变更手机号
     */
    public static function doModifyPhone($phone = 0, $new_phone = 0){

        $api  = Config::get('coreApi.moduleUser.doModifyPhone');

        $params = [
            'new_phone'     => $new_phone,
            'phone'         => $phone,
        ];


        $return = HttpQuery::corePost($api,$params);

        return $return;

    }

    /**
     * @param $userId
     * @param $cash
     * @param $tradePassword
     * @param $note
     * @return bool
     * @throws \Exception
     * @desc 增加账户余额
     */
    public static function doIncBalance($userId, $cash, $tradePassword, $note,$ticketId = '', $eventId=''){

        $api = Config::get('coreApi.moduleUser.doIncBalance');

        $params = [
            'user_id'        => $userId,
            'cash'           => $cash,
            'trade_password' => $tradePassword,
            'note'           => $note,
            'ticket_id'      => $ticketId,
            'event_id'       => $eventId
        ];

        $return = HttpQuery::corePost($api,$params);

        if(!$return['status']){

            throw new \Exception($return['msg']);

        }

        return $return['data'];

    }

    /**
     * @param $userId
     * @param $cash
     * @param $tradePassword
     * @param $note
     * @return bool
     * @throws \Exception
     * @desc 账户余额扣款
     */
    public static function doDelBalance($userId, $cash, $tradePassword, $note, $ticketId = '', $eventId=''){

        $api = Config::get('coreApi.moduleUser.doDelBalance');

        $params = [
            'user_id'        => $userId,
            'cash'           => $cash,
            'trade_password' => $tradePassword,
            'note'           => $note,
            'ticket_id'      => $ticketId,
            'event_id'       => $eventId
        ];

        $return = HttpQuery::corePost($api,$params);

        if(!$return['status']){

            throw new \Exception($return['msg']);

        }

        return $return['data'];

    }

    /**
     * @desc 获取用户的信息列表[后台]
     * @param $param
     * @param $page
     * @param $size
     * @return null|void
     */
    public static function getUserListAll($page,$size, $param){

        $api = Config::get('coreApi.moduleUser.getUserListAll');

        $params = array_merge(['page'=>$page,'size'=>$size], $param);

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }

    /**
     * @desc 获取用户统计数据
     * @author lgh
     * @param $param
     * @return null|void
     */
    public static function getUserStatistics($param){
        $api = Config::get('coreApi.moduleUser.getUserStatistics');

        $return = HttpQuery::corePost($api,$param);

        return $return;
    }
     /**
     * @param $start
     * @param $end
     * @return mixed
     * @desc 某个时间段内的注册总数
     */
    public function getUserAmountByDate($start,$end){

        $api    = Config::get('coreApi.moduleUser.getUserAmountByDate');

        $params = ['start'=>$start,'end'=>$end];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }

    /**
     * @return null|void
     * @desc 获取总注册数
     */
    public function getUserTotal(){

        $api    = Config::get('coreApi.moduleUser.getUserTotal');

        $return = HttpQuery::corePost($api);

        return $return;
    }

    /**
     * @desc 锁定账户信息
     * @author lgh
     * @param $userId
     * @param $status
     * @return null|void
     */
    public function doUserStatusBlock($userId, $status){

        $api    = Config::get('coreApi.moduleUser.doStatusBlock');

        $params = [
            'user_id'=>$userId,
            'status'=>$status,
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;
    }

    /**
     * @desc 获取当天生日的用户
     * @return null|void
     */
    public static function getBirthdayUser(){

        $api    = Config::get('coreApi.moduleUser.getBirthdayUser');

        $return = HttpQuery::corePost($api);

        return $return;
    }

    /**
     * @desc 通过多个身份证号(逗号隔开)获取用户信息
     * @param $identityCards
     * @return null|void
     */
    public function getUserByIdCards($identityCards){

        $api    = Config::get('coreApi.moduleUser.getUserByIdCards');

        $params = [
            'identity_cards'=>$identityCards,
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;
    }

    /**
     * @desc 通过多个手机号(逗号隔开)获取用户信息
     * @param $phones
     * @return null|void
     */
    public function getUserByPhones($phones){

        $api    = Config::get('coreApi.moduleUser.getUserByPhones');

        $params = [
            'phones'=>$phones,
        ];
        $return = HttpQuery::corePost($api, $params);

        return $return;
    }

    /**
     * @param $userId
     * @return null|void
     * @desc 账户冻结
     */
    public function doUserFrozen($userId){

        $api    = Config::get('coreApi.moduleUser.doUserFrozen');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;

    }

    /**
     * @param $userId
     * @return null|void
     * @desc 账户解冻
     */
    public function doUserUnFrozen($userId){

        $api    = Config::get('coreApi.moduleUser.doUserUnFrozen');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        return $return;

    }

    /**
     * @desc    账户资金统计-从核心库获取数据
     * 获取信息
     *  活期投资金额
     *  用户余额
     *  定期再投金额
     *  留存金额
     *  今日投资
     *  今日充值
     *  今日体现
     *  今日回款
     *  getCoreApiFundStatistics
     **/
    public static function getCoreApiFundStatistics(){

        $api    = Config::get('coreApi.moduleUser.getFundStatisticsWithDay');

        $return = HttpQuery::corePost($api);

        if ($return['status'] && !empty($return['data'])) {

            $data = $return['data'];

            return $data;

        } else {

            return [];

        }

    }
    /**
     * @param $userIds
     * @param $allUserIds
     * @return array
     * 被邀请人待收明细
     */
    public static function getPartnerPrincipal($userIds,$allUserIds){

        $api    = Config::get('coreApi.moduleUser.getPartnerPrincipal');

        $params = [
            'user_ids'      =>  $userIds,
            'all_user_ids'  => $allUserIds
        ];

        $return = HttpQuery::corePost($api, $params);

        if($return['status']){

            return $return['data'];
        }else{

            return [];
        }

    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * 获取投资记录列表
     */
    public static function getInvestListByUserId($userId, $page, $size)
    {

        $api = Config::get('coreApi.moduleUser.getInvestListByUserId');

        $params = [
            'user_id' => $userId,
            'page'    => $page,
            'size'    => $size,
        ];

        $return = HttpQuery::corePost($api, $params);

        if($return['status']){

            return $return['data'];
        }else{

            return [];
        }

    }

    /**
     * @param $userId
     * @return array
     * 获取投资记录列表
     */
    public static function getUserInvestDataByUserId($userId)
    {

        $api = Config::get('coreApi.moduleUser.getUserInvestDataByUserId');

        $params = [
            'user_id' => $userId,
        ];

        $return = HttpQuery::corePost($api, $params);

        if($return['status']){

            return $return['data'];
        }else{

            return [];
        }

    }

    /**
     * @param $userId
     * @param $score
     * @return bool
     * @throws \Exception
     * @desc 修改用户风险承受能力测评分数
     */
    public static function doAssessmentScore($userId,$score){

        $api = Config::get('coreApi.moduleUser.doAssessmentScore');

        if(empty($api)){
            throw new \Exception(LangModel::getLang('MODEL_USER_PASSWORD_TYPE_ERROR'));
        }

        $return = HttpQuery::corePost($api, ['user_id'=>$userId,'assessment_score'=>$score]);

        if(!$return['status']){

            throw new \Exception($return['msg']);

        }

        return true;
    }

    /**
     * @desc    获取投资借款相关统计
     * @author  @linglu
     **/
    public static function getCoreApiInvestStat(){

        $api    = Config::get('coreApi.moduleUser.getCoreApiInvestStat');
        $return = HttpQuery::corePost($api);
        if ($return['status'] && !empty($return['data'])) {
            $data   = $return['data'];
            return  $data;
        } else {
            return  [];
        }
    }

}