<?php
/**
 * create by PhpStorm
 * User: lgh
 * Date 16/08/24
 * Time 18:17
 * @desc 合伙人管理
 */

namespace App\Http\Controllers\Admin\Partner;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\Activity\ActivityFundHistoryDb;
use App\Http\Logics\Invite\InviteLogic;
use App\Http\Logics\Invite\InviteRatesLogic;
use App\Http\Logics\Partner\PartnerLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\AdminUser;
use App\Tools\ToolArray;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;
use Redirect;

class PartnerController extends AdminController{

    const PAGE_SIZE = 20;

    /**
     * @desc 合伙人管理
     * @author lgh
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $partnerLogic  = new PartnerLogic();
        $page = $request->input('page', 1);
        $param         = $request->all();
        $assign['search'] = $param;
        if(!empty($param['phone'])){
            $userInfo  = UserModel::getBaseUserInfo($param['phone']);

            if($userInfo){
                $userId = $userInfo['id'];
            }else{
                $userId = -1;
            }
            $param['userId'] = $userId;
        }
        $partnerInfo = $partnerLogic->getAdminPartnerInfo($param, $page, self::PAGE_SIZE);

        if($partnerInfo){
            $assign['partnerInfo'] = $partnerInfo['list'];
            $toolPaginate = new ToolPaginate($partnerInfo['total'], $page, self::PAGE_SIZE, '/admin/partner/index');

            $paginate = $toolPaginate->getPaginate();
            $assign['pageInfo'] = [
                "total"         => $paginate['total'],
                "last_page"     => $paginate['last_page'],
                "per_page"      => $paginate['per_page'],
                "current_page"  => $paginate['current_page'],
                "url"           => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"],
            ];
        }

        return view('admin.partner.index', $assign);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 合伙人详情
     * @author lqh
     */
    public function detail(Request $request){

        $userId = $request->input('userId', '');
        $interest = $request->input('interest',0);
        $page  = $request->input('page', 1);
        $p1     = $request->input('p1',1);
        $p2     = $request->input('p2',1);



        if(empty($userId)){

            return Redirect::to('/admin/partner/index');
        }
        $userInfo  = UserModel::getCoreApiUserInfo($userId);

        if(!empty($userInfo)) {
            $params['user_id'] = $userId;
        }else{
            return Redirect::to('/admin/partner/index');
        }

        $userInfo['interest'] = $interest;
        //合伙人详情
        $partnerLogic  = new PartnerLogic();
        //1.邀请合伙人详情（仅展示好友当前数据）

        $partner        = $partnerLogic->getPartnerInviteInfo($params, $page, self::PAGE_SIZE);

        $userInfo['invite_num'] = $partner['total'];
        $userInfo['principal'] = 0;

        $config = $partnerLogic->getInviteConfig();

        $userInfo['rate'] = $config['BASE_RATE'];

        $partnerInvite = $invPageInfo = [];

        //邀请人数大于0
        if($partner['total'] > 0){
            
            //$partnerInvite = $partner['list'];
            $toolPaginates = new ToolPaginate($partner['total'], $page, self::PAGE_SIZE, '/admin/partner/detail', 'userId='.$userId.'&interest='.$interest.'&page=');
            $invPageInfo = $toolPaginates->getPaginate();

            $userIds = array_keys($partner['list']);

            if($partner['total'] > self::PAGE_SIZE){
                
                $partnerUser    = $partnerLogic->getInviteByUserId($userId);
                $allUserIds        = ToolArray::arrayToIds($partnerUser,'user_id');

            }else{
                $allUserIds = $userIds;
            }
            //查询所有被邀请人的待收明细
            $data = $partnerLogic->getPartnerPrincipal($allUserIds,$partner['list'],$config);

            $partnerInvite = $data['list'];
            $userInfo['principal'] = $data['principal'];
            $userInfo['rate']   = $data['rate'];
        }
        

        //2.佣金收益记录

        $partnerIncList = $partnerLogic->getPartnerCashList($params, $p1, 30);

        //3.佣金转出记录

        $partnerOut = $partnerLogic->getPartnerTurnOutList($params, $p2, 30);
        if($partnerOut){
            $partnerOutList = $partnerOut['list'];
        }
        $viewData = [

            'partner'         => $userInfo,
            'partnerInvite'   => $partnerInvite,
            'invPageInfo'     => $invPageInfo,
            'partnerIncList'  => $partnerIncList,
            //'incPageInfo'     => $incPageInfo,
            'partnerOutList'  => $partnerOutList,
            //'outPageInfo'     => $outPageInfo,

        ];

        return view('admin.partner.detail', $viewData);

    }

