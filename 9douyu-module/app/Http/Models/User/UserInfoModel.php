<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/10
 * Time: 上午11:09
 */
namespace App\Http\Models\User;

use App\Http\Models\Model;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Http\Dbs\User\UserInfoDb;

use App\Lang\LangModel;

use App\Http\Models\User\UserModel;

use App\Http\Logics\RequestSourceLogic;
use Illuminate\Support\Facades\Request;

/**
 * 用户扩展 model类
 * Class UserRegisterModel
 * @package App\Http\Models\User
 */
class UserInfoModel extends Model{


    public static $codeArr = [


    ];


    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_USER_INFO;


    /**
     * 用户风险承受能力测评选项分数
     *
     */
    public static $scoreArr = [
        'a' => 10,
        'b' => 8,
        'c' => 5,
        'd' => 3,
        'e' => 0,
    ];

    /**
     * @param int $userId
     * @return array
     * 通过用户Id获取用户扩展信息
     */
    public function getUserInfo($userId){

        $db = new UserInfoDb();
        $info = $db -> getByUserId($userId);

        return $info;

    }

    /**
     * 用户附加信息用户
     * @param $data
     */
    public function create($data){
        $userInfoDb = new UserInfoDb;
        $userInfoDb->register($data);
    }


    /**
     * 用户附加信息用户
     * @param $data
     */
    public function doCreate($data){
        $userInfoDb = new UserInfoDb;
        return $userInfoDb->register($data);
    }
    /**
     * 数字转36进制
     * @param $number
     * @return string
     */
    protected function _numberToBase36($number) {
        return base_convert($number, 10, 36);
    }

    /**
     * 36进制转数字
     * @param $base36Number
     * @return string
     */
    protected function _base36ToNumber($base36Number) {
        return base_convert($base36Number, 36, 10);
    }

    /**
     * 获取邀请码
     * 1234567 百万级用户id，取前3位转成36进制数，得到两位36进制数，后4位保持不变，得到6位邀请码 (36*36=1296 > 999)
     * @param $userId 用户id
     * @see method parseInviteCodeToUserId
     * @return string
     */
    function generateCode($userId) {
        $userIdStr          = str_pad((string)$userId, 7, '0', STR_PAD_LEFT);
        $needToConvertStr   = substr($userIdStr, 0, 3); //取3位转成36进制
        $leftStr            = substr($userIdStr, 3);
        $convertRes         = str_pad($this->_numberToBase36($needToConvertStr), 2, '0', STR_PAD_LEFT);
        return $convertRes. $leftStr;
    }

    /**
     * 解析邀请码得到用户id
     * 123456 6位邀请码，取前两位36进制数，转成3位10进制数，后4位保持不变，拼接得到用户id
     * @param $code 邀请码
     * @see method generateCode
     * @return int
     */
    function parseInviteCodeToUserId($code) {
        $needToConvertStr   = substr($code, 0, 2);    //取前两位36进制数转3位10进制数字
        $leftStr            = substr($code, 2);
        $convertRes         = str_pad($this->_base36ToNumber($needToConvertStr), 3, '0', STR_PAD_LEFT);
        return (int)($convertRes . $leftStr);
    }

    /**
     * @param $userId
     * @return string
     * 根据userId 生成邀请码，并返回邀请码
     */
    public function setUserCodeByUid( $userId ){

        $db = new UserInfoDb();

        $info = $db -> getByUserId($userId);

        //如果已经存在，则返回邀请码。
        if( !empty($info['invite_code']) ){

            return $info['invite_code'];

        }

        $inviteCode = $this->generateCode($userId);

        $data["invite_code"]    = $inviteCode;
        //如果没有邀请信息，则先更新邀请信息，生成该用户的邀请码,并添加邀请信息
        if(empty($info)){

            $data["ip"]         = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : Request::getClientIp();
            $data["userId"]    = $userId;
            $result             = $db->register($data);

        }else{

            $result = $db->updateInfo($userId, $data);
        }

        if ($result)
        {
            return $inviteCode;
        }

        return $result;

    }

    /**
     * @desc 获取用户分平台数据统计
     * @param $param
     * @return mixed
     */
    public function getUserInfoStatistics($param){

        $where = $this->formatGetUserInput($param);

        $obj = $this->getUserInfoCondition($where);
        //注册人数
        $data['userNum'] = $obj->distinct()->count('user_id');
        return $data;
    }

    /**
     * @desc 用户统计条件t
     * @param $where
     * @return UserDb
     */
    public function getUserInfoCondition($where){/*dump($param);exit;*/
        $startTime = $where['start_time'];
        $endTime   = $where['end_time'];
        $sourceCode= $where['app_request'];
        $obj = new UserInfoDb();
        //时间控制
        if($startTime){
            $obj = $obj->where('created_at','>=',$startTime);
        }
        if($endTime){
            $obj = $obj->where('created_at','<=',$endTime);
        }
        //注册来源
        if($sourceCode){
            $obj = $obj->where('source_code',$sourceCode);
        }
        return $obj;
    }

    /**
     * @desc 格式化用户条件输入
     * @param $data
     * @return array
     */
    public function formatGetUserInput($data){
        $attribute                     = [];

        $attribute['start_time']       = isset($data['start_time']) ? $data['start_time'] : null;
        $attribute['end_time']         = isset($data['end_time']) ? $data['end_time'] : null;
        $attribute['app_request']           = isset($data['app_request']) ? $data['app_request'] : 0;

        return $attribute;
    }


    /**
     * @param $userId
     * @param $score
     * @return bool
     * @desc 修改用户风险承受能力测评分数
     */
    public static function doAssessmentScore($userId,$score){
        if(empty($userId)){
            return false;
        }
        $data = [];
        $data["assessment_score"] = $score;
        $db = new UserInfoDb();
        $result = $db->updateInfo($userId, $data);

        return $result;
    }

    /**
     * @desc 根据用户ID修改用户扩展信息
     * @param $userId
     * @param $data
     * @return array
     * @throws \Exception
     */
    public function updateUserInfo($userId, $data){

        $userInfoDb = new UserInfoDb();

        $userInfo = $userInfoDb->updateInfo( $userId, $data );

        if( $userInfo == false ){
            throw new \Exception('用户个人信息修改失败', self::getFinalCode('getUserInfo'));
        }
    }

    /**
     * @param int $score
     * @return string
     * @desc  判断投资风险评定类型
     */
    public static function assessmentType($score = 0){

        $type = '';

        if(0<=$score && $score<=22)
            $type = '保守型';
        elseif(22<$score && $score<=44)
            $type = '稳健型';
        elseif(44<$score && $score<=66)
            $type = '平衡型';
        elseif(66<$score && $score<=88)
            $type = '积极型';
        elseif(88<$score && $score<=100)
            $type = '激进型';

        return $type;
    }
}
