<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/28
 * Time: 下午4:23
 */

namespace App\Http\Logics\ThirdApi;


use App\Http\Dbs\Invest\InvestDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Tools\ToolArray;
use App\Tools\ToolTime;

class R360Logic extends Logic
{

    CONST
        DEFAULT_TOKEN_PREFIX    =   "9douyu_";      //生成token的前缀


    /**
     * @param $token
     * @return array|bool
     * @desc 验证Token
     */
    protected static function verifyToken($token)
    {
        $apiToken   =   self::setApiToken();

        if( $apiToken != $token ){

            $msg    =   'token error, verifyToken is error';

            return self::returnResult($msg);
        }

        return true;
    }

    /**
     * @param $userName
     * @param $password
     * @return array
     * @desc 获取token 的接口
     */
    public static function getToken($userName , $password)
    {

        if( empty($userName) || empty($password) ){

            $msg    =   'param error, username or password is Empty';

            return self::returnResult($msg);
        }

        $apiUserName    =   self::getR360UserName();

        $apiPassWord    =   self::getR360PassWord();

        if( $userName != $apiUserName || $apiPassWord != $password){

            $msg    =   'param error, username or password is Empty';

            return self::returnResult($msg);
        }

        $token  =   self::setApiRequestToken($userName , $password);

        return self::returnResult($token,'',true);
    }

    /**
     * @param $date
     * @param $page
     * @param $size
     * @return array
     * @desc 获取正在投标中的项目
     */
    public static function getInvestIngProjectList($date , $page , $pageSize)
    {
        $startTime      =   date("Y-m-d H:i:s",ToolTime::getUnixTime($date));

        $endTime        =   date("Y-m-d H:i:s",ToolTime::getUnixTime($date,'end'));

        $projectList    =   ProjectModel::getInvestIngProject($startTime,$endTime,$page,$pageSize);

        $totalAmount    =   self::getInvestCashAmount($projectList['list']);

        $projectList['list'] = self::doFormatOutputProjectList($projectList['list']);

        $formatProject  =   self::returnOutputFormat($projectList,$totalAmount,$page , $pageSize,'onSaleBorrowList');

        return $formatProject;
    }

    /**
     * @param $date
     * @param $page
     * @param $pageSize
     * @return array
     * @desc 获取某一天成功借款的项目
     */
    public static function getSuccessProjectList($date , $page , $pageSize)
    {
        $startTime      =   date("Y-m-d H:i:s",ToolTime::getUnixTime($date));

        $endTime        =   date("Y-m-d H:i:s",ToolTime::getUnixTime($date,'end'));

        $projectList    =   ProjectModel::getProjectWithTime($startTime,$endTime,$page , $pageSize);

        $totalAmount    =   self::getInvestCashAmount($projectList['list']);

        $projectList['list']=   self::doFormatOutputProjectList($projectList['list'],true);

        $formatProject  =   self::returnOutputFormat($projectList,$totalAmount,$page , $pageSize);

        return $formatProject;
    }


    /**
     * @param array $projectList
     * @param int $totalAmount
     * @param int $page
     * @param int $pageSize
     * @return array
     * @desc 格式化返回的数据格式
     */
    protected static function returnOutputFormat($projectList = array() ,$totalAmount = 0,$page =1 , $pageSize = 50,$borrowList = 'borrowList')
    {
        $total      =   $projectList['total'];

        $outputFormat   =   [
            'totalPage'         =>  ceil($total/$pageSize), //总页数
            'currentPage'    =>   $page,                 //当前页数
            'totalCount'     =>   $total,//总标数
            'totalAmount'    =>   $totalAmount,//当天借款总金额数
            $borrowList      =>   $projectList['list'],//标书的列表
        ];

        return $outputFormat;

    }

