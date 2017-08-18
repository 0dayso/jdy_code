<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/4
 * Time: 下午12:01
 */

namespace App\Http\Logics\Project;


use App\Http\Dbs\Activity\ActivityStatisticsDb;
use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Dbs\Invest\InvestDb;
use App\Http\Logics\Activity\Statistics\StatisticsLogic;
use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\CreditAssign\CreditAssignLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Bonus\BonusModel;
use App\Http\Models\Bonus\UserBonusModel;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Invest\CurrentModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Models\Project\ProjectModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Tools\ToolMoney;
use App\Tools\ToolUrl;
use App\Http\Models\Common\CoreApi\ProjectModel as CoreApiProjectModel;
use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;
use App\Http\Dbs\Credit\CreditDb;
use Cache;

class ProjectAppLogic extends Logic
{

    /**
     * 该项目债权关系模型实例
     * @var
     */
    protected $ProjectLinkCreditModel = null;

    protected $ProjectDetailLogic = null;

    public function __construct()
    {
        $this->ProjectLinkCreditModel   = new ProjectLinkCreditModel();

        $this->ProjectDetailLogic      = new ProjectDetailLogic();

    }

    /**
     * @param $projectId
     * @param int $userId
     * @return mixed
     * @desc 项目详情接口
     */
    public function getAppV4ProjectDetail( $projectId, $userId=0 ,$actToken = '')
    {

        try{

            $result['project_detail'] = $this->getAppV4Detail( $projectId );

            $result['user_info']      = $this->getUserInfo($userId);

            $result['agreement']      = $this->getV4Agreement($result['project_detail']['project_way'], $projectId);

            $result['activity']       = $this->getV4RecommendActivity($result['project_detail']['product_line'] ,$actToken);

            $this->setActTokenByUserId($userId , $actToken);

        }catch(\Exception $e){

            return self::callError($e->getMessage(), AppLogic::CODE_ERROR);

        }



        return self::callSuccess($result);

    }


    /**
     * @param $type
     * @param $projectId
     * @return array
     * 协议
     */
    public function getV4Agreement($type='', $projectId){

//        if($type != 30 && $type != 20 && $type != 40){

            $titleArr = explode('-', AgreementLogic::getTitleByType('argument'));

            $result[] = [
                'title' => !empty($titleArr[1]) ? '《'.$titleArr[1].'》' : '',
                'url'   => env('APP_URL_WX').'/agreement?type=argument&project_id='.$projectId,
            ];

//        }

        /*$titleArr = explode('-', AgreementLogic::getTitleByType($type));

        $result[] = [
            'title' => !empty($titleArr[1]) ? '<<'.$titleArr[1].'>>' : '',
            'url'   => env('APP_URL_WX').'/agreement?type='.$type.'&project_id='.$projectId,
        ];*/

        return $result;

    }

    /**
     * @param $projectId
     * @return array
     * @desc 定期项目数据
     */
    public function getAppV4Detail( $projectId ){

        //$project['linkCredit']  = $this->ProjectDetailLogic->getProjectLineCredit($projectId);
        $project['linkCredit']  = $this->ProjectDetailLogic->getCreditByProjectId($projectId);

        $project['project']     = $this->ProjectDetailLogic->getCoreProjectInfo($projectId);

        $project                = $this->formatAppV4GetOutput($project);

        return $project;

    }

