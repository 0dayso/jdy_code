<?php
/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2017/3/29
 * Time: 下午2:15
 */

namespace App\Http\Models\AdminUsers;

use App\Http\Dbs\AdminUsers\AdminUsersDb;
use App\Http\Models\Model;
use App\Lang\LangModel;

class AdminUsersModel extends Model
{

    /**
     * @param $id
     * @return array
     * @throws \Exception
     * @desc 通过Id获取管理员信息
     */
    public function getUserInfoById($id){

        $db = new AdminUsersDb();

        $result = $db->getUserInfoById($id);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'), self::getFinalCode('getUserInfoById'));
        }

        return $result;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 通过Id修改管理员信息
     */
    public function doUpdate($id, $data){

        $db = new AdminUsersDb();

        $result = $db -> edit($id, $data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_ARTICLE_EDIT'), self::getFinalCode('doUpdate'));
        }

        return $result;
    }

    /**
     * @param $email
     * @return array
     * @throws \Exception
     * @desc 通过email获取管理员信息
     */
    public function getUserInfoByEmail($email){

        $db = new AdminUsersDb();

        $result = $db->getUserInfoByEmail($email);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'), self::getFinalCode('getUserInfoByEmail'));
        }

        return $result;
    }

    /**
     * @param $verify
     * @param $usedVerify
     * @return bool
     * @throws \Exception
     * @desc 验证工号是否正确!
     */
    public function checkVerifyFormat( $verify , $usedVerify )
    {
        if( $verify != $usedVerify ) {
            
            throw new \Exception('输入的工号不正确!', self::getFinalCode('getUserInfoById'));
        }
        return true;

    }
    
}