<?php
/**
 * Created by PhpStorm.
 * User: @llper
 * Date: 2016年11月24日
 * Time: 下午4:53
 */

namespace App\Http\Models\Common;

use App\Http\Dbs\DbKvdb\DbKvdbDb;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use Log;


class DbKvdbModel extends Model
{

    public static $codeArr            = [
        'addData'  => 1,

    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_DBKV;

    /**
     * @param $key
     * @param array $data
     * @throws \Exception
     * 添加数据
     */
    public function addData($key,array $data){

        $db = new DbKvdbDb();

        $params = [
            'rawkey'    => $key,
            'md5key'    => md5($key),
            'val'       => json_encode($data),
        ];

        $result = $db->doDbKvdbAdd($params);

        if( empty($result) ){

            throw new \Exception(LangModel::getLang('ERROR_DBKV_ADD_FIELD'), self::getFinalCode('addData'));

        }

        return $result;
    }


    /**
     * @param   $date
     * @return  array
     * @desc    通过date获取数据
     */
    public function getDbKvdbByRawkey( $rawkey ){

        $db = new DbKvdbDb();

        $result = $db ->getDbKvdbByRawkey($rawkey);

        if(!$result) return [];

        return $result;

    }

    /**
     * @param   $page
     * @param   $size
     * @return  array
     * @desc    获取分页数据
     */
    public function getDbKvdbList( $rawkey, $page, $size , $startTime='', $endTime='' ){

        $db     = new DbKvdbDb();

        $result = $db -> getDbKvdbList($rawkey,$page, $size,$startTime,$endTime);

        if(!$result) return [];

        return $result;

    }


}