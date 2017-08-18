<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/2
 * Time: 下午7:13
 */

namespace App\Http\Logics\Current;

use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Current\ProjectDb;
use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Logics\Invest\CurrentLogic as InvestCurrentLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Bonus\BonusModel;
use App\Http\Models\Bonus\UserBonusModel;
use App\Http\Models\Invest\CurrentModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use App\Tools\ToolUrl;

class CurrentUserLogic extends Logic
{


    /**
     * @param int $userId
     * @param $client
     * @return array
     * App端零钱计划项目详情页面 - V4.0
     */
    public function getAppV4Detail($userId = 0, $client=''){

        $result['detail']       = $this->getV4CurrentDetail($userId);

        $result['user_current'] = $this->getV4CurrentUserInfo($userId, $client);

        $result['agreement']    = $this->getV4Agreement();

        return self::callSuccess($result);

    }

    /**
     * @param string $type
     * @return array
     * 活期投资协议
     */
    public function getV4Agreement( $type = 'current' ){

        $titleArr = explode('-', AgreementLogic::getTitleByType($type));

        $result = [
            [
                'title' => !empty($titleArr[1]) ? '《'.$titleArr[1].'》' : '',
                'url'   => env('APP_URL_WX').'/agreement?type='.$type,
            ]
        ];

        return $result;

    }

    /**
     * @return array
     * @desc 项目详情
     */
    public function getV4CurrentDetail($userId = 0){

        $logic = new CurrentLogic();

        $data = $logic->getShowProject();

        $totalAmount        = $data['total_amount'];
        $investedAmount     = $data['invested_amount'];

        $freeAmount         = $totalAmount - $investedAmount;   //项目剩余可投金额

        //获取零钱计划配置信息
        $currentModel       = new CurrentModel();
        $config             = $currentModel->getConfig();
        //最小投资金额文字说明
        $investMinNote         = sprintf(LangModel::getLang('CURRENT_MIN_INVEST_NOTE'),ToolMoney::formatDbCashDelete($config['INVEST_CURRENT_MIN']));

        $result = [
            "id"            => $data['id'],
            'name'          => $data['name'],
            'rate'          => number_format($data['rate'],1),
            'left_amount'   => $freeAmount,
            'left_amount_note'  => number_format($freeAmount,2),
            "total_amount"  => $totalAmount,               //总投资额
            'rate_note'     => ProjectDb::INTEREST_RATE_NOTE.'(%)',
            'count_note'    => '持有金额(元)',
            'yesterday_interest_note' => '昨日收益(元)',
            'safe_note'     => '帐户资金享有银行级安全保障',
            'input_note'    => $investMinNote,
            'min_invest_cash' => ToolMoney::formatDbCashDelete($config['INVEST_CURRENT_MIN']),
            "publish_time"  => $data['publish_at'],       //发布时间
            //'detail_url'    => env('APP_URL_WX').'/app/topic/current',
            'detail_url'    => env('APP_URL_WX').'/project/descriptions',
            'safe_url'      => env('APP_URL_WX').'/article/security',
            "is_native_calculate"       => SystemConfigModel::getConfig('IS_NATIVE_CALCULATE') ? : 0,
        ];

        //$result['new_user_add_rate'] = !$userId ? '4.0' : '';
        $result['new_user_add_rate'] = '';

        return $result;

    }

    /**
     * @param $userId
     * @param $client
     * @return array
     * @desc 用户信息
     */
    public function getV4CurrentUserInfo( $userId, $client ){

        $result = [];

        //用户登录情况下
        if($userId > 0){

            $userInfo = $this->getUser($userId);

            //零钱计划帐户信息
            $accountInfo        = \App\Http\Models\Common\CoreApi\CurrentModel::getCurrentUserInfo($userId);

            //用户加息利率
            $clientArr      = BonusModel::getClientArr();

            $appRequest     = $clientArr[$client];

            $userBonusModel = new UserBonusModel();

            //正在使用中的加息券
            $usingBonus     = $userBonusModel -> getUsingCurrentBonusList($userId);

            //加息券
            $bonusList      = $userBonusModel -> getCurrentAbleUserBonusList($userId, $appRequest);

            $bonus          = array_filter($bonusList);
            $countCoupons   = count($bonus);

            $currentModel       = new CurrentModel();
            $investMax                  = $currentModel->getMaxInvestInCash($userId);


            if(!empty($accountInfo)){

                $result = [
                    'user_id'               => $accountInfo['user_id'],
                    'amount'                => $accountInfo['cash'],
                    'yesterday_interest'    => (float)ToolMoney::formatDbCashDelete($accountInfo['yesterday_interest']),
                    'create_time'           => $accountInfo['created_at'],
                    'interest'              => ToolMoney::formatDbCashDelete($accountInfo['interest']),
                    'add_rate'              => empty($usingBonus['rate'])?'':number_format($usingBonus['rate'],1),
                    'count_coupons'         => $countCoupons,
                    'invest_max_amount'     => max(0, ($investMax - $accountInfo['cash'])),
                ];
                $result['out_max_cash'] = $currentModel->getUserLeftInvestOutCashByUserId($userId);

            }else{
                $result['invest_max_amount'] = $investMax;
            }

            if(!empty($userInfo)){

                $result['user_id']          = $userInfo['id'];
                $result['balance']          = $userInfo['balance'];

            }

        }

        return $result;

    }

