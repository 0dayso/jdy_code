<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/25
 * Time: 下午3:28
 * Desc: 债权模型接口
 */

namespace App\Http\Models\Credit;


interface CreditInterfaceModel{

    /**
     * 获取列表【用于后台列表展示】
     * @param array $condition 查询条件
     * @return mixed
     */
    public function getAdminList($condition = []);

    /**
     * 格式化列表【用于后台列表展示】
     * @param $dataList
     * @return mixed
     */
    public function formatAdminList($dataList);

}