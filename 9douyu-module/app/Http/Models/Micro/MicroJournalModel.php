<?php

/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/19
 * Time: 上午10:18
 */

namespace App\Http\Models\Micro;

use App\Http\Dbs\Micro\MicroJournalDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class MicroJournalModel extends  Model
{
    public static $codeArr = [
        'addMicroJournalCode'             => 1, //添加
        'editMicroJournalCode'            => 2, //修改
        'deleteMicroJournalCode'          => 3, //删除
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_MICRO_JOURNAL;

    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 添加数据
     */
    public static function doAdd( $data )
    {
        $db         =   new MicroJournalDb();

        $return     =   $db->doAdd( $data );

        if( empty($return) ){

            throw new \Exception(LangModel::getLang("ERROR_ADD_MICRO_JOURNAL_FAILED"),self::getFinalCode('addMicroJournalCode'));
        }

        return $return;
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 更新内容
     */
    public static function doEdit( $id, $data )
    {
        $db         =   new  MicroJournalDb();

        $return     =   $db->doEdit($id,$data);

        if ( empty($return) ){

            throw new  \Exception(LangModel::getLang('ERROR_EDIT_MICRO_JOURNAL_FAILED'),self::getFinalCode('editMicroJournalCode'));
        }

        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @desc 删除数据
     */
    public static function doDelete( $id )
    {
        $db         =   new  MicroJournalDb();

        $return     =   $db->doDelete($id);


        if ( empty($return) ){

            throw new  \Exception(LangModel::getLang('ERROR_DELETE_MICRO_JOURNAL_FAILED'),self::getFinalCode('editMicroJournalCode'));
        }

        return true;
    }
    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @desc 验证ID值是否存在
     */
    public static function doVerifyId( $id )
    {
        $db         =   new MicroJournalDb();

        $return     =   $db->getById($id);

        if( empty($return) ){

            throw new  \Exception(LangModel::getLang('ERROR_EMPTY_MICRO_JOURNAL'),self::getFinalCode('editMicroJournalCode'));
        }
        return true;
    }

}