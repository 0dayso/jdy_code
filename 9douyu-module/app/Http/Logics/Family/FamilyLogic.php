<?php
/**
 * User: caelyn
 * Date: 16/6/12
 * Time: 14:56
 */

namespace App\Http\Logics\Family;

use App\Http\Dbs\Notice\NoticeDb;
use App\Http\Logics\Logic;

use App\Http\Logics\Notice\NoticeLogic;
use App\Http\Logics\User\RegisterLogic;

use App\Http\Logics\User\LoginLogic;

use App\Http\Logics\User\UserLogic;
use App\Http\Models\SystemConfig\SystemConfigModel;

use App\Http\Models\Common\CoreApi\BankCardModel;

use App\Http\Models\User\UserModel;

use App\Http\Models\Family\FamilyModel;

use App\Http\Models\Common\PasswordModel;

use App\Http\Models\Common\SmsModel;

use App\Http\Models\User\UserInfoModel;

use Illuminate\Support\Facades\Request;

use App\Http\Dbs\Family\FamilyDb;

use App\Lang\LangModel;

use Session;
use Log;

class FamilyLogic extends Logic{

    /*
     * 是否已经拥有授权账户（可以管理别人的账户）
     */
    public static function hasAuthAccount($myUid, $familyId = false) {

        $db = new FamilyDb();

        return $db->hasAuthAccount($myUid, $familyId);
    }

    /**
     * [获取家庭账户角色标签]
     * @return [array]
     */
    public function getHotAccount(){

    	$val = SystemConfigModel::getConfig('FAMILY_ACCOUNT_TAG.HOT');

        $res = explode(',',$val);

        return $res;
    }

    /**
     * [添加家庭账户]
     * @param  [int]     $myUserId 
     * @param  [string]  $phone 
     * @param  [string]  $familyRole 
     * @param  [string]  $client 
     * @return [array]   
     */
    public function addFamily($myUserId,$phone,$familyRole,$client){

        if(empty($phone) || empty($familyRole)){

            return self::callError();
        }
        //添加家庭账户user账号
        $result = $this->addFamilyAccount($myUserId,$phone,$familyRole,$client);

        if(!$result['status']){

            return self::callError($result['msg']);
        }

        $familyId = $result['data']['familyId'];

        //已实名直接添加授权关系
        $familyInfo = UserModel::getCoreApiUserInfo($familyId);
        $authStatus = UserLogic::getUserAuthStatus($familyInfo);

        if($authStatus['name_checked'] == 'on' && $myUserId>0 ){

            $result = $this->addFamilyRelation($myUserId,$familyId,$familyRole);

            if($result['status']){

                return self::callSuccess(['isBind'=>true]);
            }else{

                return self::callError($result['msg']);
            }

        } else {

            return self::callSuccess(['isBind'=>false]);
        }

    }

    /**
     * [添加家庭账户账号]
     * @param [int]     $myUserId   
     * @param [string]  $phone      
     * @param [string]  $familyRole 
     * @param [string]  $client     
     */
    public function addFamilyAccount($myUserId,$phone,$familyRole,$client){

        if(empty($phone) || empty($familyRole)){
            return self::callError();
        }

        $familyInfo = UserModel::getCoreApiBaseUserInfo($phone);

        try {
            if(empty($familyInfo)){

                $password   = substr(md5(time() . rand(10000000, 99999999)), 0, 20);

                $dbpassword = PasswordModel::encryptionPassword($password);

                $res        = UserModel::createUser($phone,$dbpassword);

                $familyId   = $res['id'];
                //加扩展表
                $data       = [
                    'userId'     => $familyId,
                    'ip'         => isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : Request::getClientIp(),
                    'source_code'=> $client,
                ];

                $userInfoModel = new UserInfoModel;

                $userInfoModel->create($data);
            } else {
                $familyId = $familyInfo['id'];
            }
            Session::put("FAMILY_ID", $familyId);

            return self::callSuccess(['familyId'=>$familyId]);

        } catch (\Exception $e) {

            return self::callError($e->getMessage());
        }
    }


    /**
     * [添加授权关系]
     * @param   [int]     $myUserId   
     * @param   [int]     $familyId   
     * @param   [string]  $familyRole 
     * @return  [array]   
     */
    public function addFamilyRelation($myUserId,$familyId,$familyRole){

        $userInfo   = UserModel::getCoreApiUserInfo($myUserId);
        $familyUserInfo = UserModel::getCoreApiUserInfo($familyId);
         //是否已存在绑定关系
        $FamilyModel = new FamilyModel();

        $bindInfo = $FamilyModel->getByFamilyId($familyId);

        if(!empty($bindInfo)){

            if($bindInfo['my_uid']==$myUserId){

                return self::callError(LangModel::getLang('ERROR_FAMILY_ADD_REPEAT'));
            }
            if($bindInfo['id']>0){

                return self::callError(LangModel::getLang('ERROR_FAMILY_AUTH_MORE'));
            }
        }
        
        //是否存在交叉授权关系
        $isAuthMore = $FamilyModel->isAuthMore($myUserId,$familyId);

        if($isAuthMore){
            return self::callError(LangModel::getLang('ERROR_FAMILY_MORE_AUTH'));
        }
        //已绑卡直接授权
        $res = $FamilyModel->auth($myUserId, $familyId, $familyRole);

        if($res){

            $msgTpl = NoticeLogic::getMsgTplByType(NoticeDb::TYPE_FAMILY);

            $msg = sprintf($msgTpl, $userInfo['phone']);

            //事件机制
            $param['notice'] = [
                'title'     => NoticeDb::TYPE_FAMILY,
                'user_id'   => $familyId,
                'message'   => $msg,
                'type'      => NoticeDb::TYPE_FAMILY
            ];

            $param['sms'] = [
                'phone' => $familyUserInfo['phone'],
                'family_role'   => $familyRole
            ];

            \Event::fire(new \App\Events\User\FamilyAuthSuccessEvent($param));

            return self::callSuccess();
        }

        return self::callError(LangModel::getLang('ERROR_FAMILY_ADD_FAIL'));
    }

