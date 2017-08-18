<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/28
 * Time: 下午7:24
 */

namespace App\Http\Controllers\Pc\TimeCash;

use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\TimeCash\TimeCashLogic;
use Illuminate\Http\Request;

class TimeCashController extends UserController
{

    public function __construct()
    {
        parent::__construct();
        $this->checkIdentity();

    }

    public function index()
    {

        $userInfo       = $this->getUser();
        #借款额度
        $loanAmountArr  = TimeCashLogic::getLoanAmount();
        #借款期限
        $loanTimeArr    = TimeCashLogic::getLoanTime();
        #还款方式
        $refundTypeArr  = TimeCashLogic::getRefundType();

        $viewData       =   [
            'loanAmountArr' => $loanAmountArr,
            'loanTimeArr'   => $loanTimeArr,
            'refundTypeArr' => $refundTypeArr,
            'name'          => $userInfo['real_name'],
            'phone'         => $userInfo['phone'],
        ];

        return view("pc.timecash.timecashLoan",$viewData);
    }


    /**
     * @param   Request $request
     * @return  array
     * @desc    执行借款
     */
    public function doLoan( Request $request )
    {
        $userInfo       = $this->getUser();
        #$name          =   $request->input("name", '');
        #$phone         =   $request->input("phone",'');
        $name   = $userInfo['real_name'];
        $phone  = $userInfo['phone'];
        $loan_amount=   $request->input("loan_amount",  '');
        $loan_time  =   $request->input("loan_time",    '');
        $refund_type=   $request->input("refund_type",  '');

        $params =   [
            'name'          => $name,
            'phone'         => $phone,
            'loan_amount'   => $loan_amount,
            'loan_time'     => $loan_time,
            'refund_type'   => $refund_type,
        ];

        $result = TimeCashLogic::doAddLoan($params);

        return $result;

    }


}