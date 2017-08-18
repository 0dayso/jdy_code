<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/1
 * Time: 下午3:55
 */

namespace App\Http\Logics\ThirdApi;


use App\Http\Dbs\Invest\InvestDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Tools\ToolArray;

class LycjLogic extends Logic
{
    const
        STATUS_UNFINISHED_FAILED    =   160,    //用来标示项目逾期或者已垫付的
        STATUS_INVESTING_PROJECT    =   "0",    //进行中的项目
        STATUS_FINISHED_PROJECT     =   "1",    //满标,完结的项目
        STATUS_FAILED_PROJECT       =   "2";    //逾期失败的项目

    /**
     * @param $quest
     * @return string
     * @desc 生成零壹财经的sign
     */
    public static function setProduceSign()
    {
        $questParam =   explode("&",urldecode($_SERVER['QUERY_STRING']));

        $visitSign  =   self::setVisitSign();

        array_pop($questParam);

        $signDataStr = $visitSign."&".implode("&",$questParam);

        return strtoupper(md5($signDataStr));
    }

    /**
     * @param $timeFrom
     * @param $timeTo
     * @param $status
     * @param int $pageIndex
     * @param int $pageSize
     * @desc  通过时间状态获得项目数据
     */
    public static function getProjectByStatus($timeFrom,$timeTo,$status,$pageIndex=1,$pageSize=100,$sign='')
    {
        #$produceSign = self::setProduceSign();
        #if($produceSign != $sign){
        #    return self::setReturnResult('签名验证失败');
        #}
        $param = [
            'page'      =>  (int)$pageIndex,
            'pageSize'  =>  (int)$pageSize,
            'start_time'=>  $timeFrom,
            'end_time'  =>  $timeTo,
            'status_key'=>  $status,
        ];

        $checked = self::checkFilterParam($param);
        if($checked){

            return $checked;
        }

        #获取数据
        $projectList    =   self::setProjectByDateAndStatus($param);

        if( $projectList['total'] < 1 ){

            return self::setReturnResult('暂无指定条件项目');

        }
        #格式化数据
        $projectList['list'] =   self::doSetFormatProjectInformation($projectList['list']);

        $total  =   ceil($projectList['total']/$pageSize);

        return self::setReturnResult('获取数据成功',1,$pageIndex,$total,$projectList['list']);

    }

    /**
     * @param   $param
     * @return  array
     * @desc    参数检测
     *
     */
    public static function checkFilterParam($param){
        #状态检测
        if(empty($param["status_key"]) || !in_array($param["status_key"], [self::STATUS_FINISHED_PROJECT , self::STATUS_INVESTING_PROJECT])){
            return self::setReturnResult('请选择项目状态');
        }
        #开始时间，结束时间
        if(isset($param["start_time"]) && empty($param["start_time"]) ){
            return self::setReturnResult('请输入开始时间');
        }

        if(isset($param["end_time"]) && empty($param["end_time"]) ){
            return self::setReturnResult('请输入结束时间');
        }
        return [];

    }


    /**
     * @param $id
     * @return array()
     * @desc  通过项目获取到项目的投资记录
     */
    public static function getInvestmentRecord( $project_id, $pageIndex=1, $pageSize=100 )
    {
        if($project_id < 0){
            return self::setReturnResult('暂无指定条件项目');
        }
        $investDb   = new InvestDb();
        $projectIds =   [$project_id];
        $investTotal=   $investDb->getInvestTotalByProject($projectIds);

        if( $investTotal < 1){

            return self::setReturnResult('暂无指定条件项目');
        }

        $investList =   $investDb->getInvestList($project_id, $pageIndex, $pageSize );

        $investList =   self::doSetFormatInvestmentRecord($project_id, $investList);

        $total  =   ceil($investTotal/$pageSize);

        return self::setReturnResult('获取数据成功',1, $pageIndex, $total, $investList);

    }

    /**
     * @param $timeFrom
     * @param $timeTo
     * @param $status
     * @return array
     * @desc   验证的数据接口
     */
    public static function setDataValidation($timeFrom,$timeTo,$status)
    {
        #$validationData =   [];
        $param = [
            'page'      =>  1,
            'pageSize'  =>  2000,
            'start_time'=>  $timeFrom,
            'end_time'  =>  $timeTo,
            'status_key'=>  $status,
        ];

        $checked = self::checkFilterParam($param);

        if($checked){

            return $checked;
        }

        #获取数据
        $projectList    =   self::setProjectByDateAndStatus($param);

        if( $projectList['total'] < 1 ){

            return self::setReturnResult('暂无指定条件项目');

        }
        #格式化数据
        $result = self::doSetFormatDataValidation($projectList);

        return self::setReturnResult('获取数据成功',1, '', '', $result);

    }