     /**
     * [获取主账号下的家庭列表]
     * @param  [int] $myUid 
     * @return [array]        
     */
    public function getByMyUid($myUid) {

        $familyModel = new FamilyModel();

        $result  = $familyModel->getByMyUid($myUid);

        return $result;
    }

    /**
     * [clear session]
     */
    public static function unsetSession(){

        Session::forget('FAMILY_ID');

        Session::forget('FAMILY_ROLE');

        Session::forget('FAMILY_ROLE_SUCCESS');

    }

    /**
     * [清除邀请关系]
     */
    public static function clearInvite(){

        cookie("invite_id", null);
        
        cookie("invite_type", null);
        
        cookie("user_type", null);
        
        cookie("sourceId", null);    
    }

    /**
     * [获取渠道推广下载链接]
     * @param  [string] $channel 
     * @param  [string] $source 
     * @return [string]          
     */
    public static function downAppLink($channel,$source){

        $config             = SystemConfigModel::getConfig("APP_DOWNLOAD");

        if( $source == 'android' ){

            $channel_type   = cookie("channel_type");

            $channelType    = $channel_type?$channel_type:$channel;

            $androidConfig  = SystemConfigModel::getConfig("PUSH_ACTIVITY_ANDROID_APK");

            $downLink       = env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com').$config['ANDROID_APK'];

            if($androidConfig[$channelType]){

                $downLink   = env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com').$androidConfig[$channelType];
            }
        }elseif($source == 'ios' ){

            $downLink       = $config['IOS_IPA'];

        }else{

            $downLink       = "/zt/appdownload";
        }
        return $downLink;
    }

    /**
     * [推广页检测手机号]
     * @param  [string] $phone 
     * @return [array]        
     */
    public function checkUniquePhone($phone){

        try {

            UserModel::validationPhone($phone);

        } catch (\Exception $e) {

            return self::callError($e->getMessage());
        }
        
        $userInfo               = UserModel::getCoreApiBaseUserInfo($phone);

        Session::put('ACTIVATE_PHONE', $phone);

        Session::put('ACTIVATE_PHONE_STATUS', "success");

        if(empty($userInfo)) {
            $code       = SmsModel::getRandCode();

            SmsModel::setPhoneVerifyCode($code,$phone);

            $msg        = sprintf(LangModel::getLang('PHONE_VERIFY_CODE_ACTIVATE'),$code);

            $smsRes     = SmsModel::verifySms($phone,$msg); 

            if(!$smsRes['status']){

                Session::put('ACTIVATE_CODE_STATUS', "fail");

                return self::callError(LangModel::getLang('USER_REGISTER_SEND_REGISTER_SMS_ERROR'));
            }
        } else {
            //返回页面
            Session::put('ACTIVATE_PHONE_STATUS', "activate");

            return self::callError(LangModel::getLang('ERROR_PHONE_EXIST'));

        }
        return self::callSuccess();
    }

    /**
     * [家庭账户推广注册]
     * @param  [string] $phone
     * @param  [string] $password
     * @return [array]   
     */
    public function doRegister($phone,$password){

        $data   = [
            'phone'          => $phone,             // 手机号
            'password'       => $password,          // 密码
            'invite_id'      => cookie('invite_id'),               // 自媒体id 
            'user_type'      => cookie("user_type")                   // 自媒体类型
        ];
        
        //数据处理
        $registerLogic       = new RegisterLogic();
        $result              = $registerLogic->doRegister($data);

        //如果创建成功-》请求token -》pc 或 wap 登陆
        if($result['status']) {
 
            $LoginLogic     = new LoginLogic();

            $res = $LoginLogic->in(['username'=>$phone,'password'=>$password,'factor'=>'']);

            // 如果浏览器访问 写入 cookie
            if ($res['status']) {

                $LoginLogic->handleFrom($res['data']);
            }
            return self::callSuccess();
        }
        return self::callError($result['msg']);
    }

    /**
     * @desc 解绑家庭账户
     * @param $id
     * @return array
     */
    public function unbindFamily($id){
        $familyDb  = new FamilyDb();
        try{
            if(empty($id)){
                return self::callError('ID号为空');
            }
            $res = $familyDb->unbindFamily($id);

        }catch (\Exception $e) {
            return self::callError($e->getMessage());
        }
        return self::callSuccess($res, '解绑成功');
    }

}