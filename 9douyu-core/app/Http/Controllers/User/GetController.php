<?php
/**
 * 检测用户
 *
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 上午10:14
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Http\Logics\User\FundStatisticsLogic;
use App\Http\Logics\Fund\FundHistoryLogic;
use App\Http\Logics\Invest\InvestLogic;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Illuminate\Http\Request;

use App\Http\Logics\User\GetLogic;

/**
 * 检测用户接口 api ['phone','password','status']
 * Class GetController
 * @package App\Http\Controllers\User
 */
class GetController extends Controller
{
    /**
     * @SWG\Post(
     *   path="/user/get/baseUserInfo",
     *   tags={"User"},
     *   summary="根据手机号获取用户基本信息",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="超时订单处理成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="超时订单处理失败。",
     *   )
     * )
     */
    public function baseUserInfoByPhone(Request $request){
        $phone       = $request->input('phone');

        $GetLogic    = new GetLogic();
        $logicReturn = $GetLogic->getBaseUserInfo($phone);
        if($logicReturn['status']){
            $logicReturn['data']['balance'] = ToolMoney::formatDbCashDelete($logicReturn['data']['balance']);
        }
        self::returnJson($logicReturn);
    }

    /**
     * @SWG\Post(
     *   path="/user/get/userInfo",
     *   tags={"User"},
     *   summary="根据UID获取用户信息",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户信息成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户信息失败。",
     *   )
     * )
     */
    public function userInfoById(Request $request){
        $user_id       = $request->input('user_id');

        $GetLogic    = new GetLogic();
        $logicReturn = $GetLogic->getUserInfo($user_id);
        if($logicReturn['status']){
            $logicReturn['data']['balance'] = ToolMoney::formatDbCashDelete($logicReturn['data']['balance']);
        }
        self::returnJson($logicReturn);
    }

    /**
     * @SWG\Post(
     *   path="/user/get/userInterest",
     *   tags={"User"},
     *   summary="根据UID获取用户零钱计划定期收益",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户id",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户零钱计划定期收益成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户零钱计划定期收益失败。",
     *   )
     * )
     */
    public function userInterestById(Request $request){

        $user_id       = $request->input('user_id');

        $GetLogic    = new GetLogic();
        $logicReturn = $GetLogic->getUserInterest($user_id);

        $logicReturn['data'] = $this -> _filterData($logicReturn['data']);

        self::returnJson($logicReturn);

    }

    /**
     * @param $data
     * @return mixed
     * @desc 数据controller格式化
     */
    private function _filterData($data){

        if(!empty($data['current'])){
            $data['current']['cash']                = isset($data['current']['cash']) ? ToolMoney::formatDbCashDelete($data['current']['cash']) : 0;
            $data['current']['interest']            = isset($data['current']['interest']) ? ToolMoney::formatDbCashDelete($data['current']['interest']) : 0;
            $data['current']['yesterday_interest']  = isset($data['current']['yesterday_interest']) ? ToolMoney::formatDbCashDelete($data['current']['yesterday_interest']) : 0;
        }

        //零钱计划近七日计息
        if(!empty($data['current']) && is_array($data['current']['seven_interest']) && count($data['current']['seven_interest']) > 0){

            foreach($data['current']['seven_interest'] as $key => $item){

                $data['current']['seven_interest'][$key]['interest'] = ToolMoney::formatDbCashDelete($item['interest']);
                $data['current']['seven_interest'][$key]['principal'] = ToolMoney::formatDbCashDelete($item['principal']);

            }
        }

        if(!empty($data['project'])){
            $data['project']['refund_interest']             = isset($data['project']['refund_interest']) ? ToolMoney::formatDbCashDelete($data['project']['refund_interest']) : 0;
            $data['project']['refund_principal']            = isset($data['project']['refund_principal']) ? ToolMoney::formatDbCashDelete($data['project']['refund_principal']) : 0;
        }

        if(!empty($data['project']) && is_array($data['project']['product_line']) && count($data['project']['product_line']) > 0) {

            foreach($data['project']['product_line'] as $key => $item){

                unset($data['project']['product_line'][$key]);
                $data['project']['product_line'][$item['product_line']]['interest']  = ToolMoney::formatDbCashDelete($item['interest']);
                $data['project']['product_line'][$item['product_line']]['principal'] = ToolMoney::formatDbCashDelete($item['principal']);

            }

        }

        if(!empty($data['project']) && is_array($data['project']['no_full_at']) && count($data['project']['no_full_at']) > 0) {

            foreach($data['project']['no_full_at'] as $key => $item){

                unset($data['project']['no_full_at'][$key]);
                $data['project']['product_line'][$item['product_line']]['principal'] += ToolMoney::formatDbCashDelete($item['principal']);

            }

        }else{
            unset($data['project']['no_full_at']);
        }

        return $data;

    }

