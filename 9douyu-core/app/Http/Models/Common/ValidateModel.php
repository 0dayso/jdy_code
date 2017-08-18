<?php
/**
 * User: zhangshuang
 * Date: 16/4/19
 * Time: 10:39
 * Desc: 数据统一验证Model
 */
namespace App\Http\Models\Common;
use App\Http\Models\Model;
use App\Lang\LangModel;

class ValidateModel extends Model{

    //支持的所有订单号前缀列表,需要支持其他前缀的列表，只需要添加一条即可

    private static $orderPrefixList = [
        'JDY'
    ];

    /*
    //订单扩展表用于标识订单支付通道 提现类型
    private static $orderType = [

        //网银支付
        '1000' => '京东网银',
        '1001' => '融宝网银',
        '1002' => '新生网银',

        //认证支付
        '1101' => '连连认证支付',
        '1102' => '易宝认证支付',

        //代扣
        '1201' => '钱袋宝代扣',
        '1202' => '联动优势代扣',
        '1203' => '翼支付代扣',
        '1204' => '融宝代扣',

        //提现
        '2000' => '提现',
    ];
    */

    //订单来源平台

    private static $orderFrom = [
        'pc','wap','android','ios'
    ];

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
        'isDecimalCash'                     => 20,
        'isInvestId'                        => 21

    ];

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
     * @param $userId
     * @throws Exception
     * 判断是否是正确的投资ID
     */
    public static function isInvestId($investId = 0){
        $pattern        = '/^\d+$/';
        if(!preg_match($pattern, $investId) || 0 === (int)$investId) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_INVEST_ID'), self::getFinalCode('isInvestId'));

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
     * @param string $orderId
     * @throws \Exception
     * 验证订单号格式是否合法
     */
    public static function isOrderId($orderId = ''){

        //订单号格式为：前缀._date("YmdHis") . rand(1000,9999),不同的产品来源的订单号以前缀区分

        //将所有前缀列表以'|'拼接
        $orderPrefix = implode('|',self::$orderPrefixList);

        $pattern        = '/^('.$orderPrefix.')\_\d{18}$/';

        if(!preg_match($pattern, $orderId) || '' === $orderId) {

            throw new \Exception(LangModel::getLang('ERROR_INVALID_ORDER_ID'), self::getFinalCode('isOrderId'));

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
     * @param $type
     * 检查是否是合法的充值或提现类型
     * 订单扩展表用于标识订单支付通道 提现类型
     */
    public static function isOrderType($type = 0){

        //if(0 === (int)$type || !isset(self::$orderType[$type])){

        $pattern        = '/^\d{4}$/';

        if(0 === (int)$type ||!preg_match($pattern, $type) ){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_ORDER_TYPE'), self::getFinalCode('isOrderType'));
        }

    }

    /**
     * @param string $from
     * 判断是否是合法的平台 pc wap android ios
     */
    public static function isOrderFrom($from = ''){

        $from = strtolower($from);

        if($from === '' || !in_array($from,self::$orderFrom)){

            throw new \Exception(LangModel::getLang('ERROR_INVALID_ORDER_FROM'), self::getFinalCode('isOrderFrom'));
        }

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
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_INVALID_TOTAL_AMOUNT'), self::getFinalCode('isTotalAmount'));
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
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_PUBLISH_TIME'), self::getFinalCode('isDate'));
        }
        return true;
    }

    /**
     * @param $profit
     * @return bool
     * @throws \Exception
     */
    public static function isProfit($profit){

        $pattern        = '/^[0-9]+([.][0-9]{1,2})?$/';

        if(!preg_match($pattern, $profit) || 0 === (float)$profit) {

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_INVALID_RATE'), self::getFinalCode('isProfit'));

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


}