<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/20
 * Time: 上午11:11
 */

namespace App\Http\Dbs\User;

use App\Http\Dbs\JdyDb;

/**
 * 用户附加信息
 * Class UserInfoDb
 * @package App\Http\Dbs
 */
class UserInfoDb extends JdyDb
{

    protected $table = 'user_info';

    const

        YMF_CODE     = 'ymf',//我的资产页面【消费额度】

        END_CONST    = 0;

    /**
     * 用户附加信息信息
     * @param $data
     * @return mixed
     */
    public function register($data){

        $this->ip           = $data['ip'];
        $this->user_id      = $data['userId'];
        $this->source_code  = empty($data['source_code'])?'':$data['source_code'];
        $this->invite_code  = empty($data['invite_code'])?'':$data['invite_code'];
        $this->assessment_score = isset($data['assessment_score']) ? $data['assessment_score'] : null;
        $this->save();

        return $this->id;
    }

    /**
     * @param $userId
     * @return array
     * @desc 通过userId获取用户信息
     */
    public function getByUserId( $userId ){

        return $this -> dbToArray(
                self::where('user_id', $userId)->first()
        );

    }

    /**
     * @param $userIds
     * @return mixed
     * 根据多个用户ID获取用户信息
     */
    public function getByUserIds($userIds){

        return self::whereIn('user_id',$userIds)
            ->get()
            ->toArray();
    }

    /**
     * @param $userId
     * @param $data
     * @return mixed
     * @date 更新用户扩展信息
     */
    public function updateInfo($userId, $data){

        return self::where('user_id', $userId)
            ->update($data);

    }

    /**
     * @param $inviteCode
     * @return array
     * @desc 通过邀请码获取用户Id
     */
    public function getByInviteCode( $inviteCode ){

        return $this->dbToArray(
            self::where('invite_code', $inviteCode)
                ->select('user_id')
                ->first()
        );

    }


    /**
     * @desc    获取用户来源
     **/
    public function getUserSource(){
        $result = self::select(
            \DB::raw("
                            COUNT('id') AS totalNum,
                            sum(if(source_code=1,1,0))  AS pcNum,
                            sum(if(source_code=3,1,0))  AS iosNum,                
                            sum(if(source_code=4,1,0))  AS androidNum,
                            sum(if(source_code=2,1,0))  AS wapNum
                        ")
        )
            ->first();
        return $this->dbToArray($result);

    }


}
