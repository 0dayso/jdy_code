<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午4:46
 * Desc: 广告相关
 */

namespace App\Http\Models\Ad;

use App\Http\Models\Model;
use App\Http\Dbs\Ad\AdDb;

class AdModel extends Model
{

    public static function getUrlType(){
        return [
            AdDb::JUMP_TO_URL      => '链接',
            AdDb::JUMP_TO_PROJECT  => '投资列表(APP)',
            AdDb::JUMP_TO_USERINFO => '用户资产(APP)',
            AdDb::JUMP_TO_BONUS    => '优惠券列表(APP)',
        ];
    }

    public static function type(){
        return [
            AdDb::JUMP_TO_URL      => 'url',
            AdDb::JUMP_TO_PROJECT  => 'project',
            AdDb::JUMP_TO_USERINFO => 'assets',
            AdDb::JUMP_TO_BONUS    => 'bonus',
        ];
    }
}
