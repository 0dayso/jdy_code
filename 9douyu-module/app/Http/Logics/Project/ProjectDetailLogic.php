<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Logics\Project;

use App\Http\Dbs\Invest\InvestDb;
use App\Http\Logics\Logic;

use App\Http\Models\Credit\CreditAllModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Http\Models\User\UserModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolStr;
use App\Lang\AppLang;
use App\Tools\ToolTime;
use Log;

use App\Http\Logics\Credit\CreditAllLogic;

use App\Http\Logics\Credit\CreditExtendLogic;

use App\Http\Models\Project\ProjectLinkCreditModel;

use App\Http\Models\Credit\CreditModel;

use App\Http\Models\Credit\CreditUserLoanModel;

use App\Http\Dbs\Project\ProjectDb;

use App\Http\Dbs\Credit\CreditDb;

use App\Http\Models\Bonus\UserBonusModel;

use App\Http\Dbs\Bonus\BonusDb;

use App\Http\Models\Project\ProjectModel;

use App\Http\Models\Common\CoreApi\ProjectModel as CoreApiProjectModel;

use App\Tools\ToolUrl;

use App\Tools\EditorImage;
/**
 * 项目详情接口
 * Class ProjectDetailLogic
 * @package App\Http\Logics\Project
 */
class ProjectDetailLogic extends Logic
{
    /**
     * 该项目债权关系模型实例
     * @var
     */
    protected $ProjectLinkCreditModel = null;

    public function __construct()
    {
        $this->ProjectLinkCreditModel = new ProjectLinkCreditModel;
    }

    /**
     * 项目详情 获取
     * @param int $id
     * @param bool $isInvest
     * @return array
     */
    public function get($id = 0, $isInvest=false)
    {
        $project = $this->getCoreProjectInfo($id);

//        if($isInvest == true && ($project['type'] == ProjectDb::INVEST_TIME_DAY || $project['type'] == ProjectDb::INVEST_TIME_DAY_ONE || $project['product_line'] == ProjectDb::PROJECT_PRODUCT_LINE_SDF))
//        {
//            $project['format_invest_time'] = ToolTime::getDayDiff(ToolTime::dbDate(),ToolTime::getDate($project['end_at']));
//        }

        return $project;
    }

    /**
     * 获取pc、wap端 项目详情债权相关信息
     */
    public function getCreditBrowserShowData($projectId = 0, $userId=0){

        try{
            // 债权关联表
            $model = new ProjectLinkCreditNewModel();

            $projectLinkCredit = $model->getCreditListByProjectId( $projectId );

            $projectCredit = CreditAllModel::getCreditDetailById( $projectLinkCredit );

            // 债权图片
            $editorImage       =  new EditorImage;

            $companyView       = [];
            //关联单个债权
            if(!empty($projectCredit)) {
                $projectCreditDetail = $projectCredit[0];
                Log::info('getCreditBrowserShowData', [$projectCreditDetail]);
                $projectWay = $projectCreditDetail['type'] == CreditDb::TYPE_NINE_CREDIT || $projectCreditDetail['type'] == CreditDb::TYPE_PROJECT_GROUP ? $projectCreditDetail['type'] : $projectCreditDetail['source'];
                if (!empty($projectWay)) {
                    switch ($projectWay) {
                        case CreditDb::SOURCE_FACTORING://保理
                            $companyView = $projectCreditDetail;
                            $companyView['credit_company']        = $projectCreditDetail['company_name'];
//                            $companyView['trade_info_links']      = $editorImage->_parseImageLinks($projectCreditDetail['transactional_data']);
//                            $companyView['factor_info_links']     = $editorImage->_parseImageLinks($projectCreditDetail['traffic_data']);

                            break;
                        case CreditDb::SOURCE_CREDIT_LOAN://信贷
                            $companyView = $projectCreditDetail;
//                            if( isset( $projectCreditDetail['contract_agreement'] ) ){
//                                $companyView['agreement_images_links'] = $editorImage->_parseImageLinks($projectCreditDetail['contract_agreement']);
//                            }
//                            if( isset( $projectCreditDetail['company_photo'] ) ){
//                                $companyView['industry_images_links']  = $editorImage->_parseImageLinks($projectCreditDetail['company_photo']);
//                            }

                            break;
                        case CreditDb::SOURCE_HOUSING_MORTGAGE: //房产抵押
                            $companyView = $projectCreditDetail;
//                            $companyView['identity_images_links'] = $editorImage->_parseImageLinks($projectCreditDetail['certificates']);
//                            $companyView['homeloan_images_links']  = $editorImage->_parseImageLinks($projectCreditDetail['mortgage']);

                            break;
                        case CreditDb::TYPE_NINE_CREDIT: // 九省心
                            break;

                        case CreditDb::SOURCE_THIRD_CREDIT: //第三方

                            $creditListInfo = array_slice(json_decode($projectCreditDetail['credit_list'],true),0,10);
                            if(!empty($userId)){
                                $investDb = new InvestDb();
                                $investInfo = $investDb->getInvestProjectByUserId($projectId, $userId);
                                if($investInfo){
                                    $creditListInfo = json_decode($projectCreditDetail['credit_list'],true);
                                }
                            }

                            $projectCreditDetail['credit_list_info'] = $creditListInfo;
                            $companyView = $projectCreditDetail;
                            break;

                    }
                }
            }

            //新项目按照类型关联信息为空
            if( empty( $projectCredit ) && $projectWay == CreditDb::TYPE_CREDIT_LOAN_USER )
            {
                $creditLoanUserModel = new CreditUserLoanModel();

                $creditInfo = json_decode( $projectLinkCredit['credit_info'], true );

                $projectCredit = $creditLoanUserModel->getCreditInfoById( $creditInfo[0]['credit_id'] );

                $companyView['credit_list_info'] = $projectCredit;

            }

            return ['projectWay'=> $projectWay, 'companyView'=> $companyView];

        }catch (\Exception $e){
            Log::error('getCreditBrowserShowData: '. $projectId, [$e->getCode(), $e->getMessage(), $e->getLine()]);
        }

        return ['projectWay'=> null, 'companyView'=> []];
    }

