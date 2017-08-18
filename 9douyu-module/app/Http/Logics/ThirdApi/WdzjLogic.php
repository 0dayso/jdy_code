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
use App\Http\Logics\Logic;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Current\RateModel;
use App\Http\Dbs\Project\ProjectLinkCreditNewDb;
use App\Http\Dbs\Credit\CreditAllDb;

class WdzjLogic extends Logic
{
    const
        MAX_SIZE    =   20;     //默认最大数据

    /**
     * @return array
     * @desc 进行中的项目
     */
    public static function getInvestingProject()
    {
        $projectLogic   =   new ProjectLogic();

        $projectLine    =   'ALL';

        $page           =   1;

        $size           =   20;

        $status         =   130;

        $projectInfo    =   $projectLogic->getListByProjectLine($projectLine, $page, $size, $status);

        return  WdzjLogic::formatProjectListApi( $projectInfo['list']);
    }
    /**
     * @param $param
     * @return array
     * @desc 获取理财项目
     */
    public static function getProjectsByDate( $param )
    {
        $projectList    =   self::setProjectByDate($param);

        $currentList    =   self::setCurrentProjectByDate($param);

        $totalPage      =   $projectList['total'];

        if($currentList){

            $totalPage      +=   1;
        }

        $mergeProject   =   array_merge($projectList['list'],$currentList);

        if(count($totalPage) < 1){

            $returnProject["totalPage"]   = "1";

            $returnProject["currentPage"] = "1";

            $jsonArr['borrowList']        =  array();

        }else{
            $pageData = self::doSetPageInfo($param['pageSize'],$param['page'],$mergeProject,$totalPage);
            //制造分页
            $returnProject["totalPage"]   = $pageData['countPage'];

            $returnProject["currentPage"] = $param['page'];

            $returnProject['borrowList']  = $pageData['pageData'];
        }

        return $returnProject;
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

        $projectList    =   ProjectModel::getProjectWithTime($startTime,$endTime,$pageIndex,$pageSize);

        $projectList['list'] =   self::formatProjectListApi($projectList['list']);

        return $projectList;
    }

    /**
     * @param $param
     * @return array
     * @desc 获取零钱计划
     */
    protected static function setCurrentProjectByDate( $param )
    {

        $startTime      =   $param['start_time'];

        $endTime        =   $param['end_time'];

        $currentLogic   =   new CurrentLogic();

        $currentProject =   $currentLogic->getCurrentProjectByDate($startTime,$endTime);

        return self::formatCurrentProjectListApi($currentProject,$param);
    }

    /**
     * @param $time
     * @param $page
     * @param $pageSize
     * @return array
     * @处理请求的参数
     */
    public function doVerification( $time , $page , $pageSize )
    {
        if( empty($time) ){

            return self::callError('日期不合法');
        }

        if( empty($page) ){

            $page   =   1 ;
        }


        if( empty($pageSize) ){

            $pageSize   =self::MAX_SIZE;
        }

        $time  = strtotime($time);

        $startTime   =  date("Y-m-d 00:00:00",$time);

        $endTime     =  date("Y-m-d H:i:s",strtotime('+1 day',$time));

        $paramArr    =  [
            'page'      =>  (int)$page,
            'pageSize'  =>  (int)$pageSize,
            'start_time'=>  $startTime,
            'end_time'  =>  $endTime,
        ];

        return self::callSuccess($paramArr);
    }


