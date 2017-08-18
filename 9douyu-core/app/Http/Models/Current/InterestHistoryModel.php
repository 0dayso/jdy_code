<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/27
 * Time: 下午3:44
 * Desc: 零钱计划计息历史
 */

namespace App\Http\Models\Current;

use App\Http\Dbs\CurrentInterestHistoryDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class InterestHistoryModel extends Model
{

    public static $codeArr = [
        'addInfoEmptyData'                  => 1,
        'addInfo'                           => 2
    ];


    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_CURRENT_INTEREST_HISTORY;


    /**
     * @param array $data
     * @return static
     * @throws \Exception
     * @desc 批量插入记录
     */
    public function addInfo($data=[])
    {

        if( empty($data) ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INTEREST_HISTORY'),self::getFinalCode('addInfoEmptyData'));

        }

        $db = new CurrentInterestHistoryDb();

        $res = $db->addInfo($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_CURRENT_INTEREST_HISTORY'),self::getFinalCode('addInfo'));

        }

        return $res;

    }

}