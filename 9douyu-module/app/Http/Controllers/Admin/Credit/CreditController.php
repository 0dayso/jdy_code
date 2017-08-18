<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 债权控制器接口
 */

namespace App\Http\Controllers\Admin\Credit;

interface CreditController{

    /**
     * 创建债权视图
     * @return mixed
     */
    public function create();

    /**
     * 债权列表
     * @return mixed
     */
    public function lists();


    /**
     * 编辑债权视图
     * @param int $id 指定债权ID
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function edit($id, \Illuminate\Http\Request $request);
}