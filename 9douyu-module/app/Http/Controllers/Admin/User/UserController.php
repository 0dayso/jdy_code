<?php
/**
 * create by phpstorm
 * User: lgh
 * Date 16/08/19
 * Time 14:01 PM
 * @desc 用户管理控制器面板
 */
namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\OrderDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Family\FamilyLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Order\WithdrawLogic;
use App\Http\Logics\Project\RefundRecordLogic;
use App\Http\Logics\Recharge\OrderLogic;
use App\Http\Logics\Recharge\RechargeLogic;
use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\User\ChangePhoneLogLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\CoreApi\RefundModel;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\HttpQuery;
use App\Tools\ToolArray;
use App\Tools\ToolPaginate;
use App\Tools\ToolTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Logics\Logic;
use Redirect;

/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends AdminController{

    const PAGE_SIZE = 20; //设置列表每页条数

    /**
     * @desc 用户列表页面
     * @author lgh
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $page       = $request->input('page',1);
        $phone      = $request->input('phone');
        $realName   = $request->input('real_name');
        $idCard     = $request->input('identity_card');
        $startTime  = $request->input('startTime');
        $endTime    = $request->input('endTime');
        $sourceCode = $request->input('source_code');

        $userLogic  =  new UserLogic();
        $param = [
            'phone'     =>  $phone,
            'startTime' =>  $startTime,
            'endTime'   =>  $endTime,
            'real_name' =>  $realName,
            'identity_card' =>  $idCard,
        ];
        $userList   =   [];

        if( empty($request->all()) ){

            $userList=[
                'data'      => [],
                'total'     =>  0,
                'last_page' =>  "",
                'per_page'  =>  '',
                'current_page'=> '',

            ];

        }else{

            $userList = $userLogic->getUserListAll($page, self::PAGE_SIZE, $param);

        }


        $assign['user_list'] = $userList['data'];
        $assign['pageInfo'] = [
            "total"         => $userList['total'],
            "last_page"     => $userList['last_page'],
            "per_page"      => $userList['per_page'],
            "current_page"  => $userList['current_page'],
            "url"           => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"],
        ];
        $assign['search_form'] = $param;
        return view('admin.user.index', $assign);
    }

    /**
     * @desc 有余额但一定时间内未进行投资的用户列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function remindInvest(Request $request){

        $balance = $request->input('balance',100);
        $days    = $request->input('days',7);
        $page    = $request->input('page',1);
        $size    = self::PAGE_SIZE;

        $userLogic  =  new UserLogic();

        $param = [
            'balance' =>  $balance,
            'days'    =>  $days,
            'page'    =>  $page,
            'size'    =>  $size,
        ];

        $userList = [];

        if( empty($request->all()) ){

            $userList['data']  = [];
            $userList['total'] = 0;

        }else{

            $userList = $userLogic->getNoInvestUser($param);

        }
        $pageParam  = '?balance='.$balance.'&days='.$days;

        $toolPaginate = new ToolPaginate($userList['total'], $page, $size, '/admin/user/remind'.$pageParam);

        $paginate   = $toolPaginate->getPaginate();

        $assign['user_list'] = $userList['data'];

        $assign['search_form'] = $param;

        $assign['paginate'] =   $paginate;

        return view('admin.user.remind', $assign);
    }

    /**
     * @desc 变更用户的手机号
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function changePhone(Request $request){

        $phone = $request->input('phone');
        $newPhone = $request->input('new_phone');
        $assign['phone']      = $phone;
        $assign['new_phone']  = $newPhone;

        //提交查询按钮
        if($phone && $newPhone){

            $assign['userInfo']  = UserModel::getBaseUserInfo($phone);

            $assign['userNewInfo']=UserModel::getBaseUserInfo($newPhone);
        }
        
        return view('admin.user.change', $assign);
    }

    /**
     * @desc 更改手机号ajax
     * @param Request $request
     * @return bool
     */
    public function doChangePhone(Request $request){
        $phone  = $request->input('phone');
        $newPhone = $request->input('new_phone');
        if(empty($phone) || empty($newPhone)){
            return false;
        }

        //$result = UserModel::doModifyPhone($phone, $newPhone);
        $logic  =   new ChangePhoneLogLogic();
        
        $result = $logic->doChangePhone($phone, $newPhone);
        
        return self::ajaxJson($result);
    }

    /**
     * @desc 家庭账户解绑
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unbindFamily(Request $request){

        $familyLogic  =  new FamilyLogic();
        $phone = $request->input('phone');
        //通过手机号获取账户信息
        if($phone){
            //手机号搜索主账户信息
            $userInfo  = UserModel::getBaseUserInfo($phone);
            //获取家庭账户的列表信息
            if($userInfo){
                $assign['familyList'] = $familyLogic->getByMyUid($userInfo['id']);

                //获取子账户id列表
                $familyIds = implode(',',ToolArray::arrayToIds($assign['familyList'], 'family_id'));
                //获取子账户信息
                $familyUserInfo = UserModel::getUserListByIds($familyIds);
                //格式化子账户的信息集合
                $familyUserInfo = ToolArray::arrayToKey($familyUserInfo, 'id');
                foreach($assign['familyList'] as $key=>$val){
                    /*$familyUserInfo = UserModel::getCoreApiUserInfo($val['family_id']);//获取子账户的信息*/

                    if(isset($familyUserInfo[$val['family_id']])){
                        $assign['familyList'][$key]['family_name'] = $familyUserInfo[$val['family_id']]['real_name'];
                        $assign['familyList'][$key]['phone'] = $familyUserInfo[$val['family_id']]['phone'];
                    }
                }
            }
        }
        $assign['phone']      = $phone;
        return view('admin.user.family', $assign);
    }

    /**
     * @desc 解绑家庭账户操作
     * @author lgh
     * @param Request $request
     * @return string
     */
    public function doUnbindFamily(Request $request){

        $id = $request->input('id');

        $familyLogic = new FamilyLogic();

        $return  = $familyLogic->unbindFamily($id);

        return self::ajaxJson($return);

    }

    /**
     * @desc [管理后台]锁定用户帐号
     * @author lgh
     * @param Request $request
     * @return bool|string
     */
    public function doUserStatusBlock(Request $request){

        $userId = $request->input('user_id');
        $status = $request->input('status');

        $userLogic = new UserLogic();

        if(empty($userId)){

            return false;
        }

        $result = $userLogic->doUserStatusBlock($userId, $status);

        return self::ajaxJson($result);
    }

    /**
     * @desc 用户详情
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userInfo(Request $request, $id){

        $userLogic      = new UserLogic();
        $userBonusLogic = new UserBonusLogic();
        $withdrawLogic  = new WithdrawLogic();
        $orderLogic     = new OrderLogic();
        $refundLogic    = new RefundRecordLogic();
        $investLogic    = new TermLogic();

        $userInfo = $userLogic->getUserInfoById($id);

        //用户资产
        $userAccount = $userLogic -> getUserInfoAccount($id);
        $productLineArr = empty($userAccount['project']['product_line'])?'':$userAccount['project']['product_line'];
        $projectJsx     = empty($productLineArr[ProjectDb::PROJECT_PRODUCT_LINE_JSX])?['interest'=>0,'principal'=>0]:$productLineArr[ProjectDb::PROJECT_PRODUCT_LINE_JSX];
        $projectJax     = empty($productLineArr[ProjectDb::PROJECT_PRODUCT_LINE_JAX])?['interest'=>0,'principal'=>0]:$productLineArr[ProjectDb::PROJECT_PRODUCT_LINE_JAX];
        $projectSdf     = empty($productLineArr[ProjectDb::PROJECT_PRODUCT_LINE_SDF])?['interest'=>0,'principal'=>0]:$productLineArr[ProjectDb::PROJECT_PRODUCT_LINE_SDF];
        //用户总资产
        $userAccount['project']['total_amount'] = $projectJsx['interest'] + $projectJsx['principal'] + $projectJax['interest'] + $projectJax['principal'] + $projectSdf['principal'];
        $userInfo['total_amount'] = $userAccount['current']['cash'] + $userInfo['balance'] + $userAccount['project']['total_amount'];//用户总资产
        $userInfo['total_amount_interest']  = $projectJsx['interest'] + $projectJax['interest'];//待收收益
        $userInfo['total_amount_principal'] = $projectJsx['principal'] + $projectJax['principal'] + $projectSdf['principal'];//待收本金
        $userInfo['refundTotal'] = $userInfo['total_amount_interest']+$userInfo['total_amount_principal']; //待收本息
        $userInfo['total_interest']  = $userAccount['project']['refund_interest'] + $userAccount['current']['interest']; //累计收益
        $userInfo['current']['total_amount'] = $userAccount['current']['cash'];//零钱计划
        //累计投资总额
        $userInfo['invest_total'] = $userAccount['project']['refund_principal'] + $userInfo['current']['total_amount'] + $userInfo['total_amount_principal'];
        //提现总额
        $userInfo['withdrawAmount'] = $withdrawLogic->getWithdrawStatistics(['userId' => $id, 'status'=> OrderDb::STATUS_SUCCESS])['cash'];//累计成功提现总额
        $userInfo['rechargeAmount'] = $orderLogic->getRechargeStatistics(['userId' => $id])['cash'];//累计充值总额

        //还款中的项目
        $sDate = ToolTime::getYearFirstDay();
        $date  = ToolTime::getAfterYearDay();
        $eDate = ToolTime::getYearLastDay($date);

        $refund = $refundLogic->getRefundingListByDate($id, $sDate, $eDate)['data'];


        //用户红包
        $bonusInfo = $userBonusLogic->getAllUserBonus($id);
        $assign = [
            'userInfo'   => $userInfo,
            'bonusInfo'  => $userBonusLogic->getFormatUserBonusStatus($bonusInfo),
            'userAccount'=> $userAccount,
            'refund'     => $refund,
        ];
        return view('admin.user.info', $assign);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 奖励加币功能
     */
    public function changeBalance(Request $request){

        $phone      = $request->input('phone', '');
        $userInfo   = '';

        if(!empty($phone)){
            $userInfo  = UserModel::getBaseUserInfo($phone);
        }

        $viewData = [
            'phone'     => $phone,
            'userInfo'  => $userInfo,
            'code'      => empty($phone)?'': md5($phone.ToolTime::dbNow()),
        ];

        return view('admin.user.changeBalance', $viewData);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 执行奖励加币功能
     */
    public function doChangeBalance(Request $request){

        $phone          = $request->input('phone', '');
        $cash           = $request->input('cash', 0);
        $note           = $request->input('note', '');
        $code           = $request->input('code', '');
        $confirmCode    = $request->input('confirmCode', '');
        $type           = $request->input('type', 0);

        $logic = new UserLogic();

        $result = $logic->doChangeBalance($phone, abs($cash), $note, $code, $confirmCode, $type);
        $message = [ 1=>'加款', 2=> '扣款'];

        if($result['status']){
            return redirect('/admin/user/changeBalance?phone='.$phone)->with('message', '给用户'.$message[$type].'成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }

    }

    /**
     * @param Request $request
     * @return string
     * @desc 冻结
     */
    public function doUserStatusFrozen(Request $request){

        $userId = $request->input('user_id',0);

        $logic = new UserLogic();

        $result = $logic->doUserFrozen($userId);

        return self::ajaxJson($result);

    }

    /**
     * @param Request $request
     * @return string
     * @desc 解冻
     */
    public function doUserStatusUnFrozen(Request $request){

        $userId = $request->input('user_id',0);

        $logic = new UserLogic();

        $result = $logic->doUserUnFrozen($userId);

        return self::ajaxJson($result);

    }

    public function checkUserLoginInfo(Request $request)
    {
        $userId     =   $request->input('user_id',0);

        $returnUrl  =   '';

        if(!empty($userId) && (int)$userId != 0){
            $userLogic  =   new UserLogic();

            $loginList  =   $userLogic->getUserLoginInfo($userId, self::PAGE_SIZE);

            $returnUrl  =   'http://'.$_SERVER['SERVER_NAME'].'/admin/user/loginInfo?user_id='.$userId;
        }

        $view =[
                'loginList' =>  isset($loginList['data']) ? $loginList['data'] : [],
                'source'    =>RequestSourceLogic::$clientSource,
            ];

        $view['pageInfo'] = [
            "total"         => isset($loginList['total']) ? $loginList['total'] :0,
            "last_page"     => isset($loginList['last_page']) ? $loginList['last_page']: "",
            "per_page"      => isset($loginList['per_page']) ? $loginList['per_page'] :'',
            "current_page"  => isset($loginList['current_page']) ?$loginList['current_page'] : '' ,
            "url"           => $returnUrl,
        ];
        //dd($view);
        return view('admin.user.loginInfo', $view);
    }
}