    /**
     * @param array $project
     * @return array
     * @desc 定期项目格式化
     */
    public function formatAppV4GetOutput( $project = [] ){

        $linkCredit            = $project['linkCredit'];

       // $projectWay            = ProjectLinkCreditModel::getProjectWay($linkCredit);

       // $projectWay            = ProjectLinkCreditModel::getOldProjectWay($projectWay);
        $creditType            =   $linkCredit['type'] == CreditDb::TYPE_NINE_CREDIT ? CreditDb::TYPE_NINE_CREDIT : $linkCredit['source'];

        $projectWay            =   ProjectLinkCreditModel::getOldProjectWay( $creditType );

        $project               = $project['project'];

        //投资金额 与 投资人数
        $db            = new InvestDb();
        $investSummary = $db->getInvestBrief($project['id']);

        $projectLineKey= $project['type'] + $project['product_line'];

        $projectType  = ProjectModel::getProjectStatusNote($project['status'], $project['publish_at']);

        $project_detail = [

            "project_id"                => $project['id'],
            "project_name"              => $project['name'],

            "profit_percentage"         => number_format($project['profit_percentage'],1),//利率
            "base_rate"                 => number_format($project['base_rate'],1),//利率
            "after_rate"                => number_format($project['after_rate'],1),//利率
            "profit_percentage_note"    => ProjectDb::INTEREST_RATE_NOTE."(%)",

            "total_time"                => $project['format_invest_time'],//项目期限
            "total_time_note"           => $project['invest_time_unit'],//天或月
            "invest_time_note"          => '项目期限',

            "min_invest"                => env('INVEST_UNIT'),//最小投资金额
            "min_invest_note"           => env('INVEST_UNIT').'元起投',
            "invest_hint_note"          => env('INVEST_UNIT').'元起投',
            "can_assign_day"            => '30',
            "can_assign_day_note"       => '30天可转让',
//            "refund_type_name"          => CreditModel::refundAppType($project['refund_type']),
            "refund_type_name"          => $project['refund_type_note'],
            "refund_type"               => $project['refund_type'],

            "publish_time"              => $project['publish_at'],//暂不确定
            "left_amount"               => ToolMoney::formatDbCashDelete($project['total_amount'] - $project['invested_amount']),//剩余可投
            "left_amount_note"          => number_format(ToolMoney::formatDbCashDelete($project['total_amount'] - $project['invested_amount'])),

            //"safe"                      => ToolUrl::getAppBaseUrl() . '/app/topic/safe',//资产安全//
            "detail"                    => ToolUrl::getAppBaseUrl() . '/app/project/product/detail/'. $project['id'] . '?v=2',//产品详情
            "finance_page"              => ToolUrl::getAppBaseUrl() . '/app/topic/financing/desc/'. $projectLineKey, //理财介绍//

            "invest_user"               => $investSummary['num'],//投资人数
            //"invest_amount_total"       => $investSummary['cash'],// 投资总额

            "safe_note"                 => "帐户资金享有银行级安全保障",//显示

            "project_type"              => $projectType,//项目售卖状态 //
            'project_type_note'         => ProjectModel::getProjectTypeNote($projectType),

            ////
            "project_invest_type"       => ProjectDb::PROJECT_INVEST_TYPE_CREDIT,//项目类型-定期
            'product_line'              => $project['product_line'] + $project['type'] ,    //项目类型,用来锁定活动页面项目的匹配度
            "product_line_note"         => $project['product_line_note'],
            "project_way"               => $projectWay,
            "is_new"                       => $project['new'],
            "is_native_calculate"       => SystemConfigModel::getConfig('IS_NATIVE_CALCULATE') ? : 0,

        ];

        if($project['pledge'] == 1){
            $limitCash = !empty(SystemConfigModel::getConfig('NOVICE_PROJECT_INVEST_LIMIT')) ? SystemConfigModel::getConfig('NOVICE_PROJECT_INVEST_LIMIT') : 50000;
            $project_detail['novice_type']      = '1';
            $project_detail['invest_hint_note']  = env('INVEST_UNIT').'元起,最高限额'.$limitCash.'元';
        }

        return $project_detail;

    }

    /**
     * @param $userId
     * @return array
     * @desc 用户信息
     */
    public function getUserInfo($userId){

        $result = $this->getUser($userId);

        $userInfo = [];

        if(!empty($result)){

            //用户资产
            $userAccount = CoreApiUserModel::getCoreApiUserInfoAccount($userId);

            $model = new CurrentModel();
            //当日可用最大零钱
            $current_able_use = $model->getUserLeftInvestOutCashByUserId($userId);

            //新手项目用户最大投资额
            $limitCash = !empty(SystemConfigModel::getConfig('NOVICE_PROJECT_INVEST_LIMIT')) ? SystemConfigModel::getConfig('NOVICE_PROJECT_INVEST_LIMIT') : 50000;
            //获取用户投资记录
            $investData = TermLogic::getUserInvestDataByUserId($userId);

            $userInfo = [
                'id'                => $result['id'],
                'balance'           => $result['balance'],
                'current_cash'      => empty($userAccount['current']['cash'])?0:ToolMoney::formatDbCashDelete($userAccount['current']['cash']),
                'current_able_use'  => $current_able_use,
                'real_name'         => $result['real_name'],
                'identity_card'     => $result['identity_card'],
                'trading_password'  => 'on',
                'novice_invest_max' => empty($investData) ? $limitCash : 0,
            ];
        }

        return $userInfo;

    }

