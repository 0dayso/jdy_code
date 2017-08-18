<?php
/**
 * @desc    小微金融
 * @date    2016年12月07日
 * @author  @llper
 *
 */

namespace App\Http\Logics\ThirdApi;

use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Tools\ToolArray;
use App\Tools\ToolCurl;
use Log;

class XiaoWeiLogic extends Logic
{
    const
        MEMBERNO    = "A0000024",   //本机构编码
        //机构前置机接口地址（机构根据自己部署的地址进行修改）
        BASEURL     = "http://59.110.63.100:8080/pcac_front/queryRiskInfo";

    /**
     * @param   string $requestData
     * @return  array
     */
    public static  function getQueryRiskInfo($requestData=""){
        if($requestData){
            //机构前置机接口地址（机构根据自己部署的地址进行修改）
            #$baseurl='http://192.168.109.108:8080/pcac_front/queryRiskInfo';
            $return = self::queryRiskInfoRest("POST",self::BASEURL,$requestData);

            return $return;

        }else{

            return self::setReturnResult('110',"非法的请求参数，requestData 为空或无法识别，应符合 JSON 格式规范");

        }

    }

    /**
     * rest 请求
     * @param String $method 请求类型
     * @param String $uri 请求路由
     * @param Array $param 请求参数
     * @param Array $options 请求设置
     * @return String
     */
    public static function queryRiskInfoRest($method,$url,$param=NULL,$options=NULL){

        $option_defaults = array(
            CURLOPT_HEADER      => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT     => 10,
            CURLOPT_HTTPHEADER  => array(
                'Content-type: application/x-www-form-urlencoded'
            )
        );

        $handle = curl_init();
        // Compose querry
        if($method=='GET'){
            $options = array(
                CURLOPT_URL => $param ? ($url."?".$param) : $url,
                CURLOPT_CUSTOMREQUEST => $method, // GET POST PUT PATCH DELETE HEAD OPTIONS
            );
        }else if($method=='POST'){
            $options = array(
                CURLOPT_URL         => $url,
                CURLOPT_CUSTOMREQUEST=>$method, // GET POST PUT PATCH DELETE HEAD OPTIONS
                CURLOPT_POSTFIELDS  => 'requestData='.$param,
            );
        }

        curl_setopt_array($handle,($options + $option_defaults));
        // send request and wait for response
        $response =  curl_exec($handle);
        return($response);
    }


    /**
     * @desc    机构风险信息
     * @param   string $requestData
     * @return  array
     * 采集机构反馈结果
     * 在线健康检查
     * 入库量统计
     */
    public static  function getMemberRiskInfo($requestData=""){
        if($requestData){
            Log::info(__METHOD__." 小微金融-请求信息- ", json_decode($requestData,true));
            $requestDataJson= json_decode($requestData, true);  //接收的数据

            $targetMemberNo = $requestDataJson['targetMemberNo'];
            $action         = $requestDataJson["data"]["action"];
            $jsonResponse   = [];

            switch ($action){
                case "health":
                    //进入健康状态接口
                    $jsonResponse=self::health($targetMemberNo);
                    break;
                case "memberInfo":
                    //进入客户入库量
                    $jsonResponse=self::memberInfo($targetMemberNo);
                    break;
                case "feedback":
                    // 进入风险信息
                    $version = $requestDataJson["version"];

                    if($version !="1.0"){
                        return self::setReturnResult('-1',"协议版本不匹配");
                        exit;
                    }else{
                        $idType = $requestDataJson["data"]["idType"];
                        $idNo   = $requestDataJson["data"]["idNo"];
                        $jsonResponse = self::feedback($targetMemberNo,$idType,$idNo);
                    }

                    break;
            }
            Log::info(__METHOD__." 小微金融-返回信息", $jsonResponse);
            return $jsonResponse;
        }else{

            return self::setReturnResult('110',"非法的请求参数，requestData 为空或无法识别，应符合 JSON 格式规范");

        }

    }

    /***
     * @param   string $targetMemberNo
     * @return  array
     * @desc    机构接口健康状态
     * @param   $targetMemberNo 机构编号
     */
    public static  function health($targetMemberNo=""){

        $result = array (
            'status'    => '0',
            'message'   => '查询成功',
            'memberNo'  => $targetMemberNo,
            'data'  => array(
                'apistatus' => '0',             //业务系统状态
                'memberNo'  => $targetMemberNo  //机构编号
            )
        );

        return $result;

    }

