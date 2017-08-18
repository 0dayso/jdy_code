<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午11:33
 * Desc: 定期项目
 */

namespace App\Http\Models\Invest;

use App\Http\Dbs\CurrentAccountDb;
use App\Http\Dbs\CurrentProjectDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;
use App\Http\Models\Model;

class CurrentModel extends Model
{

    public static $codeArr = [
        'invest'                    => 1,
        'checkCanInvestGetObj'      => 2,
        'checkCanInvestCash'        => 3,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_INVEST_CURRENT;

    public function invest($id, $cash)
    {

        $db = new CurrentProjectDb();

        $res = $db->invest($id, $cash);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_UPDATE_PROJECT'), self::getFinalCode('invest'));

        }

        return $res;

    }

    /**
     * @param $projectId
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 检测项目可投
     */
    public function checkCanInvest($projectId, $cash)
    {

        $db = new CurrentProjectDb();

        $info = $db->getObj($projectId);

        if( empty($info) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_EXIST'), self::getFinalCode('checkCanInvestGetObj'));

        }

        $leftAmount = $info['total_amount'] - $info['invested_amount'];

        if( $leftAmount < abs($cash) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_LEFT_AMOUNT'), self::getFinalCode('checkCanInvestCash'));

        }

        return true;

    }

    /**
     * @param $userId
     * @return array
     * @desc 通过用户id获取用户零钱计划数据
     */
    public static function getTotalInterestByUserId( $userId ){

        $db = new CurrentAccountDb();

        $return = $db -> getCurrentInfoByUserId( $userId );

        return is_object($return) ? $return->toArray() : [];

    }

}