    /**
     * @param $projectId
     * @param $page
     * @return array
     * @desc 项目投资记录
     */
    public function getAppV4ProjectInvestRecords($projectId, $page, $size=10){

        $logicResult     = $this->ProjectDetailLogic->appGetInvestRecord($projectId, $page, $size);

        if($page == 1){
            $logicResult['data'] = array_merge($logicResult['data']['top_list'],$logicResult['data']['list']);
        }else{
            $logicResult['data'] = $logicResult['data']['list'];
        }

        return $logicResult;

    }

    /**
     * @param $projectId
     * @return array
     * 项目回款记录
     */
    public function getAppV4ProjectRefundRecord( $projectId ){

        //项目还款计划
        $logicResult     = $this->ProjectDetailLogic->getRefundPlan( $projectId );

        return Logic::callSuccess($logicResult);

    }

    /**
     * @param $userId
     * @param $projectId
     * @param $client
     * @return array
     *
     * @desc 优惠券列表
     */
    public function getAppV4ProjectAbleUserBonus($userId, $projectId, $client){

        $clientArr      = BonusModel::getClientArr();

        $appRequest     = $clientArr[$client];

        $projectInfo    = CoreApiProjectModel::getProjectDetail($projectId);

        if( empty($projectInfo) ){

            return self::callError('项目不存在');

        }

        $userBonusModel = new UserBonusModel();

        $bonusList      = $userBonusModel -> getAbleUserBonusListByProject($userId, $projectInfo['product_line'], $projectInfo['type'], $appRequest, $projectInfo['refund_type']);

        $logic = new UserBonusLogic();

        $result  = $logic->formatApp4BonusList($bonusList);

        return Logic::callSuccess($result);

    }

    /**
     * @param $cash
     * @param $projectId
     * @param $userBonusId
     * @param $projectType
     * @return array
     * 项目预期收益
     */
    public function getAppV4ProjectGetInterest($cash, $projectId, $userBonusId, $projectType){

        if($projectType){ //债权转让

            $creditAssignLogic = new CreditAssignLogic();

            $profit = $creditAssignLogic->getInvestProfit($projectId, $cash);


        }else{

            $projectDetailLogic = new ProjectDetailLogic();

            //加息券
            if($userBonusId > 0){

                $UserBonusModel = new UserBonusModel();

                $userBonus = $UserBonusModel->getUserBonusById($userBonusId);

                if($userBonus['bonus_info']['type']==BonusDb::TYPE_CASH){

                    $cash += (float)$userBonus['bonus_info']['money'];

                }

            }

            $profit = $projectDetailLogic->getInvestProfit($projectId,$userBonusId,0,$cash);

        }

        return $profit;

    }

    /**
     * @return mixed
     * @desc 返回推荐活动具体信息
     */
    public static function getV4RecommendActivity( $productLine,  $actToken )
    {
        return StatisticsLogic::recommendActivity( $productLine,  $actToken );
    }

    /**
     * @param int $userId
     * @param string $actToken
     * @desc 通过用户Id 设置act_Token
     */
    public static function setActTokenByUserId($userId =0 , $actToken = '')
    {
        if( !empty($userId) && !empty($actToken) ) {

           $cacheKey    =  ActivityStatisticsDb::ACT_TOKEN_CACHE . $userId ;
           \log::info('set-act-cache',['user_id' => $userId ,'token' =>$actToken]);
            Cache::put($cacheKey , $actToken, 60 ) ;
        }
    }

    public static function getActTokenByUserIdUseCache( $userId = 0 ,$actToken='' )
    {
        if( !empty($userId) && empty($actToken) ) {

            $cacheKey       =  ActivityStatisticsDb::ACT_TOKEN_CACHE . $userId ;
             \log::info('get-act-cache',['user_id' => $userId ,'token' =>$actToken]);
            return  Cache::get($cacheKey) ;
        }

        return $actToken ;
    }
}