    /**
     * @param array $projects
     * @return array
     * @desc 格式化定期产品数据
     */
    protected static function formatProjectListApi($projects = array()){

        $list               = array();
        if(!empty($projects)) {

            $type           =  self::setProjectType();

            $address        = array(
                'province' => '',
                'city'     => ''
            );

            foreach ($projects as $key => $project) {

                $process    = self::formatProjectProcess($project);
                //满标才输出
                if( $process == 100){

                    $projectId  = $project['id'];

                    //$company    = self::getProjectCredit($projectId);
                    $company    = self::getProjectCreditNew($projectId);

                    $successTime= $project['full_at'];

                    $borrowUrl  = env('APP_URL_PC') . "/project/detail/".$projectId; //项目地址

                    $percentage = $project['profit_percentage'] . "%";

                    $timeUnit   = mb_substr($project['invest_time_unit'],0,1,"utf-8");

                    //$loanUsername=  isset($company['loan_username']) ? serialize(array_filter($company['loan_username'])) : $projectId;
                    $loanUsername=  isset($company['loan_username']) ? $company['loan_username'] : $projectId;

                    $userName = hash("md5", $loanUsername);

                    $list[$key]['projectId']    = $projectId;                    //项目主键(唯一)
                    $list[$key]['title']        = $project['name'];              //借款标题
                    $list[$key]['amount']       = $project['total_amount'];      //借款金额
                    $list[$key]['schedule']     = self::formatProjectProcess($project);                      //进度
                    $list[$key]['interestRate'] = $percentage;                   //利率
                    $list[$key]['deadline']     = $project['invest_time'];       //借款期限
                    $list[$key]['deadlineUnit'] = $timeUnit;                     //期限单位* 仅限 ‘天’ 或 ’月’
                    $list[$key]['reward']       = 0;                             //奖励
                    $list[$key]['type']         = $type;                         //借款类型可根据平台的情况修改，不限于上述类型。若一个标有多个类型，则在每个类型中间加半角分号“;”（如实地认证+担保，就传“实地认证;担保”）
                    $list[$key]['repaymentType']= self::setFormatRefundType($project['refund_type']);                //还款方式
                    $list[$key]['province']     = $address['province'];          //借款人所在省份。
                    $list[$key]['city']         = $address['city'];              //借款人所在城市
                    $list[$key]['userName']     = $userName;                     //发标人ID
                    $list[$key]['userAvatarUrl'] = '';                            //发标人头像的URL
                    $list[$key]['amountUsedDesc']= isset($company['loan_use']) ? $company['loan_use'] : " ";             //借款用途
                    $list[$key]['revenue']      = '';                            //营收。
                    $list[$key]['loanUrl']      = $borrowUrl;                    //标的详细页面地址链接
                    $list[$key]['successTime']  = $successTime;                  //标的成功时间
                    $list[$key]['publishTime']  = $project['publish_at'];      //发标时间
                    $list[$key]['subscribes'] = array();

                    //投资人数据（具体字段看下面的投标列表信息）
                    $investList     =   self::getInvestUserInfo($projectId);
                    if( $investList ){

                        foreach ($investList as $num => $invest){

                            $subscribeUser  = hash("md5", $invest['user_id'] . "_9douyu");

                            $cash           = sprintf("%.2f", $invest['cash']);

                            $list[$key]['subscribes'][] = array(
                                'subscribeUserName'     => $subscribeUser,        //投标人ID
                                'amount'                => $cash,                     //投标金额
                                'validAmount'           => $cash,              //有效金额
                                'addDate'               => $invest['created_at'],    //投标时间
                                'status'                => 1,                         //投标状态
                                'type'                  => 0,                       //标识手动或自动投标
                            );
                        }
                    }

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
    protected static function getInvestUserInfo( $projectId )
    {
        $investDb   = new InvestDb();

        return $investDb->getInvestList($projectId);

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 通过项目获取投资零钱计划数据
     */
    protected static function getInvestCurrentProjectUserInfo( $params )
    {
        $investDb   = new CurrentLogic();

        $investInfo = $investDb->getInvestCurrentUserByTime($params['start_time'], $params['end_time']);

        return $investInfo;
    }

    /**
     * @param $investsList
     * @return array
     * @desc 格式化零钱计划投资人数据
     */
    protected static function doFormatInvestCurrentUserInfo( $investsList )
    {
        if( empty($investsList)){

            return [];
        }

        $subscribes     =   [];

        foreach ($investsList as $invest) {

            $subscribeUserName = hash("md5", $invest['user_id'] . "_9douyu");

            $cash               = sprintf("%.2f", $invest['cash']);

            $validAmount  = sprintf("%.2f", $invest['cash']);

            $subscribes[] = array(

                'subscribeUserName' => $subscribeUserName,          //投标人ID
                'amount'            => $cash,                       //投标金额
                'validAmount'       => $validAmount,                //有效金额
                'addDate'           => $invest['created_at'],      //投标时间
                'status'            => 1,                           //投标状态
                'type'              => $invest['type'] ==\App\Http\Dbs\Current\InvestDb::INVEST_CURRENT_AUTO ? 1 : 0,                           //标识手动或自动投标
            );
        }

        return $subscribes;
    }
    /**
     * @param $currentProject
     * @return mixed
     * @desc 格式化零钱计划的数据
     */
    protected static function formatCurrentProjectListApi( $currentProject ,$param)
    {
        if( empty($currentProject) ){

            return $currentProject;
        }
        $list       =   [];
        $rateModel      = new RateModel();

        $rateData       = $rateModel->getRate();

        $totalAmount        =   0;
        $investedAmount     =   0;
        $interestRate       =   (float)$rateData['rate'];   //项目最后利率
        $currentIds         =   array_column($currentProject,"id");
        $currentId          =   "";
        $publishTime        =   "";
        $successTime        =   "";
        foreach ($currentProject as $key => $current ){

            if( $current['total_amount'] == $current['invested_amount'] ){

                $totalAmount    +=  $current['total_amount'];   //借款金额
                $investedAmount +=  $current['invested_amount']; //投资总额
                $currentId      =   $current['id'];
                $publishTime    =   $current['created_at'];
                $successTime    =   $current['updated_at'];
            }

        }
        //如果没有满标的就随心,则返回空
        if( $totalAmount  <= 0 ){

            return [];
        }

        $processData            =   ['invested_amount'=>$investedAmount,'total_amount'=>$totalAmount];

        $investList             =   self::getInvestCurrentProjectUserInfo($param);

         //项目数据
        $borrowUrl              =   env('APP_URL_PC') . "/project/current/detail";//项目地址

        $userName               =   hash("md5",serialize($currentIds));

        //满标才输出
        $list['projectId']      =   "JSX_".$currentId;                                  //项目主键(唯一)
        $list['title']          =   "零钱计划";                                     //借款标题
        $list['amount']         =   $totalAmount;                                //借款金额
        $list['schedule']       =   self::formatProjectProcess($processData);    //进度
        $list['interestRate']   =   $interestRate . "%";                         //利率
        $list['deadline']       =   "2";                                         //借款期限
        $list['deadlineUnit']   =   '天';                                        //期限单位* 仅限 ‘天’ 或 ’月’
        $list['reward']         =   0;                                           //奖励
        $list['type']           =   '抵押标;零钱计划产品';                             //借款类型可根据平台的情况修改，不限于上述类型。若一个标有多个类型，则在每个类型中间加半角分号“;”（如实地认证+担保，就传“实地认证;担保”）
        $list['repaymentType']  =   1;                                           //还款方式
        $list['province']       =   '';                                          //借款人所在省份。
        $list['city']           =   '';                                          //借款人所在城市
        $list['userName']       =   $userName;                                   //发标人ID
        $list['userAvatarUrl']  =   '';                                          //发标人头像的URL
        $list['amountUsedDesc'] =   '';                                          //借款用途
        $list['revenue']        =   '';                                          //营收。
        $list['loanUrl']        =   $borrowUrl;                                  //标的详细页面地址链接
        $list['successTime']    =   $successTime;                                //标的成功时间
        $list['publishTime']    =   $publishTime;                                //发标时间
        $list['subscribes']     =   self::doFormatInvestCurrentUserInfo($investList);

        return [$list];
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
        $process    =   $project['invested_amount'] / $project['total_amount'] * 100;

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
        $logic      =   new ProjectDetailLogic();

        $creditInfo =   $logic->getProjectLineCredit($projectId);

        return $logic->getCreditDetail($creditInfo);
    }

    /**
     * @desc 获取项目的债权信息(New)
     * @return array
     */
    public static function getProjectCreditNew( $projectId )
    {
        $projectLinkNewDb = new ProjectLinkCreditNewDb();

        $creditAllDb = new CreditAllDb();


        $credit = $projectLinkNewDb->getByProjectId( $projectId );


        return $creditAllDb->getCreditByCreditId( $credit->credit_id );
    }

    /**
     * @param $pageSize
     * @param $page
     * @param $data
     * @param $total
     * @return array
     * @desc 生成分页
     */
    protected static function doSetPageInfo($pageSize,$page,$data,$total)
    {
        $page       =   empty($page) ? '1' : $page; #判断当前页面是否为空 如果为空就表示为第一页面

        $start      =   ($page-1) * $pageSize; #计算每次分页的开始位置


        $countPage  =   ceil($total/$pageSize); #计算总页面数

        $pageData   =   [];

        $returnData =   [];

        $pageData   =   array_slice($data,$start,$pageSize);

        $returnData['countPage'] = $countPage;

        $returnData['pageData']  = $pageData;

        return $returnData; #返回查询数据
    }

    /**
     * @param $refundType
     * @return int|mixed
     * @desc 格式化还款方式
     */
    protected static function setFormatRefundType( $refundType )
    {
        $refundTypeFormat   =   [
            ProjectDb::REFUND_TYPE_BASE_INTEREST   => 1,       //到期还本息
            ProjectDb::REFUND_TYPE_ONLY_INTEREST   => 5,       //按月付息，到期还本
            ProjectDb::REFUND_TYPE_FIRST_INTEREST  => 9,       //投资当日付息，到期还本
        ];

        return !empty($refundTypeFormat[$refundType]) ? $refundTypeFormat[$refundType] : 1;
    }
}
