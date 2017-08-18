<?php
/**
 * User: zhangshuang
 * Date: 16/4/23
 * Time: 14:43
 * Desc: 卡相关model层基类
 */

namespace App\Http\Models\BankCard;

use App\Http\Dbs\BankCardDb;
use App\Http\Dbs\UserDb;
use App\Http\Models\Common\BankCardModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Dbs\AuthCardDb;
use App\Http\Dbs\BankDb;
use Illuminate\Support\Facades\Auth;

class CardModel extends Model{


    public static $codeArr = [
        
        'beforBindgetInfoById'    => 1,
        'cardFrozenByUserId'      => 2,
        'cardUnFrozenByUserId'    => 3,

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_BANK_CARD;


    /**
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    protected  static function beforBind($userId){

        //判断用户是否存在
        $userDb = new UserDb();
        $user = $userDb->getInfoById($userId);

        if(!$user){
            throw new \Exception(LangModel::getLang('ERROR_USER_NOT_EXIST'),self::getFinalCode('beforBindgetInfoById'));
        }
        //判断用户是否有绑卡
        return self::getUserAuthCard($userId);



    }

    /**
     * @param $userId
     * @return mixed
     * 获取用户绑定银行卡
     */
    public static function getUserAuthCard($userId){

        $db = new AuthCardDb();
        $authCard = $db->getAuthCardByUserId($userId);

        return $authCard;
    }

    /**
     * @param $bankId
     * @return mixed
     * 获取用户银行卡信息
     */
    public static function getUserCard($bankId = 0){
        $recordObj = BankDb::select(['name'])->where('id','=', $bankId)->first();
        return is_object($recordObj) ? $recordObj->getAttributes() : [];

    }

    /**
     * @param $userId
     * @return mixed|void
     * @throws \Exception
     * @desc 冻结用户帮定银行卡信息
     */
    public function cardFrozenByUserId($userId){

        $cardInfo = self::getUserAuthCard($userId);

        $res = [];

        if(empty($cardInfo)){

            return $res;

        }

        $db = new AuthCardDb();

        $res = $db->frozenByUserId($userId);

        $bankCardDb = new BankCardDb();

        $bankRes = $bankCardDb->frozenByUserId($userId);

        if(!$res){

            throw new \Exception(LangModel::getLang('ERROR_CARD_FROZEN'),self::getFinalCode('cardFrozenByUserId'));

        }

        return $res;

    }

    /**
     * @param $userId
     * @return mixed|void
     * @throws \Exception
     * @desc 解冻用户帮定银行卡信息
     */
    public function cardUnFrozenByUserId($userId){

        $cardInfo = self::getUserAuthCard($userId);

        $res = [];

        if(empty($cardInfo)){

            return $res;

        }

        $db = new AuthCardDb();

        $res = $db->unFrozenByUserId($userId);

        $bankCardDb = new BankCardDb();

        $bankRes = $bankCardDb->unFrozenByUserId($userId);

        if(!$res){

            throw new \Exception(LangModel::getLang('ERROR_CARD_UNFROZEN'),self::getFinalCode('cardUnFrozenByUserId'));

        }

        return $res;

    }
}