    public function doFormatCreditLoadUser($projectCredit)
    {
        if( empty($projectCredit) ) {

            return $projectCredit;
        }
        $projectCredit['format_loan_username']   =   isset( $projectCredit['loan_username'] ) ? array_filter( explode(',',$projectCredit['loan_username']) ) : '';
        $projectCredit['format_loan_user_identity']   =  isset($projectCredit['loan_user_identity']) ? array_filter( explode(',', $projectCredit['loan_user_identity']) ): '';

        return $projectCredit;
    }
    /**
     * 项目详情-产品详情
     *
     * @param int $projectId
     * @return array
     */
    public function getProductCreditDetail($projectId = 0)
    {
        try {

            // 债权关联表
            $model = new ProjectLinkCreditNewModel();

            $projectLinkCredit = $model->getCreditListByProjectId( $projectId );

            $projectCredit = CreditAllModel::getCreditDetailById( $projectLinkCredit );

            // 项目信息
            $project           = $this->getCoreProjectInfo($projectId);

            $editorImage       =  new EditorImage;

            $companyView       = $refundPlan = [];

            $projectWay        = $view = '';
            //关联单个债权
            if(!empty($projectCredit)) {
                $projectCreditDetail = $projectCredit[0];
                Log::info('产品详情-关联债权', [$projectCreditDetail]);
                $projectWay = $projectCreditDetail['type'] == CreditDb::TYPE_NINE_CREDIT || $projectCreditDetail['type'] == CreditDb::TYPE_PROJECT_GROUP ? $projectCreditDetail['type'] : $projectCreditDetail['source'];

                $view              = self::getView($projectWay);


                if (!empty($projectWay)) {
                    switch ($projectWay) {
                        case CreditDb::SOURCE_FACTORING://保理
                            $companyView = [
                                'credit_company' => $projectCreditDetail['company_name'],
//                                'factor_summarize' => $projectCreditDetail['factor_summarize'],
//                                'trade_info_links' => $editorImage->_parseImageLinks($projectCreditDetail['transactional_data']),
//                                'factor_info_links' => $editorImage->_parseImageLinks($projectCreditDetail['traffic_data']),
                            ];
                            break;

                        case CreditDb::SOURCE_CREDIT_LOAN://信贷
                            //项目还款计划
                            $refundPlan      = $this->getRefundPlan($projectId);
//                            $companyView = [
//                                'basic_info' => $projectCreditDetail['background'],
//                                'agreement_images_links' => isset($projectCreditDetail['contract_agreement'])?$editorImage->_parseImageLinks($projectCreditDetail['contract_agreement']):'',
//                                'industry_images_links' => isset($projectCreditDetail['company_photo'])?$editorImage->_parseImageLinks($projectCreditDetail['company_photo']):'',
//                            ];

                            break;

                        case CreditDb::SOURCE_HOUSING_MORTGAGE://房产抵押
//                            $companyView = [
//                                'identity_images_links' => $editorImage->_parseImageLinks($projectCreditDetail['certificates']),
//                                'homeloan_images_links' => $editorImage->_parseImageLinks($projectCreditDetail['mortgage']),
//                            ];
                            break;

                        case CreditDb::TYPE_PROJECT_GROUP://项目集
                            $companyView = self::getOutputCredit($projectCredit);
                            break;

                        case CreditDb::TYPE_NINE_CREDIT://九省心

                            break;
                        case CreditDb::SOURCE_THIRD_CREDIT://第三方

                            $creditListInfo = json_decode($projectCreditDetail['credit_list'],true);
                            $projectCreditDetail['credit_list_info'] = $creditListInfo;
                            $companyView = $projectCreditDetail;

                            break;
                    }
                    $companyView = array_merge($projectCreditDetail, $companyView);

                }
            }else{
                //新项目债权信息
                if( $projectWay == CreditDb::TYPE_CREDIT_LOAN_USER )
                {
                    $creditLoanUserModel = new CreditUserLoanModel();

                    $creditInfo = json_decode( $projectLinkCredit['credit_info'], true );

                    $projectCredit = $creditLoanUserModel->getCreditInfoById( $creditInfo[0]['credit_id'] );

                    $companyView['credit_list_info'] = $projectCredit;

                }
                \Log::info('项目详情-产品详情 债权信息为空 项目ID：'. $projectId . ' ;关联信息：' . json_encode($projectLinkCredit));
            }

            $projectModel = new ProjectModel;
            $formatInvestTime = $projectModel->getFormatInvestTime($project, $projectWay);//获取投资期限

            $projectView = [
                'id'                   => $project['id'],
                'name'                 => $project['name'],
                'default_title'        => isset($project['name']) && !empty($project['name']) ?$project['name'] :$projectModel::getProductLine(($project['product_line']+$project['type'])),
                'percentage_float_one' => (float)$project['profit_percentage'],
                //'format_invest_time'   => $formatInvestTime,
                'format_invest_time'   => $project['format_invest_time'],
                'invest_time_unit'     => ProjectModel::getInvestTimeUnit($project['refund_type'], $projectWay),
                'refund_end_time'      => $project['end_at'],
                'invest_min_cash'      => env('INVEST_UNIT'),
                'refund_type_text'     => CreditModel::refundType($project['refund_type']),
                'refund_type'          => $project['refund_type'],
                'credit_desc'          => isset($projectCreditDetail['credit_desc']) ? $projectCreditDetail['credit_desc'] : null,
            ];
            $projectView = array_merge($project, $projectView);

            Log::info('geCreditDetail', [$view, $projectWay, $projectView, $companyView, $refundPlan]);

            if(empty($view) || empty($projectWay)){
                throw new \Exception('视图 或 项目类型不能为空');
            }

            $data = [
                'view'        => $view,
                'projectWay'  => $projectWay,
                'data'        => [
                    'project'    => $projectView,
                    'company'    => $companyView,
                    'refundPlan' => $refundPlan,
                ]
            ];

        } catch (\Exception $e) {
            $data['msg'] = $e->getMessage();
            $data['code'] = $e->getCode();

            Log::error(__METHOD__ . 'Error', $data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess($data);
    }

    /**
     * 显示金额格式化【万元】
     *
     * @param array $projectCredit
     * @return array
     */
    public static function getOutputCredit($projectCredit = []){
        if(!empty($projectCredit)){
            foreach($projectCredit as $k => $record){
                $projectCredit[$k]['loan_amounts'] = ToolMoney::formatDbCashDeleteTenThousand($record['loan_amounts']);
            }
        }
        return $projectCredit;
    }

    /**
     * 获取指定债权类
     * @param int $code
     * @return string
     */
    public static function getView($code = 0){
        $view = null;
        switch($code){
            case CreditDb::SOURCE_FACTORING:
                $view = 'newfactor';
                break;
            case CreditDb::SOURCE_CREDIT_LOAN:
                $view = 'newcredit';
                break;
            case CreditDb::SOURCE_THIRD_CREDIT:
                $view = 'third';
                break;
            case CreditDb::TYPE_CREDIT_LOAN_USER:
                $view = 'new';
                break;
            case CreditDb::SOURCE_HOUSING_MORTGAGE:
                $view = 'newhouse';
                break;
            case CreditDb::TYPE_NINE_CREDIT:
                $view = 'jsx';
                break;
            case CreditDb::TYPE_PROJECT_GROUP:
                $view = 'group';
                break;
        }
        return $view;
    }




    /**
     * 获取app 项目详情
     *
     * @param int $id
     * @return array
     */
    public function appGet($id = 0){
        $project                    = [];
        try {
            $project['linkCredit']  = $this->getCreditByProjectId($id);

            $project['project']     = $this->getCoreProjectInfo($id);

            $project                = self::formatAppGetOutput($project);

        }catch (\Exception $e){
            $data['project']        = $project;
            $data['id']             = $id;
            $data['msg']            = $e->getMessage();
            $data['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $data);

            return self::callError($e->getMessage());
        }

        return self::callSuccess($project);
    }


    /**
     * 获取投资记录
     *
     * @param int $id 项目ID
     * @param int $page 当前页码
     * @return array
     */
    public function appGetInvestRecord($id = 0, $page = 1, $size=10){
        //单比投资排行榜
        $maxInvestTop = $this->getMaxInvestTop($id);
        if(!empty($maxInvestTop)){
            foreach($maxInvestTop as $k => $item){
                $item['invest_time'] = $item['created_at'];
                $maxInvestTop[$k] = $item;
            }
        }
        //投资记录
        $investList  = $this->getInvestList($id, $page, $size);
        if(!empty($investList)){
            foreach($investList as $k => $item){
                $item['invest_time'] = $item['created_at'];
                $investList[$k] = $item;
            }
        }


        $items = [
                'top_list' => empty($maxInvestTop) ? [[]] : $maxInvestTop,
                'list' => empty($investList) ? [[]] : $investList,
        ];


        return self::callSuccess($items);

    }


    /**
     * 格式化项目详情
     *
     * @param array $project
     * @return array
     */
    public static function formatAppGetOutput($project = []){

        $linkCredit            = $project['linkCredit'];

//        $projectWay            = ProjectLinkCreditModel::getProjectWay($linkCredit);
//
//        $projectWay            = ProjectLinkCreditModel::getOldProjectWay($projectWay);

        $creditType            =   $linkCredit['type'] == CreditDb::TYPE_NINE_CREDIT ? CreditDb::TYPE_NINE_CREDIT : $linkCredit['source'];

        $projectWay            =   ProjectLinkCreditModel::getOldProjectWay( $creditType );

        $project               = $project['project'];


        //投资金额 与 投资人数
        $db            = new InvestDb();
        $investSummary = $db->getInvestBrief($project['id']);

        //ProjectModel::getProductLine(($project['type'] + $project['product_line']))  产品线[备用]

        $projectLineKey= $project['type'] + $project['product_line'];

        $projectType  = ProjectModel::getProjectStatusNote($project['status'], $project['publish_at']);

        $items = [
            "project" => [
                "project_id"         => $project['id'],
                "project_name"       => $project['name'],
                'product_line'       => $project['product_line'],
                "refund_type"        => $project['refund_type'],
                "refund_type_name"   => CreditModel::refundAppType($project['refund_type']),
                "project_way"        => $projectWay,
                "profit_percentage"  => $project['profit_percentage'],//利率
                "project_type"       => $projectType,//项目售卖状态 //
                "publish_time"       => $project['publish_at'],//暂不确定
                "can_invest_amount"  => ToolMoney::formatDbCashDelete($project['total_amount'] - $project['invested_amount']),//剩余可投
                "total_time"         => $project['format_invest_time'],//项目期限
                "total_time_note"    => $project['invest_time_unit'],//天或月
                "min_invest"         => env('INVEST_UNIT'),//最小投资金额
                "min_invest_note"    => env('INVEST_UNIT').'元起投',
                "interest_note"      => "当日计息",
                "button_down_note"   => "帐户资金享有银行级安全保障",//显示
                "project_invest_type"=> ProjectDb::PROJECT_INVEST_TYPE_CREDIT,//项目类型-定期
//                "process"            => $project['invested_amount']/$project['total_amount'],//进度
                "product_line_note"  => $project['product_line_note'],
                "safe"               => ToolUrl::getAppBaseUrl() . '/app/topic/safe',//资产安全//
                "detail"             => ToolUrl::getAppBaseUrl() . '/app/project/product/detail/'. $project['id'] . '?v=2',//产品详情
                //"finance_page"       => ToolUrl::getAppBaseUrl() . '/app/topic/financing/desc/'. $projectLineKey, //理财介绍//
                "finance_page"       => ToolUrl::getAppBaseUrl() . '/app/topic/financing/desc/'. $project['id'], //理财介绍//
                'project_type_note'  => ProjectModel::getProjectTypeNote($projectType),

        ],
            "invest_user"            => $investSummary['num'],//投资人数

            "invest_amount_total"    => $investSummary['cash'],// 投资总额

            "share_info" => self::getShareInfo($project),

            "activity" => [//广告活动数据
               // "__EMPTY" => "__EMPTY"//todo 暂无
            ],
        ];

        return $items;
    }

    /**
     * app产品详情分享
     *
     * @param array $project
     * @return array
     */
    public static function getShareInfo($project = []){
        // 产品线label + id
        //$shareName    = ProjectModel::getProductLine(($project['type'] + $project['product_line'])).' '. $project['id'];
        $shareName    = $project['name'].' '. $project['id'];
        // 微信分享显示的图片
        $shareShowImg = '/static/images/WXY6.jpg';

        // 微信分享跳转的地址
        $shareSubUrl  =  '/project/detail/id/'.$project['id'];
        $shareUrl     = env('WEIXIN_URL') . $shareSubUrl;

        return [//分享数据
            "share_title" => $shareName,
            "share_desc"  => $shareName,
            "share_img"   => env('WEIXIN_URL') . $shareShowImg,
            "share_url"   => $shareUrl,
            "purl"        => env('WEIXIN_URL') . $shareShowImg,
        ];
    }

    /**
     * 详情页面广告活动数据 todo
     * @return array
     */
    public static function getActivity(){
        return [
            "name"       => "",
			"title"      =>  "",
			"url"        =>  "",
			"share_img"  => "",
			"share_title"=> "",
			"share_desc" => "",
			"share_url"  => "",
			"pro_type"   => "",
			"purl"       => "",
        ];
    }
    /**
     * 获取项目债权关联数据
     * @param $id
     * @return mixed
     */
    public function getProjectLineCredit($id){
        return  $this->ProjectLinkCreditModel->getByProjectId($id);
    }

    /**
     * 多个ID获取项目关联债权的数据
     * @param $ids
     * @return mixed
     */
    public function getProjectLineCredits($ids){
        return $this->ProjectLinkCreditModel->getByProjectIds($ids);
    }

    /**
     * 获取关联的所有债权【极端情况 涉及六个表】
     * @param $projectLinkCredit
     * @return array
     */
    public function getCreditDetail($projectLinkCredit){
        if(empty($projectLinkCredit))
            return [];
        $credit_info_array = $data = $result = [];
        $credit_info = $projectLinkCredit['credit_info'];

        if($credit_info){
            $credit_info_array = json_decode($credit_info, true);
        }

        if(empty($credit_info_array)){
            return [];
        }
        //同一个类型一起查表
        foreach($credit_info_array as $record){
            $result[$record['type']][] = $record['credit_id'];
        }

        foreach($result as $code => $idData){
            $class = \App\Http\Models\Credit\CreditModel::getClass($code);
            if($class !== null && class_exists($class)){
                $data[] =\App\Http\Models\Credit\CreditModel::getDetailByIds($class, $idData);
            }
        }

        if(empty($data))
            return [];
        $result = [];
        foreach($data as $key => $records){
            foreach($records as $record){
                $result[] = $record;
            }
        }

        return $result;
    }

    /**
     * 获取关联的所有债权(新)
     * @param $creditId
     * @return array
     */
    public function getCreditDetailNew($creditId){
        if(empty($creditId))
            return [];
        $model = new CreditAllModel();

        $result = $model->getCreditDetailById($creditId);

        return $result;
    }

    /**
     * 获取内核项目详情
     *
     * @param $id
     * @return string
     */
    public function getCoreProjectInfo($id){

        $data = $this->ProjectLinkCreditModel->getCoreProjectDetail($id);

        return $data;
    }

    /**
     * @param $projectId
     * @return array
     * @desc 获取投资列表
     */
    public function getInvestList($projectId, $page = 1, $size=10){
        $investDb = new InvestDb();
        $list = $investDb->getInvestList($projectId, $page, $size);
        if(empty($list)) return [];
        //curl多个用户信息
        $userIds = array_column($list,'user_id');
        $userIds = array_unique($userIds);
        $usersInfo =  UserModel::getCoreUserListByIds($userIds);
        $usersInfo = ToolArray::arrayToKey($usersInfo);
        //处理数据
        $data = [];
        foreach($list as $key=>$val){
            $data[$key] = $val;
            $data[$key]['cash'] = ToolMoney::formatDbCashDelete($val['cash']);
            $data[$key]['phone'] = isset($usersInfo[$val['user_id']]['phone']) ? ToolStr::hidePhone($usersInfo[$val['user_id']]['phone']) : '';
        }
        return $data;
    }

    /**
     * @param $projectId
     * @return array
     * @desc 获取投资概况
     */
    public function getInvestBrief($projectId){
        $db = new InvestDb();
        $res = $db->getInvestBrief($projectId);
        if(!empty($res)){
            $res['cash'] = ToolMoney::formatDbCashDelete($res['cash']);
            if(empty($res['num'])){
                $res['avg'] = 0;
            }else {
                $res['avg'] = round($res['cash'] / $res['num'], 2);
            }
        }

        return $res;
    }

    /**
     * @param $projectId
     * @return array
     * @desc 获取单比投资排行
     */
    public function getMaxInvestTop($projectId){
        $limit = 3;
        $investDb = new InvestDb();
        $list = $investDb->getMaxInvestTop($projectId,$limit);
        if(empty($list)) return [];
        //curl多个用户信息
        $userIds = array_column($list,'user_id');
        $userIds = array_unique($userIds);
        $usersInfo =  UserModel::getCoreUserListByIds($userIds);
        $usersInfo = ToolArray::arrayToKey($usersInfo);

        //处理数据
        $data = [];
        //note
        $noteArray = [
            '0'=>'金',
            '1'=>'银',
            '2'=>'铜'
        ];
        foreach($list as $key=>$val){
            $data[$key] = $val;
            $data[$key]['cash'] = ToolMoney::formatDbCashDelete($val['cash']);
            $data[$key]['phone'] = empty($usersInfo)?'暂无':ToolStr::hidePhone($usersInfo[$val['user_id']]['phone']);
            $data[$key]['note']  = $noteArray[$key];
        }
        return $data;
    }

    /**
     * @param $projectId
     * @return array
     * @desc 获取项目还款计划
     */
    public function getRefundPlan($projectId){
        $projectDetail = $this->get($projectId);
        $plans = $this->ProjectLinkCreditModel->getCoreProjectRefundPlan($projectId);
        if(empty($plans)) return[];
        $data = [];
        $lastKey = count($plans) - 1;
        foreach($plans as $key=>$val){
            $data[$key] = $val;
            $data[$key]['refund_cash'] = ToolMoney::formatDbCashDelete($val['refund_cash']);
            if($projectDetail['refund_type'] == ProjectDb::REFUND_TYPE_EQUAL_INTEREST){
                $data[$key]['refund_note'] = '本金＋利息';
            }else{
                $data[$key]['refund_note'] = ($key==$lastKey) ? '本金＋利息' : '利息';
            }
        }
        return $data;
    }

    /**
     * [项目收益for app]
     * @param  [int] $projectId     [项目id]
     * @param  [int] $userBonusId   [使用红包加息券id]
     * @param  [int] $type          [1算一算 0预期回款]
     * @param  [int] $cash          [投资金额]
     * @param  [int] $project_way
     * @return [array]
     */
    public function getInvestProfit($projectId,$userBonusId,$type,$cash,$project_way=0){


        //项目详情
        $project = $this->getCoreProjectInfo($projectId);
        //数据初始化
        $couponRate = $coupon_interest = 0;
        //加息券
        if($userBonusId > 0){

            $UserBonusModel = new UserBonusModel();

            $userBonus = $UserBonusModel->getUserBonusById($userBonusId);

            if($userBonus['bonus_info']['type']==BonusDb::TYPE_COUPON_INTEREST){

                $couponRate = (float)$userBonus['bonus_info']['rate'];

            }

        }

        if($type){

            $res = CoreApiProjectModel::getBonusPlanInterest($projectId,$cash,$project['profit_percentage']);

            $result = $res["rate_interest"];

        } else {

            $res = CoreApiProjectModel::getBonusPlanInterest($projectId,$cash,$couponRate);

            $result = array(
                'interest'          => $res["cash_interest"],                                           //原利率所得利息
                'principalInterest' => $cash+$res["cash_interest"],                                     //原利率所得本息
                'couponInterest'    => $res['rate_interest'],                                           //使用加息券的额外奖励利息
                'couponRate'        => $couponRate,                                                     //加息券利率
                'couponText'        => empty($res['rate_interest'])?'':sprintf(AppLang::APP_INVEST_AWARD_CASH,$res['rate_interest'])    //使用加息券文字提示
            );

        }

        return self::callSuccess($result);
    }


    /**
     * 投资页面-输入框数据显示
     */
    public static function getInvestPercent(){
        $data  = [
            'items'=> "86.2%的人投资超过千元",
        ];
        return self::callSuccess($data);
    }



    //理财介绍页
    public static function getFinancePage($productLine = 101)
    {
        switch($productLine){
            case ProjectDb::PRODUCT_LINE_ONE_MONTH :
                $returnProjectType = "project-jsx";
                break;
            case ProjectDb::PRODUCT_LINE_THREE_MONTH :
                $returnProjectType = "project-jsx";
                break;
            case ProjectDb::PRODUCT_LINE_SIX_MONTH :
                $returnProjectType = "project-jsx";
                break;
            case ProjectDb::PRODUCT_LINE_TWELVE_MONTH :
                $returnProjectType = "project-jsx";
                break;
            case ProjectDb::PRODUCT_LINE_FACTORING :
                $returnProjectType = "project-free";
                break;
            default:
                $returnProjectType = "project-jsx";
                break;
        }
        return $returnProjectType;
    }

    /*
     * 获取当前项目的最后一期回款时间
     */
    public function getLastRefundPlanTime($projectId)
    {
        if( empty($projectId) ){
            return [];
        }
        $projectRefundPlan  =   $this->getRefundPlan($projectId);

        $planTimes          =   array_column($projectRefundPlan,'refund_time');
        $key                =   array_search(max($planTimes),$planTimes);
        return $planTimes[$key];
    }



    /**
     * 获取项目债权相关信息
     *
     * @param int $projectId
     * @return array
     */
    public function getCreditData($projectId = 0){
        $projectCredit = [];
        try{
            // 债权关联表
            $projectLinkCredit = $this->getProjectLineCredit($projectId);

            // 债权信息
            $projectCredit     = $this->getCreditDetail($projectLinkCredit);

        }catch (\Exception $e){
            Log::info(__METHOD__ . $projectId, [$e->getCode(), $e->getMessage(), $e->getLine()]);
        }

        return $projectCredit;
    }

    /**
     * 获取项目债权关联数据
     * @param $id
     * @return mixed
     */
    public function getProjectLineCreditDetail($id){
        try {
            return $this->ProjectLinkCreditModel->getByProjectId($id);
        }catch (\Exception $e){
            Log::info(__METHOD__ . $id, [$e->getCode(), $e->getMessage(), $e->getLine()]);
        }
        return [];
    }

    /**
     * @param $projectId | init
     * @return array | 债券的具体信息 读取新的债券规则
     */
    public static function getCreditByProjectId( $projectId = 0 )
    {
        if ( empty( $projectId ) ) {

            return [];
        }

        $linkCreditNewModel  =   new ProjectLinkCreditNewModel();

        $creditId       =   $linkCreditNewModel->getByProjectId($projectId) ;

        $creditInfo     =   CreditAllLogic::getCreditByCreditId($creditId);

        $extra  =   CreditExtendLogic::getCreditExtendByCreditId($creditId);

        $creditInfo =  array_merge($creditInfo, $extra);

        return $creditInfo;
    }
}
