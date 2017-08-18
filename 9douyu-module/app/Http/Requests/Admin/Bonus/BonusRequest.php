<?php

namespace App\Http\Requests\Admin\Bonus;

use App\Http\Requests\Request;

use App\Http\Dbs\Bonus\BonusDb;

class BonusRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $array =  [
            'name'          => 'required',
            'type'          => 'required',
            'client_type'   => 'required',
            //'project_type'  => 'required',
            //'rate'          => 'required',
            //'money'         => 'required',
            'use_type'      => 'required',
            'min_money'     => 'required',
            //'max_money'     => 'required',
            //'current_day'   => 'required',
            'send_start_date'=> 'required',
            'send_end_date' => 'required',
            'using_desc'    => 'required',
            'note'          => 'required',
            //'status'        => 'required',
            'give_type'     => 'required',
            //'created_at'    => 'required',
            //'updated_at'    => 'required',
        ];

        $type = $this->input('type');

        if($type == BonusDb::TYPE_CASH){
            $array['money'] = 'required';
        }else{
            $array['rate'] = 'required';
        }

        if($type == BonusDb::TYPE_COUPON_CURRENT){
            $array['current_day'] = 'required';
        }else{
            $array['project_type'] = 'required';
        }

        $effectType = $this->input('effect_type');

        if($effectType == BonusDb::EFFECT_NOW){
            $array['expires'] = 'required';
        }else{
            $array['effect_start_date'] = 'required';
            $array['effect_end_date'] = 'required';
        }
        return $array;
    }


    public function attributes(){

        return [
            'name'              => '名称',
            'type'              => '类型',
            'client_type'       => '客户端类型',
            'project_type'      => '项目类型',
            'rate'              => '利率',
            'money'             => '金额',
            'use_type'          => '使用类型',
            'min_money'         => '最小金额',
            'max_money'         => '最大金额',
            'expires'           => '期限（天）',
            'current_day'       => '加息天数',
            'send_start_date'   => '发布开始时间',
            'send_end_date'     => '发布结束时间',
            'using_desc'        => '使用范围',
            'note'              => '备注',
            'status'            => '状态',
            'give_type'         => '是否可转让',
            'effect_type'       => '生效类型',
            'effect_start_date' => '开始生效时间',
            'effect_end_date'   => '生效结束时间',
        ];
    }
}
