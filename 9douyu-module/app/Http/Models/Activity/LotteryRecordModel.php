<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/25
 * Time: 下午4:23
 */

namespace App\Http\Models\Activity;


use App\Http\Dbs\Activity\LotteryConfigDb;
use App\Http\Dbs\Activity\LotteryRecordDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use Mockery\CountValidator\Exception;

class LotteryRecordModel extends Model
{
    public static $codeArr = [
        'doAddRecord'           => 1,
        'doEditRecord'          => 2,
        'lotteryEmpty'          =>  3,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_LOTTERY_CONFIG;

    /**
     * @param $data
     * @return bool
     * @desc 添加中奖记录
     */
    public static function doAddRecord( $data )
    {
        $return     =   LotteryRecordDb::doAdd($data);

        if( !$return ||  empty($return) ){

            throw new Exception(LangModel::getLang('ERROR_LOTTERY_RECORD_ADD_FAILED'),self::getFinalCode('doAddRecord'));
        }

        return true;
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * @desc 更新中奖记录
     */
    public static function doUpdate( $id , $data )
    {
        $return     =   LotteryRecordDb::doUpdate($id,$data);

        if( !$return ||  empty($return) ){

            throw new Exception(LangModel::getLang('ERROR_LOTTERY_RECORD_EDIT_FAILED'),self::getFinalCode('doEditRecord'));
        }

        return true;
    }

    public function doVerifyLottery( $id )
    {
        $lotteryDb  =   new LotteryConfigDb();

        $lottery    =   $lotteryDb->getById($id);

        if( empty($lottery) ){

            throw new Exception(LangModel::getLang('ERROR_LOTTERY_DETAILS_IS_EMPTY'),self::getFinalCode('lotteryEmpty'));
        }

        return $lottery;
    }
}