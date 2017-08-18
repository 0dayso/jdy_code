<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/7
 * Time: 17:13
 */
namespace  App\Http\Requests\Admin\Media;

use App\Http\Requests\Request;

class ChannelRequest extends Request{


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
            'group_id'   => 'required',
            'name'       => 'required',
            'desc'       => 'required',
            'url'        => 'required',
            //'package'    => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
        ];
    }


    public function attributes(){

        return [
            'group_id'   => '分组ID',
            'name'       => '渠道名称',
            'desc'       => '渠道描述',
            'url'        => '落地页',
            'package'    => '推广包',
            'start_date' => '推广开始日期',
            'end_date'   => '推广结束日期',

        ];
    }
}