    /**
     * @param array $projectList
     * @return number|string
     * @desc 投资有的有效金额
     */
    protected static function getInvestCashAmount($projectList = array() )
    {
        if( empty($projectList) ){

            return "0";
        }

        $investCashArr  =   array_column($projectList,"invested_amount");

        return array_sum($investCashArr);
    }
    /**
     * @param array $projectList
     * @return array
     * @desc 格式满标的项目数据
     */
    protected static function doFormatOutputProjectList($projectList   =   array() ,$isSuccess = false)
    {
        if( empty($projectList) ){

            return [];
        }
        $formatList     =   [];

        $projectType    =   self::setProjectType();

        $projectIds     =   array_column($projectList,"id");

        $investList     =   self::getInvestUserInfo($projectIds);

        foreach ($projectList as $key => $project ){

            $projectId      =   $project['id'];

            $borrowUrl      =   env('APP_URL_PC') . "/project/detail/".$projectId; //项目地址

            $repaymentType  =   self::setFormatRefundType($project['refund_type']);

//            $company        =   self::getProjectCredit($projectId);
            $company        =   self::getProjectCreditNew($projectId);

            $companyInfo    =   !empty($company['data']) && $company['status'] == true ? $company['data'][0] : [];

//            $loanUsername   =   isset($companyInfo['loan_username']) ? serialize(array_filter(json_decode($companyInfo['loan_username'],true))) : $projectId;
            $loanUsername   =   isset($companyInfo['loan_username']) ? $companyInfo['loan_username'] : $projectId;

            $userName       =   hash("md5", self::DEFAULT_TOKEN_PREFIX.$loanUsername);

            $percentage     =   $project['profit_percentage']."%";

            $process        =   self::formatProjectProcess($project);

            $timeUnit       =   mb_substr($project['invest_time_unit'],0,1,"utf-8");

            $formatList[$key]['projectId']       = (string)$projectId;                    //项目主键(唯一)
            $formatList[$key]['title']           = $project['name'];              //借款标题
            $formatList[$key]['amount']          = $project['total_amount'];      //借款金额
            $formatList[$key]['schedule']        = $process;                      //进度
            $formatList[$key]['interestRate']    = $percentage;                   //利率
            $formatList[$key]['deadline']        = $project['invest_time'];       //借款期限
            $formatList[$key]['deadlineUnit']    = $timeUnit;                     //期限单位* 仅限 ‘天’ 或 ’月’
            $formatList[$key]['reward']          = 0;                             //奖励
            $formatList[$key]['type']            = $projectType;                  //借款类型可根据平台的情况修改，不限于上述类型。若一个标有多个类型，则在每个类型中间加半角分号“;”（如实地认证+担保，就传“实地认证;担保”）
            $formatList[$key]['repaymentType']   = $repaymentType;                //还款方式
            $formatList[$key]['province']        = '';                            //借款人所在省份。
            $formatList[$key]['city']            = '';                            //借款人所在城市
            $formatList[$key]['userName']        = $userName;                     //发标人ID
            $formatList[$key]['userAvatarUrl']   = '';                            //发标人头像的URL
            $formatList[$key]['amountUsedDesc']  = isset($company['loan_use']) ? $company['loan_use'] : " "; ;             //借款用途
            $formatList[$key]['revenue']         = '';                            //营收。
            $formatList[$key]['loanUrl']         = $borrowUrl;                    //标的详细页面地址链接
            $formatList[$key]['publishTime']     = $project['publish_at'];      //发标时间

            if( $isSuccess == true){

                $formatList[$key]['successTime']    = $project['max_invest_time'];     //标的成功时间

                $formatList[$key]['subscribes']     = $investList[$projectId];          //投资人数据（具体字段看下面的投标列表信息）
                //投资人数据（具体字段看下面的投标列表信息）

//                $investList         =   self::getInvestUserInfo($projectId);
//
//                if($investList ){
//
//                    foreach($investList as $invest) {
//
//                        $subscribeUserName = hash("md5",$invest['user_id']."_9douyu");
//
//                        $cash              = sprintf("%.2f", $invest['cash']);
//
//                        $formatList[$key]['subscribes'][]      = array(
//                            'subscribeUserName'     =>   $subscribeUserName,        //投标人ID
//                            'amount'                =>   $cash,                     //投标金额
//                            'validAmount'           =>   $cash,                     //有效金额
//                            'addDate'               =>   $invest['created_at'],    //投标时间
//                            'status'                =>   1,                         //投标状态
//                            'type'                  =>   0,        //标识手动或自动投标
//                        );
//
//                    }
//                }
            }
        }

        return $formatList;
    }
    /**
     * @param $project
     * @return float|int
     * @desc 计算项目进度
     */
    protected static function formatProjectProcess( $project )
    {
        $process    =   $project['invested_amount'] / $project['total_amount'] * 100;

        if( $process <1 && $project['invested_amount'] >1 ){

            $process=1;

        }

        return $process;
    }
    /**
     * @param $projectIds
     * @return mixed
     * @desc 通过项目获取投资数据
     */
    protected static function getInvestUserInfo( $projectId = array())
    {
//        $investDb   = new InvestDb();
//
//        return $investDb->getInvestList($projectId);
        
        $logic      =   new TermLogic();

        $investList =   $logic->getNormalInvestByProjectIds($projectId);

        return self::doFormatInvestList($investList);
    }

