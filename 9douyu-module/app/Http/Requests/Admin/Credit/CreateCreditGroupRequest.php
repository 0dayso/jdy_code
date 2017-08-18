<?php

namespace App\Http\Requests\Admin\Credit;

/**
 * 项目集债权验证
 * Class CreateCreditGroupRequest
 * @package App\Http\Requests\Admin\Credit
 */
class CreateCreditGroupRequest extends CreateRequest
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
            'company_name'                  => 'required',
            'loan_amounts'                  => 'required',
            'interest_rate'                 => 'required',
            'repayment_method'              => 'required',
            'expiration_date'               => 'required',
            'loan_deadline'                 => 'required',
            'contract_no'                   => 'required',
            //'loan_username'                 => 'required',
            //'loan_user_identity'            => 'required',

            'financing_company'              => 'required',
            'program_area_location'          => 'required',
            'loan_use'                       => 'required',
            'repayment_source'               => 'required',
            //'loan_contract'                 => 'required',


        ];
    }


    public function attributes(){

        return [
            'source'                        => '债权来源',
            'type'                          => '债权样式',
            'credit_tag'                    => '债权标签',
            'company_name'                  => '企业名称',
            'loan_amounts'                  => '借款金额',
            'interest_rate'                 => '利率',
            'repayment_method'              => '还款方式',
            'expiration_date'               => '到期日期',
            'loan_deadline'                 => '借款期限',
            'contract_no'                   => '合同编号',
            'loan_username'                 => '借款人姓名',
            'loan_user_identity'            => '证件号',

            'financing_company'             => '融资企业',
            'program_area_location'         => '项目区域位置',
            'loan_use'                      => '借款用途',
            'repayment_source'              => '还款来源',
            'loan_contract'                 => '借款合同',

        ];
    }
}
