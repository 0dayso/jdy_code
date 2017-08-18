<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/16
 * Time: 17:07
 * Desc: 核心接口调用Model基类
 */

namespace App\Http\Models\Common;

/**
 *
 * Class CoreApiModel
 * @package App\Http\Models\Common
 * 请求核心接口公共类,接口分为两种类型
 *
 * 1.获取(get) 若成功返回 data里面的数据
 * 2.写操作(do) 直接返回结果
 * 3.接口中有金额相关操作的地方,
 *      请求核心时需要将分转化为元,
 *      返回结果中包含金额的需要将元转化为分
 * 4.若核心添加新的接口,请完善config/coreApi.php & 添加相应的调用函数,以免造成重复开发相关的调用函数
 */

use App\Http\Models\SystemConfig\SystemConfigModel;

use Log;

class CoreApiModel{

    /**
     * 接口模块划分
     * 1.事件相关
     * 2.订单相关
     * 3.用户相关
     * 4.银行卡相关
     * 5.定期项目相关
     * 6.零钱计划相关
     * 7.回款相关
     * 8.资金流水相关
     * 9.核心系统配置相关
     */


}