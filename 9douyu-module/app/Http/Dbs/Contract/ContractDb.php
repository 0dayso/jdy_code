<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/4
 * Time: 下午9:25
 */

namespace App\Http\Dbs\Contract;


use App\Http\Dbs\JdyDb;

class ContractDb extends JdyDb
{

    const
        CONTRACT_STATUS_DOING = 1 , //合同生成中（用于君子签）
        CONTRACT_STATUS_GOT   = 0 , //合同保全成功（适用于全表）
        PROJECT_CREDIT_ASSIGN = 40; //债转项目合同类型

    /**
     * @param $data
     * @return mixed
     * @desc 添加合同记录
     */
    public function add($data)
    {

        return $this->insert($data);

    }

    /**
     * @param $data
     * @return mixed
     * @desc 更新合同记录
     */
    public function doUpdate($data)
    {

        return $this->where('invest_id', $data['invest_id'])->update($data);

    }

    /**
     * @param $investId
     * @return mixed
     * @desc 通过投资Id查询合同信息
     */
    public function getByInvestId( $investId )
    {

        $return =    $this->where('invest_id', $investId)->first();

        return $this->dbToArray ($return);
    }

    /**
     * @param $investId
     * @return mixed
     * @desc 通过投资Id查询合同信息
     */
    public function getByInvestIds( $investId )
    {

        return $this->whereIn('invest_id', $investId)
                    ->get()
                    ->toArray();

    }

}
