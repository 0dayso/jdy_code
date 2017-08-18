<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/2
 * Time: 下午4:01
 */

namespace App\Http\Requests\Admin\Project;

use App\Http\Requests\Request;

/**
 * 项目字段验证
 * Class ProjectRequest
 * @package App\Http\Requests\Admin\Project
 */
class ProjectRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     * 登录验证
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'invest_days'   => 'required',
            'base_rate'     => 'required',
            'publish_time'  => 'required',
            'invest_time'   => 'required',
            //'end_at'        => 'required',
            'total_amount'  => 'required',
            'credit_id'     => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'invest_days'   => '融资时间',
            'base_rate'     => '预计年利率',
            'publish_time'  => '发布时间',
            'invest_time'   => '项目期限',
            //'end_at'        => '到期日期',
            'total_amount'  => '项目金额',
            'credit_id'     => '债权',
        ];
    }

}