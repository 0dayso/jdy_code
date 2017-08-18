<?php

/**
 * 家庭账户
 * 授权人：授权给别人管理自己账户的用户（如：妈妈）
 * 被授权人：接受别人授权，帮忙管理别人账户的用户（如：儿子）
 * 管理关系：儿子帮妈妈管理账户
 * 
 * Class FamilyDb
 */
namespace App\Http\Dbs\Family;

use App\Http\Dbs\JdyDb;

class FamilyDb extends JdyDb{

    public function doAdd($data) {
        $this->my_uid       = $data['my_uid'];
        $this->family_id    = $data['family_id'];
        $this->call_name    = $data['call_name'];
        $this->save();
        return $this->id;
    }

    /**
     * 是否已经拥有授权账户（可以管理别人的账户）
     * @param $userId   被授权用户id
     * @param $familyId 授权用户id
     * @return mixed
     */
    public function hasAuthAccount($myUid, $familyId = false) {

        return $this->where('my_uid', $myUid)
            ->where(function($query) use ($familyId) {
                if($familyId) {
                    $query->where('family_id',$familyId);
                }
            })
            ->where('is_bind',1)
            ->get()->toArray();
    }

    /**
     * 获取主帐号下的所有子帐号
     * @param $userId
     * @return
     */
    public function getFamilyId($userId){

        return $this->where('my_uid', $userId)->where('is_bind',1)->get()->toArray();

    }

    /**
     * 获取家庭账户所属关系记录
     * @param $familyId
     * @return
     */
    public function getByFamilyId($familyId){

        return $this->where('family_id', $familyId)->where('is_bind',1)->get()->toArray();
    }

    /**
     * [获取绑定关系是否存在]
     * @param  [int]  $myUid    
     * @param  [int]  $familyId 
     * @return [array]           
     */
    public function getByMyUidAndFamilyId($myUid,$familyId){

        return $this->where('my_uid', $myUid)->where('family_id', $familyId)->where('is_bind',1)->get()->toArray();

    }

    /**
     * @desc 解绑家庭账户
     * @param $id
     * @return mixed
     */
    public function unbindFamily($id){
        return $this->where('id',$id)->delete();
    }
    
}