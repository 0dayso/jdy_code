<?php

namespace App\Http\Requests\Admin\Credit;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;

class CreditLoanUserRequest extends CreateRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules1()
    {
        return [
            'credit_list'  => 'required',
            ];

    }
    /**
     * Get the validation rules that apply to the request.
     * @desc Handle-record Credit rules
     *
     * @return array
     */
    public function rules( $recoreType, $loanType = 1)
    {
        if( $recoreType == 1 )
        {
            $rules = [
                'credit_list'  => 'required',
                ];

        }else{
            $rules = $this->rulesCommon();

            $rules = ( ( $loanType == 1 ) ? ( [ 'loan_amounts' => 'numeric|max:200000|required'] + $rules ) : ( [ 'loan_amounts' => 'numeric|max:1000000|required'] + $rules ) );

        }

        return $rules;
    }

    /**
     * @desc 债权提交匹配规则
     */
    public function rulesCommon()
    {

        return [
            //
            'credit_name'     => 'required',
            'loan_type' =>        'integer|required',
            'manage_fee'       => 'numeric|required',
            'interest_rate'    => 'numeric|required',
            //'project_publish_rate'    => 'numeric|required',
            'repayment_method' => 'integer|required',
            'loan_deadline'    => 'integer|required',
            'loan_days'    => 'integer|required',
            'loan_phone'     => 'integer|required',
            'loan_user_identity' => 'required',
            'loan_username'    => 'required',
            'bank_name'    => 'required',
            'bank_card'    => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'record_type'  => '录入方式',
            'credit_list'  => '批量导入execl债权文件',
            'company_name'     => '债权名称',
            'loan_amounts'     => '借款金额',
            'manage_fee'       => '平台管理费',
            'interest_rate'    => '利率',
            'project_publish_rate'    => '项目发布利率',
            'repayment_mothod' => '还款方式',
            'loan_deadline'    => '借款期限',
            'loan_days'    => '融资时间',
            'loan_phone'     => '借款人手机号',
            'loan_user_identity' => '借款人身份证',
            'loan_username'    => '借款人姓名',
            'bank_name'        => '银行名称',
            'bank_card'        => '银行卡号',
            ];
    }
}
