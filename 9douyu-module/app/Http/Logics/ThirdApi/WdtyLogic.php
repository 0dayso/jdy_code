<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/11
 * Time: 上午10:14
 */

namespace App\Http\Logics\ThirdApi;

use App\Http\Dbs\Invest\InvestDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Current\RateModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;

class WdtyLogic extends Logic
{
    const
        MAX_PAGE_SIZE               =   20,    //默认最大数据
        STATUS_UNFINISHED_FAILED    =   160,    //用来标示项目逾期或者已垫付的
        STATUS_INVESTING_PROJECT    =   "0",    //进行中的项目
        STATUS_FINISHED_PROJECT     =   "1",    //满标,完结的项目
        STATUS_FAILED_PROJECT       =   "2";    //逾期失败的项目

    /**
     * @param $param
     * @return array
     * @desc 获取理财项目
     */
    public static function getProjectsByDate( $param )
    {

        $projectList    =   self::setProjectByDate($param);

        if( $projectList['total'] < 1 ){

            return self::setReturnResult('暂无指定条件项目');

        }

        $total  =   ceil($projectList['total']/$param['pageSize']);

        return self::setReturnResult('获取数据成功',1,$param['page'],$total,$projectList['list']);

    }

    /**
     * @param $param
     * @return array
     * @desc 获取定期项目
     */
    protected static function setProjectByDate( $param )
    {

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

        $projectList['list'] =   self::formatProjectListApi($projectList['list'],$param['status_key']);

        return $projectList;
    }

    /**
     * @param $time
     * @param $page
     * @param $pageSize
     * @return array
     * @desc 处理请求的参数
     */
    public static function doVerification( $statusKey,$startTime,$endTime,$page,$pageSize )
    {

        $status     =   self::getStatus($statusKey);

        if( $status == false ){

            return self::callError('status参数不能为空');
        }

        if( empty($endTime) || empty($startTime)){

            return self::callError('查询起始时间不合法~');
        }

        $paramArr    =  [
            'page'      =>  (int)$page,
            'pageSize'  =>  (int)$pageSize,
            'start_time'=>  $startTime,
            'end_time'  =>  $endTime,
            'status_key'=>  $statusKey,
        ];

        return self::callSuccess($paramArr);
    }

    /**
     * @param $projectId
     * @param $startTime
     * @param $endTime
     * @param $page
     * @param $pageSize
     * @return array
     * @desc 格式化投资查询数据
     */
    public static function doVerificationInvest($projectId,$page,$pageSize)
    {

        if( empty($projectId) ){

            return self::callError('id参数不能为空');
        }
        $paramArr    =  [
            'page'      =>  (int)$page,
            'pageSize'  =>  (int)$pageSize,
            'project_id'=>  $projectId,
        ];

        return self::callSuccess($paramArr);
    }

    /**
     * @param array $projects
     * @return array
     * @desc 格式化定期产品数据
     */
    protected static function formatProjectListApi($projects = array(),$status){

        $list               = array();

        if(!empty($projects)) {

            $type           =  self::setProjectType();

            $investLogic    =   new TermLogic();

            foreach ($projects as $key => $project) {

                $process    = self::formatProjectProcess($project);

                $projectId      =   $project['id'];

//                $company        =   self::getProjectCredit($projectId);
                $company        =   self::getProjectCreditNew($projectId);

                $companyInfo    =   !empty($company['data']) && $company['status'] == true ? $company['data'][0] : [];

                $platformName   =   "九斗鱼";

                $borrowUrl      =   env('APP_URL_PC') . "/project/detail/".$projectId; //项目地址

                $percentage     =   $project['profit_percentage'] / 100;

//                $loanUsername   =   isset($companyInfo['loan_username']) ? serialize(array_filter(json_decode($companyInfo['loan_username'],true))) : $projectId;
                $loanUsername   =   isset($companyInfo['loan_username']) ? $companyInfo['loan_username'] : $projectId;

                $userName       =   hash("md5", $loanUsername);

                $userId         =   isset($companyInfo['id']) ? $companyInfo['id'] : $projectId;

                $projectWay     =   self::setFormatRefundType($project['refund_type']);

                $investNum      =   $investLogic->getInvestTotalByProject($projectId);

                $termType       =   $project['refund_type'] == ProjectDb::REFUND_TYPE_BASE_INTEREST ? 0 : 1;

                $list[$key]['id']              = (string)$projectId;                    //项目主键(唯一)
                $list[$key]['url']             = (string)$borrowUrl;                    //地址
                $list[$key]['platform_name']   = (string)$platformName;                 //平台名称
                $list[$key]['title']           = (string)$project['name'];              //标题
                $list[$key]['username']        = (string)$userName;                     //发标人 （借款人） 用户名
                $list[$key]['status']          = (int)$status;                          //这笔借款标的状态，数字表示
                $list[$key]['userid']          = (int)$userId;                          //发标人 （借款人） 用户标号的 的 ID
                $list[$key]['c_type']          = (int)$type;                             //如:信用,流转，债权转让标,秒标等
                $list[$key]['amount']          = (float)$project['total_amount'];       //借款金额
                $list[$key]['rate']            = (float)$percentage;                    //借款年利率
                $list[$key]['period']          = (int)$project['invest_time'];           //还款期限
                $list[$key]['pay_way']         = (int)$projectWay;                       //还款方式
                $list[$key]['process']         = (float)$process;                        //完成百分比
                $list[$key]['reward']          = (float)0;                              //投标奖励
                $list[$key]['guarantee']       = (float)0;                              //担保奖励
                $list[$key]['start_time']      = (string)$project['created_at'];      //发标时间
                $list[$key]['invest_num']      = (int)$investNum;                       //投资次数
                $list[$key]['c_reward']        = sprintf("%.2f",0);                     //续投奖励
                $list[$key]['p_type']          = (int)$termType;                         //0代表天  1代表月

                if( $status == self::STATUS_FINISHED_PROJECT){

                    $list[$key]['end_time']        = (string)$project['max_invest_time'];                  //成功时间
                }
            }

        } else {

            $list = array();
        }

        return $list;
    }
    /**
     * @param $projectIds
     * @return mixed
     * @desc 通过项目获取投资数据
     */
    public static function getInvestProject( $params = array())
    {
//        $investDb   = new InvestDb();

        $projectIds =   [$params['project_id']];

//        $investTotal=   $investDb->getInvestTotalByProject($projectIds);
//
//        if( $investTotal < 1){
//
//            return self::setReturnResult('暂无指定条件项目');
//        }
//
//        $investList =   $investDb->getInvestList($params['project_id'],$params['page'],$params['pageSize']);

        $investList =   ProjectModel::getNormalInvestByProjectIds($projectIds);

        $investList =   self::doFormatInvestListApi($params['project_id'],$investList);

        return self::setReturnResult('获取数据成功',1,1,1,$investList);
    }

