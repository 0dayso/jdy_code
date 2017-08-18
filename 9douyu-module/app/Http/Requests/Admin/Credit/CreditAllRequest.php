<?php
/**
 * 债权合并后添加验证
 * Class CreditAllRequest
 * @package App\Http\Requests\Admin\Credit
 */

namespace App\Http\Requests\Admin\Credit;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;

class CreditAllRequest extends CreateRequest
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
    public function rules( $recoreType )
    {
        if( $recoreType == 1 )
        {
            $rules = [
                'credit_list'  => 'required',
                ];

        }else{
            $rules = $this->rulesCommon();
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
            'company_name'     => 'required',
            'loan_amounts'    => 'numeric|required',
            'interest_rate'    => 'numeric|required',
            //'project_publish_rate'    => 'numeric|required',
            'repayment_method' => 'integer|required',
            'expiration_date'    => 'date|required',
            'loan_deadline'    => 'integer|required',
            //'loan_user_identity' => 'required',
            //'loan_username'    => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'record_type'  => '录入方式',
            'credit_list'  => '批量导入execl债权文件',
            'company_name'     => '债权名称',
            'loan_amounts'     => '借款金额',
            'interest_rate'    => '利率',
            'repayment_mothod' => '还款方式',
            'expiration_date'    => '到期时间',
            'loan_deadline'    => '借款期限',
            'loan_user_identity' => '借款人身份证',
            'loan_username'    => '借款人姓名',
            ];
    }
}
