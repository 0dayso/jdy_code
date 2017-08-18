<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/22
 * Time: 上午10:38
 */

namespace App\Http\Logics\JdyDataApi;

use Illuminate\Support\Facades\App;

use Log;

use Event;

use App\Http\Models\User\UserRegisterModel;

use App\Lang\LangModel;

use App\Http\Logics\RequestSourceLogic;

use App\Http\Models\User\UserModel;

use App\Http\Models\Bank\CardModel;

use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;

use App\Http\Dbs\Identity\CardDb;
/**
 * todo 该文件夹下 为九斗鱼数据对接 用 测试后移除
 *
 * Class RegisterLogic
 * @package App\Http\Logics\JdyDataApi
 */
class RegisterLogic extends \App\Http\Logics\User\RegisterLogic
{
    /**
     * 注册
     * @param array $data
     * @return array
     */
    public function doRegister($data = []){
        try {
            $userRegisterModel         = new UserRegisterModel;

            $data['request_source']    = RequestSourceLogic::getSource();
            // 必填字段验证 [手机、密码、手机验证码、请求来源]
            $userRegisterModel->validationFieldEmpty($data['phone'], LangModel::getLang('USER_REGISTER_FIELD_PHONE'));
            $userRegisterModel->validationFieldEmpty($data['password'], LangModel::getLang('USER_REGISTER_FIELD_PASSWORD'));
            //$userRegisterModel->validationFieldEmpty($data['phone_code'], LangModel::getLang('MODEL_USER_FIELD_PHONE_CODE'));
            $userRegisterModel->validationFieldEmpty($data['request_source'], LangModel::getLang('USER_REGISTER_FIELD_REQUEST_FROM'));

            // 来源验证
            $data['request_source'] = $userRegisterModel->validationRequestFrom($data['request_source']);

            // 验证手机号 有效性
            UserModel::validationPhone($data['phone']);

            // 验证密码 有效性[不能使纯数字或字母, 长度、是否与交易密码相同]
            // PasswordModel::validationPassword($data['password']);

            // 验证手机验证码 有效性
            //$userRegisterModel->validationPhoneCode($data['phone'], $data['phone_code']);

            // 核心接口验证[注册过的 和 锁定的]
            $userInfo                   = UserModel::getCoreApiBaseUserInfo($data['phone']);

            $userRegisterModel->validationCoreUserInfo($userInfo);

            //创建用户
            $coreApi['phone']    = $data['phone'];
            $coreApi['password'] = $data['password'];
            //$coreApi['password'] = PasswordModel::encryptionPassword($data['password']);
            $coreApi['id']       = isset($data['id']) ? $data['id'] : null;
            $data['coreApiData']        = $userRegisterModel->doCoreApiRegister($coreApi);

        }catch (\Exception $e){
            $data['msg']     = $e->getMessage();
            $data['code']    = $e->getCode();
            $data['userInfo']= isset($userInfo) ? $userInfo : null;

            Log::error(__METHOD__ . 'Error', $data);

            return self::callError($e->getMessage());
        }

        //创建成功 [记录附加信息、邀请关系、活动]
        Event::fire(new \App\Events\User\RegisterSuccessEvent(
            ['data' => $data]
        ));

        return self::callSuccess($data, '创建用户成功');
    }

    /**
     * @param $userId
     * @param $name
     * @param $cardNo
     * @param $idCard
     * 用户实名+绑卡
     */
    public function verify($userId,$name,$cardNo,$idCard,$from){


        $log = [
            'user_id'   => $userId,
            'name'      => $name,
            'card_no'   => $cardNo,
            'id_card'   => $idCard,
            'from'      => $from
        ];

        try{

            //身份证号验证
            //ValidateModel::isIdCard($idCard);
            //姓名格式判断
            //ValidateModel::isName($name);
            //银行卡号格式判断
            //ValidateModel::isBankCard($cardNo);

            //融宝储蓄卡鉴权接口
            //CardModel::checkUserBankCard($cardNo,$name,$idCard);

            //获取银行卡的相关信息
            $bankId = CardModel::getCardInfo($cardNo);

            //判断是否有支持该卡的银行
            //CardModel::checkInvalidPayTypeBankId($bankId);

            //调用核心进行实名+绑卡操作
            UserModel::doVerify($userId,$name,$cardNo,$bankId,$idCard);

            //记录成功日志
            $identityDb = new CardDb();
            $identityDb->addRecored($name,$idCard,$from);

            Log::info(__METHOD__.'Success',$log);

        }catch (\Exception $e){

            Log::error(__METHOD__.'Error',['log'=> $log, 'error' => $e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();

    }

    /**
     *
     * @param array $data
     * @return array
     */
    public static function modifyPhone($data = [])
    {

        $request          = app('request');
        $oldPhone         = $request->input('old_phone');
        try {

            $return = CoreApiUserModel::doModifyPhone($oldPhone, $data['phone']);

        } catch (\Exception $e) {
            $attributes['data'] = $data;
            $attributes['msg'] = $e->getMessage();
            $attributes['code'] = $e->getCode();
            Log::error(__METHOD__ . 'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess($return);

    }
}