    /**
     * @param $userId
     * @return array
     * @desc 活期收益记录
     */
    public function getAppCurrentInterestList($userId){

        $logic = new UserLogic();

        return $logic->getCurrentInterestList($userId);

    }

    /**
     * @param $userId
     * @param $client
     * @return array
     * @desc 获取活期可以优惠券
     */
    public function getAppV4CurrentUserAbleBonus($userId, $client){

        $userBonusModel = new UserBonusModel();

        //用户加息利率
        $clientArr      = BonusModel::getClientArr();

        $appRequest     = $clientArr[$client];

        //可用加息券信息
        $bonusList      = $userBonusModel -> getCurrentAbleUserBonusList($userId, $appRequest);

        $userBonusLogic = new UserBonusLogic();

        $bonusInfo      = $userBonusLogic->formatApp4BonusList($bonusList);

        return self::callSuccess($bonusInfo);

    }

    /**
     * @param $userId
     * @param $cash
     * @param $client
     * @param int $bonusId
     * @return array
     * 活期转入
     */
    public function currentAppV4Invest($userId, $cash, $client, $bonusId=0){

        $data = [
            'user_id'   => $userId,
            'cash'      => $cash,
            'from'      => $client,
            'bonus_id'  => $bonusId,
        ];

        $investLogic = new InvestCurrentLogic();

        $return = $investLogic->doInvest($data, false);

        if($return['status']){

            //零钱计划加息券ID
            $bonusId = $data['bonus_id'];
            //零钱计划转入金额
            $cash   = $data['cash'];


            $info               = $investLogic->getInvestData($bonusId,$cash,$data['user_id'],$data['from']);

            $info['rate']       = (float)$info['rate'];
            if(!empty($info['add_rate'])){
                $info['add_rate']       = (float)$info['add_rate'];
            }

            //$info['interest_start'] = ToolTime::dbDate();
            //$info['interest_end']   = ToolTime::getDateAfterCurrent();

            unset($info['id']);

            $result['info'] = $info;

            \Log::info('currentAppV4SuccessInvest', [$result]);
            return self::callSuccess($result);

        }else{

            \Log::info('currentAppV4ErrorInvest', [$return]);
            return self::callError($return['msg'], AppLogic::CODE_ERROR);
        }

    }

    /**
     * @param $userId
     * @param $cash
     * @param $client
     * @return array
     *
     * 活期转出
     */
    public function currentAppV4InvestOut($userId, $cash, $client){

        $data = [
            'user_id'   => $userId,
            'cash'      => $cash,
            'from'      => $client,
        ];

        $investLogic = new InvestCurrentLogic();

        $return = $investLogic->doInvestOut($data, false);

        if($return['status']){

            \Log::info('currentAppV4SuccessInvest', [$return]);
            return self::callSuccess([]);

        }else{

            \Log::info('currentAppV4ErrorInvest', [$return]);
            return self::callError($return['msg'], AppLogic::CODE_ERROR);
        }


    }

    /**
     * @param $userId
     * @param $bonusId
     * @param $client
     * @return array
     * 使用活期加息券
     */
    public function currentAppV4UsedBonus($userId, $bonusId, $client){

        $data = [
            'user_id'   => $userId,
            'bonus_id'  => $bonusId,
            'from'      => $client,
        ];

        $logic = new UserBonusLogic();

        $result = $logic->doUserCurrentBonus($data, false);

        if($result['status']){

            \Log::info('currentAppV4SuccessInvest', [$result]);
            return self::callSuccess($result['data']);

        }else{

            \Log::info('currentAppV4ErrorInvest', [$result]);
            return self::callError($result['msg'], AppLogic::CODE_ERROR);
        }

    }

    /**
     * @param $cash
     * @param $rate
     * @param int $addRate
     * @return array
     * @desc 活期预期收益
     */
    public function currentAppV4GetInterest($cash, $rate, $addRate=0){

        $rate = (float)$rate;
        $addRate = (float)$addRate;
        $cash   = (int)$cash;

        $totalRate = $rate + $addRate;

        $oneDayBaseInterest = $cash / 365 / 100;

        $result = [
            'interest'      => round($oneDayBaseInterest * $rate , 2),
            'add_interest'  => round($oneDayBaseInterest * $addRate , 2),
            'total_interest'    => round($oneDayBaseInterest * $totalRate , 2),
        ];

        return self::callSuccess($result);

    }

}