    /***
     * @param   $param
     * @return  array
     * @desc    核心库中获取项目列表
     *
     */
    public static function setProjectByDateAndStatus($param){

        $startTime      =   $param['start_time'];
        $endTime        =   $param['end_time'];
        $pageIndex      =   $param['page'];
        $pageSize       =   $param['pageSize'];

        $projectList    =   ['list' => [] , 'total' => 0 ];

        if( $param['status_key'] == self::STATUS_FINISHED_PROJECT ){

            $projectList    =   ProjectModel::getProjectWithTime($startTime,$endTime,$pageIndex,$pageSize);
        }
        if( $param['status_key'] == self::STATUS_INVESTING_PROJECT ){

            $projectList    =   ProjectModel::getInvestIngProject($startTime,$endTime,$pageIndex,$pageSize);
        }

        return $projectList;

    }


    /**
     * @param array $projectList
     * @return array
     * @desc  格式化项目数据
     */
    protected static function doSetFormatProjectInformation( $projectList = array())
    {
        $list               = array();

        if(!empty($projectList)) {

            $investLogic    =   new TermLogic();

            foreach ($projectList as $key => $project) {

                $process        = self::formatProjectProcess($project);

                $projectId      =   $project['id'];

//                $company        =   self::getProjectCredit($projectId);
                $company        =   self::getProjectCreditNew($projectId);
                $companyInfo    =   !empty($company['data']) && $company['status'] == true ? $company['data'][0] : [];

                $platformName   =   "九斗鱼";

                $borrowUrl      =   env('APP_URL_PC') . "/project/detail/".$projectId;  //项目地址

                $percentage     =   $project['profit_percentage'] / 100;

//                $loanUsername   =   isset($companyInfo['loan_username']) ? serialize(array_filter(json_decode($companyInfo['loan_username'],true))) : $projectId;
                $loanUsername   =   isset($companyInfo['loan_username']) ? $companyInfo['loan_username'] : $projectId;

                $userName       =   hash("md5", $loanUsername);
                $userId         =   isset($companyInfo['id']) ? $companyInfo['id'] : $projectId;
                $asset_type     = "";
                if(isset($companyInfo["source"])){
                    $sourceType =   CreditModel::getSourceType();
                    $asset_type =   isset($sourceType[$companyInfo["source"]])?$sourceType[$companyInfo["source"]]:"";
                }
                $projectWay     =   self::setFormatRefundTypeName($project['refund_type']);
                $productType    =   self::setFormatProjectProductLineName($project["product_line"]);

                $investNum      =   $investLogic->getInvestTotalByProject($projectId);
                $termType       =   $project['refund_type'] == ProjectDb::REFUND_TYPE_BASE_INTEREST ? 0 : 1;
                $borrowPeriod  =   "";
                if(isset($project['invest_time']) && $project['invest_time'] > 0 ){
                    $borrowPeriod  =   $termType==1?$project['invest_time']."个月":$project['invest_time']."天";
                }

                $list[$key]['id']           = (string)$projectId;                    //项目主键(唯一)
                $list[$key]['link']         = (string)$borrowUrl;                    //地址
                $list[$key]['title']        = (string)$project['name'];              //标题
                $list[$key]['username']     = (string)$userName;                     //发标人 （借款人） 用户名
                $list[$key]['userid']       = (int)$userId;                          //发标人 （借款人） 用户标号的 的 ID
                $list[$key]['amount']       = (float)$project['total_amount'];       //借款金额
                $list[$key]['interest']     = (float)$percentage;                    //借款年利率

                $list[$key]["asset_type"]   = $asset_type;                    //资产类型 如:信用,流转，债权转让标,秒标等
                $list[$key]["borrow_type"]  = $productType;                   //借款类型，
                $list[$key]["product_type"] = "定期";                         //产品类型， 散标，理财计划，定期，活期

                $list[$key]["borrow_period"]= (string)$borrowPeriod;                //借款期限
                $list[$key]["repay_type"]   = (string)$projectWay;                  //还款方式
                $list[$key]["percentage"]   = (float)$process;                      //完成百分比
                $list[$key]["reward"]       = (float)0;                             //投标奖励
                $list[$key]["guarantee"]    = (float)0;                             //担保奖励
                $list[$key]["credit"]       = '';                                   //信用等级

                $list[$key]["verify_time"]  = (string)$project['publish_at'];       //发标时间
                $list[$key]["reverify_time"]= (string)$project['max_invest_time'];  //成功时间
                $list[$key]["invest_count"] = (int)$investNum;                      //投资次数
                $list[$key]["borrow_detail"]= '';                                   //借款详情
                $list[$key]["attribute1"]   = '';                                   //扩展字段1
                $list[$key]["attribute2"]   = '';                                   //扩展字段2
                $list[$key]["attribute3"]   = '';                                   //扩展字段3

            }

        } else {

            $list = array();
        }

        return $list;

    }


