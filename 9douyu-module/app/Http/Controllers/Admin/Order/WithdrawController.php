<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/6
 * Time: 上午10:35
 * Desc: 提现管理控制器
 */

namespace App\Http\Controllers\Admin\Order;

use App\Http\Logics\Order\WithdrawLogic;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Redirect;
use App\Http\Logics\Recharge\OrderLogic;
use App\Tools\ToolPaginate;

/**
 * Class WithdrawController
 * @package App\Http\Controllers\Admin\Withdraw
 */
class WithdrawController extends AdminController{

    CONST SIZE = 10;

    /**
     * 提现列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        $data = $request->all();


//        $page = $request->get('page',1);
//        $size = $request->get('size',20);
//
//        $withdrawLogic = new WithdrawLogic();
//
//        $list = $withdrawLogic->getAdminList($data);
//
//        $data['page'] = '';
//        $query = http_build_query($data);
//
//        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/withdraw',$query);
//
//        $paginate = $toolPaginate->getPaginate();
//
//        $viewData = [
//            'list'              => $list['data'],
//            'total'             => $list['total'],
//            'paginate'          => $paginate,
//            'params'            => $data,
//            'status_list'       => $list['status_list']
//        ];

        $page   = $request->get('page',1);

        $size   = $request->get('size',20);

        $export = $request->input('export');

        $query  = '';

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

            $withdrawLogic  = new WithdrawLogic();

            if( $export ){

                $data['size']   =   '100000';   //清空查询范围

                $withdrawLogic->doExport($data);

                die;
            }

            $list           = $withdrawLogic->getAdminList($data);

            $query          = http_build_query($data);
        }

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/withdraw',$query);

        $paginate = $toolPaginate->getPaginate();

        $viewData = [
            'list'              => $list['data'],
            'total'             => $list['total'],
            'total_cash'        => isset($list['total_cash']) ? $list['total_cash'] : "0.00",
            'fee_total'         => isset($list['handling_fee_total']) ? $list['handling_fee_total'] : "0.00",
            'paginate'          => $paginate,
            'params'            => $data,
            'status_list'       => WithdrawLogic::setOrderStatus(),
        ];

        return view('admin.order.withdrawList',$viewData);
    }


    /**
     * @param Request $request
     * T+0提现处理列表
     */
    public function withdrawRecord(Request $request){

        $page   = $request->get('page',1);

        $size = 10;

        $logic  = new WithdrawLogic();

        $list = $logic->getWithdrawRecord($page,10);

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/withdrawRecord');

        $paginate = $toolPaginate->getPaginate();

        $viewData = [
            'list'              => $list['list'],
            'total'             => $list['total'],
            'paginate'          => $paginate,
        ];

        return view('admin.order.withdrawRecord',$viewData);
    }

    
    /**
     * @param Request $request
     * 发送指定时间段的提现邮件
     */
    public function sendEmail(Request $request){

        $logic  = new WithdrawLogic();

        $email  = $request->input('email','');
        $id     = $request->input('id','');


        $return = $logic->sendWithdrawEmail($id,$email);

        return $this->ajaxJson($return);
    }

    /**
     * @desc    提现-发邮件 发送指定时间段的提现邮件
     * @param   Request $request
     * @date    2017-03-24
     * @author  @linglu
     *
     */
    public function sendEmailWithdraw(Request $request){

        $logic  = new WithdrawLogic();

        $email  = $request->input('email','');
        $id     = $request->input('id','');
        $type   = $request->input('type','');


        $return = $logic->sendWithdrawEmailNew($id,$email,$type);

        return $this->ajaxJson($return);
    }


    /**
     * 提现查看
     * $param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($id){
        $withdrawLogic = new WithdrawLogic();
        //TODO view info
        return view('admin.order.withdrawView');
    }

    /**
     * 发送处理消息
     */
    public function sendDoneMsg(Request $request){
        $id = $request->input('id');
        $withdrawLogic = new WithdrawLogic();
        $res = $withdrawLogic->sendDoneMsg($id);
        if($res['status']){
            return Redirect::to('admin/withdraw')->with('success',$res['msg']);
        }
        return Redirect::back()->with('errors',$res['msg']);
    }


    /**
     * @return string
     * 批量发送处理短信
     */
    public function sendBatchMsg($id){

        $logic = new WithdrawLogic();

        $result = $logic->sendBatchMsg($id);

        if($result['status']){
            return Redirect::to('admin/withdrawRecord')->with('message','请求发送成功');
        }
        return Redirect::back()->with('message',$result['msg']);
    }


    /**
     * @param $orderId
     * @return mixed
     * 查看订单明细
     */
    public function info($orderId){

        $logic          = new WithdrawLogic();
        $order       = $logic->getOrderInfo($orderId);

        $order['isShow'] = $logic->showEditButton($order);

        return view('admin.order.info',$order);
    }


    /**
     * @param Request $request
     * @return mixed
     * 后台编辑提现订单状态
     */
    public function doEdit(Request $request){
        
        $status     = $request->input('status',0);
        $orderId    = $request->input('order_id','');

        if(!$status) {

            return Redirect::back()->with('errors','缺少状态参数');
        }

        $logic  = new WithdrawLogic();

        $res    = $logic->doEdit($request->all());
        
        if($res['status']){
            return Redirect::to('admin/withdraw')->with('success',$res['msg']);
        }

        return Redirect::back()->with('errors',$res['msg']);


    }
}