<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 17:13
 */
namespace  App\Http\Requests\Admin\Current;

use App\Http\Requests\Request;

class RateRequest extends Request{


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
            'rate_date'               => 'required|date|after:yesterday',
            'rate'                    => 'required|numeric|min:0'

        ];
    }


    public function attributes(){

        return [
            'rate_date'               => '日期不能早于今天',
            'rate'                    => '利率必须是大于0的整数',

        ];
    }
}