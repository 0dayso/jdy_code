<?php
/** ****************************** 额外加息的Model层 ******************************
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/8/30
 * Time: 上午10:08
 */

namespace App\Http\Models\Activity;


use App\Http\Dbs\Activity\AwardRecordDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use Config;
use Mockery\CountValidator\Exception;
use App\Http\Models\Common\HttpQuery;
use App\Http\Logics\Logic;

class AwardRecordModel extends Model
{

    public static $codeArr = [
        'doInsert'             => 1,
        'doUpdate'             => 2,
    ];
    
    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_ACTIVITY_FUND_HISTORY;

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 增加记录
     */
    public static function doInsert( $data)
    {
        $db     =   new AwardRecordDb();
        
        $res    =   $db->addAwardRecord($data);

        if( !$res ){
            throw new Exception(LangModel::getLang('ERROR_ACTIVITY_ADD_RECORD'), self::getFinalCode('doInsert'));
        }

        return $res;
    }

    /*
     * 更新记录
     */
    public static function doUpdate( $data , $attribute,$filed = 'id' )
    {
        $db         =   new AwardRecordDb();
        $res        =   $db->updateRecord($attribute,$filed,$data);
        if( !$res ){
            throw new Exception(LangModel::getLang('ERROR_ACTIVITY_UPDATING_RECORD'),self::getFinalCode("doUpdate"));
        }
        return $res;
    }
}