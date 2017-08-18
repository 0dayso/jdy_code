<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/18
 * Time: 下午3:20
 * Desc: 项目模型
 */

namespace App\Http\Models\Project;

use App\Http\Dbs\Credit\CreditDb;
use App\Http\Dbs\Invest\InvestDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Model;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Log;
use App\Http\Dbs\Project\ProjectDb;
use Config;
use Cache;

class ProjectModel extends Model{

    public static $codeArr            = [
        'curlPostCreateProject'         => 1,
        'curlPostDelProject'            => 2,
        'curlPostUpdateProject'         => 3,
        'curlPostDetailProject'         => 4,
        'checkProjectArr'               => 5,
        'checkCanInvestByStatus'        => 6,
        'checkCanInvestAmount'          => 7,
        'getInvestMinCashByProductLine' => 8,
        'checkCanUseBonusByProductLine' => 9,
        'checkProjectRateLimit'         => 10
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_PROJECT;

    /**
     * 获取产品线
     * @return array
     */
    public static function getProductLine($lineKey = null){
        $return  = [
            ProjectDb::PRODUCT_LINE_ONE_MONTH               => '九省心_1月期',
            ProjectDb::PRODUCT_LINE_THREE_MONTH             => '九省心_3月期',
            ProjectDb::PRODUCT_LINE_SIX_MONTH               => '九省心_6月期',
            ProjectDb::PRODUCT_LINE_TWELVE_MONTH            => '九省心_12月期',
            ProjectDb::PRODUCT_LINE_FACTORING               => '九安心',
            ProjectDb::PRODUCT_LINE_LIGHTNING_SIX_MONTH     => '闪电付息_6月期',
            ProjectDb::PRODUCT_LINE_LIGHTNING_TWELVE_MONTH  => '闪电付息_12月期',
        ];

        if($lineKey === null)
            return $return;

        if(isset($return[$lineKey])){
            return $return[$lineKey];
        }
        return null;
    }

    /**
     * app4.0获取优惠券
     * @return array
     */
    public static function getAppBonusProductLine(){
        $return  = [
            ProjectDb::PRODUCT_LINE_FACTORING               => '九安心',
            ProjectDb::PRODUCT_LINE_ONE_MONTH               => '1',
            ProjectDb::PRODUCT_LINE_THREE_MONTH             => '3',
            ProjectDb::PRODUCT_LINE_SIX_MONTH               => '6',
            ProjectDb::PRODUCT_LINE_TWELVE_MONTH            => '12',
        ];
        return $return;
    }

    /**
     * @param $productLine
     * @return array
     * @desc 将产品线分割  101=九省心_1月期 product_line:100-九省心 type:1-1月期
     */
    public static function getFormatProjectLine($productLine){

        $data = [];

        $data['type']         = $productLine%100;
        $data['product_line'] = $productLine - $data['type'];

        return $data;

    }

    /**
     * @return array
     * @desc 获取产品线数组
     */
    public static function getProductLineArr()
    {

        return [
            '九省心'   => '/admin/project/lists?product_line=JSX',
            '九安心'   => '/admin/project/lists?product_line=JAX',
            '闪电付息' => '/admin/project/lists?product_line=SDF',
        ];

    }

    /*public static function getStatusArr()
    {

        return [

        ];

        STATUS_UNAUDITED            = 100,  //未审核
        STATUS_AUDITE_FAIL          = 110,  //未通过
        STATUS_UNPUBLISH            = 120,  //未发布

        STATUS_INVESTING            = 130,  //投资中
        STATUS_UNBEGIN              = 140,  //未开始

        STATUS_REFUNDING            = 150,  //还款中
        STATUS_FINISHED             = 160   //已完结

    }*/

    /*
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 远程请求核心创建项目接口
     */
    public static function curlPostCreateProject($data){

        $url = self::getCoreUrl('doCreate');

        $result = HttpQuery::corePost($url, $data);

        Log::Info(__METHOD__,[$result]);

        if(!$result['status']){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_CREATE'), self::getFinalCode('curlPostCreateProject'));
        }

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 远程请求核心删除项目接口
     */
    public static function curlPostDelProject($data){

        $url = self::getCoreUrl('doDelete');

        $result = HttpQuery::corePost($url, $data);

        Log::Info(__CLASS__.__METHOD__.'Info',[$result]);

        if(!$result['status']){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_DELETE'), self::getFinalCode('curlPostDelProject'));
        }

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 远程请求核心删除项目接口
     */
    public static function curlPostUpdateProject($data){

        $url = self::getCoreUrl('doUpdate');

        $result = HttpQuery::corePost($url, $data);

        Log::Info(__CLASS__.__METHOD__.'Info',[$result]);

        if(!$result['status']){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_UPDATE'), self::getFinalCode('curlPostUpdateProject'));
        }

        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 远程请求核心项目接口
     */
    public static function curlPostDetailProject($data){

        $url = self::getCoreUrl('detail');

        $result = HttpQuery::corePost($url, $data);

        Log::Info(__CLASS__.__METHOD__.'Info',[$result]);

        if(!$result['status']){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_DETAIL_GET_FAIL'), self::getFinalCode('curlPostDetailProject'));
        }

        return $result;

    }

    /**
     * @param array $projectIds
     * @return array
     * @desc 根据1-n个项目id获取项目信息
     */
    public static function getProjectListByIds($projectIds){
        if(!is_array($projectIds)) return[];

        $api  = Config::get('coreApi.moduleProject.getProjectListByIds');

        $return = HttpQuery::corePost($api,['project_ids' => implode(',',$projectIds)]);

        if( $return['code'] == Logic::CODE_SUCCESS ){

            return $return['data'];

        }
        return [];
    }

    /**
     * @param $sign
     * @return string
     * @获取curlPost 完整的url
     */
    private static function getCoreUrl( $sign ){

        return Config::get('coreApi.moduleProject.'.$sign);

    }

    /**
     * @param $creditIds
     * @param $creditInfo
     * @return array
     * @desc 格式化更新使用债权数据
     */
    public static function formatCredit($creditIds, $creditInfo){

        $credit = [];

        if(is_array($creditIds)){

            foreach($creditIds as $key => $id){

                $type = $creditInfo[$id]["type"];

                $credit[$key] =  [
                    'id'                        => $id,
                    'update_status_identifier'  => $type,
                    'status_code'               => CreditDb::STATUS_CODE_ACTIVE,
                    'cash'                      => $creditInfo[$id]['cash'],
                ];

            }
        }

        return $credit;

    }

    /**
     * 获取定期项目的投资总额
     */
    public static function getInvestAmount(){
        //今日之前的项目投资总额 m_project_before_today_invest_amount
        $baseKey = 'M_P_B_T_I_A:%s';

        $db       = new InvestDb();
        $today    = ToolTime::dbDate();
        $key      = sprintf($baseKey,$today);

        $beforeTodayAmount  = Cache::get($key);

        if(!$beforeTodayAmount){
            //获取今日之前的投资总额
            $beforeTodayAmount  = $db->getBeforeInvestAmountByDate($today);

            //缓存今日之前的投资总额
            $minutes = 24 * 60;
            Cache::put($key, $beforeTodayAmount, $minutes);
        }

        //今日的项目投资总额 m_project_today_invest_amount
        $todayKey = 'M_P_T_I_A:%s';
        $tKey     = sprintf($todayKey,$today);

        $todayAmount  = Cache::get($tKey);

        if(!$todayAmount){
            //获取今日的投资总额
            $todayAmount = $db->getAfterInvestAmountByDate($today);

            //缓存今日的投资总额
            $minutes = 5;
            Cache::put($tKey, $todayAmount, $minutes);
        }

        $totalAmount = $beforeTodayAmount + $todayAmount;

        return $totalAmount;

    }

    /**
     * 获取项目投资期限
     * @param array $project
     * @return int
     */
    public function getFormatInvestTime($project = [], $projectWay = null){

        $status         =  $project['status'];
        $publishTime    =  $project['publish_at'];  // 发布时间
        $investDays     =  $project['invest_days']; // 融资周期 【天】
        $investTime     =  $project['invest_time']; // 投资期限 【到期还本息 ：天】
        $InvestEndTime  =  date("Y-m-d", strtotime("{$publishTime} +{$investDays} days"));//投资结束时间
        $projectEndTime =  $project['end_at'];      // 项目完结时间
        $refundType     =  $project['refund_type']; // 还款类型

        $formatInvestTime          = $investTime;
        if($projectWay == CreditDb::TYPE_PROJECT_GROUP) {
            if(in_array($status, array(ProjectDb::STATUS_REFUNDING, ProjectDb::STATUS_FINISHED))) {
                    //还款中、完成状态 todo 【项目集、回款中|完结】按满标时间算息【老系统】
                    $formatInvestTime = $this->getBetweenDay(date("Y-m-d",strtotime($projectEndTime)), date("Y-m-d", strtotime($InvestEndTime)));
                }else{
                    if(time() > strtotime($publishTime)) {
                        $formatInvestTime = $this->getBetweenDay(date("Y-m-d",strtotime($projectEndTime)), date("Y-m-d"));
                    }else{
                        $formatInvestTime = $this->getBetweenDay(date("Y-m-d",strtotime($projectEndTime)), date("Y-m-d", strtotime($publishTime)));
                    }
            }
        } elseif($refundType == ProjectDb::REFUND_TYPE_BASE_INTEREST) {
            if(in_array($status, array(ProjectDb::STATUS_REFUNDING, ProjectDb::STATUS_FINISHED))) {
                //还款中、完成状态 todo【到期还本息、回款中|完结】按满标时间算息【老系统】
                $userTime       = $this->getDateDiff(date("Y-m-d",strtotime($InvestEndTime)), date("Y-m-d",strtotime($publishTime)));
                $formatInvestTime = $investTime - $userTime['d'];
            } else if($status == ProjectDb::STATUS_INVESTING){
                $dateDiff                           = $this->getDateDiff($InvestEndTime);
                //过期
                if($dateDiff['invert'] == 1) {
                    $formatInvestTime      = 0;
                } else {
                    //投资中，未发布
                    $publishTime = strtotime($publishTime);
                    if(time() > $publishTime) {
                        $userTime         = $this->getDateDiff(date("Y-m-d"), date("Y-m-d",$publishTime));
                        $formatInvestTime = $investTime - $userTime['d'];
                    }
                }
            }
        }
        return $formatInvestTime;
    }
    /**
     * 获取投资期限单位
     *
     * @param $refundType
     * @param $project_way
     * @return string
     */
    public static function getInvestTimeUnit($refundType = null, $project_way = null){
        if($refundType == ProjectDb::REFUND_TYPE_BASE_INTEREST || $project_way == CreditDb::TYPE_PROJECT_GROUP){
            $investTimeUnit  =  '天';//[项目集 || 到期还本息]
        }else{
            $investTimeUnit  =  '个月';
        }
        return $investTimeUnit;
    }


    /**
     * 获取状态标示
     *
     * @param int $status
     * @return null|string
     */
    public static function getProjectStatusNote($status = 0, $publish_at = ''){
        $dbNow = ToolTime::dbNow();
        $project_type = null;
        switch($status){
            case ProjectDb::STATUS_UNPUBLISH://未发布
            case ProjectDb::STATUS_UNAUDITED://未审核
            case ProjectDb::STATUS_AUDITE_FAIL://未通过
                $project_type  = 'foreshow';
                break;
            case ProjectDb::STATUS_INVESTING://投资中
                if(!empty($publish_at) && $publish_at > $dbNow){
                    $project_type  = 'foreshow';
                }else{
                    $project_type = 'investing';
                }
                break;
            case ProjectDb::STATUS_REFUNDING://还款中
                $project_type = 'refunding';
                break;
            case ProjectDb::STATUS_FINISHED://已还款
                $project_type = 'finished';
                break;
            // full_scale_audit、before_refund todo【新系统不存在】
        }

        return $project_type;
    }

    /**
     * @param array $project
     * @param $cash
     * @return bool
     * @throws \Exception
     * @desc 检测项目可投金额
     */
    public static function checkCanInvest($project=[], $cash)
    {

        //检测项目为空
        self::checkProjectArr($project);

        //检测项目状态
        self::checkCanInvestByStatus($project['status']);

        //起投金额检测
        self::checkMinInvestCash($cash, $project['product_line']);

        //投资闪电付息
        if( $project['product_line'] == ProjectDb::PROJECT_PRODUCT_LINE_SDF ){

            self::checkSdfInvestCash($cash, $project['product_line']);

        }

        //检测项目剩余可投金额
        self::checkCanInvestAmount($project['left_amount'], $cash, $project['product_line']);

        return true;

    }

    /**
     * @param array $project
     * @return bool
     * @throws \Exception
     * @desc 检测项目为空
     */
    protected static function checkProjectArr($project=[]){

        if( empty($project) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_EMPTY'), self::getFinalCode('checkProjectArr'));

        }

        return true;

    }

    /**
     * @param $status
     * @return bool
     * @throws \Exception
     * @desc 检测项目投资状态
     */
    protected static function checkCanInvestByStatus($status){

        if( $status != ProjectDb::STATUS_INVESTING ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_STATUS'), self::getFinalCode('checkCanInvestByStatus'));

        }

        return true;

    }

    /**
     * @param $leftAmount
     * @param $cash
     * @throws \Exception
     * @desc 检测项目可投金额
     */
    protected static function checkCanInvestAmount($leftAmount, $cash, $productLine)
    {

        $minCash = self::getInvestMinCashByProductLine($productLine);

        if( $leftAmount < $cash || (($leftAmount - $cash) < $minCash && ($leftAmount - $cash) > 0 )){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_LEFT_AMOUNT'), self::getFinalCode('checkCanInvestAmount'));

        }

        return true;

    }

    /**
     * @param $cash
     * @param $productLine
     * @return bool
     * @throws \Exception
     * @desc 检测起投金额
     */
    protected static function checkMinInvestCash($cash, $productLine)
    {

        $minCash = self::getInvestMinCashByProductLine($productLine);

        if( $cash < $minCash ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_MIN_AMOUNT'), self::getFinalCode('checkMinInvestCash'));

        }

        return true;

    }

    /**
     * @param $cash
     * @desc 检测闪电付息项目的投资金额必须是起投金额的倍数
     */
    protected static function checkSdfInvestCash($cash, $productLine)
    {

        $minCash = self::getInvestMinCashByProductLine($productLine);

        if( ($cash % $minCash) > 0 ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_SDF_INVEST_CASH'), self::getFinalCode('checkSdfInvestCash'));

        }

        return true;

    }

    /**
     * @param $productLine
     * @return bool
     * @desc 获取项目的最小投资金额限制
     */
    public static function getInvestMinCashByProductLine($productLine)
    {

        $key = 'PROJECT_INVEST_LIMIT_MIN';

        $config = SystemConfigModel::getConfig($key);

        if( isset($config[$productLine]) ){

            return $config[$productLine];

        }

        throw new \Exception(LangModel::getLang('ERROR_PROJECT_EMPTY_CONFIG'), self::getFinalCode('getInvestMinCashByProductLine'));

    }

    /**
     * @param $productLine
     * @return bool
     * @throws \Exception
     * @desc 检测产品线是否可用红包加息券,针对前置付息的判断
     */
    public static function checkCanUseBonusByProductLine($productLine)
    {

        if( $productLine == ProjectDb::PROJECT_PRODUCT_LINE_SDF ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_USE_BONUS'), self::getFinalCode('checkCanUseBonusByProductLine'));

        }

        return true;

    }

    /**
     * @param $productLine
     * @param $rate
     * @param $bonusRate
     * @return bool
     * @throws \Exception
     * @dec 检测项目是否能使用加息券
     * 101 一月期
     * 103 三月期
     * 106 六月期
     * 112 十二月期
     * 200 九安心
     * 306 闪电付息6月期
     * 312 闪电付息12月期
     */
    public static function checkProjectRateLimit($productLine,$rate,$bonusRate,$bonusMoney=0){

        $key = 'PROJECT_CAN_USE_BONUS_PROFIT';

        $config = SystemConfigModel::getConfig($key);

        if(!empty($config[$productLine]) && $rate > $config[$productLine] && ($bonusRate > 0 ||$bonusMoney >0) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_CANNOT_USE_BONUS'), self::getFinalCode('checkProjectRateLimit'));
        }

        return true;

    }

    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * @param $bonusMoney
     * @param $bonusRate
     * @return mixed
     * @throws \Exception
     * @dec 投资定期项目
     */
    public static function doInvest($userId,$projectId,$cash,$bonusMoney,$bonusRate){
        $api = Config::get('coreApi.moduleProject.doInvest');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId,'project_id'=>$projectId,'cash'=>$cash,'bonus_cash'=>$bonusMoney,'bonus_rate'=>$bonusRate));
        if($res['code']==Logic::CODE_SUCCESS){
            return $res['data'];
        }
        throw new \Exception($res['msg']);
    }

    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * @param $bonusMoney
     * @param $bonusRate
     * @return mixed
     * @throws \Exception
     * @dec 零钱投资定期项目
     */
    public static function doInvestByCurrent($userId,$projectId,$cash,$bonusMoney,$bonusRate){
        $api = Config::get('coreApi.moduleProject.doInvestByCurrent');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId,'project_id'=>$projectId,'cash'=>$cash,'bonus_cash'=>$bonusMoney,'bonus_rate'=>$bonusRate));
        if($res['code']==Logic::CODE_SUCCESS){
            return $res['data'];
        }
        throw new \Exception($res['msg']);
    }

