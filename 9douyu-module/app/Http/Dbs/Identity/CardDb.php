<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/18
 * Time: 17:18
 */
namespace App\Http\Dbs\Identity;

use App\Http\Dbs\JdyDb;

class CardDb extends JdyDb{

    protected $table = 'identity_card';


    public function addRecored($name,$idCard,$appRequest,$photo=''){

        $res = true;

        if(empty($this->getInfoByIdCard($name, $idCard))){

            $this->name             = $name;
            $this->identity_card    = strtoupper($idCard);
            $this->app_request      = $appRequest;
            $this->photo            = $photo;

            $res = $this->save();

        }

        return $res;

    }

    /**
     * @desc 根据认证时间获取实名认证用户身份证号
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getIdentityByAuthTime($startTime,$endTime){

        return self::select('identity_card','app_request','name',\DB::raw('if(length(`identity_card`) = 18, SUBSTRING(`identity_card`,7,8), SUBSTRING(`identity_card`,5,8)) as birthday'))
            ->where('created_at','>=',$startTime)
            ->where('created_at','<=',$endTime)
            ->get()
            ->toArray();
    }

    /**
     * @param $name
     * @param $idCard
     * @return mixed
     * @desc 查询信息是否存在
     */
    public function getInfoByIdCard($name, $idCard){

        return self::where('identity_card', $idCard)
            ->where('name', $name)
            ->first();

    }

}