    /**
     * @param Request $request
     * @desc 添加用户邀请关系
     */
    public function addInvite(Request $request){

        $invitePhone = $request->input('invite_phone', '');
        $userId      = $request->input('user_id', 0);
        $phone       = $request->input('phone', '');

        $logic = new InviteLogic();

        $result = $logic->doAddPartnerInvite($userId, $invitePhone);

        if($result['status']){
            return redirect("/admin/partner/detail?phone={$phone}")->with('message', "{$phone} 邀请 {$invitePhone} 的合伙人关系添加成功");
        }else {
            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }

    }

    /**
     * @param   Request $request
     * @return  mixed
     * @desc    活动奖励导出功能
     * @date    2016年11月22日
     * @author  @llper
     */

    public function activityRewardExport(Request $request){

        $startTime  = $request->input('start_time', date('Y-m-d', (time() - 3600*24*30) ));
        $endTime    = $request->input('end_time' ,  date("Y-m-d"));

        if($endTime < $startTime){
            return redirect()->back()->withInput($request->input())->with('message', '请输入正常时间区间!');
        }
        $partnerLogic  = new PartnerLogic();
        //活动奖励类型-转入
        $type   = ActivityFundHistoryDb::TYPE_IN;
        //活动奖励来源-合伙人
        $source = ActivityFundHistoryDb::SOURCE_PARTNER;
        $result = $partnerLogic->activityRewardExport($startTime, $endTime,$type,$source);
        if(!$result){
            return redirect()->back()->withInput($request->input())->with('message', '该时间区间没有数据~');
        }
    }
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 列表
     */
    public function inviteRates( Request $request ){

        $phone = $request->input('phone', '');

        $inviteRateLogic = new InviteRatesLogic();

        $data['list'] = $inviteRateLogic->getListByPhone($phone);

        return view('admin.partner.inviteRates', $data);

    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 执行添加
     */
    public function addInviteRates( Request $request ){

        $data = $request->all();

        $data['admin_id'] = AdminUser::getAdminUserId();

        $userInfo = UserModel::getBaseUserInfo($data['phone']);

        if( empty($userInfo) ){

            return redirect()->back()->withInput($request->input())->with('message', '手机号不存在');

        }

        $data['user_id'] = $userInfo['id'];

        $inviteRateLogic = new InviteRatesLogic();

        $result = $inviteRateLogic->doAdd($data);

        if( $result['status'] ){

            return redirect("/admin/inviteRates?phone={$data['phone']}")->with('message', "{$data['phone']}添加加息利率成功");

        }else{

            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);

        }

    }

    /**
     * @param Request $request
     * @return mixed
     * @desc 执行删除
     */
    public function delInviteRates( Request $request ){

        $data = $request->all();

        $inviteRateLogic = new InviteRatesLogic();

        $result = $inviteRateLogic->doDel($data);

        if( $result ){

            return redirect()->back()->withInput($request->input())->with('message', '删除成功,请刷新查看');

        }else{

            return redirect()->back()->withInput($request->input())->with('message', '删除失败,请重试');

        }

    }

    /**
     * @desc    解绑合伙人
     * @author  @linglu
     * @param   user_id
     * @param   other_user_id
     */
    public function unbindInvite( Request $request )
    {

        $userId = $request->input('user_id');
        $ouserId= $request->input('other_user_id');

        $logic  = new InviteLogic();
        $res    = $logic->unbindInvite($userId,$ouserId);

        if($res){
            return redirect()->back()->withInput($request->input())->with('message', '解绑成功！');
        }else{
            return redirect()->back()->withInput($request->input())->with('fail', '解绑失败请重试！');
        }


    }

}