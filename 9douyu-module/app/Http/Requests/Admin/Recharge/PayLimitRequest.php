<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 17:13
 */
namespace  App\Http\Requests\Admin\Recharge;

use App\Http\Requests\Request;

class PayLimitRequest extends Request{


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
            'bank_id'               => 'required',
            'pay_type'              => 'required',
            'limit'                 => 'required',
            'day_limit'             => 'required',
            'month_limit'           => 'required',

        ];
    }


    public function attributes(){

        return [
            'bank_id'            => '银行名称',
            'pay_type'           => '支付通道',
            'limit'              => '单笔限额',
            'day_limit'          => '单日限额',
            'month_limit'        => '单月限额'
        ];
    }
}