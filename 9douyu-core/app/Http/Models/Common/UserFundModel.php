<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/13
 * Time: 上午10:14
 * Desc: 更新账户余额 + 生成资流水
 */

namespace App\Http\Models\Common;

use App\Http\Dbs\UserDb;
use App\Http\Dbs\FundHistoryDb;
use App\Http\Models\Model;
use App\Lang\LangModel;

class UserFundModel extends Model
{

    public static $codeArr = [
        'decreaseUserBalanceAdd'                => 1,
        'decreaseUserBalanceUpdateBalance'      => 2,
        'increaseUserBalanceAdd'                => 3,
        'increaseUserBalanceUpdateBalance'      => 4,
        'increaseUserBalance'                   => 5,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_COMMON_USER_FUND;

    /**
     * @param $userId
     * @param $cash
     * @param $eventId
     * @param string $note
     * @desc 减少账户余额
     */
    public function decreaseUserBalance($userId, $cash, $eventId, $note='')
    {

        $userModel = new UserModel();

        $userModel->checkUserBalance($userId, $cash);

        $cash = -abs($cash);

        $userDb = new UserDb();

        //更新账户余额
        $userRes = $userDb->updateBalance($userId, $cash);

        if( !$userRes ){

            throw new \Exception(LangModel::getLang('ERROR_USER'), self::getFinalCode('decreaseUserBalanceUpdateBalance'));

        }

        $fundHistoryDb = new FundHistoryDb();

        $fundData = $fundHistoryDb->getFundData($userId, $cash, $eventId, $note);

        //插入资金流水
        $fundRes = $fundHistoryDb->add($fundData);

        if( !$fundRes ){

            throw new \Exception(LangModel::getLang('ERROR_FUND_HISTORY'), self::getFinalCode('decreaseUserBalanceAdd'));

        }

        return $fundRes;

    }

    /**
     * @param $userId
     * @param $cash
     * @param $eventId
     * @param string $note
     * @return bool
     * @throws \Exception
     * @desc 增加账户金额
     */
    public function increaseUserBalance($userId, $cash, $eventId, $note='')
    {

        $cash = abs($cash);

        $userDb = new UserDb();

        //更新账户余额
        $userRes = $userDb->updateBalance($userId, $cash);

        if( !$userRes ){

            throw new \Exception(LangModel::getLang('ERROR_USER'), self::getFinalCode('increaseUserBalance'));

        }

        $fundHistoryDb = new FundHistoryDb();

        $fundData = $fundHistoryDb->getFundData($userId, $cash, $eventId, $note);

        //插入资金流水
        $fundRes = $fundHistoryDb->add($fundData);

        if( !$fundRes ){

            throw new \Exception(LangModel::getLang('ERROR_FUND_HISTORY'), self::getFinalCode('increaseUserBalance'));

        }

        return $fundRes;
    }







}