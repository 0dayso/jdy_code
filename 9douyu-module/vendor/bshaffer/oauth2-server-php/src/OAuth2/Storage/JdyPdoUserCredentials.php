<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/18
 * Time: 下午5:01
 */

namespace OAuth2\Storage;

use App\Http\Dbs\UserDb;
/**
 * todo 九斗鱼 pdo 存储方式
 * Class JdyUserCredentials
 * @package OAuth2\Storage
 */
class JdyPdoUserCredentials implements UserCredentialsInterface
{

    public function checkUserCredentials($username = null, $password = null){

    }

    public function getUserDetails($username = null){

    }
}