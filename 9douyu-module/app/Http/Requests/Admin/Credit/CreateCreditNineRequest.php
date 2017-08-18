<?php

namespace App\Http\Requests\Admin\Credit;

/**
 * 九省心债权验证
 * Class CreateCreditNineRequest
 * @package App\Http\Requests
 */
class CreateCreditNineRequest extends CreateRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'source'                        => 'required',
            'type'                          => 'required',
            'credit_tag'                    => 'required',
            'plan_name'                     => 'required',
            'loan_amounts'                  => 'required',
            'interest_rate'                 => 'required',
            'repayment_method'              => 'required',
            'expiration_date'               => 'required',
            'loan_deadline'                 => 'required',
            'contract_no'                   => 'required',

//            'credit_info'                   => 'credit_info',
            'program_no'                    => 'required',
            //'file'                        => 'required',


        ];
    }


    public function attributes(){

        return [
            'source'                        => '债权来源',
            'type'                          => '债权样式',
            'credit_tag'                    => '债权标签',
            'plan_name'                     => '计划名称',
            'loan_amounts'                  => '借款金额',
            'interest_rate'                 => '利率',
            'repayment_method'              => '还款方式',
            'expiration_date'               => '到期日期',
            'loan_deadline'                 => '借款期限',
            'contract_no'                   => '合同编号',


            'program_no'                    => '项目编号',
            'file'                          => '文件',
            'credit_info'                   => '债权列表',

        ];
    }
}
