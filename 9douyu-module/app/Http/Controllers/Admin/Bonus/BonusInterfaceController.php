<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 红包加息券后台接口
 */

namespace App\Http\Controllers\Admin\Bonus;


interface BonusInterfaceController{

    /**
     * 创建红包或加息券视图
     * @return mixed
     */
    public function getCreate();

    /**
     * 列表
     * @return mixed
     */
    public function getLists();

    /**
     * 编辑
     * @param int $id
     * @return mixed
     */
    public function getUpdate($id = 0, \Illuminate\Http\Request $request);

}