<?php
/**
 * User: zhangshuang
 * Date: 16/4/19
 * Time: 10:39
 * Desc: 数据统一验证Model
 */
namespace App\Http\Models\Common;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Model;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;

class ValidateModel extends Model{

    
    public static $codeArr = [
        'isUserId'                          => 1,
        'isProjectId'                       => 2,
        'isCash'                            => 3,
        'isBankId'                          => 4,
        'isOrderId'                         => 5,
        'isHandingFee'                      => 6,
        'isName'                            => 7,
        'isIdCard'                          => 8,
        'isIdCardCheckAgeByIDCard'          => 9,
        'isOrderType'                       => 10,
        'isOrderFrom'                       => 11,
        'checkReasonIsEmpty'                => 12,
        'isBankCard'                        => 13,
        'isTotalAmount'                     => 14,
        'isDate'                            => 15,
        'isProfit'                          => 16,
        'isNullName'                        => 17,
        'isInvestDays'                      => 18,
        'isInvestTime'                      => 19,
        'checkSuggestContentEmpty'          => 20,
        'checkSuggestContentTooLong'        => 21,
        'isCurrentRate'                     => 22,
        'isCurrentProfit'                   => 23,
        'isDecimalCash'                     => 24,
        'checkBalance'                      => 25,
        'isSmsCode'                         => 26,
        'isEmail'                           => 27,
        'isNoviceInvestUser'                => 28,
        'isAbleInvestCash'                  => 29,


    ];

    //用户反馈意见最多800个汉字
    const MAX_SUGGEST_NUM = 800;

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_COMMON_VALIDATE;

