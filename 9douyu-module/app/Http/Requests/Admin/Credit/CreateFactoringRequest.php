<?php

namespace App\Http\Requests\Admin\Credit;

/**
 * 保理债权验证
 * Class CreateFactoringRequest
 * @package App\Http\Requests\Admin\Credit
 */
class CreateFactoringRequest extends CreateRequest
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

            'riskcalc_level'                => 'required',

            'company_level'                 => 'required',
            'downstream_level'              => 'required',
            'profit_level'                  => 'required',
            'downstream_refund_level'       => 'required',
            'liability_level'               => 'required',
            'guarantee_level'               => 'required',

            'company_level_value'           => 'required',
            'downstream_level_value'        => 'required',
            'profit_level_value'            => 'required',
            'downstream_refund_level_value' => 'required',
            'liability_level_value'         => 'required',
            'guarantee_level_value'         => 'required',

            //'keywords'                      => 'required',
            //'credit_desc'                   => 'required',
            'factor_summarize'              => 'required',
            'repayment_source'              => 'required',
            'factoring_opinion'             => 'required',
            'business_background'           => 'required',
            'introduce'                     => 'required',
            'risk_control_measure'          => 'required',
            //'transactional_data'            => 'required',
            //'traffic_data'                  => 'required',

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

            'riskcalc_level'                => 'riskcalc信用评级',

            'company_level'                 => '企业经营规模',
            'downstream_level'              => '下游企业实力',
            'profit_level'                  => '企业盈利能力',
            'downstream_refund_level'       => '下游回款能力',
            'liability_level'               => '资产负债水平',
            'guarantee_level'               => '担保方实例',

            'company_level_value'           => '企业经营规模',
            'downstream_level_value'        => '下游企业实力',
            'profit_level_value'            => '企业盈利能力',
            'downstream_refund_level_value' => '下游回款能力',
            'liability_level_value'         => '资产负债水平',
            'guarantee_level_value'         => '担保方实例',

            'keywords'                      => '关键字',
            'credit_desc'                   => '债权综述',
            'factor_summarize'              => '项目综述',
            'repayment_source'              => '还款来源',
            'factoring_opinion'             => '保理公司意见',
            'business_background'           => '原债权企业介绍',
            'introduce'                     => '原债务企业介绍',
            'risk_control_measure'          => '风控措施',
            'transactional_data'            => '基础交易材料',
            'traffic_data'                  => '保理业务材料',

        ];
    }
}
