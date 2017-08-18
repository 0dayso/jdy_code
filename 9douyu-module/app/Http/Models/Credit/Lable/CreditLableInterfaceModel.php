<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/31
 * Time: 下午3:26
 * desc 债权标签类接口
 */
namespace App\Http\Models\Credit\Lable;


interface CreditLableInterfaceModel
{
    /**
     * 获取未使用债权
     * @param int $condition
     * @return mixed
     */
    public function getUnusedCreditList($condition = 0);



}