    /**
     * @desc
     *  获取客户的入库量和更新时间
     *  机构根据实际情况进行填写数据
     * @param memberNo
     * @return
     */
    public static function memberInfo($targetMemberNo){

        $creditUserArr = CreditModel::getCreditUser();
        $ind = 0;
        if($creditUserArr){
              $ind = self::creditUserFormat($creditUserArr);
        }
        $result = array (
            'status'    => '0',
            'message'   => '查询成功',
            'data'  => array(
                'ent'       => 0,               //企业客户数量
                'memberNo'  =>$targetMemberNo,  //机构的编号
                'ind'       => $ind,            //个人客户数量
                'blackList' => 0,               //黑名单客户数量
                'updateTime'=> date('Y-m-d h:i:s',time())//更新时间即当天时间
            )
        );

        return $result;
    }
    /**
     * @desc    处理借款用户
     *
     **/
    public static function creditUserFormat($creditUserArr=[]){
        $idNoArr = [];
        $idNoStr = '';
        foreach($creditUserArr as $creditUser){

            if($creditUser->loan_user_identity){

                $idNoStr .= $creditUser->loan_user_identity.',';
                /*
                if($idNoArr1){
                    if(is_array($idNoArr1)){
                        foreach ($idNoArr1  as $kk=>$vv){
                            if( $vv && !in_array($vv,$idNoArr)) {
                                $idNoArr[] = $vv;
                            }
                        }
                    }else{
                        if(!in_array($idNoArr1,$idNoArr)){
                            $idNoArr[] = $idNoArr1;
                        }
                    }

                }*/

            }
        }

        $idNoArr = explode(',', $idNoStr);

        return count($idNoArr);
    }

    /**
     * @param string $targetno
     * @param string $idType
     * @param string $idNo
     *
     * @desc  机构风险信息反馈结果
     */
    public static  function feedback($targetno="",$idType="",$idNo=""){

        #测试返回数据-全格式数据
        $resultData = self::feedbackCompleteData($targetno,$idType,$idNo);

        if($resultData){

            return $resultData;

        }

        #借款通过笔数
        $approvedArr    =  CreditModel::getCreditWithUser($idNo);
        #借款拒绝笔数
        $rejectedArr    = [];
        #借款再审笔数
        $inProgressArr  = [];
        #贷款信息
        $loanInfo       = self::feedbackFormat($approvedArr,$rejectedArr,$inProgressArr);

        #获取借款人姓名
        $loadUserInfo   = isset($approvedArr[0])?(array)$approvedArr[0]:[];

        $name           = self::getLoanCreditName($idNo,$loadUserInfo);

        #黑名单
        $blackListInfo  = [];

        $result = array(
            "status"    => "0",
            "message"   => "",
            "data"      => array(
                "memberNo"  => $targetno,   //反馈机构编号
                "idType"    => $idType,
                "idNo"      => $idNo,
                "name"      => $name,
                "loanInfo"  => $loanInfo,
                "blackListInfo" => $blackListInfo,

            ),

        );

        return $result;
    }