    /**
     * @param int $projectId
     * @param int $cash
     * @param int $profit
     * @return array
     * @desc 获取收益
     */
    public function getProfit($projectId,$cash,$profit){
        $fee = array();
        if(empty($projectId))   return $fee;
        $api = Config::get('coreApi.moduleProject.getPlanInterest');
        $res = HttpQuery::corePost($api,array('project_id'=>$projectId,'cash'=>$cash,'profit'=>(int)$profit));
        if($res['code']==Logic::CODE_SUCCESS){
            $fee = $res['data'];
        }
        return $fee;
    }

    /**
     * @param $projectId
     * @param $cash
     * @param string $invest_time
     * @return array
     * @desc 根据项目ID和投资金额获取首次回款记录
     */
    public function getFirstRefund($projectId,$cash,$invest_time = ''){

        $api = Config::get('coreApi.moduleRefund.getFirstRefundRecord');

        $res = HttpQuery::corePost($api,array('project_id'=>$projectId,'cash'=>$cash,'invest_time'=>$invest_time));

        if(!empty($res)){

            return array_shift($res);
        }
        return array();
    }


    /**
     * 获取还款类型 汉字标示
     */
    public static function getRefundTypeNote($refundType){

        $data = [
            ProjectDb::REFUND_TYPE_BASE_INTEREST   => '到期还本息',
            ProjectDb::REFUND_TYPE_ONLY_INTEREST   => '按月付息，到期还本',
            ProjectDb::REFUND_TYPE_FIRST_INTEREST  => '投资当日付息，到期还本',
        ];
        if(isset($data[$refundType])){
            return $data[$refundType];
        }
        return '';
    }

    /**
     * 获取项目类型汉字 标示
     */
    public static function getProjectTypeNote($projectype = 'no'){
        $noteArr = ["foreshow" => "敬请期待", "investing" => "立即投资", "refunding" => "已售罄", "full_scale_audit" => "已售罄", "finished" => "已还款", "before_refund" => "已还款", 'no'=>'不可投资'];
        return isset($noteArr[$projectype]) ? $noteArr[$projectype] : "不可投资" ;
    }

    /**
     * @param $investDay
     * @param int $isNew
     * @throws \Exception
     * 检测项目融资时间
     */
    public static function checkProjectInvestDays( $investDay, $isNew=0 ){

        if($investDay > 20 && $isNew){

            throw new \Exception('融资时间不得大于20天', self::getFinalCode('checkProjectInvestDays'));

        }

    }


}
