<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 17:13
 */
namespace  App\Http\Requests\Admin\Current;

use App\Http\Requests\Request;

class CreditRequest extends Request{


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'               => 'required',
            'total_amount'       => 'required',
            'refund_type'        => 'required',
            'invest_time'        => 'required',
            'percentage'         => 'required',
            'contract_no'        => 'required',
            'end_time'           => 'required',

        ];
    }


    public function attributes(){

        return [
            'name'               => '债权名称',
            'total_amount'       => '债权金额',
            'refund_type'        => '还款方式',
            'invest_time'        => '融资时间',
            'percentage'         => '年化利率',
            'contract_no'        => '合同编号',
            'end_time'           => '到期日期',

        ];
    }
}