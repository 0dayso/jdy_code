<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/2
 * Time: 14:22
 * Desc: 提现订单操作model
 */
namespace App\Http\Models\Order;
use App\Http\Dbs\OrderExtendDb;
use App\Http\Dbs\UserDb;
use App\Http\Dbs\WithdrawOrderDb;
use App\Http\Dbs\WithdrawRecordDb;
use App\Http\Logics\Warning\OrderLogic;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Http\Dbs\BankDb;
use App\Http\Models\Common\EmailModel;
use App\Http\Models\Common\ExceptionCodeModel;


class OperateModel extends Model{


    public static $codeArr = [
        'sendWithdrawEmailFailed'                     => 1,
        'sendWithdrawEmailHaveNoUndealData'           => 2,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_WITH_DRAW_OPERATE;


    /**
     * 分页获取未处理的提现列表
     * @param date $startDate
     * @param date $endDate
     * @return array
     */
    function getUnDealListByPage($startDate, $endDate,$page,$size=500){

        //获取未处理的订单列表
        $orderList = $this->getUnDealOrderList($startDate,$endDate,$page,$size);

        //提现列表
        $withdrawList = [];
        //提现总金额
        $amount       = 0;

        //用户信息列表
        $userList       = $this->getUserListByOrderList($orderList);

        //订单扩展信息列表
        $extendList     = $this->getOrderExtendByOrderList($orderList);

        foreach ($orderList as $key => $val){

            $cash       = ToolMoney::formatDbCashDelete($val['cash']);//提现金额处理成元

            $orderId    = $val['order_id'];//订单号
            $userId     = $val['user_id'];       //用户ID
            $withdrawList[$key] = [
                'order_id'      => $orderId,
                'card_number'   => $extendList[$orderId]['card_number'],
                'real_name'     => $userList[$userId]['real_name'],
                'cash'          => $cash,
                'bank_id'       => $extendList[$orderId]['bank_id']
            ];

            $amount += $cash;

        }

        $return = [
            'list' => $withdrawList,
            'amount'  => $amount
        ];

        return $return;

    }
    
    /**
     * 分页获取处理中的提现列表
     * @param date $startDate
     * @param date $endDate
     * @return array
     * getDealingListByPage
     */
    function getDealingListByPage($startDate, $endDate,$page,$size=500){

        //获取处理中的订单列表
        $orderList = $this->getDealingOrderList($startDate,$endDate,$page,$size);

        //提现列表
        $withdrawList = [];
        //提现总金额
        $amount       = 0;

        //用户信息列表
        $userList       = $this->getUserListByOrderList($orderList);

        //订单扩展信息列表
        $extendList     = $this->getOrderExtendByOrderList($orderList);

        foreach ($orderList as $key => $val){

            $cash       = ToolMoney::formatDbCashDelete($val['cash']);//提现金额处理成元

            $orderId    = $val['order_id'];//订单号
            $userId     = $val['user_id'];       //用户ID
            $withdrawList[$key] = [
                'order_id'      => $orderId,
                'card_number'   => $extendList[$orderId]['card_number'],
                'real_name'     => $userList[$userId]['real_name'],
                'cash'          => $cash,
                'bank_id'       => $extendList[$orderId]['bank_id']
            ];

            $amount += $cash;

        }

        $return = [
            'list' => $withdrawList,
            'amount'  => $amount
        ];

        return $return;

    }


    /**
     * @param $orderList
     * @return array
     * 根据订单列表获取相应的订单扩展信息
     */
    private function getOrderExtendByOrderList($orderList){

        //获取所有订单编号
        $orderIds  = ToolArray::arrayToIds($orderList,'order_id');

        //获取多个订单号的扩展信息
        $extendDb = new OrderExtendDb();
        $extendList = $extendDb->getOrderListByOrderIds($orderIds);
        $extendList = ToolArray::arrayToKey($extendList,'order_id');

        return $extendList;

    }

    /**
     * @param $orderList
     * @return array
     * 根据订单列表获取相应的用户信息
     */
    public function getUserListByOrderList($orderList){

        //获取所有用户ID
        $userIds   = ToolArray::arrayToIds($orderList,'user_id');
        //获取多个用户的信息
        $userDb = new UserDb();
        $userList = $userDb->getUserListByUserIds($userIds);
        $userList = ToolArray::arrayToKey($userList,'id');

        return $userList;
    }


    /**
     * @param $startDate
     * @param $endDate
     * @param $page
     * @param $size
     * @return mixed
     * 分页获取指定时间内未处理的提现订单
     */
    public function getUnDealOrderList($startDate,$endDate,$page,$size){

        $db = new WithdrawOrderDb();
        //分页获取指定日期内的订单信息
        $orderList = $db->getUnDealListByPage($startDate,$endDate,$page,$size);
        return $orderList;

    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $page
     * @param $size
     * @return mixed
     * 分页获取指定时间内处理中的提现订单
     */
    public function getDealingOrderList($startDate,$endDate,$page,$size){

        $db = new WithdrawOrderDb();
        //分页获取指定日期内的订单信息
        $orderList = $db->getDealingListByPage($startDate,$endDate,$page,$size);
        return $orderList;

    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $page
     * @param $size
     * 获取指定日期内的订单列表
     */
    public function getListByPage($startDate,$endDate,$size){

        $db = new WithdrawOrderDb();
        //分页获取指定日期内的订单信息
        $orderList = $db->getListByPage($startDate,$endDate,$size);
        return $orderList;

    }

    

    /**
     * @param $withDraws
     * @param $startDate
     * @param $page
     * @param $bankList
     * @return string
     * 分页存储提现数据
     */

    public function saveWithDrawFile($withDraws,$startDate,$endData,$page,$bankList){

        $total = 0;
        $totalCash = 0;

        $result = "单笔序号,收款方银行账号,银行类型,真实姓名,付款金额(元),账户属性,账户类型,开户地区,开户城市,支行名称,联行号,付款说明,收款人手机号,所属机构\r\n";
        //单笔提现最大限额,超出将拆分成多个订单
        $maxCash = WithdrawOrderDb::WITHDRAW_SPLIT_LIMIT;

        foreach ($withDraws as $w){

            $id         = $w['order_id'];       //订单号
            $realName   = $w["real_name"];      //姓名
            $cardNumber = $w["card_number"];    //银行卡号
            $bankName   = $bankList[$w['bank_id']]['name']; //银行名称

            $bankName   = str_replace(array("浦发银行","平安银行"),array("上海浦东发展银行","平安银行（深发展）"),$bankName);
            $bankBranch = "";   //支行名称
            $cash       = $w["cash"]; //金额转化成分为单位

            $n          = floor($cash/$maxCash);
            $totalCash += $cash;

            if($n > 0) {
                for($i=0; $i<$n; $i++) {
                    $result .= $id.','.$cardNumber.','.$bankName.','.$realName.','.round($maxCash,2).',对私,借记卡,'.$bankBranch.',,,,'.$id.",,\r\n";
                    $total++;
                }

            }
            $newCash = $cash - $n*$maxCash;
            if(!empty($newCash)) {
                $result .= $id.','.$cardNumber.','.$bankName.','.$realName.','.round($newCash,2).',对私,借记卡,'.$bankBranch.',,,,'.$id.",,\r\n";
                $total++;
            }
        }

        if($result && $total > 0){

            $title = "提现统计数据";

            //$fileName   = date("Y-m-d",strtotime($startDate))."_{$page}.txt";
            $fileName   = date("YmdH",strtotime($startDate)).'-'.date("YmdH",strtotime($endData))."_{$page}.txt";

            $dirName    = base_path() . "/storage/withdraw/";
            if(!is_dir($dirName)) {
                mkdir($dirName, 0777);
                chmod($dirName, 0777);
            }
            $savePath   = $dirName.$fileName;
            file_put_contents($savePath,$result);
            return array(
                'path'=>$savePath,
                'file'=>$fileName,
                'total' => $total,
                'cash' => $totalCash,
            );

        }

    }


    /**
     * @desc    丰付代付-发邮件   分页存储提现数据
     * @param   $withDraws
     * @param   $startDate
     * @param   $page
     * @param   $bankList
     * @return  string
     *
     */

    public function saveWithDrawSumaFile($withDraws, $startTime,$endTime,$page,$bankList){

        $total      = 0;
        $totalCash  = 0;
        $currentDate= date('Ymd');
        $sortNum    = $this->fillString($page,'4','0');
        $pcNum      = $currentDate.$sortNum;

        $bankSumaArr= WithdrawModel::getSumaBank();

        //单笔提现最大限额,超出将拆分成多个订单
        $maxCash    = WithdrawOrderDb::WITHDRAW_SPLIT_LIMIT;
        $dataArr[]  = ["商户流水号","账户类型","收款方户名","金额(元)","银行账号","开户银行","开户省份","开户城市","支行名称","银行用途"];
        $bankProvince = "";         //开户省份
        $bankCity   = "";           //开户城市
        $bankBranch = "";           //支行名称
        $bankDesc   = "";           //银行用途
        $tradeId    = 1;
        foreach ($withDraws as $w){

            $orderId    = $w['order_id'];       //订单号
            $realName   = $w["real_name"];      //姓名
            $cardNumber = $w["card_number"];    //银行卡号
            $bankName   = '';                   //银行名称
            if(isset($bankSumaArr[$w['bank_id']])){
                $bankSuma   = $bankSumaArr[$w['bank_id']];
                $bankName   = $bankSuma['bank_first'].'-'.$bankSuma['bank_company'].'-'.$bankSuma['bank_code'];
            }


            $cash       = $w["cash"];   //提现金额

            $n          = floor($cash/$maxCash);
            $totalCash += $cash;

            if($n > 0) {
                for($i=0; $i<$n; $i++) {
                    $dataArr[] =[
                                $tradeId, '个人银行账户',  $realName, round($maxCash,2), $cardNumber,
                                $bankName, $bankProvince,$bankCity, $bankBranch,    $orderId
                            ];
                    $tradeId++;
                    $total++;
                }

            }
            $newCash = $cash - $n*$maxCash;
            if(!empty($newCash)) {
                $dataArr[] =[
                                $tradeId, '个人银行账户',  $realName, round($newCash,2), $cardNumber,
                                $bankName, $bankProvince,$bankCity, $bankBranch,    $orderId
                            ];
                $tradeId++;
                $total++;
            }
        }
        $dataPreArr[]   = [ "日期", $currentDate, "批次号", $pcNum, "明细数目", $total, "总金额(元)", $totalCash , $bankBranch, $bankDesc];
        $dataPreArr[]   = [ "", "", "", "", "", "", "", "" , "", ""];
        $result     = array_merge($dataPreArr,$dataArr);
        \Log::info(__METHOD__.' : '.__LINE__."-SUMADATA-".var_export($result,true));
        if($result && $total > 0){

            $fileName   = $pcNum;
            $dirName    = base_path() . "/storage/exports/";

            if(!is_dir($dirName)) {
                mkdir($dirName, 0777);
                chmod($dirName, 0777);
            }
            $savePath   = $dirName.$fileName.'.xls';


            \Excel::create($fileName,function($excel) use ($result){

                $excel->sheet('score', function($sheet) use ($result){

                    #$sheet->rows($result);
                    $sheet->setColumnFormat(array(
                        'E' => '@',
                    ));
                    $sheet->fromArray($result);

                });

            })->store('xls');


            ##file_put_contents($savePath,$result);
            ##@file_put_contents($savePath, implode("\n", $result));
            return array(
                'path'  => $savePath,
                'file'  => $fileName,
                'total' => $total,
                'cash'  => $totalCash,
            );

        }

    }


    /**
     * @desc    丰付代付-发邮件   分页存储提现数据
     * @param   $withDraws
     * @param   $startDate
     * @param   $page
     * @param   $bankList
     * @return  string
     *
     */

    public function saveWithDrawUcfFile($withDraws, $startTime,$endTime,$page,$bankList){

        $total      = 0;
        $totalCash  = 0;
        $currentDate= date('Ymd');
        $sortNum    = rand(1000,9999);
        $pcNum      = $currentDate.$sortNum;

        $bankSumaArr= WithdrawModel::getSumaBank();

        //单笔提现最大限额,超出将拆分成多个订单
        $maxCash    = WithdrawOrderDb::WITHDRAW_SPLIT_LIMIT;
        $dataArr[]  = ["商户流水号","收款方户名","收款方账号","收款方开户机构","账户类型","金额(元)","开户机构支行全称","代发类型","备注"];
        $payType    = "";           //代发类型
        $bankBranch = "";           //支行名称
        $bankDesc   = "";           //备注
        foreach ($withDraws as $w){

            $orderId    = $w['order_id'];       //订单号
            $realName   = $w["real_name"];      //姓名
            $cardNumber = $w["card_number"];//银行卡号
            $bankName   = '';                   //银行名称
            if(isset($bankSumaArr[$w['bank_id']])){
                $bankSuma   = $bankSumaArr[$w['bank_id']];
                $bankName   = $bankSuma['bank_name'];
            }

            $cash       = $w["cash"];   //提现金额
            $totalCash += $cash;

            $dataArr[] =[
                $orderId, $realName,  $cardNumber, $bankName,'对私',round($cash,2), $bankBranch,$payType,$bankDesc
            ];
            $total++;
        }
        $dataPreArr[]   = [ "总笔数(笔):", $total, "总金额(元):", $totalCash, "", "",$bankBranch, $payType,$bankDesc];

        $result     = array_merge($dataPreArr,$dataArr);
        \Log::info(__METHOD__.' : '.__LINE__."-UCFDATA-".var_export($result,true));
        if($result && $total > 0){

            $fileName   = "UCF".$pcNum;
            $dirName    = base_path() . "/storage/exports/";

            if(!is_dir($dirName)) {
                mkdir($dirName, 0777);
                chmod($dirName, 0777);
            }
            $savePath   = $dirName.$fileName.'.xlsx';


            \Excel::create($fileName,function($excel) use ($result){

                $excel->sheet('score', function($sheet) use ($result){

                    $sheet->setColumnFormat(array(
                        'C' => '@',
                        'F' => '@',
                    ));
                    $sheet->fromArray($result, null, 'A1', false, false);

                });

            })->store('xlsx');


            ##file_put_contents($savePath,$result);
            ##@file_put_contents($savePath, implode("\n", $result));
            return array(
                'path'  => $savePath,
                'file'  => $fileName,
                'total' => $total,
                'cash'  => $totalCash,
            );

        }

    }


    //字符串填充 - 数据左边加零
    public function fillString($str,$len,$pad_str,$type='1') {
        $length = $len - strlen($str);
        if($length < 1) return $str;
        if ($type == 1) {
            $str = str_repeat($pad_str, $length).$str;
        } else {
            $str .= str_repeat($pad_str,$length);
        }
        return $str;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $total
     * @return mixed
     * 发送提现邮件
     */
    public function sendWithdrawEmail($startDate,$endDate,$total,$emails){

        //提现邮件单个文件最多记录数,最多1000条
        $size = WithdrawOrderDb::WITHDRAW_EMAIL_MAX_NUM;

        $amount     = 0;    //提现总金额
        $realTotal  = 0;     //总订单笔数(拆分)
        $fileArr    = [];   //文件名称列表
        $totalPage  = ceil($total / $size); //总页数


        $body = "";

        //$body .= $this->getCss();
        $body .= "<table class='table'>
        <tr>
        <th>文件名</th>
        <th>订单数量</th>
        <th>订单金额</th>
        </tr>";


        //获取所有银行名称
        $bankDb     = new BankDb();
        $bankList   = $bankDb->getAllBank();
        $bankList   = ToolArray::arrayToKey($bankList,'id');
        //分页获取提现数据
        for($page = 1;$page <= $totalPage;$page++){

            //分页获取未处理的订单
            $result = $this->getDealingListByPage($startDate,$endDate,$page,$size);

            $withDraws = $result["list"];
            $amount    += $result["amount"];

            //保存提现文件
            $arr = $this->saveWithDrawFile($withDraws,$startDate,$endDate,$page,$bankList);

            $fileArr[] = $arr['path'];
            $realTotal += $arr['total'];

            $wt = "<tr>
            <th>{$arr['file']}</th>
            <th>{$arr['total']}</th>
            <th>{$arr['cash']}</th>
            </tr>";
            $body.= $wt;
            
        }

        $body .= "<tr><th colspan='3'>提现申请时间段：{$startDate} 至 {$endDate}，共{$realTotal}笔提现，总额为：{$amount}元</th></tr>";
        $body .= "</table>";

        //添加提现记录
        $this->addWithdrawRecord($startDate,$endDate,$realTotal,$amount);
        
        //组织邮件内容开始
        $returnArr = [
            'status'    => true,
            'errorMsg'  => '提现邮件发送成功'
        ];

        //存在提现用户发送邮件
        if(count($fileArr) >= 1) {
            
            //$emailModel = new EmailModel();
            
            $data['subject'] = $body;
            $data['attachment'] = $fileArr;
            $data['startTime'] = $startDate;
            $data['endTime'] = $endDate;
            
            $result = OrderLogic::doSendWithdrawEmail($data,$emails);
            //$result = $emailModel->sendHtmlEmail($receiveEmails,$subject,$body,$fileArr);
            foreach($fileArr as $file) {
                @unlink($file);
            }
            //发送失败
            if($result['status'] === false){

                throw new \Exception($result['msg'], self::getFinalCode('sendWithdrawEmailFailed'));
            }
        }else{
            //不存在提现未处理的记录,可能存在问题,发放报警
            throw new \Exception(LangModel::getLang('ERROR_WITH_DRAW_UNDEAL_HAVE_NOT_DATA'), self::getFinalCode('sendWithdrawEmailHaveNoUndealData'));
        }



    }



    /**
     * @desc    丰付代付-发邮件
     * @param   $startDate  时间段-开始时间
     * @param   $endDate    时间段-结束时间
     * @param   $total
     * @return  mixed
     *
     */
    public function sendWithdrawEmailSuma($startDate,$endDate,$total,$emails){

        //提现邮件单个文件最多记录数,最多1000条
        $size = WithdrawOrderDb::WITHDRAW_EMAIL_MAX_NUM;

        $amount     = 0;        //提现总金额
        $realTotal  = 0;        //总订单笔数(拆分)
        $fileArr    = [];       //文件名称列表
        $totalPage  = ceil($total / $size); //总页数


        $body = "";
        //$body .= $this->getCss();
        $body .= "<table class='table'>
            <tr>
            <th>文件名</th>
            <th>订单数量</th>
            <th>订单金额</th>
            </tr>";


        //获取所有银行名称
        $bankDb     = new BankDb();
        $bankList   = $bankDb->getAllBank();
        $bankList   = ToolArray::arrayToKey($bankList,'id');
        //分页获取提现数据
        for($page = 1;$page <= $totalPage;$page++){

            //分页获取未处理的订单
            $result     = $this->getDealingListByPage($startDate,$endDate,$page,$size);

            $withDraws  = $result["list"];
            $amount    += $result["amount"];

            //保存提现文件
            $arr = $this->saveWithDrawSumaFile($withDraws,$startDate,$endDate,$page,$bankList);

            $fileArr[] = $arr['path'];
            $realTotal += $arr['total'];

            $wt = "<tr>
                <td>{$arr['file']}</td>
                <td>{$arr['total']}</td>
                <td>{$arr['cash']}</td>
                </tr>";
            $body.= $wt;

        }

        $body .= "<tr><td colspan='3'>提现申请时间段：{$startDate} 至 {$endDate}，共{$realTotal}笔提现，总额为：{$amount}元</td></tr>";
        $body .= "</table>";

        //添加提现记录
        $this->addWithdrawRecord($startDate, $endDate,$realTotal,$amount);

        //组织邮件内容开始
        $returnArr = [
            'status'    => true,
            'errorMsg'  => '提现邮件发送成功'
        ];

        //存在提现用户发送邮件
        if(count($fileArr) >= 1) {

            //$emailModel = new EmailModel();

            $data['subject']    = $body;
            $data['attachment'] = $fileArr;
            $data['startTime']  = $startDate;
            $data['endTime']    = $endDate;

            #\Log::info(__METHOD__.' : '.__LINE__."-DATA-".var_export($data,true));
            $result = OrderLogic::doSendWithdrawEmail($data,$emails);
            //$result = $emailModel->sendHtmlEmail($receiveEmails,$subject,$body,$fileArr);
            foreach($fileArr as $file) {
                @unlink($file);
            }
            //发送失败
            if($result['status'] === false){

                throw new \Exception($result['msg'], self::getFinalCode('sendWithdrawEmailFailed'));
            }
        }else{
            //不存在提现未处理的记录,可能存在问题,发放报警
            throw new \Exception(LangModel::getLang('ERROR_WITH_DRAW_UNDEAL_HAVE_NOT_DATA'), self::getFinalCode('sendWithdrawEmailHaveNoUndealData'));
        }



    }

    /**
     * @desc    丰付代付-发邮件
     * @param   $startDate  时间段-开始时间
     * @param   $endDate    时间段-结束时间
     * @param   $total
     * @return  mixed
     *
     */
    public function sendWithdrawEmailUcf($startDate,$endDate,$total,$emails){

        //提现邮件单个文件最多记录数,最多1000条
        $size = WithdrawOrderDb::WITHDRAW_EMAIL_MAX_NUM;

        $amount     = 0;        //提现总金额
        $realTotal  = 0;        //总订单笔数(拆分)
        $fileArr    = [];       //文件名称列表
        $totalPage  = ceil($total / $size); //总页数


        $body = "";
        //$body .= $this->getCss();
        $body .= "<table class='table'>
            <tr>
            <th>文件名</th>
            <th>订单数量</th>
            <th>订单金额</th>
            </tr>";


        //获取所有银行名称
        $bankDb     = new BankDb();
        $bankList   = $bankDb->getAllBank();
        $bankList   = ToolArray::arrayToKey($bankList,'id');
        //分页获取提现数据
        for($page = 1;$page <= $totalPage;$page++){

            //分页获取未处理的订单
            $result     = $this->getDealingListByPage($startDate,$endDate,$page,$size);

            $withDraws  = $result["list"];
            $amount    += $result["amount"];

            //保存提现文件
            $arr = $this->saveWithDrawUcfFile($withDraws,$startDate,$endDate,$page,$bankList);

            $fileArr[] = $arr['path'];
            $realTotal += $arr['total'];

            $wt = "<tr>
                <td>{$arr['file']}</td>
                <td>{$arr['total']}</td>
                <td>{$arr['cash']}</td>
                </tr>";
            $body.= $wt;

        }

        $body .= "<tr><td colspan='3'>提现申请时间段：{$startDate} 至 {$endDate}，共{$realTotal}笔提现，总额为：{$amount}元</td></tr>";
        $body .= "</table>";

        //添加提现记录
        $this->addWithdrawRecord($startDate, $endDate,$realTotal,$amount);

        //组织邮件内容开始
        $returnArr = [
            'status'    => true,
            'errorMsg'  => '提现邮件发送成功'
        ];

        //存在提现用户发送邮件
        if(count($fileArr) >= 1) {

            //$emailModel = new EmailModel();

            $data['subject']    = $body;
            $data['attachment'] = $fileArr;
            $data['startTime']  = $startDate;
            $data['endTime']    = $endDate;

            #\Log::info(__METHOD__.' : '.__LINE__."-DATA-".var_export($data,true));
            $result = OrderLogic::doSendWithdrawEmail($data,$emails);
            //$result = $emailModel->sendHtmlEmail($receiveEmails,$subject,$body,$fileArr);
            foreach($fileArr as $file) {
                @unlink($file);
            }
            //发送失败
            if($result['status'] === false){

                throw new \Exception($result['msg'], self::getFinalCode('sendWithdrawEmailFailed'));
            }
        }else{
            //不存在提现未处理的记录,可能存在问题,发放报警
            throw new \Exception(LangModel::getLang('ERROR_WITH_DRAW_UNDEAL_HAVE_NOT_DATA'), self::getFinalCode('sendWithdrawEmailHaveNoUndealData'));
        }



    }


    /**
     * @param $startDate
     * @param $endDate
     * @param $total
     * @param $cash
     * 添加提现记录
     */
    private function addWithdrawRecord($startDate,$endDate,$total,$cash){
        
        $db = new WithdrawRecordDb();
        
        $result = $db->getByTime($startDate,$endDate);

        if(!$result){

            $data = [
                'start_time' => $startDate,
                'end_time'   => $endDate,
                'cash'       => $cash,
                'num'        => $total
            ];

            $db->addRecord($data);

        }
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $total
     * @desc 创建记录
     */
    public function addWithdrawRecords($startDate, $endDate, $total){

        //提现邮件单个文件最多记录数,最多1000条
        $size = WithdrawOrderDb::WITHDRAW_EMAIL_MAX_NUM;

        $amount     = 0;    //提现总金额
        $realTotal  = 0;     //总订单笔数(拆分)
        $totalPage  = ceil($total / $size); //总页数

        #$maxCash= WithdrawOrderDb::WITHDRAW_SPLIT_LIMIT;
        $maxCash = WithdrawOrderDb::WITHDRAW_UCF_LIMIT;

        //分页获取提现数据
        for($page = 1;$page <= $totalPage;$page++){

            //分页获取未处理的订单
            $result = $this->getUnDealListByPage($startDate,$endDate,$page,$size);

            $withDraws = $result["list"];

            $amount    += $result["amount"];

            foreach ($withDraws as $w){

                $cash       = $w["cash"]; //金额转化成分为单位

                $realTotal +=  ceil($cash/$maxCash);

            }

        }

        //添加提现记录
        $this->addWithdrawRecord($startDate,$endDate,$realTotal,$amount);

    }


    /**
     * @desc    创建提现记录
     * @author  linglu
     * @param   $startDate
     * @param   $endDate
     * @param   $total
     *
     */
    public function addWithdrawRecordsNew($startDate, $endDate, $total='0'){

        $db     = new WithdrawOrderDb();
        //获取指定日期之内的提现订单金额
        $withDrawDta= $db->getUnDealOrderCashByDate($startDate,$endDate);

        $totalNum   = isset($withDrawDta["totalNum"]) ? $withDrawDta["totalNum"]:0;
        $totalCash  = isset($withDrawDta["totalCash"])? $withDrawDta["totalCash"]:0;
        //添加提现记录
        $this->addWithdrawRecord($startDate,$endDate, $totalNum, $totalCash);

    }


}