    /**
     * @param array $investList
     * @return array
     * @desc 格式化投资数据
     */
    protected static function doFormatInvestListApi($projectId, $investList =  array())
    {
        $formatList =   [];

        $borrowUrl      =   env('APP_URL_PC') . "/project/detail/".$projectId;

        if( !empty($investList) ){

            foreach ($investList as $key  => $invest ){

                $userId         = hash("md5","user_id_9douyu".$invest['user_id']);

                $userName       = hash("md5","user_name_9douyu".$invest['user_id']);

                $formatList[$key]['id']              = (string)$projectId;         //标编号
                $formatList[$key]['link']            = (string)$borrowUrl;                         //地址
                $formatList[$key]['useraddress']     = "";                            //用户所在地
                $formatList[$key]['username']        = (string)$userName;                     //发标人 （借款人） 用户名
                $formatList[$key]['userid']          = (string)$userId;                       //发标人 （借款人） 用ID
                $formatList[$key]['type']            = (string)"手动";                         //投标方式
                $formatList[$key]['money']           = (float)$invest['cash'];               //投标金额
                $formatList[$key]['account']         = (float)$invest['cash'];               //有效金额
                $formatList[$key]['status']          = "成功";                        //投标状态
                $formatList[$key]['add_time']        = (string)$invest['created_at'];        //投标时间
            }
        }

        return $formatList;
    }
    /**
     * @return string
     * @desc 获取项目类型
     */
    protected static function setProjectType()
    {
        return  "0";
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
     * @desc 获取项目的债权信息(新)
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
        1 按月等额本息还款,；
        2 按月付息,到期还本,
        3 按天计息， 一次性还本付息；
        4 按月计息，一次性还本付息；
        5 按季分期还款 （等额本金， 如果个平台的等额本金还款不为按季度分期还款， 请在接口对接的时候与技术详细沟通） ；
        16 投资当日付息,到期还本
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

    /**
     * @param string $key
     * @return mixed
     * @desc 获取标的状态0 正在投标中的借款标；
     *      1，已完成-包括还款中和已完成的借款标，
     *      2，已逾期-包括已垫付(新系统不存在逾期或者借款失败的状态,所以展示为空)
     */
    protected static function getStatus($key = '')
    {
        $statusArr  =   [self::STATUS_INVESTING_PROJECT ,self::STATUS_FAILED_PROJECT , self::STATUS_FAILED_PROJECT  ];

        if( $key =='' && !isset($statusArr[$key])){

            return false;
        }

        return true;
    }

    /**
     * @return array
     * @desc 定义默认返回的数据
     */
    public static function setReturnResult($msg="未授权的访问!",$code = "-1",$page ="0",$total = "0",$loans = null)
    {
        return  [
            "result_code"   =>   $code,
            "result_msg"    =>   $msg,
            "page_count"    =>   $total,
            "page_index"    =>   $page,
            "loans"         =>   $loans,
        ];
    }
}