    /**
     * @desc   用户贷款信息-数据格式化
     *
     **/
    public static function feedbackFormat($approvedArr,$rejectedArr,$inProgressArr){
        $approved_0_6_Arr =  $approved_6_12_Arr = $approved_12_24_Arr = [];
        #当前时间戳
        $currentTime= time();
        #6个月前
        $preSixMonth= date("Y-m-d", ($currentTime - 3600*24*30*6));
        #12个月前
        $preOneYear = date("Y-m-d", ($currentTime - 3600*24*30*12));
        #24月前
        $preTwoYear = date("Y-m-d", ($currentTime - 3600*24*30*24));

        $lastDataInfo   = [];
        #收集债权信息-通过债权获取项目信息
        $creditInfoArr  = [];

        if($approvedArr){
            $lastDataInfo=$approvedArr[0];
            foreach($approvedArr as $key=>$val){
                $val = (array) $val;

                $credit_id  = $val["id"];
               // $typeInt    = (int) $val["type"];
               // $typeString = "\"".$typeInt."\"";

               // $creditInfoArr[]  =  "\"credit_id\":".$credit_id.",\"type\":".$typeInt;
               // $creditInfoArr[]  =  "\"credit_id\":".$credit_id.",\"type\":".$typeString;
                $creditInfoArr[] = $credit_id;

                if($val["created_at"] >= $preSixMonth){

                    $approved_0_6_Arr[] = $val;

                }elseif($val["created_at"] >= $preOneYear && $val["created_at"] < $preSixMonth){

                    $approved_6_12_Arr[] = $val;

                }elseif($val["created_at"] >= $preTwoYear && $val["created_at"] < $preOneYear){

                    $approved_12_24_Arr[]   = $val;

                }

             }
        }

        #贷款审核信息
        $loanApprovalInfo   = array(

            'approved_0_6'  => !empty($approved_0_6_Arr)?count($approved_0_6_Arr):0,     // 通过笔数0到6个月
            'approved_6_12' => !empty($approved_6_12_Arr)?count($approved_6_12_Arr):0,   // 通过笔数6到12个月
            'approved_12_24'=> !empty($approved_12_24_Arr)?count($approved_12_24_Arr):0, // 通过笔数12到24个月

            'rejected_0_6'  => 0,   // 拒绝笔数0到6个月
            'rejected_6_12' => 0,   // 拒绝笔数6到12个月
            'rejected_12_24'=> 0,   // 拒绝笔数12到24个月

            'inProgress_0_6'=> 0,   // 在审笔数0到6个月
            'inProgress_6_12'   =>0,// 在审笔数6到12个月
            'inProgress_12_24'  =>0,// 在审笔数12到24个月

            'lastRejectTime'=>'',   // 最近一次拒绝贷款申请时间

        );

        $uncleared = 0;
        $unclearedAmount    = 0;

        #var_dump($creditInfoArr);exit;
        #通过债权-获取项目信息
        if($creditInfoArr){

            $projectIdArr =  ProjectLinkCreditModel::getProjectByCredit($creditInfoArr);

            if($projectIdArr){

                $projectList    = ProjectModel::getCreditProjectById($projectIdArr);


                if($projectList){
                    $unclearedInfo  = self::projectListFormat($projectList);
                    $uncleared      = $unclearedInfo["uncleared"];
                    $unclearedAmount= $unclearedInfo["unclearedAmount"];
                }
            }
        }

        if(count($approvedArr) ==1 && $unclearedAmount > 0 ){
            $unclearedAmount = min($unclearedAmount,$lastDataInfo->loan_amounts);
        }
        #贷款基本信息
        $loanBasicInfo      = array(
            'loanTotal'         => !empty($approvedArr)?count($approvedArr):0,// 借款成功总笔数
            'uncleared'         => $uncleared,           // 未结清正常笔数
            'unclearedAmount'   => $unclearedAmount,     // 正常待还总额

            'overdueUncleared'      => 0,       // 未结清逾期笔数
            'overdueUnclearedAmount'=> 0,       // 未结清逾期总额
            'overdueUnclearedMaxTime'=>0,       // 未结清最长逾期时间

            'overdueCleared'        => 0,       // 已结清逾期笔数
            'overdueClearedAmount'  => 0,       // 逾期已还款总额
            'overdueMaxTime'        => 0,       // 历史最长逾期时间
        );


        #最近一笔贷款信息
        $recentLoanInfo     = array(
            'loanOriginationTime'   => isset($lastDataInfo->created_at)?date('Y-m-d',strtotime($lastDataInfo->created_at)):'',   // 最近一次贷款放款时间
            'loanOriginationAmount' => isset($lastDataInfo->loan_amounts)?$lastDataInfo->loan_amounts:0,// 最近一次贷款放款金额

            'overdueClearedAmount'  => 0,           // 最近一笔当前逾期金额
            'overdueAmount'         => 0,           // 最近一笔贷款逾期总额
            'overdueMaxTime'        => 0,           // 最近一笔贷款最长逾期时间

        );


        $loanInfo   = array(
            "loanApprovalInfo"  => $loanApprovalInfo,
            "loanBasicInfo"     => $loanBasicInfo,
            "recentLoanInfo"    => $recentLoanInfo,

        );

        return $loanInfo;

    }

    /**
     * @desc 处理债权对应项目
     **/
    public static function projectListFormat($projectList){
        $uncleared      = count($projectList);
        $unclearedAmount= 0;
        foreach ($projectList as $key=>$value){
            $unclearedAmount += $value["principal"];
        }

        $unclearedArr   =[
            "uncleared"     => $uncleared,
            "unclearedAmount"=>$unclearedAmount
        ];
        return $unclearedArr;
    }

