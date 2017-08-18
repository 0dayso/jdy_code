<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/29
 * Time: 11:09
 */

namespace App\Http\Dbs\Bank;

use App\Http\Dbs\JdyDb;

class CardErrorLogDb extends JdyDb{

    const REAPAY = 'Reapal',
          UMPAY  = 'Umpay';

    const STATUS_SUCCESS = 200,
          STATUS_FAIL = 500;

    /**
     * @param $data
     * 添加错误的鉴权日志
     */
    public function addRecord($data){

        self::insert($data);
    }


    /**
     * @param $idCard
     * @param $cardNo
     * @param $name
     * @param string $phone
     * @return mixed
     * 根据三要素鉴权结果
     */
    public function getByThreeElements($idCard,$cardNo,$name,$channel){

        $obj =  self::where('id_card',$idCard)
                ->where('card_no',$cardNo)
                ->where('name',$name)
                ->where('channel',$channel)
                ->where('status',self::STATUS_FAIL)
                ->first();

        return $this->dbToArray($obj);
    }

    /**
     * @param $idCard
     * @param $cardNo
     * @param $name
     * @param string $phone
     * @return mixed
     * 根据三要素鉴权结果
     */
    public function getByFourElements($idCard,$cardNo,$name,$channel){

        return self::where('id_card',$idCard)
            ->where('card_no',$cardNo)
            ->where('name',$name)
            ->where('channel',$channel)
            ->first();
    }
}