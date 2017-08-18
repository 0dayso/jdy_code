<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 17:13
 */
namespace  App\Http\Requests\Admin\Media;

use App\Http\Requests\Request;

class GroupRequest extends Request{


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
            'name'       => 'required',
            'desc'       => 'required',
        ];
    }


    public function attributes(){

        return [
            'name'       => '分组名称',
            'desc'       => '分组描述',
        ];
    }
}