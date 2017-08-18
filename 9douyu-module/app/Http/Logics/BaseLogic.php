<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 17/2/17
 * Time: 下午6:42
 */

namespace App\Http\Logics;

use Illuminate\Support\Facades\DB;

/**
 * 逻辑基础类
 *
 * Class BaseLogic
 * @package App\Http\Logics
 */
abstract class BaseLogic
{
    public static function beginTransaction()
    {
        DB::beginTransaction();
    }

    public static function rollback()
    {
        DB::rollback();
    }

    public static function commit()
    {
        DB::commit();
    }
}