    /**
     * @param array $projectList
     * @desc    格式化验证数据
     */
    public static function doSetFormatDataValidation($projectList=[]){

        $projectListArr = $projectList["list"];
        $amount_total   = 0;
        //借款笔数
        $volume_count   = isset($projectList["total"])?$projectList["total"]:0;
        //投资人数
        $investor_count = 0;
        //投标记录
        $investment_num = 0;

        $borrowerArr    = [];
        if($projectListArr){

            $projectIds = ToolArray::arrayToIds($projectListArr, 'id');
            $investLogic    = new TermLogic();
            $investment_num = $investLogic->getInvestTotalByProject($projectIds);
            $investor_count = $investLogic->getInvestPeopleByProject($projectIds);

            foreach ($projectListArr as $key => $project) {

                $projectId      = $project['id'];
                #获取债权信息
//                $company        = self::getProjectCredit($projectId);
                $company        = self::getProjectCreditNew($projectId);
                $companyInfo    = !empty($company['data']) && $company['status'] == true ? $company['data'][0] : [];

//                $loanUsername   = isset($companyInfo['loan_username']) ? serialize(array_filter(json_decode($companyInfo['loan_username'],true))) : $projectId;
                $loanUsername   = isset($companyInfo['loan_username']) ? $companyInfo['loan_username'] : $projectId;
                $userName       = hash("md5", $loanUsername);
                $userId         = isset($companyInfo['id']) ? $companyInfo['id'] : $projectId;

                $amount_total  += $project["total_amount"];
                if(!in_array($userId, $borrowerArr)){

                    $borrowerArr[]= $userId;

                }

            }

        }
        $borrower_count = count($borrowerArr);
        $result =  [
            [
                "amount_total"    => $amount_total,
                "volume_count"    => $volume_count,
                "borrower_count"  => $borrower_count,
                "investor_count"  => $investor_count,
                "investment_num"  => $investment_num,
            ]
        ];
        return   $result;

    }

    /**
     * @param array $investList
     * @return array
     * @desc  格式化投资记录
     */
    protected static function doSetFormatInvestmentRecord($projectId ,$investList = array())
    {
        $formatList   =   [];
        $borrowUrl      =   env('APP_URL_PC') . "/project/detail/".$projectId;

        if( !empty($investList) ){

            foreach ($investList as $key  => $invest ){

                $userId         = hash("md5","user_id_9douyu".$invest['user_id']);
                $userName       = hash("md5","user_name_9douyu".$invest['user_id']);
                $bid_source     = $invest["app_request"]=="pc"?"PC端":"移动端";

                $formatList[$key]['invest_id']       = (string)$invest["invest_id"];//表的主键
                $formatList[$key]['id']              = (string)$projectId;          //标编号
                $formatList[$key]['link']            = (string)$borrowUrl;          //地址
                $formatList[$key]['username']        = (string)$userName;           //发标人 （借款人） 用户名
                $formatList[$key]['userid']          = (string)$userId;             //发标人 （借款人） 用ID
                $formatList[$key]['type']            = (string)"手动";               //投标方式
                $formatList[$key]['money']           = (float)$invest['cash'];      //投标金额
                $formatList[$key]['account']         = (float)$invest['cash'];      //有效金额
                $formatList[$key]['status']          = "成功";                            //投标状态
                $formatList[$key]['add_time']        = (string)$invest['created_at'];    //投标时间
                $formatList[$key]['bid_source']      = (string)$bid_source;              //投标来源

            }
        }

        return $formatList;
    }


    /**
     * @return mixed
     * @desc 零壹财经安全接口的visit_sign
     */
    protected static function setVisitSign()
    {
        $config     =   self::setConfig();

        return $config['VISIT_SIGN'];
    }
    /**
     * @return mixed
     * @desc 零壹财经安全接口的visit_key
     */
    protected static function setVisitKey()
    {
        $config     =   self::setConfig();

        return $config['VISIT_KEY'];
    }

