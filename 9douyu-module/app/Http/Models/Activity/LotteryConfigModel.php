<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/23
 * Time: 下午4:25
 */

namespace App\Http\Models\Activity;


use App\Http\Dbs\Activity\LotteryConfigDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use Mockery\CountValidator\Exception;

class LotteryConfigModel extends Model
{
    public static $codeArr = [
        'doAddLottery'             => 1,
        'doEditLottery'            => 2,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_LOTTERY_CONFIG;

    /**
     * @param $data
     * @desc 添加奖品设置
     */
    public function doAdd( $data )
    {
        $db     =   new LotteryConfigDb();

        $return =   $db->doAdd($data);

        if( empty($return) ||!$return ){

            throw new Exception(LangModel::getLang("ERROR_LOTTERY_CONFIG_ADD_FAILED"),self::getFinalCode('doAddLottery'));
        }

        return $return;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @desc 更新
     */
    public function doEdit( $id , $data )
    {
        $db     =   new LotteryConfigDb();

        $return =   $db->doEdit($id , $data);

        if( empty($return) ||!$return ){

            throw new Exception(LangModel::getLang("ERROR_LOTTERY_CONFIG_EDIT_FAILED"),self::getFinalCode('doEditLottery'));
        }

        return $return;
    }

}