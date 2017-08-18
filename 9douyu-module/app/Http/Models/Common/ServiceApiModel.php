<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/16
 * Time: 17:07
 * Desc: 第三方服务接口调用Model基类
 */

namespace App\Http\Models\Common;
use App\Http\Models\Model;

/**
 *
 * Class ServiceApiModel
 * @package App\Http\Models\Common
 * 请求第三方接口公共类,接口分为两种类型
 * 4.若第三方服务添加新的接口,请完善config/serviceApi.php & 添加相应的调用函数,以免造成重复开发相关的调用函数
 */

class ServiceApiModel extends Model{

    /**
     * 接口模块划分
     * 1.银行卡相磁
     * 2.支付相关
     * 3.短信相关
     * 4.邮件相关
     */

}