<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/31
 * Time: 下午3:26
 */

namespace App\Http\Models\Credit\Lable;

use App\Http\Dbs\Credit\CreditDb;

use App\Http\Dbs\Project\ProjectDb;

/**
 * 九省心六月期债权
 * Class CreditNineSixModel
 * @package App\Http\Models\CreditLable
 */
class CreditNineSixModel extends CreditLableModel implements CreditLableInterfaceModel
{
    /**
     * 获取未使用的债权集合
     * @param array $condition
     * @return mixed [update_status_identifier 更新标示]
     */
    public function getUnusedCreditList($condition = []){
        $condition['credit_tag']  = ProjectDb::PRODUCT_LINE_SIX_MONTH;
        $field = [
            'id',
            'source',
            'type',
            'credit_tag',
            'loan_amounts',
            'interest_rate',
            'repayment_method',
            'expiration_date',
            'loan_deadline',
        ];
        $data = $this->get($field, $condition);
        $data = $this->formatData($data);
        return $data;
    }
}