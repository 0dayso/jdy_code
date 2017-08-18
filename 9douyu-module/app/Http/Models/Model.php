<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午5:30
 * Desc: 公用model
 */

namespace App\Http\Models;

use App\Http\Models\Common\ExceptionCodeModel;

use Config;

use DateTime;

class Model
{


    public static $defaultNameSpace = ExceptionCodeModel::EXP_MODEL_BASE;

    public static $codeArr = [];

    protected static function getFinalCode($errorText='')
    {

        $codeExt = isset(static::$codeArr[$errorText]) ? static::$codeArr[$errorText] : 0;

        if( isset(static::$expNameSpace)  ){

            return static::$expNameSpace + $codeExt;

        }else{

            return self::$defaultNameSpace;

        }

    }

    /**
     *
     * 获取两个时间字符串的时间差
     * @require PHP v.5.3.0
     * @param $endTime      To
     * @param $now          From    默认为当前时间（含时分秒）
     */
    function getDateDiff($endTime, $now = true) {
        if($now === true) {
            $dateNow    = new DateTime();
        }else {
            $dateNow    = new DateTime($now);
        }

        $dateEnd        = new DateTime($endTime);

        $diff           = $dateNow->diff($dateEnd);     //DateInterval object
        //$diff           = array("invert"=>0,"y"=>0,"m"=>6,"d"=>3,"h"=>2,"i"=>1,"s"=>56);

        return (array)$diff;    //force into array
    }

    /**
     * 求两天的时间差
     * @param $endTime  To
     * @param $now      From
     * @return 相差天数，如果From的日期比To的日期大，则返回负数
     */
    public function getBetweenDay($endTime, $now = true) {
        $dateDiff = $this->getDateDiff($endTime, $now);
        if($dateDiff['invert']) {   //$now > $endTime
            $leftDay = -$dateDiff['days'];
        } else {
            $leftDay = $dateDiff['days'];
        }

        return $leftDay;
    }

}