    /**
     * @desc 获取借款人姓名
     *
     **/
    public static function getLoanCreditName($idNo,$loadUserInfo=[]){
        $name           = "";
        if($loadUserInfo){

            if(isset($loadUserInfo['loan_user_identity'])){
                $loanIdNoArr = json_decode($loadUserInfo['loan_user_identity'], true);

                if($loanIdNoArr){
                    foreach ($loanIdNoArr as $kk=>$vv){
                        if( $vv == $idNo){
                            if(isset($loadUserInfo['loan_username'])){
                                $loanArr = json_decode($loadUserInfo['loan_username'], true);
                                if($loanArr){
                                    $name = isset($loanArr[$kk])?$loanArr[$kk]:'';
                                }else{
                                    $name = $loadUserInfo['loan_username'];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $name;
    }

    /**
     * @return array
     * @desc 定义默认返回的数据
     */
    public static function setReturnResult($status="",$message = "",$data = [])
    {
        return  [
            "status"    =>   $status,
            "message"   =>   $message,
            "data"      =>   $data,
        ];
        exit();
    }

    /**
     * @desc    测试用的全数据
     * 主要测试使用
     **/
    public static function feedbackCompleteData($targetno,$idType,$idNo){

        $userIdNoArr = ['340621198911127336','372923199404063834','411528198712055403'];

        $userNameArr = [
            '340621198911127336' => '凌路',
            '372923199404063834' => '张强',
            '411528198712055403' => '罗莉',
        ];

        if(!in_array($idNo,$userIdNoArr)){

            return [];
        }

        #贷款审核信息
        $loanApprovalInfo   = array(

            'approved_0_6'  => "1",     // 通过笔数0到6个月
            'approved_6_12' => "2",     // 通过笔数6到12个月
            'approved_12_24'=> "2",     // 通过笔数12到24个月

            'rejected_0_6'  => "1",     // 拒绝笔数0到6个月
            'rejected_6_12' => "1",     // 拒绝笔数6到12个月
            'rejected_12_24'=> "1",     // 拒绝笔数12到24个月

            'inProgress_0_6'=> "1",     // 在审笔数0到6个月
            'inProgress_6_12'   =>0,    // 在审笔数6到12个月
            'inProgress_12_24'  =>0,    // 在审笔数12到24个月

            'lastRejectTime'    =>'2016-08-08', // 最近一次拒绝贷款申请时间

        );

        #贷款基本信息
        $loanBasicInfo      = array(
            'loanTotal'         => 5,           // 借款成功总笔数
            'uncleared'         => 2,           // 未结清正常笔数
            'unclearedAmount'   => 100000.00,   // 正常待还总额

            'overdueUncleared'      => 1,           // 未结清逾期笔数
            'overdueUnclearedAmount'=> 120000.00,   // 未结清逾期总额
            'overdueUnclearedMaxTime'=>30,          // 未结清最长逾期时间

            'overdueCleared'        => 1,           // 已结清逾期笔数
            'overdueClearedAmount'  => 80000.00,    // 逾期已还款总额
            'overdueMaxTime'        => 30,          // 历史最长逾期时间
        );


        #最近一笔贷款信息
        $recentLoanInfo     = array(
            'loanOriginationTime'   => '2016-08-18',    // 最近一次贷款放款时间
            'loanOriginationAmount' => 3000000.00,      // 最近一次贷款放款金额

            'overdueClearedAmount'  => 132783.00,       // 最近一笔当前逾期金额
            'overdueAmount'         => 213030.00,       // 最近一笔贷款逾期总额
            'overdueMaxTime'        => 30,              // 最近一笔贷款最长逾期时间

        );


        $loanInfo   = array(
            "loanApprovalInfo"  => $loanApprovalInfo,
            "loanBasicInfo"     => $loanBasicInfo,
            "recentLoanInfo"    => $recentLoanInfo,

        );

        #黑名单
        $blackListInfo  = [
            [
                "type"  => 1,
                "time"  => '2016-08-02',
                'amount'=> 1314000.00,
                'reason'=> '通过伪造身份证、银行卡、手机号等信息，骗取机构贷款',
            ],


        ];

        $result = array(
            "status"    => "0",
            "message"   => "",
            "data"      => array(
                "memberNo"  => $targetno,   //反馈机构编号
                "idType"    => $idType,
                "idNo"      => $idNo,
                "name"      => isset($userNameArr[$idNo])?$userNameArr[$idNo]:'',
                "loanInfo"  => $loanInfo,
                "blackListInfo" => $blackListInfo,

            ),

        );

        return $result;

    }


}
