<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/5/31
 * Time: 上午10:43
 * Desc: 项目逻辑处理器
 */

namespace App\Http\Logics\Project;

use App\Http\Dbs\Credit\CreditAllDb;
use App\Http\Dbs\Credit\CreditDb;
use App\Http\Dbs\Credit\CreditUserLoanDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Logics\Credit\CreditUserLoanLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\CoreApi\StatisticsModel;
use App\Http\Models\Common\LoanUserApi\LoanUserCreditApiModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Credit\CreditAllModel;
use App\Http\Models\Credit\Lable\CreditFactoringModel;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Credit\Lable\CreditLableModel;
use App\Http\Models\Credit\Lable\CreditLightningSixModel;
use App\Http\Models\Credit\Lable\CreditLightningTwelveModel;
use App\Http\Models\Credit\Lable\CreditNineOneModel;
use App\Http\Models\Credit\Lable\CreditNineSixModel;
use App\Http\Models\Credit\Lable\CreditNineThreeModel;
use App\Http\Models\Credit\Lable\CreditNineTwelveModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Http\Models\Project\ProjectModel;
use App\Http\Models\Project\ProjectExtendModel;
use App\Http\Models\Common\CoreApi\ProjectModel as CoreApiProjectModel;
use App\Jobs\Refund\ProjectJob;
use App\Tools\ExportFile;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Log;
use Event;
use Queue;

class ProjectLogic extends Logic
{
    protected $userId = 1; //todo lqh-当前登录后台用户管理员id

    //投资框状态
    static $statusArr = array(
        'no_login'                    => 'noLogin',                    //未登陆
        'no_name_checked'             => 'noNameCheck',                //未实名认证
        'no_password_checked'         => 'noSetTrade',                 //未实名认证-未设置交易密码
        'no_start'                    => 'notStart',                   //项目未开始
        'can_invest'                  => 'canInvest',                  //项目可以投资
        'refunding'                   => 'refund',                     //还款中
        'finished'                    => 'finished',                   //已经还款

    );

    /**
     * @param $productId
     * @return mixed
     * @获取债券列表
     */
    public static function getCredit( $productId , $isEdit = true){
        $productLine = self::getCreditFunName();
        $funName = $productLine[$productId];
        return self::$funName($isEdit);
    }

    /**
     * @desc 获取产品线
     * @return mixed
     */
    public static function getProductLine(){
        return ProjectModel::getProductLine();
    }

    /**
     * @desc 还款方式
     * @return mixed
     */
    public static function getRefundType(){
        return CreditModel::refundTypeForOperation();
    }

    /**
     * @return array
     * @desc 获取产品线数组
     */
    public static function getProductLineArr()
    {

        return ProjectModel::getProductLineArr();

    }


    /**
     * @param $projectLine
     * @param $page
     * @param $size
     * @param string $status
     * @param $projectIds
     * @return array
     * @desc 通过产品线分类,分页获取项目列表
     */
    public function getListByProjectLine($projectLine, $page, $size, $status='', $type=false, $projectIds = false)
    {
        if ($projectLine == 'JSX') {

            $data = \App\Http\Models\Common\CoreApi\ProjectModel::getJsxProjectList($page, $size, $status, $type);

        } elseif($projectLine == 'JAX') {

            $data = \App\Http\Models\Common\CoreApi\ProjectModel::getJaxProjectList($page, $size, $status, $type);

        } elseif($projectLine == 'SDF') {

            $data = \App\Http\Models\Common\CoreApi\ProjectModel::getSdfProjectList($page, $size, $status, $type);
        } else {

            $data = \App\Http\Models\Common\CoreApi\ProjectModel::getProjectListByStatus($page, $size, $status, $projectIds);
        }

        if( isset($data['list']) && !empty($data['list']) ){

            foreach ($data['list'] as $key => $record){

                //格式化利率
                $data['list'][$key]['profit_percentage']    = (float)$record['profit_percentage'];
                $data['list'][$key]['base_rate']            = (float)$record['base_rate'];
                $data['list'][$key]['after_rate']           = (float)$record['after_rate'];
            }

        }else{

            $data = ['total' => 0, 'list' => []];

        }

        return $data;

    }

    /**
     * @return null|void
     * @desc 获取投资中的普付宝项目（1个）
     */
    public function getPfbProjectDetail(){
        $data = \App\Http\Models\Common\CoreApi\ProjectModel::getPfbProjectDetail();

        return $data;
    }

    /**
     * @desc 获取优选项目列表[九安心＋九省心]
     * @param $productLine array
     * @param $page int
     * @param $size int
     * @param $status
     * @return array
     */