    /**
     * @SWG\Post(
     *   path="/user/get/userList",
     *   tags={"User"},
     *   summary="根据多个用户id获取用户信息",
     *     @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="user_ids",
     *      in="formData",
     *      description="用户id（以逗号分隔）",
     *      required=true,
     *      type="string",
     *      default="82692,82691",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getListByUserIds( Request $request )
    {

        $userIds = $request->input('user_ids');

        $logic = new GetLogic();

        $return = $logic->getListByUserIds($userIds);

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/user/get/getNoInvestUserList",
     *   tags={"User"},
     *   summary="获取长时间未投资的用户列表[管理后台]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="209c02k29",
     *   ),
     *   @SWG\Parameter(
     *      name="balance",
     *      in="formData",
     *      description="用户余额",
     *      required=true,
     *      type="integer",
     *      default="100",
     *   ),
     *   @SWG\Parameter(
     *      name="days",
     *      in="formData",
     *      description="未投资天数",
     *      required=true,
     *      type="integer",
     *      default="7",
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="integer",
     *      default="1"
     *   ),
     *  @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="分页limit",
     *      required=true,
     *      type="integer",
     *      default="2"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getNoInvestUserList( Request $request )
    {

        $data = $request->all();

        $fundHistory = new FundHistoryLogic();

        $result      = $fundHistory->getNoInvestUserId($data);

        $userIds     = '';

        foreach($result['data']['list'] as $key=>$value){

            $userIds .= $value['user_id'].',';

        }

        $userIds = rtrim($userIds,',');

        $logic   = new GetLogic();

        $return  = $logic->getListByUserIds($userIds);

        $list = $return['data'];

        $return['data'] = [];

        foreach($list as $k=>$v){

            $return['data']['data'][] = $list[$k];

            foreach($result['data']['list'] as $key=>$val){

                if($val['user_id'] == $v['id']){

                    $return['data']['data'][$k]['ctime'] = $val['ctime'];

                }

            }

        }

        $return['data']['total'] = $result['data']['total'];

        self::returnJson($return);

    }

    /**
     * @SWG\Post(
     *   path="/user/get/adminUserListAll",
     *   tags={"User"},
     *   summary="获取用户的列表信息[管理后台]",
     *     @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="phone",
     *      in="formData",
     *      description="手机号",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="real_name",
     *      in="formData",
     *      description="姓名",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="identity_card",
     *      in="formData",
     *      description="身份证",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="startTime",
     *      in="formData",
     *      description="注册开始时间",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="endTime",
     *      in="formData",
     *      description="注册结束时间",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页数",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *  @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="分页limit",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getAdminUserListAll( Request $request)
    {
        $param       = $request->all();

        $logic = new GetLogic();

        $return = $logic->getAdminUserListAll($param);

        self::returnJson($return);
    }

    /**
     * @SWG\Post(
     *   path="/user/get/userAmountDate",
     *   tags={"User"},
     *   summary="根据日期获取用户的注册数",
     *   @SWG\Parameter(
     *      name="start",
     *      in="formData",
     *      description="开始日期，如：Y-m-d",
     *      required=false,
     *      type="string",
     *   ),
     *  @SWG\Parameter(
     *      name="end",
     *      in="formData",
     *      description="结束日期，如：Y-m-d",
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取记录成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取记录失败。",
     *   )
     * )
     */
    public function getUserAmountByDate( Request $request){

        $start = $request->input('start');

        $end   = $request->input('end');

        $logic = new GetLogic();

        $return = $logic->getUserAmountByDate($start,$end);

        self::returnJson($return);
    }