    /**
     * @param $userId
     * @throws Exception
     * 判断是否是正确的用户ID
     */
    public static function isUserId($userId = 0){
        $pattern        = '/^\d+$/';
        if(!preg_match($pattern, $userId) || 0 === (int)$userId) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_USER_ID'), self::getFinalCode('isUserId'));

        }

    }

    /**
     * @param $userId
     * @throws Exception
     * 判断是否是正确的用户ID
     */
    public static function isProjectId($projectId = 0){
        $pattern        = '/^\d+$/';
        if(!preg_match($pattern, $projectId) || 0 === (int)$projectId) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_PROJECT_ID'), self::getFinalCode('isProjectId'));

        }

    }


    /**
     * @param $cash
     * @throws Exception
     * 判断金额是否正确
     */
    public static function isCash($cash = 0){

        $pattern        = '/^\d+$/';

        if(!preg_match($pattern, $cash) || 0 === (int)$cash) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_CASH'), self::getFinalCode('isCash'));

        }
    }

    /**
     * @param $cash
     * @return bool
     * @throws \Exception
     * 可以为小数金额
     */
    public static function isDecimalCash($cash){
        $pattern        = '/^[0-9]+([.][0-9]{1,2})?$/';
        if(!preg_match($pattern, $cash) || $cash < 0.01) {
            throw new \Exception(LangModel::getLang('ERROR_INVALID_DECIMAL_CASH'), self::getFinalCode('isDecimalCash'));
        }
        return true;
    }

    /**
     * @param int $bankId
     * @throws Exception
     * 无效的银行ID
     */
    public static function isBankId($bankId = 0){

        $pattern        = '/^\d{1,2}$/';

        if(!preg_match($pattern, $bankId) || 0 === (int)$bankId) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_BANK_ID'), self::getFinalCode('isBankId'));

        }
    }

    /**
     * @param string $cardNo
     * @throws \Exception
     * 判断银行卡号是否合法
     */
    public static function isBankCard($cardNo = ''){

        $len = strlen($cardNo);

        if(!($len == 16 || $len == 17 || $len == 19 || preg_match('#^\d{18}$#', $cardNo))){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_BANK_CARD'), self::getFinalCode('isBankCard'));
        }
    }


    /**
     * @param int $handingFee
     * @throws \Exception
     * 手续费格式验证
     */
    public static function isHandingFee($handingFee = 0){

        $pattern        = '/^\d+$/';
        if(!preg_match($pattern, $handingFee)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_WITHDRAW_HANDING_FEE'), self::getFinalCode('isHandingFee'));

        }
    }


    /**
     * 姓名格式校验
     * @param $name
     */
    public static function isName($name) {

        if(preg_match('#[a-z\d~!@\#$%^&*()_+{}|\[\]\-=:<>?/"\'\\\\]#', $name)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_NAME'), self::getFinalCode('isName'));
        }
    }


    /**
     * @param $idCard
     * @return bool
     * 身份证格式判断
     */
    public static function isIdCard($idCard)
    {

        if(!preg_match('/^(\d{15}|\d{17}X|\d{18})$/i', $idCard)) {
            $res = false;
        } else if(strlen($idCard) == 18) {
            $res     = self::idcard_checksum18($idCard);
        } else if((strlen($idCard) == 15)) {
            $idCard = self::idcard_15to18($idCard);
            $res     = self::idcard_checksum18($idCard);
        } else {
            $res     = false;
        }

        if(empty($res)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_ID_CARD'), self::getFinalCode('isIdCard'));
        }else{
            if(!self::checkAgeByIDCard($idCard)){

                throw new \Exception(LangModel::getLang('ERROR_AGE_IS_LESS_EIGHTEEN'), self::getFinalCode('isIdCardCheckAgeByIDCard'));
            }
        }

        return true;
    }


    /**
     * @param $note
     * @throws \Exception
     * 取消提现原因是否为空
     */
    public static function checkReasonIsEmpty($note){

        if($note === ''){
            throw new \Exception(LangModel::getLang('ERROR_INVALID_CANCLE_REASON'), self::getFinalCode('checkReasonIsEmpty'));

        }
    }

    /**
     * @param $IDCard
     * @return bool
     * 判断用户是否已年满十八岁
     */
    private static function checkAgeByIDCard($IDCard){

        if(strlen($IDCard)==18){

            $tyear = (int)substr($IDCard,6,4);

            $tmonth = (int)substr($IDCard,10,2);

            $tday = (int)substr($IDCard,12,2);

        }elseif(strlen($IDCard)==15){

            $tyear = (int)("19".substr($IDCard,6,2));

            $tmonth = (int)substr($IDCard,8,2);

            $tday = (int)substr($IDCard,10,2);

        }

        $birthday = strtotime($tyear.'-'.$tmonth.'-'.$tday.' + 18 years');

        $today = time();

        if($today > $birthday){

            return true;

        }else{

            return false;

        }
    }


    // 计算身份证校验码，根据国家标准GB 11643-1999
    private static function idcard_verify_number($idcard_base)
    {
        if(strlen($idcard_base) != 17) {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }

    // 将15位身份证升级到18位
    private static function idcard_15to18($idcard){
        if (strlen($idcard) != 15){
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
                $idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
            } else {
                $idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
            }
        }
        $idcard = $idcard . self::idcard_verify_number($idcard);
        return $idcard;
    }

    // 18位身份证校验码有效性检查
    private static function idcard_checksum18($idcard){
        if (strlen($idcard) != 18){
            return false;
        }
        $idcard_base = substr($idcard, 0, 17);
        if (self::idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @param $totalAmount
     * @throws \Exception
     * @return bool
     */
    public static function isTotalAmount($totalAmount){
        $pattern        = '/^\d+$/';
        if(!preg_match($pattern, $totalAmount) || 0 === (int)$totalAmount) {
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_LEFT_AMOUNT'), self::getFinalCode('isTotalAmount'));
        }
        return true;
    }

    /**
     * @param $date
     * @return bool
     * @throws \Exception
     * @desc 验证发布日期是否合法
     */
    public static function isDate($date){
        $isDate = strtotime($date)?strtotime($date):false;
        if(!$isDate){
            throw new \Exception(LangModel::getLang('ERROR_INVALID_DATE'), self::getFinalCode('isDate'));
        }
        return true;
    }

    /**
     * @param $rate
     * @return bool
     * @throws \Exception
     * 验证零钱计划利率
     */
    public static function isCurrentRate($rate){
        $pattern        = '/^[1-9]+([.][0-9]+)?$/';
        if(!preg_match($pattern, $rate)) {
            throw new \Exception(LangModel::getLang('ERROR_INVALID_CURRENT_RATE'), self::getFinalCode('isCurrentRate'));
        }
        return true;
    }

    /**
     * @param $profit
     * @return bool
     * @throws \Exception
     * 验证零钱计划加息利率
     */
    public static function isCurrentProfit($profit){
        $pattern        = '/^[0-9]+([.][0-9]+)?$/';
        if(!preg_match($pattern, $profit)) {
            throw new \Exception(LangModel::getLang('ERROR_INVALID_PROFIT_RATE'), self::getFinalCode('isCurrentProfit'));
        }
        return true;
    }



    /**
     * @param $name
     * @throws \Exception
     * @desc 项目名称为空
     */
    public static function isNullName($name){
        if(empty(trim($name))){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_EMPTY_NAME'), self::getFinalCode('isNullName'));
        }
    }

    /**
     * @param $investDays
     * @return bool
     * @throws \Exception
     * @desc 融资天数不合法
     */
    public static function isInvestDays($investDays)
    {
        $pattern        = '/^\d+$/';
        if(!preg_match($pattern, $investDays) || 0 === (int)$investDays) {
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_INVALID_INVEST_DAYS'), self::getFinalCode('isInvestDays'));
        }
        return true;
    }

    /**
     * @param $investTime
     * @return bool
     * @throws \Exception
     * @desc 投资期限不合法
     */
    public static function isInvestTime($investTime){
        $pattern        = '/^\d+$/';
        if(!preg_match($pattern, $investTime) || 0 === (int)$investTime) {
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_INVALID_INVEST_TIME'), self::getFinalCode('isInvestTime'));
        }
        return true;
    }

    /**
     * @param string $phone
     * @throws \Exception
     * 判断是否是手效的手机号码
     */
    public static function isPhone($phone = ''){

        $pattern    = '/^(13\d|14[57]|15[012356789]|18\d|17[0135678])\d{8}$/';
        if(!preg_match($pattern, $phone)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_PHONE'), self::getFinalCode('isPhone'));

        }

    }

    /**
     * @param string $code
     * @throws \Exception
     * 验证短信验证码格式是否正确
     */
    public static function isSmsCode($code = ''){

        $pattern    = '/^\d{6}$/';
        if(!preg_match($pattern, $code)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_SMS_CODE'), self::getFinalCode('isSmsCode'));

        }
    }

    /**
     * @param $content
     * @throws \Exception
     * 反馈意见内容验证
     */
    public static function checkSuggestContent($content){

        if($content === ''){
            
            throw new \Exception(LangModel::getLang('ERROR_USER_SUGGEST_EMPTY'),self::getFinalCode('checkSuggestContentEmpty'));
            
        }
        if (mb_strlen($content, 'utf8') > self::MAX_SUGGEST_NUM) {

            throw new \Exception(LangModel::getLang('ERROT_USER_SUGGEST_TOO_LONG'),self::getFinalCode('checkSuggestContentTooLong'));
            
        }
    }

    /**
     * @param array $userIds 用户Id数组
     * @return bool
     * @throws \Exception
     * @dec 验证用户id数组是否合法
     */
    public static function checkUserIds($userIds){

        if(!is_array($userIds) || empty($userIds)){
            throw new \Exception(LangModel::getLang('ERROR_USER_BONUS_USER_ID'), self::getFinalCode('checkUserIds'));
        }

        foreach( $userIds as $item){
            self::isUserId($item);
        }

        return true;

    }

    /**
     * @param $balance
     * @param $cash
     * @return bool
     * @throws \Exception
     * @dec 验证用户的账户余额与投资金额
     */
    public static function checkBalance($balance,$cash){

        //self::isCash($balance);

        if($balance < $cash){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INVEST_USER_BALANCE_NOT_ENOUGH'), self::getFinalCode('checkBalance'));

        }

        return true;

    }

    /**
     * @param $data
     * @param $name
     * @return bool
     * @throws \Exception
     * @desc
     */
    public static function isEmpty($data, $name='参数'){

        if(empty($data)){

            throw new \Exception($name.LangModel::getLang('ERROR_IS_EMPTY'), self::getFinalCode('checkBalance'));

        }

        return true;

    }

    /**
     * @param $email
     * @return bool
     * @throws \Exception
     * 邮箱
     */
    public static function isEmail($email){

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_EMAIL'), self::getFinalCode('isEmail'));

        }

        return true;

    }

    /**
     * @param $userId
     * @return bool
     * @throws \Exception
     * @desc 通过用户id获取投资记录，不是首投用户则抛异常
     */
    public static function isNoviceInvestUser($userId){

        //如果是内部账号，可以直接投资
        $sysUser = SystemConfigLogic::getConfig('SYS_INVEST_USERS', 69);

        $sysUser = explode(',', $sysUser);

        if( in_array($userId, $sysUser) ){

            return true;

        }

        //获取用户投资记录
        $result = UserModel::getUserInvestDataByUserId($userId);

        if( !empty($result) ){

            throw new \Exception(LangModel::getLang('ERROR_NOVICE_INVEST_USER'), self::getFinalCode('isNoviceInvestUser'));

        }

        return true;
    }

    /**
     * @param $cash
     * @throws \Exception
     * @desc 判断新手首投项目投资限额
     */
    public static function isAbleInvestCash($cash){

        //获取系统配置中新手首投项目可投限制额度
        $limitCash = !empty(SystemConfigModel::getConfig('NOVICE_PROJECT_INVEST_LIMIT')) ? SystemConfigModel::getConfig('NOVICE_PROJECT_INVEST_LIMIT') : 50000;

        if( $cash > $limitCash ){

            throw new \Exception(LangModel::getLang('ERROR_NOVICE_INVEST_CASH_LIMIT'), self::getFinalCode('isAbleInvestCash'));

        }
    }


}
