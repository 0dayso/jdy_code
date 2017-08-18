<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 17:13
 */
namespace  App\Http\Requests\Invest;

use App\Http\Requests\Request;

class CurrentRequest extends Request{


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
            'cash'                    => 'required|numeric|min:0',
            'trading_password'        => 'required',
        ];
    }


    public function attributes(){

        return [
            'cash'                  => '金额必须为大于零的数字',
            'trading_password'      => '交易密码不能为空',
        ];
    }
}