    public function getPreferredProjectlist( $productLine, $page, $size, $status )
    {

        $data = [];

        $data = \App\Http\Models\Common\CoreApi\ProjectModel::getProjectList($productLine, $page, $size, $status);

        if( isset($data['list']) && !empty($data['list']) ){

            foreach ($data['list'] as $key => $record){

                //格式化利率
                $data['list'][$key]['profit_percentage']    = (float)$record['profit_percentage'];
                $data['list'][$key]['base_rate']            = (float)$record['base_rate'];
                $data['list'][$key]['after_rate']           = (float)$record['after_rate'];
            }

        }else{

            $data = ['total' => 0, 'list' => []];

        }

        return $data;
    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 获取已完结项目列表
     */
    public function getFinishedList($page, $size){

        $data = $data = \App\Http\Models\Common\CoreApi\ProjectModel::getProjectFinishList($page, $size);

        if( isset($data['list']) && !empty($data['list']) ){

            foreach ($data['list'] as $key => $record){

                //格式化利率
                $data['list'][$key]['profit_percentage']    = (float)$record['profit_percentage'];
                $data['list'][$key]['base_rate']            = (float)$record['base_rate'];
                $data['list'][$key]['after_rate']           = (float)$record['after_rate'];

                //格式化金额
                $data['list'][$key]['total_amount'] = ToolMoney::formatDbCashDelete($record['total_amount']);
                $data['list'][$key]['guarantee_fund'] = ToolMoney::formatDbCashDelete($record['guarantee_fund']);
                $data['list'][$key]['invested_amount'] = ToolMoney::formatDbCashDelete($record['invested_amount']);
                $data['list'][$key]['left_amount'] = ToolMoney::formatDbCashDelete($record['left_amount']);

            }

        }else{

            $data = ['total' => 0, 'list' => []];

        }

        return $data;

    }

    /**
     * @desc 九省心1月期债权例表
     * @return mixed
     */
    public static function getCreditNineOne($isEdit = true){
        $creditModel = new CreditNineOneModel();
        $condition = [];
        if(!$isEdit) {
            $condition[] = ['can_use_amounts', '>', 0];
            $condition[] = ['expiration_date','>', date('Y-m-d')];
        }
        return $creditModel->getUnusedCreditList($condition);
    }

    /**
     * @desc 九省心3月期债权例表
     * @return mixed
     */
    public static function getCreditNineThree($isEdit = true){
        $creditModel = new CreditNineThreeModel();
        $condition = [];
        if(!$isEdit) {
            $condition[] = ['can_use_amounts', '>', 0];
            $condition[] = ['expiration_date','>', date('Y-m-d')];
        }
        return $creditModel->getUnusedCreditList($condition);
    }


    /**
     * @desc 九省心3月期债权例表
     * @return mixed
     */
    public static function getCreditNineSix($isEdit = true){
        $creditModel = new CreditNineSixModel();
        $condition = [];
        if(!$isEdit) {
            $condition[] = ['can_use_amounts', '>', 0];
            $condition[] = ['expiration_date','>', date('Y-m-d')];
        }
        return $creditModel->getUnusedCreditList($condition);
    }

    /**
     * @desc 九省心12月期债权例表
     * @return mixed
     */
    public static function getCreditNineTwelve($isEdit = true){
        $creditModel = new CreditNineTwelveModel();
        $condition = [];
        if(!$isEdit) {
            $condition[] = ['can_use_amounts', '>', 0];
            $condition[] = ['expiration_date','>', date('Y-m-d')];
        }
        return $creditModel->getUnusedCreditList($condition);
    }

    /**
     * @desc 九安心债权例表
     * @return mixed
     */
    public static function getCreditFactoring($isEdit = true){
        $creditModel = new CreditFactoringModel();
        $condition = [];
        if(!$isEdit) {
            $condition[] = ['can_use_amounts', '>', 0];
            $condition[] = ['expiration_date','>', date('Y-m-d')];
        }
        return $creditModel->getUnusedCreditList($condition);
    }

    /**
     * @desc 闪电付息6月期债权例表
     * @return mixed
     */
    public static function getCreditLightningSix($isEdit = true){
        $creditModel = new CreditLightningSixModel();
        $condition = [];
        if(!$isEdit) {
            $condition[] = ['can_use_amounts', '>', 0];
            $condition[] = ['expiration_date','>', date('Y-m-d')];
        }
        return $creditModel->getUnusedCreditList($condition);
    }

    /**
     * @desc 闪电付息12月期债权例表
     * @return mixed
     */
    public static function getCreditLightningTwelve($isEdit = true){
        $creditModel = new CreditLightningTwelveModel();
        $condition = [];
        if(!$isEdit) {
            $condition[] = ['can_use_amounts', '>', 0];
            $condition[] = ['expiration_date','>', date('Y-m-d')];
        }
        return $creditModel->getUnusedCreditList($condition);
    }

    /**
     * @param $data
     * @return mixed
     * @desc 请求核心创建项目接口
     */
    public function doCreate($data){

        self::beginTransaction();
        try {

            $projectInfo = $this->filterParams($data);
            $creditIds   = $data['credit_id'];

            //融资时间不得大于20天
            ProjectModel::checkProjectInvestDays($projectInfo['invest_days'], $projectInfo['new']);

            //验证债权金额跟项目金额
            //ProjectLinkCreditModel::checkCreditTotal($projectInfo['total_amount'], $creditInfo);

            //核心创建项目
            $result = CoreApiProjectModel::doCreateProject($projectInfo);
            $projectId = $result['data']['project_id'];

            Log::info(__METHOD__.'createProjectLinkCredit', [$result]);


            //匹配债权
            ProjectLinkCreditNewModel::createProjectLinkCreditIds( $projectId, $creditIds );
            //更新债权状态
            CreditAllModel::updateCreditStatus($creditIds, CreditAllDb::STATUS_USED);


            $attributes['project']        = $projectInfo;
            $attributes['project_id']     = $projectId;
            $attributes['credit']         = $creditIds;

            Log::info(__METHOD__.'Success', $attributes);

            self::commit();

            $newcomerArr = [
                'project_id'    => $projectId,
                'newcomer'      => $projectInfo['newcomer'],
            ];
            \Event::fire('App\Events\Project\CreateProjectSuccessEvent', [$newcomerArr]);


        }catch (\Exception $e){
            self::rollback();

            try{

                if(isset($projectId)){

                    $data['project_id'] = $projectId;
                    //核心创建项目
                    $result = CoreApiProjectModel::doDeleteProject( $projectId );

                    if($result['status']){
                        Log::info(__METHOD__.'PostDeleteSuccess', $result);
                    }

                }

            }catch (\Exception $e){

                Log::info(__METHOD__.'PostDeleteError', [$result,$data]);

            }

            $attributes['project_id']     = isset($projectId)? $projectId : 0;
            //$attributes['project']        = $projectInfo;
            //$attributes['credit']         = $creditInfo;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$result]);

    }

    /*-------------- 重构 API 添加项目 ----------------*/
    /**
     * @param $data
     * @return array
     * @desc 请求核心创建项目接口
     */
    public function apiDoCreate( $data ){

        self::beginTransaction();
        try {

            $projectInfo = $this->filterParams($data);

            $projectInfo['id'] = $data['id'];
            $projectInfo['status'] = ProjectDb::STATUS_INVESTING;

            //核心创建项目
            $result = CoreApiProjectModel::doCreateProject($projectInfo);
            //$result = ProjectModel::curlPostCreateProject( $projectInfo );
            $projectId = $result['data']['project_id'];

            $attributes['project']        = $projectInfo;
            $attributes['project_id']     = $projectId;

            Log::info(__METHOD__.'Success', $attributes);

            self::commit();
        }catch (\Exception $e){
            self::rollback();

            try{

                if(isset($projectId)){

                    $data['project_id'] = $projectId;
                    //核心删除项目
                    $result = CoreApiProjectModel::doDeleteProject($projectId);

                    if($result['status']){
                        Log::info(__METHOD__.'PostDeleteSuccess', $result);
                    }
                }

            }catch (\Exception $e){

                Log::info(__METHOD__.'PostDeleteError', [$result,$data]);

            }

            $attributes['project_id']     = isset($projectId)? $projectId : 0;
            $attributes['project']        = isset($projectInfo)? $projectInfo : '';
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();
            $attributes['line']           = $e->getLine();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$result]);

    }
    /*-------------- 重构 API 添加项目 ----------------*/

    /**
     * @return array
     * @desc 根据产口线获取债权方法
     */
    public static function getCreditFunName(){

        return [
            ProjectDb::PRODUCT_LINE_ONE_MONTH               => 'getCreditNineOne',
            ProjectDb::PRODUCT_LINE_THREE_MONTH             => 'getCreditNineThree',
            ProjectDb::PRODUCT_LINE_SIX_MONTH               => 'getCreditNineSix',
            ProjectDb::PRODUCT_LINE_TWELVE_MONTH            => 'getCreditNineTwelve',
            ProjectDb::PRODUCT_LINE_FACTORING               => 'getCreditFactoring',
            ProjectDb::PRODUCT_LINE_LIGHTNING_SIX_MONTH     => 'getCreditLightningSix',
            ProjectDb::PRODUCT_LINE_LIGHTNING_TWELVE_MONTH  => 'getCreditLightningTwelve',
        ];

    }

    /**
     * @param $data
     * @return array
     * @desc 项目post参数过滤
     */
    public function filterParams($data){

        $productLine     = (int)$data['product_line'];

        $productLineType = ProjectModel::getFormatProjectLine($productLine);

        $params = [
            'total_amount' => ToolMoney::formatDbCashAdd($data['total_amount']),           //项目总额
            'invest_days'  => (int)$data['invest_days'],            //融资时间
            'invest_time'  => (int)$data['invest_time'],            //投资期限
            'refund_type'  => (int)$data['refund_type'],            //还款方式
            'type'         => $productLineType['type'],             //项目类型
            'product_line' => $productLineType['product_line'],     //项目类型
            'base_rate'    => (float)$data['base_rate'],              //基准利率
            'after_rate'   => (float)$data['after_rate'],             //平台加息
            'created_by'   => $this->getAdminUserId(),                        //创建人  todo lqh 应该是个变量
            'publish_time' => $data['publish_time'],                //发布时间
             'name'        => isset($data['name']) && !empty($data['name']) ? $data['name']:self::getProductLine()[$productLine] ,                                //项目名称
            //'name'         => isset($data['name'])?$data['name']:self::getProductLine()[$productLine], //项目名称
            'end_at'       => isset($data['end_at'])?$data['end_at']:'',                      //项目截止时间
            'pledge'       => empty($data['pledge'])?0:(int)$data['pledge'],                 //普付宝项目专享
            'newcomer'     => empty($data['newcomer'])?0:(int)$data['newcomer'],               //新手专享
            'new'          => empty($data['new'])?0:(int)$data['new'],
        ];

        return $params;

    }

    /**
     * @param $projectId
     * @param $data
     * @return mixed
     * @desc 请求核心创建项目接口 todo lqh
     */
    public function doUpdate($projectId, array $data){

        $result = '';

        self::beginTransaction();
        try {

            $projectInfo = $this->filterParams($data);
            $projectInfo['project_id'] = $projectId;
            $creditIds   = $data['credit_id'];

            //融资时间不得大于20天
            ProjectModel::checkProjectInvestDays($projectInfo['invest_days'], $projectInfo['new']);

            //回退使用债权
            $this->recoverCreditInfo( $projectId );

            //更新使用
            $this->useCreditInfo( $projectId, $creditIds);


            //核心更新项目
            $result    = CoreApiProjectModel::doUpdateProject($projectInfo);

            $attributes['project']        = $projectInfo;
            $attributes['project_id']     = $projectId;
            $attributes['credit']         = $creditIds;

            Log::info(__METHOD__.'Success', $attributes);

            self::commit();
        }catch (\Exception $e){
            self::rollback();

            $attributes['project_id']     = $projectId;
            $attributes['project']        = $projectInfo;
            $attributes['credit']         = $creditIds;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();
            $attributes['result']         = $result;

            Log::error(__METHOD__.__LINE__.'Error', $attributes);

            return self::callError($e->getMessage());
        }

        return self::callSuccess([$result]);

    }

    /**
     * @param $id
     * @desc 通过Id获取项目详情
     * @return mixed
     */
    public static function getById( $id ){

        try{

            if(isset($id)){

                $data['project_id'] = (int)$id;

            }

            $result = CoreApiProjectModel::getProjectDetail($id);

            Log::info(__CLASS__.__METHOD__.'info', [$result]);

            if( $result ){

                $projectInfo = $result;

                $projectLinkCredit = new ProjectLinkCreditNewModel();

                $projectInfo['credit_id'] = $projectLinkCredit -> getCreditListByProjectId($id);

                Log::info(__CLASS__.__METHOD__.'info', [$projectInfo]);

            }

        }catch (\Exception $e){

            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($projectInfo);

    }

    /**
     * @param $projectId
     * @param $creditIds
     * @return bool
     * @throws \Exception
     * @desc使用债权
     */
    public function useCreditInfo( $projectId, $creditIds ){

        //匹配债权
        ProjectLinkCreditNewModel::createProjectLinkCreditIds( $projectId, $creditIds );
        //更新债权状态
        CreditAllModel::updateCreditStatus($creditIds, CreditAllDb::STATUS_USED);

        return true;

    }

    /**
     * @param $projectId
     * @return bool
     * @throws \Exception
     * @desc 回退使用债权
     */
    public function recoverCreditInfo($projectId){

        $model = new ProjectLinkCreditNewModel();

        //回退使用债权
        $creditIds  = $model->getCreditListByProjectId($projectId);

        $result = $model->delByProjectId( $projectId );

        //更新债权状态
        $return = CreditAllModel::updateCreditStatus($creditIds, CreditAllDb::STATUS_UNUSED);

        Log::Info(__METHOD__.__METHOD__.__LINE__,[$return, $creditIds]);

        return $return;

    }

    /**
     * @return array
     * @desc 首页项目包数据
     */
    public function getIndexProjectPack()
    {

        $result = \App\Http\Models\Common\CoreApi\ProjectModel::getIndexProjectPack();

        $data = [];

        //定期投资总额
        $projectInvestAmount            = ProjectModel::getInvestAmount();

        //数据统计相关
        $statisticsData = StatisticsModel::getStatistics(false);

        $creditAssignInvestAmount = isset($statisticsData['creditAssignInvestAmount']) ? $statisticsData['creditAssignInvestAmount'] : 0;

        $data['stat'] = [
            'total_interest'            => $statisticsData['totalInterest'],         //总收益
            'current_invest_amount'     => $statisticsData['currentInvestAmount'],   //零钱计划总投资额度
            'total_invest_amount'       => $statisticsData['currentInvestAmount'] + $statisticsData['projectInvestAmount'] + $creditAssignInvestAmount,    //平台总投资额
            'risk_money'     => $statisticsData['projectInvestAmount'] * 0.01 +10000000, //风险准备金,
            'user_total'     => $statisticsData['userCount']
        ];

        unset($result['totalInterest'] ,$result['currentInvestAmount'],$result['userTotal']);

        /**
         * 获取项目关联债权信息
         */
        $projectIds = $projectLinks = [];
        foreach ($result as $key => $record) {
            $projectIds[] = $record['id'];
        }
        if(!empty($projectIds)){
//            $projectLinkCreditModel = new ProjectLinkCreditModel;
//            $projectLinks           = $projectLinkCreditModel->getByProjectIds($projectIds);
            $projectLinkCreditNewModel = new ProjectLinkCreditNewModel();
            $projectLinks           = $projectLinkCreditNewModel->getByProjectIds($projectIds);
        }
        if(!empty($projectLinks)){
            $projectLinks          = array_column($projectLinks, 'credit_id', 'project_id');
            foreach($projectLinks as $projectId => $creditId){
//                $projectWay = ProjectLinkCreditModel::getProjectWay($projectLink);
//                $projectLinks[$projectLinkKey] = ProjectLinkCreditModel::getOldProjectWay($projectWay);
                $projectWay = ProjectLinkCreditNewModel::getProjectWay($creditId);
                $projectLinks[$projectId] = ProjectLinkCreditModel::getOldProjectWay($projectWay);
            }
        }

        foreach ($result as $key => $record) {

            $data[$key] = $record;

            if (empty($record)) continue;

            $data[$key] = $this->formatAppProject($record, $projectLinks);

        }



        return $data;
    }

    /**
     * @param array $data
     * @return array
     * @desc 格式化app接口的返回数据
     */
    public function formatAppProject($data=[], $projectLinks = [])
    {

        if(empty($data)){

            return [];

        }

        $data['project_type']           = ProjectModel::getProjectStatusNote($data['status'], $data['publish_at']);
        $data['project_type_note']      = ProjectModel::getProjectTypeNote($data['project_type']);
        $data['profit_percentage']      = (float)$data['profit_percentage'];
        $data['base_rate']              = (float)$data['base_rate'];
        $data['after_rate']             = (float)$data['after_rate'];
        $data['project_invest_type']    = 1;
        $data['percentage_float_one']   = (float)$data['profit_percentage'];
        $data['activity']               = [];
        $data['default_title']          = isset($data['name']) && !empty($data) ? $data['name'] :ProjectModel::getProductLine(($data['product_line']+$data['type']));
        //$data['default_title']         = ProjectModel::getProductLine(($data['product_line']+$data['type']));
        $data['refund_type_text']       = ProjectModel::getRefundTypeNote($data['refund_type']);
        $data['min_invest']             = env('INVEST_UNIT');//最小投资金额
        $data['min_invest_note']        = env('INVEST_UNIT').'元';

        $data['project_way']            = isset($projectLinks[$data['id']]) ? $projectLinks[$data['id']]: 30;//30 为老系统 九省心

        return $data;

    }

    /**
     * 获取定期投资总额
     */
    public function getInvestAmount(){

        $totalAmount = ProjectModel::getInvestAmount();

        return $totalAmount;

    }

    /**
     * @return array
     * 首页平台数据明细
     */
    public function getHomeStatisticsDetail(){

        $data                           = \App\Http\Models\Common\CoreApi\ProjectModel::getHomeStatisticsDetail();
        //定期投资总额
        $projectInvestAmount            = $this->getInvestAmount();

        //零钱计划投资总额
        $currentInvestAmount            = $data['currentInvestAmount'];

        //零钱计划+定期投资总额
        $totalInvestAmount              = $currentInvestAmount + $projectInvestAmount;

        //总投资额
        $data['totalInvestAmount']      = ToolMoney::formatDbCashDelete($totalInvestAmount);
        //定期投资总额
        $data['projectInvestAmount']    = ToolMoney::formatDbCashDelete($projectInvestAmount);
        //零钱计划+定期总收益
        $data['totalInterest']          = ToolMoney::formatDbCashDelete($data['totalInterest']);
        //已回款本息金额
        $data['refundAmount']           = ToolMoney::formatDbCashDelete($data['refundAmount']);
        //零钱计划投资总额
        $data['currentInvestAmount']    = ToolMoney::formatDbCashDelete($data['currentInvestAmount']);

        //缺少债转投资金额

        return $data;

    }

    /**
     * @param int $page
     * @param int $size
     * @return array
     * @desc 已经售罄的项目列表,售罄代表项目还款中
     */
    public function refundingList( $page=1, $size=10 )
    {

        $list = \App\Http\Models\Common\CoreApi\ProjectModel::getRefundingList($page, $size);

        if( !empty($list) ){

            foreach ($list as $key => $project){

                $list[$key] = $this->formatAppProject($project);

            }

        }else{

            $list = [[]];

        }

        return self::callSuccess(['project_list' => $list]);

    }

    /**
     * 获取项目状态
     * @param $user
     * @param $project
     */
    public static function getProjectStatus($user,$project){

        $userStatus = UserLogic::getUserAuthStatus($user);

        $status = 'no_login';

        //未登录
        if($userStatus['is_login'] == 'off'){
            $status = 'no_login';

        }else if($userStatus['name_checked'] ==  'off'){
            //未实名
            $status = 'no_name_checked';
        }else if($userStatus['password_checked'] == 'off'){
            //未设置交易密码
            $status = 'no_password_checked';
        }else{
            if($project['status'] == ProjectDb::STATUS_INVESTING && $project['publish_at'] > ToolTime::dbNow()){
                //未开始
                $status = 'no_start';
            }else if($project['status'] == ProjectDb::STATUS_INVESTING){
                //可投资
                $status = 'can_invest';
            }else if($project['status'] == ProjectDb::STATUS_REFUNDING){
                //还款中
                $status = 'refunding';
            }else if( $project['status'] == ProjectDb::STATUS_FINISHED ){
                //已还款
                $status = 'finished';
            }
        }

        return self::$statusArr[$status];

    }

    /**
     * @return array
     * @desc 获取闪电付息项目列表
     */
    public function getSdfProject(){

        $list = CoreApiProjectModel::getSdfProject();

        $sdfMinCash = ProjectModel::getInvestMinCashByProductLine(ProjectDb::PROJECT_PRODUCT_LINE_SDF);

        foreach($list as $key => $item){

            $list[$key]['min_invest_cash'] = $sdfMinCash;

        }

        return self::callSuccess($list);

    }

    /**
     * @param $id
     * @return array
     * @desc 通过审核
     */
    public function doPass( $id ){

        $result = CoreApiProjectModel::doPass( $id );

        if($result){

            return self::callSuccess($result);

        }

        return self::callError($result);

    }

    /**
     * @param $id
     * @return array
     * @desc 通过不审核
     */
    public function doNoPass( $id ){

        $result = CoreApiProjectModel::doNoPass( $id );

        if($result){

            return self::callSuccess($result);

        }

        return self::callError($result);

    }


    /**
     * @param $id
     * @return array
     * @desc 发布
     */
    public function doPublish( $id ){

        $result = CoreApiProjectModel::doPublish( $id );

        if($result){

            //项目发布向借款人系统同步状态
            $res = $this->doPublishCreditToLoanUser( $id );

            Log::info('doPublish-doPublishCreditToLoanUser', [$res]);

            return self::callSuccess($result);

        }

        return self::callError($result);

    }

    /**
     * @param $projectId
     * @return array
     * @throws \Exception
     * 发布债权
     */
    public function doPublishCreditToLoanUser( $projectId ){

        $projectLinkCreditNew = new ProjectLinkCreditNewModel();

        $projectInfo = $this->getById($projectId);

        $projectInfo = $projectInfo['data'];

        if( !empty($projectInfo['new']) && $projectInfo['new']){

            $rate = $projectInfo['profit_percentage'];

            $creditId = $result = $projectLinkCreditNew->getByProjectId( $projectId );

            $loanResult = LoanUserCreditApiModel::doPublishCredit($creditId, $projectId, $rate);

            if( !$loanResult['status'] ){

                //执行邮件报警
                $receiveEmails = \Config::get('email.monitor.accessToken');

                $emailModel = new EmailModel();

                $subject = '【Error】发布项目,同步信息出错,请管理员尽快排查';

                $emailModel->sendHtmlEmail($receiveEmails, $subject, json_encode($loanResult['data']));

            }

            return self::callError($result);

        }

        return self::callSuccess();

    }

    /**
     * @param $id
     * @return array
     * @desc 删除项目
     */
    public function doDelete( $id ){

        $result = CoreApiProjectModel::doDeleteProject( $id );

        if($result){

            return self::callSuccess($result);

        }

        return self::callError($result);

    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @throws \Exception
     * @desc 获取普付宝项目列表
     */
    public function getPfbProject($page=1,$size=6){

        $list = CoreApiProjectModel::getPfbList($page,$size);

        return self::callSuccess($list);

    }


    /**
     * 获取项目关联债权
     *
     * @param array $projectIds
     * @return array|mixed
     */
    public function getProjectCredit($projectIds = []){
        if(empty($projectIds))
            return [];
        $projectLinkCreditObj = new ProjectLinkCreditNewModel();
        $projectLinkCredit    = $projectLinkCreditObj->getByProjectIds($projectIds);

        $projectDetailLogicObj= new ProjectDetailLogic;
        // 债权信息
        $projectCredit        = [];
        if(!empty($projectLinkCredit)) {
            foreach($projectLinkCredit as $projectLinkCreditRecord) {
                $projectCredit[$projectLinkCreditRecord['project_id']] = $projectDetailLogicObj->getCreditDetailNew($projectLinkCreditRecord['credit_id']);
            }
        }

        return $projectCredit;

    }

    /**
     * @param array $data
     * @return array
     * @desc 后台导出
     */
    public function adminExport($data=[])
    {

        if (empty($data)) {

            return self::callError('信息不完整');

        }
        if($data["export_type"] ==2){
            #核心库中获取项目
            $projectList = $this->getFinishedProjectList( $data );

        }else{

            //$projectList = $this->getProjectByMaxInvestTime( $data);
            $projectList = $this->getProjectByFullTime( $data);

        }
        if (empty($projectList)) {

            return self::callError('数据信息为空');
        }

        $projectIds = ToolArray::arrayToIds($projectList, 'id');

        //债权
        $projectCredits = $this->getProjectCredit($projectIds);

        $termLogic = new TermLogic();

        $investList = $termLogic->getBonusInvestCashList($projectIds);

        //计算加息券利息,总利息
        //$refundInterest = CoreApiProjectModel::getSumInterestByProjectIds($projectIds);
        $refundInterest = CoreApiProjectModel::getInterestTypeByProjectIds($projectIds);

        $lastInvestList = $termLogic->getLastInvestTimeByProjectIdFromCore($projectIds);

        $projectList = $this->formatShowList($projectList, $refundInterest, $investList, $projectCredits, $lastInvestList);

        ExportFile::csv($projectList, 'project-list-'.ToolTime::dbDate());

    }


    /**
     * @param array $projectList
     * @param array $refundList
     * @param array $investList
     * @param array $projectCredits
     * @return array
     * @desc 后台专用导出
     */
    private function formatShowList($projectList=[], $refundList=[], $investList=[], $projectCredits=[], $lastInvestList=[]){


        if( empty($projectList) ){

            return [];

        }

        $return[] = [
            '项目id','项目来源','项目名称','筹资金额','合同编号','收款人','合同名称','红包投资','实际投资','年利率','加息券','理财周期','付款方式','投资利息','红包利息','加息券利息','已支付利息','待支付利息','利息总计','满标日期','到期日','状态','提前回款'
        ];

        foreach( $projectList as $key => $value ){

            //总利息
            $totalInterest = isset($refundList[$value['id']]['total_cash']) ? $refundList[$value['id']]['total_cash'] : "0.00";

            //加息券利息
            $rateInterest = isset($refundList[$value['id']]['rate_cash']) ? $refundList[$value['id']]['rate_cash'] : "0.00";

            //已支付收益
            $refundedCash   = isset($refundList[$value['id']]['refunded_cash']) ? $refundList[$value['id']]['refunded_cash'] : "0.00";
            //未支付收益
            $refundingCash  = isset($refundList[$value['id']]['refunding_cash']) ? $refundList[$value['id']]['refunding_cash'] : "0.00";

            //红包金额
            $bonusCash = isset($investList[$value['id']]) ? $investList[$value['id']]['bonus_cash'] : 0;

            //实际投资
            $actualCash = $value['total_amount'] - $bonusCash;

            //红包利息=红包金额/总金额*总利息
            $bonusInterest = round(($bonusCash / $value['total_amount'] * $totalInterest), 2);

            //债权相关信息
            $projectCredit = isset($projectCredits[$value['id']]) ? $projectCredits[$value['id']] : '';

            $source = CreditLogic::getSource();

            $creditSource = '';

            $contractNo = '';

            $loanUsername = '';

            $companyName = '';

            if( !empty($projectCredit) ){

                foreach( $projectCredit as $creditDetail ){

                    $creditSource .= $source[$creditDetail['source']].' ';

                    $contractNo .= $creditDetail['contract_no'].' ';
                    $loanUsername= "";
                    if(isset($creditDetail['loan_username'])){
                        //$loanArr = json_decode($creditDetail['loan_username'], true);

                        if(strpos( $creditDetail['loan_username'], ',' )){

                            $loanArr   = explode( ',', $creditDetail['loan_username']);
                            $loanUsername = implode(' ', $loanArr);

                        }else{

                            $loanUsername = $creditDetail['loan_username'];

                        }
                    }

                    $companyName = isset($creditDetail['company_name']) ? $creditDetail['company_name'] : '';

                }

            }

            $return[] = [
                'id'                => $value['id'],
                'credit_source'     => $creditSource,
                'name'              => $value['name'],
                'total_amount'      => $value['total_amount'],
                'contract_no'       => $contractNo,
                'loan_username'     => $loanUsername,
                'contract_name'     => $companyName,
                'bonus_cash'        => $bonusCash,
                'actual_cash'       => $actualCash,
                'base_rate'         => $value['base_rate'].'%',
                'after_rate'        => $value['after_rate'].'%',
                'invest_time_note'  => $value['invest_time_note'],
                'refund_type_note'  => $value['refund_type_note'],
                'actual_interest'   => round(($totalInterest - $bonusInterest - $rateInterest), 2), //实际利息=总利息-红包利息-加息券利息
                'bonus_interest'    => $bonusInterest,
                'rate_interest'     => $rateInterest,
                'refunded_cash'     => $refundedCash,
                'refunding_cash'    => $refundingCash,
                'total_interest'    => $totalInterest,
                //'last_invest_at'    => isset($lastInvestList[$value['id']]) ? $lastInvestList[$value['id']]['last_invest_time'] : '',
                'last_invest_at'    => $value['full_at'],
                'end_at'            => $value['end_at'],
                'status_note'       => $value['status_note'],
                'before_note'       =>  isset($value['before_refund']) && $value['before_refund'] == ProjectDb::BEFORE_REFUND ? "是" :"否",
            ];

        }

        return $return;

    }

    /**
     * @param $id
     * @return array
     * @desc 提前还款
     */
    public function doBeforeRefundRecord($id){

        $res = \Queue::pushOn('doBeforeRefundRecord',new ProjectJob($id));

        if( !$res ){

            return self::callError('加入队列失败,请重试');

        }

        return self::callSuccess($res, '提前还款加入队列成功,请等待系统自动执行!');

    }

    /**
     * @param   array $data
     * @return  array
     * @desc    根据项目完结时间， 获取时间段内项目信息
     */
    public function getFinishedProjectList($data = [])
    {
        return CoreApiProjectModel::getFinishedProjectList($data['start_time'], $data['end_time'],$data['is_before']);
    }

    /**
     * @param   array $data
     * @return  array
     * @desc    根据项目满标时间，获取时间段内的项目信息
     *
     */
    public function   getProjectByMaxInvestTime($data = [])
    {
//        $termLogic = new TermLogic();
//
//        $investList = $termLogic->getLastInvestListByStartTimeEndTime($data['start_time'], $data['end_time']);
//
//        if (empty($investList)) {
//
//            return [];
//
//        }
//
//        $projectIds = ToolArray::arrayToIds($investList, 'project_id');
//
//        //$projectList = CoreApiProjectModel::getProjectListByIds($projectIds);

        $startTime      =   date("Y-m-d H:i:s",ToolTime::getUnixTime($data['start_time']));

        $endTime        =   date("Y-m-d H:i:s",ToolTime::getUnixTime($data['end_time'],'end'));

        $pageIndex      =   1;

        $pageSize       =   10000;

        $projectList    =   CoreApiProjectModel::getProjectWithTime($startTime,$endTime,$pageIndex,$pageSize);

        return $projectList['list'];
    }

    /**
     * @param $data
     * @return mixed
     * @desc 通过项目满标时间获取项目信息
     */
    public static function getProjectByFullTime($data)
    {
        $startTime      =   date("Y-m-d H:i:s",ToolTime::getUnixTime($data['start_time']));

        $endTime        =   date("Y-m-d H:i:s",ToolTime::getUnixTime($data['end_time'],'end'));

        $isPledge       =   isset($data['is_pledge']) ? $data['is_pledge'] : '0';

        $projectList    =   CoreApiProjectModel::getProjectByFullTime($startTime,$endTime,$isPledge);

        return $projectList;
    }

    /*######################################## App4.0定期项目处理 ########################################*/

    /**
     * @desc 获取App4.0定期理财的定期项目列表
     * @param $productLine array | string
     * @param $page int
     * @param $size int
     * @param $status array | string
     * @return mixed
     */
    public function getAppV4ProjectList($productLine , $page, $size, $status, $projectNovice = [[]]){

        $data = [];

        $data = \App\Http\Models\Common\CoreApi\ProjectModel::getProjectList($productLine, $page, $size, $status);


        if(array_key_exists('total',$data)){
            unset($data['total']);
        }

        if( isset($data['list']) && !empty($data['list']) ){
            if($page == 1 && !empty($projectNovice[0])){
                $data['list'] = array_merge($projectNovice,$data['list']);
            }
            $data = $this->formatAppV4Project($data['list']);
        }

        return $data;
    }

    /**
     * @desc 格式化App4.0定期项目信息
     * @param $project array
     * @return array
     */
    public function formatAppV4Project($project){

        $projectIndex = [];

        $activitySign  =  ToolArray::arrayToKey( $this->setProjectActicitySign( ), 'project_id' );

        if(!empty($project)){
            foreach($project as $key=>$value){

                //判断是否有加息,否则就为空
                $afterRate = ($value['after_rate'] > 0) ? number_format($value['after_rate'], 1) : '';

                $projectIndex[$key]['id']  = $value['id'];
                $projectIndex[$key]['name']  = $value['name'];
                $projectIndex[$key]['product_line_note']  = $value['product_line_note'];
                $projectIndex[$key]['except_year_rate']  =  ProjectDb::INTEREST_RATE_NOTE.'(%)';
                $projectIndex[$key]['profit_percentage']  =  number_format( $value['profit_percentage'], 1);
                $projectIndex[$key]['base_rate']  =  number_format( $value['base_rate'], 1);
                $projectIndex[$key]['after_rate']  =  $afterRate;
                $projectIndex[$key]['project_time_note']  =  '项目期限';
                //$projectIndex[$key]['invest_time_note']  =  $value['invest_time_note'];
                $projectIndex[$key]['invest_time_note']  =  $value['format_invest_time'].$value['invest_time_unit'];
                $projectIndex[$key]['refund_note']  =  '还款方式';
                $projectIndex[$key]['status']  =  $value['status'];
                $projectIndex[$key]['status_note']  =  $value['status_note'];
                $projectIndex[$key]['refund_type_note']  =  $value['refund_type_note'];
                $projectIndex[$key]['activity_image_url']  = isset( $activitySign[$value['id']] ) ? $activitySign[$value['id']]['activity_img_url'] : '';
            }
        }
        return $projectIndex;
    }

    /**
     * @desc 组装项目活动标示与广告位关联数据
     * @return array
     */
    public function setProjectActicitySign()
    {
        $projectExtendModel = new ProjectExtendModel();

        $projectActivitySign = $projectExtendModel->getActivitySign();

        if( empty( $projectActivitySign ))
        {
            return [];
        }

        $activitySignAd  = AdLogic::getUseAbleListByPositionId( 27 );//获取项目理财列表广告

        $activitySignAd = ToolArray::arrayToKey( AdLogic::formatAppV4AdData( $activitySignAd ), 'sort' );

        foreach( $projectActivitySign as $key=> $value )
        {

            if( isset( $activitySignAd[$value['type']] ))
            {
                $projectActivitySign[$key]['activity_img_url'] = $activitySignAd[$value['type']]['file'];
            }

        }

        return $projectActivitySign;

    }

    /**
     * @desc 格式化App4.0首页定期项目列表
     * @param $project array
     * @return array
     */
    public function getAppV4HomeProject($project){

        $homeV4Project  = [];

        //判断获取的项目是否已售罄
        $isAllSellFinish = 1;
        if(!empty($project)){
            //去掉数据统计
            if(array_key_exists('stat', $project)){
                unset($project['stat']);
            }
            //首页项目九安心项目重新排序
            if(array_key_exists('jax', $project)){
                $jax = $project['jax'];
                unset($project['jax']);
                $project['jax'] = $jax;
            }
            //循环处理获取到的项目
            foreach($project as $key=>$value){
                //投资中的项目
                if($value['status'] <= ProjectDb::STATUS_INVESTING){
                    $isAllSellFinish = 0;
                    $homeV4Project[$key] = $value;
                    break;
                }

            }
            //如果所有项目都已售罄，只显示三月期
            if($isAllSellFinish == 1){
                $pointProject    =   self::getActivityProject(['three']);
                $homeV4Project['three'] = isset($pointProject['three'] ) ? $pointProject['three'] : current($project);
            }

        }
        //$homeV4Project = $this->formatAppV4HomeProjectDetail($homeV4Project);
        return $homeV4Project;
    }

    /**
     * @desc 格式化App4.0定期相关数据
     * @param $project
     * @return array
     */
    public function formatAppV4HomeProjectDetail($project){

        $projectArr = [];

        if(!empty($project)){

            foreach($project as $value){
                $projectArr['id']  = $value['id'];
                $projectArr['name']  = $value['name'];
                $projectArr['product_line_note']  = $value['product_line_note'];
                $projectArr['except_year_rate']  =  ProjectDb::INTEREST_RATE_NOTE.'(%)';
                $projectArr['profit_percentage']  =  number_format( $value['profit_percentage'], 1);
                $projectArr['base_rate']  =  number_format( $value['base_rate'], 1);
                $projectArr['after_rate']  =  number_format( $value['after_rate'], 1);
                $projectArr['project_time_note']  =  '项目期限';
                //$projectArr['invest_time_note']  =  $value['invest_time_note'];
                $projectArr['invest_time_note']  =  $value['format_invest_time'].$value['invest_time_unit'];
                $projectArr['refund_note']  =  '还款方式';
                $projectArr['refund_type_note']  =  $value['refund_type_note'];
                $projectArr['is_good_project']  =  1;
                $projectArr['good_project_img_url']  = assetUrlByCdn('static/app/images/good-project.png');
            }
        }

        return $projectArr;
    }

    /**
     * @param array $projectConfig;
     * @return miexd|array|
     * @ desc  获取当前指定的项目类型
     */
    public static function getActivityProject($projectConfig = array())
    {
        if( empty($projectConfig) || !$projectConfig ){

            return [];
        }

        $newProjectList =   CoreApiProjectModel::getNewestProjectEveryType();

        $projectGroup   =   [];

        if(empty($newProjectList)){

            return [];
        }

        foreach( $newProjectList as $key => $project ){

            if( in_array($key , $projectConfig)){

                $projectGroup[$key] =$project;
            }
        }
        return $projectGroup;
    }



    //TODO: APP4.1.3-首页改版---凌路---------
    /**
     * @desc    APPV413-首页项目包数据
     * @return  array
     *
     **/
    public function getProjectPackAppV413()
    {

        $result = \App\Http\Models\Common\CoreApi\ProjectModel::getProjectPackAppV413();

        $data = [];

        /**
         * 获取项目关联债权信息
         */
        $projectIds = $projectLinks = [];
        foreach ($result as $key => $record) {
            $projectIds[]   = $record['id'];
        }
        if(!empty($projectIds)){

            $projectLinkCreditNewModel      = new ProjectLinkCreditNewModel();
            $projectLinks                   = $projectLinkCreditNewModel->getByProjectIds($projectIds);

        }
        if(!empty($projectLinks)){
            $projectLinks                   = array_column($projectLinks, 'credit_id', 'project_id');
            foreach($projectLinks as $projectId => $creditId){
                $projectWay                 = ProjectLinkCreditNewModel::getProjectWay($creditId);
                $projectLinks[$projectId]   = ProjectLinkCreditModel::getOldProjectWay($projectWay);
            }
        }

        foreach ($result as $key => $record) {

            $data[$key]     = $record;

            if (empty($record)) continue;

            $data[$key]     = $this->formatAppProject($record, $projectLinks);

        }

        return $data;
    }

    /**
     * @desc 格式化App4.0定期相关数据
     * @param $project
     * @return array
     */
    public function getProjectRecordAppV413Format($project){

        $projectArr = [];

        if(!empty($project)){

            foreach($project as $key=>$value){
                $projectArr[$key]['id']                 = $value['id'];
                $projectArr[$key]['name']               = $value['name'];
                $projectArr[$key]['product_line_note']  = $value['product_line_note'];
                $projectArr[$key]['except_year_rate']   =  ProjectDb::INTEREST_RATE_NOTE.'(%)';
                $projectArr[$key]['profit_percentage']  =  number_format( $value['profit_percentage'], 1);
                $projectArr[$key]['base_rate']          =  number_format( $value['base_rate'], 1);
                $projectArr[$key]['after_rate']         =  number_format( $value['after_rate'], 1);
                $projectArr[$key]['project_time_note']  =  '项目期限';
                //$projectArr['invest_time_note']  =  $value['invest_time_note'];
                $projectArr[$key]['invest_time_note']   =  $value['format_invest_time'].$value['invest_time_unit'];
                $projectArr[$key]['refund_note']        =  '还款方式';
                $projectArr[$key]['refund_type_note']   =  $value['refund_type_note'];
                $projectArr[$key]['is_good_project']    =  1;
                $projectArr[$key]['good_project_img_url']  = assetUrlByCdn('static/app/images/good-project.png');
                $projectArr[$key]['status']             = $value['status'];
                $projectArr[$key]['status_note']        = $value['status_note'];
                $projectArr[$key]['invest_tip']         = $key == 'novice' ? '仅限首次投资' : '';
            }
        }

        return $projectArr;
    }




}
