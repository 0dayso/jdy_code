<?php
/**
 * User: caelyn
 * Date: 16/7/11
 * Time: 下午12:15
 */
namespace App\Http\Models\Family;

use App\Http\Models\Model;

use App\Http\Dbs\Family\FamilyDb;

class FamilyModel extends Model
{


    /**
     * [获取家庭账户所属关系记录]
     * @param  [int] $familyId 
     * @return [array]           
     */
    public function getByFamilyId($familyId){

        $familyDb = new FamilyDb();

        $res = $familyDb->getByFamilyId($familyId);

        return empty($res)?$res:$res[0];
        
    }

    /**
     * [是否交叉授权]
     * @param  [int] $a,$b
     * @return [bool]
     */
    public function isAuthMore($a,$b){

        $familyDb = new FamilyDb();

        $res = $familyDb->getByFamilyId($a);

        if(empty($res)){

            return false;
        }

        if((int)$res[0]['my_uid']!==(int)$b){

            return false;
        }
        return true;
    }

    /**
     * [授权关系判断，添加]
     * @param  [int] $myUid    
     * @param  [int] $familyId 
     * @param  [string] $callName 
     * @return [bool]           
     */
    public function auth($myUid,$familyId,$callName) {

        $familyDb = new FamilyDb();

        $result  = $familyDb->getByMyUidAndFamilyId($myUid,$familyDb);

        if($result){
            return true;
        }
        $map = [
            'my_uid'        => $myUid,
            'family_id'     => $familyId,
            'call_name'     => $callName
        ];

        $id = $familyDb->doAdd($map);

        if($id){
            return true;
        }
        return false;
    }

    /**
     * [获取主账号下的家庭列表]
     * @param  [int] $myUid 
     * @return [array]        
     */
    public function getByMyUid($myUid) {

        $familyDb = new FamilyDb();

        $result  = $familyDb->getFamilyId($myUid);

        return $result;
    }
    
}