<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/5/31
 * Time: 下午5:03
 */
namespace App\Http\Logics\Project;

use App\Http\Dbs\Current\InvestDb;
use App\Http\Dbs\Current\ProjectDb;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Bonus\BonusModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Current\RateModel;
use App\Http\Models\Project\CurrentModel;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Models\Bonus\UserBonusModel;
use App\Http\Logics\Bonus\BonusLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Lang\LangModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use App\Tools\ToolArray;
use App\Tools\ToolUrl;
use JDY\Logic\Current\CurrentInvestLogic;

class CurrentLogic extends Logic{

    /**
     * @param $projectName
     * @param $cash
     * @param $admin
     * 创建零钱计划项目
     */
    public function create($projectName,$cash,$publishAt = '',$admin = 0){

        try{

            if(!$publishAt){

                $publishAt = ToolTime::dbDate();
            }
            //检查项目金额
            ValidateModel::isCash($cash);

            //检查零钱计划项目名称
            ValidateModel::isNullName($projectName);
            //验证发布时间
            ValidateModel::isDate($publishAt);

            $model = new CurrentModel();
            $model->create($projectName,$cash,$publishAt,$admin);

        }catch(\Exception $e){

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * 获取零钱计划可投项目信息
     */
    public function getShowProject(){

        $db = new ProjectDb();

        $current = $db->getShowProject();

        $current['total_amount'] = ToolMoney::formatDbCashDelete($current['total_amount']);
        $current['invested_amount'] = ToolMoney::formatDbCashDelete($current['invested_amount']);
        //app 1 定期 2零钱计划
        $current['project_invest_type'] = 2;

        $current['project_type'] = 'investing';
        $current['project_type_note'] = '立即投资';
        $current['note']    = '灵活存取';
        $current['name']    = '零钱计划';
        //合并零钱计划利率
        $rateModel      = new RateModel();

        $rateData       = $rateModel->getRate();

        if( isset($rateData['rate']) ){

            $rateData['rate'] = (float)$rateData['rate'];

        }

        $current = array_merge($current,$rateData);

        $current['latest_interest_rate'] = $current['rate'];

        return $current;
    }

    /**
     * App端零钱计划项目详情页面
     */
    public function getDetail($userId = 0){

        $data = $this->getShowProject();

        $totalAmount        = $data['total_amount'];
        $investedAmount     = $data['invested_amount'];

        $freeAmount         = $totalAmount - $investedAmount;   //项目剩余可投金额

        //获取零钱计划配置信息
        $currentModel       = new \App\Http\Models\Invest\CurrentModel();
        $config             = $currentModel->getConfig();
        //最小投资金额文字说明
        $investMinNote         = sprintf(LangModel::getLang('CURRENT_MIN_INVEST_NOTE'),ToolMoney::formatDbCashDelete($config['INVEST_CURRENT_MIN']));

        $projectInfo = [
            "id"            =>  $data['id'],             							//项目id
            "allow_invest"  =>  $freeAmount,                    //可投金额
            "show_type"     => "investing",						//投资状态
            "total_amount"  =>  $totalAmount,               //总投资额
            "project_note"  =>  "立即投资",                 //项目信息
            //"info_url"      => ToolUrl::getAppBaseUrl().'/app/topic/current',  //零钱计划介绍
            "info_url"      => env('APP_URL_WX_HTTPS').'/project/descriptions',  //零钱计划介绍
            "info_tip"      => $investMinNote,         //零钱计划标签
            "publish_time"  => $data['publish_at'],       //发布时间
            "name"          => $data['name'],
        ];

        $userCurrentInfo = [];

        //用户登录情况下
        if($userId > 0){
            //零钱计划帐户信息
            $accountInfo        = \App\Http\Models\Common\CoreApi\CurrentModel::getCurrentUserInfo($userId);

            if(!empty($accountInfo)){
                $userCurrentInfo = [
                    'id'                    => $accountInfo['id'],
                    'user_id'               => $accountInfo['user_id'],
                    'cash'                  => $accountInfo['cash'],
                    'rate'                  => $data['rate'],
                    'yesterday_interest'    => ToolMoney::formatDbCashDelete($accountInfo['yesterday_interest']),
                    'create_time'           => $accountInfo['created_at'],
                    'interest'              => ToolMoney::formatDbCashDelete($accountInfo['interest']),
                ];
            }
        }


        //今日利率信息
        $todayRateInfo  = [
            'rate'              => $data['rate'],
            'profit_percentage' => 1,//app 遗留bug @张为
        ];

        $adDetail   = [];


        $result = [
            'user_id'           => $userId,
            'project_info'      => $projectInfo,
            'user_current_info' => $userCurrentInfo,
            'today_rate_info'   => $todayRateInfo,
            'ad_detail'         => $adDetail,
        ];

        return self::callSuccess($result);
    }


    /**
     * home 接口格式化处理
     *
     * @param array $data
     * @return array
     */
    public function formatAppHomeData($data = []){

        return self::callSuccess($data);
    }


    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @desc 根据时间,获取用户投资获取的数据
     */
    public function getInvestCurrentUserByTime($startTime,$endTime)
    {
        $db     =   new InvestDb();

        return  $db->getInvestCurrentListGroupByUserId($startTime,$endTime);
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @desc 获取零钱计划列表
     */
    public function getCurrentProjectByDate( $startTime , $endTime )
    {
        $db             =   new ProjectDb();

        $currentList    =   $db->getCurrentProjectByDate($startTime,$endTime);

        //合并零钱计划利率
        $rateModel      = new RateModel();

        $rateData       = $rateModel->getRate();

        if( !empty($currentList) ){

            foreach ($currentList as $key   =>  $current ){

                $currentList[$key]  =   $this->formatCurrentProject($current, $rateData);
            }
        }

        return $currentList;
    }

    /**
     * @param $current
     * @return mixed
     * @desc 格式化零钱计划数据
     */
    protected function formatCurrentProject( $current ,$rateData){

        $current['total_amount']    = ToolMoney::formatDbCashDelete($current['total_amount']);
        $current['invested_amount'] = ToolMoney::formatDbCashDelete($current['invested_amount']);
        //app 1 定期 2零钱计划
        $current['project_invest_type'] = 2;

        $current['project_type']      = 'investing';

        $current['project_type_note'] = '投资中';

        if( $current['total_amount'] == $current['invested_amount']){

            $current['project_type']  = 'finished';

            $current['project_type_note'] = '投资中';
        }

        $current['note']            = '灵活存取';

        if( isset($rateData['rate']) ){

            $rateData['rate'] = floor($rateData['rate']);

        }

        $current = array_merge($current,$rateData);

        $current['latest_interest_rate'] = $current['rate'];

        return $current;
    }

    /**
     * @desc 获取App4.0首页零钱计划的信息
     * @param $userId  int
     * @param $client string
     * @return array
     */
    public function getAppHomeV4Current($userId, $client){
        //获取零钱计划项目
        $currentModel = new CurrentModel();

        $currentProject = $currentModel->getProject();

        //home页面和理财列表中不需要显示加息利率，故先注释
        $userNewBonus = 0;
        $bonusRate = 0;

//        //是否显示新手专享
//        $userNewBonus = 1;
//        $userBonusLogic = new UserBonusLogic();
//        $userBonusModel = new UserBonusModel();
//
//        if($userId){
//            $currentRateBonus = $userBonusModel->getCurrentAbleUserBonusList($userId,$client);
//
//            $userNewBonus = $userBonusLogic->getAppNewUserCurrentBonus($currentRateBonus);
//
//            $bonusRate = isset($currentRateBonus[0]['rate']) ? $currentRateBonus[0]['rate'] : 0;
//
//            //如果用户有新手加息券，优先显示新手加息
//            if( $userNewBonus == 1 )
//            {
//                $bonusRate  =  $this->getNewCurrentBonusRate( $currentRateBonus );
//            }
//        }else{
//
//            $configBonusId = SystemConfigLogic::getConfig('CURRENT_BONUS_RATE_ID');
//
//            $bonusRateInfo = BonusLogic::findById($configBonusId);
//
//            $bonusRate = isset($bonusRateInfo['data']['obj']['rate']) ? $bonusRateInfo['data']['obj']['rate'] : 0;
//
//        }
        //零钱计划利率
        $currentRateModel = new RateModel();

        $rate = $currentRateModel->getRate();

        $currentProject['is_new_user_show'] = $userNewBonus;

        $currentProject['bonus_rate'] = $bonusRate;

        $currentProject['rate'] = $rate['rate'];


        return $currentProject;
    }

    /**
     * @desc 获取用户的新手零钱计划加息券
     * @param $currentRateBonus 用户活期加息券
     * return array
     */
    public function getNewCurrentBonusRate( $currentRateBonus )
    {
        if( empty( $currentRateBonus ) )
        {
            return [];
        }

        $userNewBonusId = SystemConfigLogic::getConfig('CURRENT_BONUS_RATE_ID');

        $currentBonus   = ToolArray::arrayToKey( $currentRateBonus, 'bonus_id' );

        return isset( $currentBonus[$userNewBonusId] ) ? $currentBonus[$userNewBonusId]['rate'] : 0 ;

    }

    /**
     * @desc App4.0首页零钱计划返回的数据格式化
     * @return array $homeV4Current
     */
    public function formatAppHomeV4CurrentData($currentProject){
        $homeV4Current = [];

        if(!empty($currentProject)){
            $homeV4Current = [
                'id' => $currentProject['id'],
                'name' => $currentProject['name'],
                'rate' => $currentProject['rate'] ? number_format( $currentProject['rate'], 1 ) : '7.0',
                'rate_note' => ProjectDb::INTEREST_RATE_NOTE.'(%)',
                'invest_time' => '项目期限',
                'invest_note' => '灵活存取',
                'invest_min_cash' => '起投金额',
                'money_note' =>'1元可投',
                'is_new_user_show' =>$currentProject['is_new_user_show'],
                'bonus_rate' =>number_format( $currentProject['bonus_rate'],1 ),
                ];
        }
        return $homeV4Current;
    }

    /**
     * @desc App4.0理财列表零钱计划返回的数据格式化
     * @return array $homeV4Current
     */
    public function formatAppV4ListCurrentData($currentProject){
        $currentListData = [];

        if(!empty($currentProject)){
            $leftAmount = number_format(($currentProject['total_amount']-$currentProject['invested_amount']),2);
            $currentListData = [
                'id' => $currentProject['id'],
                'name' => $currentProject['name'],
                'rate' => $currentProject['rate'] ? number_format($currentProject['rate'],1) : '7.0',
                'left_amount' => $leftAmount,
                'left_amount_unit' => $leftAmount.'元',
                'left_amount_note' => '剩余金额',
                'rate_note' => ProjectDb::INTEREST_RATE_NOTE.'(%)',
                'newer_rate' => '新手加息',
                'invest_note1' => '灵活存取',
                'rate_today' => '当日计息',
                'rate_all' => '全民理财',
                'money_note' =>'1元可投',
                'button_value' => '立即转入',
                'buy_limit' => '每人限购200,000元',
                'is_new_user_show' =>$currentProject['is_new_user_show'],
                'is_new_user_note' => ($currentProject['is_new_user_show'] == 1) ? '新手加息' : '',
                'bonus_rate' =>number_format($currentProject['bonus_rate'],1),
                ];
        }
        return $currentListData;
    }

}
