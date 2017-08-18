<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/22
 * Time: 11:53
 */

namespace App\Http\Controllers\Admin\Recharge;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Order\WithdrawLogic;
use App\Http\Logics\Pay\RechargeLogic;
use App\Http\Logics\Recharge\CheckOrderRecordLogic;
use App\Http\Logics\Recharge\OrderLogic;
use App\Http\Logics\Recharge\PayLimitLogic;
use App\Tools\ToolTime;
use Illuminate\Http\Request;
use App\Tools\ToolPaginate;

class OrderController extends AdminController{


    /**
     * @param Request $request
     * @return mixed
     * 后台充值管理,订单列表
     */
    public function index(Request $request){
        /*

        $all = $request->all();
        $page = $request->get('page',1);
        $size = $request->get('size',20);

        $logic = new OrderLogic();

        $list = $logic->getAdminList($all);

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/recharge/lists');

        $paginate = $toolPaginate->getPaginate();

        $viewData = [
            'list'              => $list['data'],
            'total'             => $list['total'],
            'paginate'          => $paginate,
        ];

        */
        $data = $request->all();

        $page = $request->get('page',1);
        $size = $request->get('size',20);

        $export = $request->input('export');

        $withdrawLogic = new OrderLogic();

        $query = '';

        $checkResult    =   '';

        if( empty($data) ){

            $list = [
                'total' => 0,
                'data'  => [],
                'status_list' => [],
                'channel_list' => []
            ];

            $list['total'] = 0;

            $list['data'] = [];

        }else{
            
            if($export ){

                $data['size']   =   '1000000';   //定义查询10000条数据
                
                $withdrawLogic->doExport($data);
                
                die;
            }

            $list = $withdrawLogic->getAdminList($data);

            $orderIds       =   array_column($list['data'],"order_id");

            $checkLogic     =   new CheckOrderRecordLogic();

            $checkResult    =   $checkLogic->getCheckOrderRecordByParam(['order_id'=>$orderIds]);

            $data['page'] = '';

            $query = http_build_query($data);

        }

        $toolPaginate   = new ToolPaginate($list['total'], $page, $size, '/admin/recharge/lists',$query);

        $paginate       = $toolPaginate->getPaginate();

        $orderTypeList  = OrderLogic::setOrderTypeList();

        $checkTime      = ToolTime::getUnixTime(date("Y-m-d",strtotime("-1 day")),'end');
        
        $viewData = [
            'list'              => $list['data'],
            'total'             => $list['total'],
            'paginate'          => $paginate,
            'params'            => $data,
            'status_list'       => $orderTypeList['status_list'],
            'channel_list'      => $orderTypeList['channel_list'],
            'total_cash'        => isset($list['total_cash']) ? $list['total_cash'] : "0.00",
            'orderStatus'       => $checkResult,
            'checkTime'         => $checkTime,
        ];

        return view('admin.recharge.order.list', $viewData);
    }

    /**
     * @desc 充值订单掉单查询
     * @author lgh
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orderSearch(Request $request){

        $orderId = $request->input('order_id');

        $withdrawLogic = new WithdrawLogic();
        $payLimitLogic = new PayLimitLogic();
        if($orderId){
            $orderInfo = $withdrawLogic->getOrderInfo($orderId);
            if(!empty($orderInfo)) {
                $payTypeNameMerge = $payLimitLogic->getPayTypeName();
                if(in_array($orderInfo['pay_type'],  array_keys($payTypeNameMerge))){
                    $payTypeName      = $payTypeNameMerge[$orderInfo['pay_type']]['alias'];
                    $rechargeReturn = RechargeLogic::getPayInstance($payTypeName)->search($orderInfo['order_id']);

                    $assign['orderInfo'] = $orderInfo;

                    $assign['orderInfo']['status_note'] = $rechargeReturn['msg'];
                }else{
                    $assign['search_msg']      = "暂不支持该订单号查询";
                }
            }
        }
        $assign['order_id']      = $orderId;
        return view('admin.recharge.order.search', $assign);
    }


    /**
     * @param Request $request
     * 充值掉单处理
     */
    public function missOrderHandle(){

        return view('admin.recharge.order.miss', []);
    }
    
    
    public function missOrderSearch(Request $request){
        
        $orderId = $request->input('order_id','');

        $logicResult = RechargeLogic::missOrderSearch($orderId);

        if($logicResult['status']){
            
            return view('admin.recharge.order.missSearch', ['orderInfo' => $logicResult['data']]);
            
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', $logicResult['msg']);
        }

    }


    /**
     * @param Request $request
     * 充值掉单处理加币
     */
    public function doMissOrderHandle(Request $request){

        $orderId = $request->input('order_id');
        
        $logicResult = RechargeLogic::doMissOrderHandle($orderId);

        if($logicResult['status']){

            return redirect('/admin/recharge/missOrderHandle')->with('message', '掉单处理成功！');
        }else {

            return redirect('/admin/recharge/missOrderHandle')->with('message', $logicResult['msg']);

        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 导出订单
     */
    public function doExportRecharge(Request $request)
    {
        $startTime      =   $request->input('start_time');

        $endTime        =   $request->input('end_time');

        $status         =   $request->input('status');

        $channel        =   $request->input('channel');

        $startTime      =   date("Y-m-d H:i:s",ToolTime::getUnixTime($startTime));

        $endTime        =   date("Y-m-d H:i:s",ToolTime::getUnixTime($endTime,'end'));

        if( empty($startTime) || empty($endTime) ){

            return redirect()->back()->withInput($request->input())->with('message', '请选择查询时间!');
        }

        $params         =[
            'start_time'    =>  $startTime,
            'end_time'      =>  $endTime,
            'status'        =>  $status,
            'channel'       =>  $channel,
        ];

        WithdrawLogic::doExportRecharge($params);

        die;
    }
}