<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午1:46
 */

namespace App\Http\Dbs;

class InvestExtendDb extends JdyDb{

    protected $table = 'invest_extend';
    public $timestamps = false;

    const
        BONUS_TYPE_RATE = 100,
        BONUS_TYPE_MONEY = 300,

    END=TRUE;


    /**
     * @param $data
     * @return bool
     * @desc 创建资金记录
     */
    public function add($data)
    {

        $this->invest_id = $data['invest_id'];

        $this->bonus_type = $data['bonus_type'];

        $this->bonus_value = $data['bonus_value'];

        $this->save();

        return $this->id;

    }

    /**
     * @param $investId
     * @return mixed
     * @desc 获取投资记录信息
     */
    public function getInfoByInvestId($investId)
    {

        $res = $this->where('invest_id', $investId)
            ->first();

        return $this->dbToArray($res);

    }

    /**
     * @param $investIds
     * @return mixed
     * 通过投资Id
     */
    public function getListByInvestIds( $investIds ){

        $result = $this::select('invest_id','bonus_value')
                ->whereIn('invest_id', $investIds)
                ->where('bonus_type', self::BONUS_TYPE_RATE)
                ->get()
                ->toArray();

        return $result;

    }

}