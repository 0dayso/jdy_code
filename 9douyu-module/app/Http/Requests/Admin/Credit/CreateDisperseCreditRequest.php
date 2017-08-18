<?php
/**
 * 新的分散债权添加验证
 * Class CreateDisperseCreditRequest
 * @package App\Http\Requests\Admin\Credit
 */

namespace App\Http\Requests\Admin\Credit;

class CreateDisperseCreditRequest extends CreateRequest{

    /**
     * @desc 获取表单验证规则
     * @return array
     */
    public function rules(){

        return [
            'record_type'  => 'integer|required',
            'credit_list'  => 'required_if:record_type,1',
            'credit_name'  => 'string|required_if:record_type,2',
            'credit_amounts'  => 'numeric|required_if:record_type,2',
            'interest_rate'  => 'numeric|required_if:record_type,2',
            'loan_deadline'  => 'numeric|required_if:record_type,2',
            'start_time'  => 'date|required_if:record_type,2',
            'end_time'  => 'date|required_if:record_type,2',
            //'loan_realname'  => 'required_if:record_type,2',
            //'loan_idcard'  => 'required_if:record_type,2',
            'contract_no'  => 'required_if:record_type,2',
            ];
    }

    public function attributes(){
        return [
            'record_type'  => '录入方式',
            'credit_list'  => 'execl债权文件',
            'credit_name'  => '债权名称',
            'credit_amounts'  => '债权金额',
            'interest_rate'  => '债权利率',
            'loan_deadline'  => '借款期限',
            'start_time'  => '开始日期',
            'end_time'  => '到期日期',
            'loan_realname'  => '借款人姓名',
            'loan_idcard'  => '借款人身份证号',
            'contract_no'  => '合同编号',
            ];
    }

}