    /**
     * @SWG\Post(
     *   path="/user/get/getUserTotal",
     *   tags={"User"},
     *   summary="获取总注册用户数",
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getUserTotal(){

        $logic = new GetLogic();

        $total = $logic->getUserTotal();

        self::returnJson(['total'=>$total]);
    }
    /**
     * @SWG\Post(
     *   path="/user/get/getUserStatistics",
     *   tags={"User"},
     *   summary="获取用户统计数据",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="start_time",
     *      in="formData",
     *      description="开始日期，如：Y-m-d",
     *      required=false,
     *      type="string",
     *   ),
     *  @SWG\Parameter(
     *      name="end_time",
     *      in="formData",
     *      description="结束日期，如：Y-m-d",
     *      required=false,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户数据失败。",
     *   )
     * )
     */
    public function getUserStatistics(Request $request){

        $all = $request->all();

        $logic = new GetLogic();

        $result = $logic->getUserStatistics($all);

        self::returnJson($result);
    }
    /**
     * @SWG\Post(
     *   path="/user/get/getBirthdayUser",
     *   tags={"User"},
     *   summary="获取当日生日的用户",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取当日生日用户数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取当日生日用户数据失败。",
     *   )
     * )
     */
    public function getBirthdayUser(){

        $logic = new GetLogic();

        $result = $logic->getBirthdayUser();

        self::returnJson($result);
    }
    /**
     * @SWG\Post(
     *   path="/user/get/getUserByIdCards",
     *   tags={"User"},
     *   summary="通过多个身份证号获取用户数据",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *     @SWG\Parameter(
     *      name="identity_cards",
     *      in="formData",
     *      description="身份证（以逗号分隔）",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="通过多个身份证号获取用户数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="通过多个身份证号获取用户数据失败。",
     *   )
     * )
     */
    public function getUserByIdCards( Request $request){

        $idCards = $request->input('identity_cards');

        $logic = new GetLogic();

        $result =$logic->getUserByIdCards($idCards);

        self::returnJson($result);
    }
    /**
     * @SWG\Post(
     *   path="/user/get/getUserByPhones",
     *   tags={"User"},
     *   summary="通过多个手机号获取用户数据",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *     @SWG\Parameter(
     *      name="phones",
     *      in="formData",
     *      description="手机号（以逗号分隔）",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="通过多个手机号获取用户数据成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="通过多个手机号获取用户数据失败。",
     *   )
     * )
     */
    public function getUserByPhones(Request $request){
        $phones = $request->input('phones');

        $logic = new GetLogic();

        $result = $logic->getUserByPhones($phones);

        self::returnJson($result);
    }

    /***
     * @desc    账户资金统计
     * @date    2016年11月24日
     * @author  @llper
     */
    /**
     * @SWG\Post(
     *   path="/user/get/getFundStatisticsWithDay",
     *   tags={"User"},
     *   summary="获取账户资金统计",
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getFundStatisticsWithDay(){

        $logic    = new FundStatisticsLogic();

        $logicReturn = $logic->getFundStatisticsWithDay();

        self::returnJson($logicReturn);
    }
    /**
     * @SWG\Post(
     *   path="/user/getPartnerPrincipal",
     *   tags={"User"},
     *   summary="获取被邀请人待收明细",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="cash",
     *      in="formData",
     *      description="待收大于些金额才算有效合伙人",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="all_user_ids",
     *      in="formData",
     *      description="某个合伙人的所有邀请人用户ID（以逗号分隔）",
     *      required=false,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取被邀请人待收明细成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取被邀请人待收明细失败。",
     *   )
     * )
     */
    public function getPartnerPrincipal(Request $request){
        
        $cash       = $request->input('cash',0);
        $allUserIds = $request->input('all_user_ids','');
        
        $logic = new InvestLogic();
        $result = $logic->getPartnerPrincipal($cash,$allUserIds);

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/getInvestListByUserId",
     *   tags={"User"},
     *   summary="获取用户的投资记录",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="3ba7919294c977fea3fb3be18c01eac8"
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户Id",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="page",
     *      in="formData",
     *      description="页码",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="条数",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户的投资记录成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户的投资记录失败。",
     *   )
     * )
     */
    public function getInvestListByUserId(Request $request)
    {

        $userId = $request->input('user_id', 0);

        $page   = $request->input('page', 1);

        $size   = $request->input('size', 10);

        $logic = new InvestLogic();

        $result = $logic->getInvestListByUserId( $userId, $page, $size );

        self::returnJson($result);

    }

    /**
     * @SWG\Post(
     *   path="/user/getUserInvestDataByUserId",
     *   tags={"User"},
     *   summary="获取用户的投资记录(包括普付宝项目)",
     *   @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *      default="3ba7919294c977fea3fb3be18c01eac8"
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户Id",
     *      required=false,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户的投资记录成功",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户的投资记录失败。",
     *   )
     * )
     */
    public function getUserInvestDataByUserId(Request $request){

        $userId = $request->input('user_id', 0);

        $logic = new InvestLogic();

        $result = $logic->getUserInvestDataByUserId($userId);

        self::returnJson($result);
    }

    /***
     * @desc    获取借款投资相关统计
     * @date    2017年06月14日
     * @author  @linglu
     */
    /**
     * @SWG\Post(
     *   path="/user/getCoreApiInvestStat",
     *   tags={"User"},
     *   summary="获取借款投资相关统计",
     *   @SWG\Response(
     *     response=200,
     *     description="获取成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取失败。",
     *   )
     * )
     */
    public function getCoreApiInvestStat(){

        $logic    = new FundStatisticsLogic();

        $logicReturn = $logic->getCoreApiInvestStat();

        self::returnJson($logicReturn);
    }

}