<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/8/31
 * Time: 下午2:28
 */

namespace App\Http\Logics\ThirdApi;

use App\Exceptions\Activity\YiMafuException;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Dbs\Ticket\CheckingTicketDb;
use App\Http\Dbs\User\UserInfoDb;
use App\Http\Dbs\Weixin\UserLinkWechatDb;

use App\Http\Logics\Logic;

use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Logics\Weixin\Module\MessageLogic;
use App\Http\Logics\Weixin\UserLogic;
use App\Http\Models\Activity\ActivityFundHistoryModel;
use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;

use App\Http\Models\Common\CoreApi\UserModel;

use App\Http\Models\Common\ServiceApi\SmsModel;

use App\Http\Models\User\UserModel as modelUser;

use App\Lang\LangModel;

use App\Http\Models\Common\HttpQuery;

use App\Http\Models\Ticket\CheckingTicketModel;

use App\Jobs\Activity\addAmountJob;

use App\Tools\ToolEnv;
use Log;

use Config;

class JyfLogic extends Logic
{

    const JY_API_URL = '/checkout/user/addAmount';//增加余额地址
    const JY_APP_KEY = '086d246764cd4b3db267896017f5af61';//加密秘钥  同 普付宝：MCH_KEY
    const JY_CALLBACK_URL = '/checkout/index';//授权回调地址
    const JY_EDMX_URL = '/checkout/user/amount/log/';//额度明细

    /**
     * 通过openid 获取用户是否为九斗鱼有效用户
     *
     * @param null $openId
     * @return bool
     */
    public static function getUserInfoByOpenId($openId = null)
    {
        try {
            if (empty($openId))
                return self::callError('openId 为空');

            $linkInfo = UserLinkWechatDb::getUserInfo($openId);

            if (empty($linkInfo['user_id'])) {
                return self::callSuccess(['user_id' => '']);
            }

            $userInfo = CoreApiUserModel::getCoreApiUserInfo($linkInfo['user_id']);

            if (!empty($userInfo) && $userInfo['status_code'] == 200) {
                return self::callSuccess(['user_id' => md5($openId)]);
            } else {
                return self::callSuccess(['user_id' => '']);
            }

        } catch (\Exception $e) {
            $data['msg'] = $e->getMessage();
            $data['code'] = $e->getCode();
            $data['openid'] = $openId;
            Log::debug(__METHOD__, $data);
            return self::callError('未知错误');
        }

    }

    /**
     * 获取可以增加随机立减额度的openId【投资大于100、关注公众号、绑定账号】
     *
     * @param array $param
     * @return bool
     */
    public static function getCanAddAmountOpenId($param = [])
    {
        if (empty($param['user_id']) || empty($param['original_cash'])) {
            return false;
        }
        //投资大于 100
        if ($param['original_cash'] < 100) {
            return false;
        }
        //获取用户与微信绑定关系
        $linkRecord = UserLinkWechatDb::getUserInfoByUserId($param['user_id']);
        if (empty($linkRecord)) {
            return false;
        }
        //绑定并关注
        if ($linkRecord['is_binding'] == 1 && $linkRecord['is_subscribe'] == 1) {
            return $linkRecord['openid'];
        }
        return false;
    }

    /**
     * 请求给用户加钱
     *
     * @param null $openId
     * @return mixed
     */
    public static function RequestAddAmount($openId = null)
    {
        try {
            $url    = Config::get('ymf.url') . self::JY_API_URL;
            $return = HttpQuery::JyPost($url, ['open_id' => $openId]);
            return $return;
        } catch (\Exception $e) {
            Log::info(__METHOD__ . 'openId:' . $openId, [$e->getCode(), $e->getMessage(), $e->getLine()]);
        }
    }


    /**
     * 获取资产中心消费额度图标
     *
     * @param int $userId
     * @return array
     */
    public static function getYmfMenu($userId = 0)
    {
        $menu = [];
        if (Config::get('ymf.open')) {
            $userInfoDb = new UserInfoDb;
            $userInfo = $userInfoDb->getByUserId($userId);
            if (!empty($userInfo['third_icon_code'])) {
                $thirdIconCode = $userInfo['third_icon_code'];
                $linkRecord = UserLinkWechatDb::getUserInfoByUserId($userId);
                if(!empty($linkRecord['openid'])) {
                    $menu = [
                        "position_num" => 3,
                        "title" => "消费额度",
                        "share_desc" => "消费额度",
                        "picture" => "/static/weixin/images/wap2/wap2-asset--third-icon-" . $thirdIconCode . ".png",
                        "location_url" => Config::get('ymf.url') . self::JY_EDMX_URL .$linkRecord['openid'],
                    ];
                }
            }
        }
        return $menu;
    }

    /**
     * 获取openid
     *
     * @param string $from
     * @return mixed
     */
    public static function getOpenId($from = '', $mchId = '')
    {
        $subWeixinLoginUrl = 'wechat/commonCallback/' . $from . '/' . $mchId;
        return UserLogic::wechatAuthorize('snsapi_base', $subWeixinLoginUrl);
    }


