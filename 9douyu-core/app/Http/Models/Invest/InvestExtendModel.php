<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午1:43
 * Desc: 投资记录
 */

namespace App\Http\Models\Invest;


use App\Http\Dbs\InvestExtendDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class InvestExtendModel extends Model
{


    public static $codeArr = [
        'add'                    => 1,
        'getByInvestId'          => 2,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_INVEST_EXTEND;

    /**
     * @param $investId
     * @param $bonusValue
     * @param $bonusType
     * @return mixed
     * @throws \Exception
     * 插入投资记录
     */
    public function add($investId, $bonusValue, $bonusType = InvestExtendDb::BONUS_TYPE_RATE)
    {

        $data = [
            'invest_id'     => $investId,
            'bonus_value'   => $bonusValue,
            'bonus_type'    => $bonusType,
        ];

        $db = new InvestExtendDb();

        $res = $db->add($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_INVEST_EXTEND_RECORD'), self::getFinalCode('add'));

        }

        return $res;

    }

    /**
     * @param $investId
     * @return mixed
     * @throws \Exception
     * 根据投资ID获取对应的数据
     */
    public function getByInvestId($investId){

        $db = new InvestExtendDb();

        $res = $db->getInfoByInvestId($investId);

        if(!$res){

            throw new \Exception(LangModel::getLang('ERROR_EMPTY_RECORD'), self::getFinalCode('getByInvestId'));
        }
        return $res;

    }

}