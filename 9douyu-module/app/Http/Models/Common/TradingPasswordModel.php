<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/7
 * Time: 下午6:37
 * Desc: 交易密码相关信息
 */

namespace App\Http\Models\Common;

use App\Http\Models\Model;
use App\Lang\LangModel;

class TradingPasswordModel extends Model
{

    public static $codeArr            = [
        'matchPassword'         => 1,
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_TRADING_PASSWORD;

    /**
     * @param $password
     * @param int $saltLength
     * @return string
     * @desc 生成交易密码
     */
    public static function generatePassword($password, $saltLength=32) {

        $saltLength = empty($saltLength) ? 32 : min($saltLength, 32);

        $salt       = substr(self::getToken32(), rand(0,5), $saltLength);

        $password   = md5($password . $salt);

        return sprintf('%s:%s', $password, $salt);
    }

    /**
     * @param $password
     * @param $dbPassword
     * @return bool
     * @throws \Exception
     * @desc 检测交易密码
     */
    public static function checkPassword($password, $dbPassword,$errorHandle=true) {

        $parts = explode(':', $dbPassword);

        if($parts[0] != md5($password . $parts[1])) {

            if($errorHandle){
                throw new \Exception(LangModel::getLang('ERROR_VERIFY_TRADING_PASSWORD'), self::getFinalCode('matchPassword'));

            }else{
                return false;
            }

        }

        return true;
    }

    /**
     * @return string
     * @desc 获取32位token
     */
    public static function getToken32() {

        return md5(md5(rand(111111111, 999999999)) . time());

    }

}