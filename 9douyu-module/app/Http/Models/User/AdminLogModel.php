<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/27
 * Time: 下午6:37
 */

namespace App\Http\Models\User;

use App\Http\Dbs\User\AdminLogDb;
use App\Http\Models\Model;

class AdminLogModel extends Model
{

    /**
     * @param $param
     * @throws \Exception
     * @desc 添加操作记录
     */
    public function createRecord( $param = [] ){

        $db     = new AdminLogDb;
        $return = $db->add($param);

        if(!$return){
            throw new \Exception('操作日志记录失败:', self::getFinalCode('createRecord'));
        }

    }

}