    /**
     * @return array|mixed
     * @desc 零壹财经的配置文件
     */
    protected static function setConfig()
    {
        return SystemConfigLogic::getConfig('LYCJ_P2P_CONFIG');
    }

    /**
     * @return string
     * @desc 获取项目类型
     */
    protected static function setProjectType()
    {
        return  "实地认证;本息安全;当日计息;担保";
    }

    /**
     * @param $project
     * @return float|int
     * @desc 计算项目进度
     */
    protected static function formatProjectProcess( $project )
    {
        $process    =   $project['invested_amount'] / $project['total_amount'];

        if( $process <1 && $project['invested_amount'] >1 ){

            $process=1;

        }

        return $process;
    }

    /**
     * @param $projectId
     * @return array
     * @desc 获取项目的债券信息
     */
    public static function getProjectCredit( $projectId )
    {
        $model      =   new ProjectLinkCreditModel();

        try{

            $creditInfo =   $model->getByProjectId($projectId);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        $logic  =   new ProjectDetailLogic();

        $result =   $logic->getCreditDetail($creditInfo);

        return self::callSuccess($result);
    }

    /**
     * @param $projectId
     * @return array
     * @desc 获取项目的债券信息(新)
     */
    public static function getProjectCreditNew( $projectId )
    {
        $model      =   new ProjectLinkCreditNewModel();

        try{

            $creditId =   $model->getByProjectId($projectId);

        }catch (\Exception $e){

            return self::callError($e->getMessage());
        }

        $logic  =   new ProjectDetailLogic();

        $result =   $logic->getCreditDetailNew($creditId);

        return self::callSuccess($result);
    }

    /**
     * @param $refundType
     * @return int|mixed
     * @desc 格式化还款方式
     *
     * 获取还款类型
     * 1 按月等额本息还款,；
     * 2 按月付息,到期还本,
     * 3 按天计息，一次性还本付息；
     * 4 按月计息，一次性还本付息；
     * 5 按季分期还款 （等额本金， 如果个平台的等额本金还款不为按季度分期还款， 请在接口对接的时候与技术详细沟通） ；
     * 16 投资当日付息,到期还本
     */
    protected static function setFormatRefundType( $refundType )
    {
        $refundTypeFormat   =   [
            ProjectDb::REFUND_TYPE_BASE_INTEREST   => 3,       //到期还本息
            ProjectDb::REFUND_TYPE_ONLY_INTEREST   => 2,       //按月付息，到期还本
            ProjectDb::REFUND_TYPE_FIRST_INTEREST  => 16,       //投资当日付息，到期还本
        ];

        return !empty($refundTypeFormat[$refundType]) ? $refundTypeFormat[$refundType] : 3;
    }

    /***
     * @param $refundType
     * @return mixed|string
     * @desc    回款类型描述信息
     *
     */
    protected static function setFormatRefundTypeName( $refundType )
    {
        $refundTypeFormat   =   [
            ProjectDb::REFUND_TYPE_BASE_INTEREST   => "到期还本息",          //到期还本息
            ProjectDb::REFUND_TYPE_ONLY_INTEREST   => "按月付息，到期还本",   //按月付息，到期还本
            ProjectDb::REFUND_TYPE_FIRST_INTEREST  => "投资当日付息，到期还本",//投资当日付息，到期还本
        ];

        return !empty($refundTypeFormat[$refundType]) ? $refundTypeFormat[$refundType] : "";
    }

    /**
     *
     * @desc    项目产品线
     *
     **/
    protected static function setFormatProjectProductLineName( $productLine )
    {
        $productLineFormat   =   [
            ProjectDb::PROJECT_PRODUCT_LINE_JSX     => "九省心",
            ProjectDb::PROJECT_PRODUCT_LINE_JAX     => "九安心",
            ProjectDb::PROJECT_PRODUCT_LINE_SDF     => "闪电付息",
        ];

        return !empty($productLineFormat[$productLine]) ? $productLineFormat[$productLine] : "";
    }

    /**
     * @return array
     * @desc 定义默认返回的数据
     */
    public static function setReturnResult($msg="未授权的访问!",$code = "-1",$page ="0",$total = "0",$data = [])
    {
        return  [
            "result_code"   =>   $code,
            "result_msg"    =>   $msg,
            "page_count"    =>   $total,
            "page_index"    =>   $page,
            "data"          =>   $data,
        ];
        exit();
    }

}