    /**
     * @param array $investList
     * @return array
     * @desc 格式化投资数据
     */
    protected static function doFormatInvestList( $investList = array())
    {
        if( empty($investList) ){

            return [];

        }

        $formatInvestList   =   [];

        foreach ($investList as $key => $invest ){

            $subscribeUserName = hash("md5",$invest['user_id']."_9douyu");

            $cash              = sprintf("%.2f", $invest['cash']);

            $formatInvest      = array(
                'subscribeUserName'     =>   $subscribeUserName,        //投标人ID
                'amount'                =>   $cash,                     //投标金额
                'validAmount'           =>   $cash,                     //有效金额
                'addDate'               =>   $invest['created_at'],    //投标时间
                'status'                =>   1,                         //投标状态
                'type'                  =>   0,        //标识手动或自动投标
            );

            $formatInvestList[$invest['project_id']][]   =  $formatInvest;

        }

        return $formatInvestList;
    }
    /**
     * @return string
     * @desc 项目文字类
     */
    protected static function setProjectType()
    {
        return '实地认证;本息安全;当日计息;担保;债权转让';
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
    2：每月等额本息(按月分期，按月等额本息)
    3：每季分期（按季分期，按季等额本息）
    5：每月付息到期还本(先息后本)
    6：等额本金(按月等额本金)
    7：每季付息到期还本（按季付息到期还本）
    15：每月付息分期还本
     */
    protected static function setFormatRefundType( $refundType )
    {
        $refundTypeFormat   =   [
            ProjectDb::REFUND_TYPE_BASE_INTEREST   => 1,       //到期还本息
            ProjectDb::REFUND_TYPE_ONLY_INTEREST   => 5,       //按月付息，到期还本
            ProjectDb::REFUND_TYPE_FIRST_INTEREST  => 16,      //投资当日付息，到期还本
        ];

        return !empty($refundTypeFormat[$refundType]) ? $refundTypeFormat[$refundType] : 5;
    }
    /**
     * @param $userName
     * @param $password
     * @return string
     * @desc 生成请求参数对应的token
     */
    protected static function setApiRequestToken($userName , $password)
    {
        return md5(self::DEFAULT_TOKEN_PREFIX.$userName.$password.ToolTime::dbDate());
    }

    /**
     * @return string
     * @desc 生成token,根据配置文件生成token
     */
    protected static function setApiToken()
    {
        $userName   =   self::getR360UserName();

        $password   =   self::getR360PassWord();

        return md5(self::DEFAULT_TOKEN_PREFIX.$userName.$password.ToolTime::dbDate());
    }
    /**
     * @return mixed
     * @DESC 对接的秘钥
     */
    protected static function getR360PassWord()
    {
        $config     =   self::setR360Config();

        return $config['R260_API_PWD'];
    }
    
    /**
     * @return mixed
     * @DESC API接口的username
     */
    protected static function getR360UserName()
    {
        $config     =   self::setR360Config();

        return $config['R360_USER_NAME'];
    }

    /**
     * @return bool|mixedu
     * @desc 融360的配置
     */
    protected static function setR360Config()
    {
        return SystemConfigLogic::getConfigByKey("APP_KEY_R360_CONFIG");
    }

    /**
     * @return array
     * @desc 定义默认返回的数据
     */
    public static function returnResult($msg = '',$status=false,$code = "8001")
    {
        return  [
            'status'    =>  $status,
            'errorNo'   =>  $code,
            'errorMsg'  =>  $msg,
        ];
    }

    /**
     * @desc    批量查询标的状态
     *
     **/
    public static function getProjectStatus($idStr=''){
        $totalLoan          = 0;
        $borrowStatusList   = [];
        if($idStr){
            $idArr  = explode('|',$idStr);
            $projectList    = ProjectModel::getProjectListByIds($idArr);

            $projectStatusArr   = [130,150,160];
            foreach ($projectList  as $key=>$value){
                if(in_array($value['status'], $projectStatusArr)){
                    $projectId  = $value['id'];
                    $status     = $value['status'] == 130 ? 1 : 2;
                    $borrowArr['projectId'] = $projectId;
                    $borrowArr['status']    = $status;
                    $borrowStatusList[]     = $borrowArr;
                }

            }
            $totalLoan      = count($borrowStatusList);

        }

        return [
            "totalLoan"         => $totalLoan,
            "borrowStatusList"  => $borrowStatusList,
        ];

    }

}