    /**
     * 给用户余额加钱
     *
     * @param array $param
     * @return array
     */
    public static function balanceAdd($param = [])
    {
        try {
            $phone           = $param['phone'];
            $note            = $param['reason'];
            $cash            = $param['coupon_total_fee'];
            $param['ticket'] = 'h' . $param['ticket'];
            
            Log::info(__METHOD__, $param);
            //检票
            $ticket = ['uuid'=> $param['ticket'], 'from_code'=> CheckingTicketDb::FROM_CODE_YMF];
            Log::info(__METHOD__ .'ticket :', [$ticket]);

            $ticketCount = CheckingTicketDb::where($ticket)->count();
            if($ticketCount > 0){
                return self::callSuccess();
            }

            self::beginTransaction();

            $userInfo = UserModel::getBaseUserInfo($phone);
            if(empty($userInfo)){
                throw new \Exception('找不到该用户');
            }
            // 活动记录
            $fundData = [
                'user_id'           => $userInfo['id'],
                'balance_change'    => $cash,
                'source'            => ActivityFundHistoryDb::SOURCE_YIMAFU,
                'note'              => $note,
            ];
            $activityFundHistoryModel = new ActivityFundHistoryModel();
            $activityFundHistoryModel->doIncrease($fundData);

            // 票据处理
            $saveReturn = CheckingTicketModel::save($ticket);
            Log::info(__METHOD__ .'票据保存结果: ', [$saveReturn]);

            $ticketCount = CheckingTicketDb::where($ticket)->count();
            if($ticketCount > 1){
                throw new YiMafuException(YiMafuException::SAVE_ERROR, YiMafuException::SAVE_ERROR_MESSAGE);
            }

            $res = \Queue::pushOn('addAmountJob', new addAmountJob($userInfo['id'], $cash, $userInfo['trading_password'], $note, $param['ticket']));
            if( !$res ){
                Log::Error('addAmountJobError', $param);
                throw new \Exception('增加余额队列添加失败');
            }
            self::commit();

            return self::callSuccess();

        }catch (YiMafuException $e){
            Log::info(__METHOD__, '已经处理过');
            return self::callSuccess();

        } catch (\Exception $e) {

            self::rollback();

            Log::debug(__METHOD__, [$e]);

            $data['msg']   = $e->getMessage();
            $data['code']  = $e->getCode();
            $data['param'] = $param;

            Log::debug(__METHOD__, $data);

            return self::callError('未知错误');
        }
    }

    /**
     * 结算对账数据查询
     *
     * @param array $param
     * @return array
     */
    public static function getYmfReconciliation($param = []){

        try {
            $date                     = $param['date'];
            $list                     = ActivityFundHistoryModel::getListsByDate($date);
            $user_ids                 = [];
            if(!empty($list)) {
                foreach ($list as $record) {
                    $user_ids[] = $record['user_id'];
                }

                $partnerLogic = new PartnerLogic;
                if (!empty($user_ids)) {
                    $userData = $partnerLogic->getUserPhoneByUserIds($user_ids, true);
                    if (empty($userData)) {
                        throw new \Exception('查询不到用户id对应的手机号信息等');
                    }
                }
                foreach($list as $key => $UserRecord){
                    if(empty($userData[$UserRecord['user_id']])){
                        throw new \Exception('查询不到用户id对应的手机号信息等');
                    }
                    $list[$key]['phone'] = $userData[$UserRecord['user_id']]['phone'];
                }
            }

            return self::callSuccess($list);

        }catch (\Exception $e){
            $data['msg']   = $e->getMessage();
            $data['code']  = $e->getCode();
            $data['param'] = $param;

            Log::debug(__METHOD__, $data);
            return self::callError('未知错误');
        }
    }

    /**
     * 发送模板消息
     *
     * @param array $param
     * @return bool
     */
    public static function sendTemplateMessage($param = []){
        Log::info(__METHOD__, [$param]);
        if(empty($param['phone'])){
            return false;
        }
        $phone    = $param['phone'];
        $userInfo = UserModel::getBaseUserInfo($phone);
        if(empty($userInfo)){
            Log::info(__METHOD__, ['找不到该用户']);
            return false;
        }

        $linkRecord = UserLinkWechatDb::getUserInfoByUserId($userInfo['id']);
        if(empty($linkRecord['openid'])) {
            Log::info(__METHOD__, ['该用户关联的openid未找到', $userInfo]);
            return false;
        }

        $data['openId'] = $linkRecord['openid'];

        $data['type']   = empty($param['type']) ? MessageLogic::TEMPLATE_MESSAGE_DEFAULT : $param['type'];

        $data['url']    = empty($param['url']) ? '' : $param['url'];

        $data['data']   = empty($param['data']) ? '' : $param['data'];

        return MessageLogic::sendTemplateMessage($data);
    }


    /**
     * 注册成功 发送密码短信
     *
     * @param $phone
     * @param string $password
     * @return array
     */
    public static function sendRegisterSucceedSms($phone, $password = ''){
        try{
            // 验证手机号 有效性
            modelUser::validationPhone($phone);

            $message = LangModel::getLang('PHONE_REGISTERED_PASSWORD');
            $message = sprintf($message, $password);
            \Log::info('一码付注册成功 发送通知 ' . $message);

            if(ToolEnv::getAppEnv() === 'production') {
                SmsModel::sendNotice($phone, $message);
            }

        }catch (\Exception $e){
            $data['phone']   = $phone;
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([], '发送成功');

    }

}