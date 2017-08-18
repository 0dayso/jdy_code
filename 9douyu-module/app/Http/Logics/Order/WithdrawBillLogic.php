<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/4
 * Time: 上午11:57
 */
namespace App\Http\Logics\Order;
use App\Http\Logics\Logic;
use App\Http\Models\Order\WithdrawBillModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use Excel;
use Log;

class WithdrawBillLogic extends Logic
{
    /**
     * 获取对账excel内容
     * 表头：0创建时间,1批次号,2账户名,3订单号,4开户行,5银行账号,6付款账号,7交易金额,8手续费,9订单状态,10失败原因,11九斗鱼订单id
     * @param $path
     * @return array
     */
    public function loadExcel($path){

        $results = $this->getDataCsv($path);

        /*$reader = Excel::load($path,'GBK');
        $results = $reader->getSheet(0)->toArray();*/
        //$results = array_slice($results,8,-1);
        $item = [];
        $statusNoteArr = ['失败','成功'];

        foreach($results as $result){
            $orderId = $result[11];
            $statusNote = iconv( "GB2312", "UTF-8", $result[9]);
            //格式不对直接返回
            if(!in_array($statusNote, $statusNoteArr)){

                return self::callError('订单号为'.$orderId.'提现订单状态有误，请检查后再上传');
            }

            $item[$orderId][] = array(
                'order_id'          => $orderId, //订单id
                'bill_status'       => $statusNote == '失败'?500:200,     //提现状态
                'bill_time'         => trim($result[0]),  //银行返回时间
                'cash'              => str_replace(',','',$result[7]),  //提现金额
                'note'              => iconv( "GB2312", "UTF-8", $result[10]) ? iconv( "GB2312", "UTF-8", $result[10]) : '', //失败原因
            );
            
        }

        $return = [];
        $errorList = [];
        foreach($item as $orderId => $val){
            if(count($val) > 1){

                $statusArr = ToolArray::arrayToIds($val,'bill_status');
                if(count($statusArr) > 1){

                    $errorList[] = $orderId;
                    continue;

                }else{
                    $cash = 0;
                    foreach($val as $order){

                        $cash += $order['cash'];
                        $time = $order['bill_time'];
                        $note = $order['note'];
                    }

                    $return[$orderId] = [
                        'order_id' => $orderId,
                        'cash'  => $cash,
                        'bill_status' => $statusArr[0],
                        'note' => $note,
                        'bill_time' => $time
                    ];
                }

            }else{
                $return[$orderId] = $val[0];
            }

        }

        if($errorList){

            Log::error(__METHOD__.'Error',$errorList);
        }

        return $return;
    }

    //增加csv格式处理
    private function getDataCsv($file='')
    {
        if( !$file ){return false;}
        $file = fopen($file,"r");
        $result = $this->input_csv($file); //解析csv
        $tem = array();
        foreach( $result as $key => $val ){
            if( count($val) <= 12 ){continue;}
            $tem[] = $val;
        }
        return $tem;
    }

    function input_csv($handle) {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = $data[$i];
            }
            $n++;
        }
        return $out;
    }

    /**
     * 提现对账数据入DB，待执行
     * @param $result
     * @return array
     */
    public function addBillInfo($result){
        try {
            $withdrawBillModel = new WithdrawBillModel();
            $withdrawBillModel->createBill($result);
        } catch(\Exception $e) {
            return self::callError($e->getMessage());
        }
        return self::callSuccess([],LangModel::getLang('SUCCESS_WITHDRAW_UPLOAD'));
    }

    /**
     * 提现对账处理
     */
    public function checkBillOrder(){
        $withdrawBillModel = new WithdrawBillModel();
        //未处理提现对账数据
        $bills = $withdrawBillModel->getBills();
        if(!is_array($bills)) return;
        //分批处理
        $billsArr = array_chunk($bills,100);
        foreach($billsArr as $arrs){
            //请求core，返回状态
            $res = $withdrawBillModel->getDoneWithdrawStatus($arrs);
            if(!$res) continue;
            //更新任务执行状态和提现状态
            //$ids = array_map('array_shift', $arrs);
            $ids = ToolArray::arrayToIds($arrs,'order_id');
            $withdrawBillModel->updateWithdrawBillSuccess($ids);
        }
    }



    /**
     * @desc    丰付代付-自动对账
     * @表头     A-请求流水号、B-批次号、C-付款类型、D-交易流水号、E-请求时间、F-交易时间、G-交易类型、
     *          H-到账类型、I-金额、J-收款方账号、K-收款方名称、L-银行、M-状态、N-备注、
     *          O-商户名称、P-请求操作员、Q-审核操作员、R-失败原因
     *
     **/
    public function loadExcelSuma($data=[]){
        foreach ($data as $key=>$value){
            if(!$value["1"] || $value["1"] =="批次号"){
                unset($data[$key]);
            }
        }

        $item   = [];
        $statusNoteArr = ['失败','成功'];
        \Log::info(__METHOD__.' : '.__LINE__.var_export($data,true));
        foreach($data as $result){
            $orderId    = $result[13];
            $statusNote = $result[12];
            //格式不对直接返回
            if(!in_array($statusNote, $statusNoteArr)){

                return self::callError('订单号为'.$orderId.'提现订单状态有误，请检查后再上传');
            }

            $item[$orderId][] = array(
                'order_id'          => $orderId,                        //订单id
                'bill_status'       => $statusNote == '失败'?500:200,    //提现状态
                'bill_time'         => trim($result[5]),                //银行返回时间
                'cash'              => str_replace(',','',$result[8]),  //提现金额
                'type'              => WithdrawBillModel::BILL_TYPE_SUMA,   //丰付代付
                //失败原因
                'note'              => $result[17],
            );

        }

        $return     = [];
        $errorList  = [];
        foreach($item as $orderId => $val){
            #判断拆分订单
            if(count($val) > 1){
                $statusArr = ToolArray::arrayToIds($val,'bill_status');
                if(count($statusArr) > 1){
                    $errorList[]    = $orderId;
                    continue;
                }else{
                    $cash   = 0;
                    $note   = '';
                    $time   = '';
                    foreach($val as $order){
                        $cash   +=$order['cash'];
                        $time   = $order['bill_time'];
                        $note   = $order['note'];
                    }

                    $return[$orderId] = [
                        'order_id'  => $orderId,
                        'cash'      => $cash,
                        'bill_status'=>$statusArr[0],
                        'note'      => $note,
                        'bill_time' => $time ,
                        'type'      => WithdrawBillModel::BILL_TYPE_SUMA,
                    ];
                }

            }else{
                $return[$orderId]   = $val[0];
            }

        }

        if($errorList){

            Log::error(__METHOD__.'Error',$errorList);
        }

        \Log::info(__METHOD__.' : '.__LINE__.var_export($return,true));
        return $return;

    }


    /**
     * @desc    先锋代付-自动对账
     * @表头     A-账务时间、B-交易创建时间、C-交易完成时间、D-商户订单号、E-交易流水号、F-提现渠道、G-网关流水号、
     *          H-银行订单号、I-金额、J-交易状态、K-回盘信息、L-收款人姓名、M-收款账号、N-收款银行、
     *          O-银行卡类型、P-会员ID、Q-业务类型
     *
     * @表头     A-商户流水号、B-交易订单号、 C-代发状态、D-收款方户名、E-收款方账号、F-收款方开户机构、G-账户类型、
     *          H-金额、I-开户机构支行全称，J-代发类型、K-失败原因、L-备注
     *
     **/
    public function loadExcelUcf($data=[]){

        foreach ($data as $key=>$value){
            if(!$value["4"] || $value["4"] =="收款方账号"){
                unset($data[$key]);
            }
        }
        $item   = [];
        $statusNoteArr = ['失败','成功'];
        \Log::info(__METHOD__.' : '.__LINE__.var_export($data,true));
        foreach($data as $result){
            $orderId    = $result[0];
            $statusNote = $result[2];
            //格式不对直接返回
            if(!in_array($statusNote, $statusNoteArr)){

                return self::callError('订单号为'.$orderId.'提现订单状态有误，请检查后再上传');
            }

            $item[$orderId][] = array(
                'order_id'          => $orderId,                        //订单id
                'bill_status'       => $statusNote == '失败'?500:200,    //提现状态
                'bill_time'         => date('Y-m-d H:i:s'),             //银行返回时间
                'cash'              => str_replace(',','',$result[7]),  //提现金额
                'type'              => WithdrawBillModel::BILL_TYPE_UCF,//先锋代付
                //失败原因
                'note'              => $result[10],
            );

        }

        $return     = [];
        $errorList  = [];
        foreach($item as $orderId => $val){
            #判断拆分订单
            if(count($val) > 1){
                $statusArr = ToolArray::arrayToIds($val,'bill_status');
                if(count($statusArr) > 1){
                    $errorList[]    = $orderId;
                    continue;
                }else{
                    $cash   = 0;
                    $note   = '';
                    $time   = '';
                    foreach($val as $order){
                        $cash   +=$order['cash'];
                        $time   = $order['bill_time'];
                        $note   = $order['note'];
                    }

                    $return[$orderId] = [
                        'order_id'  => $orderId,
                        'cash'      => $cash,
                        'bill_status'=>$statusArr[0],
                        'note'      => $note,
                        'bill_time' => $time ,
                        'type'      => WithdrawBillModel::BILL_TYPE_SUMA,
                    ];
                }

            }else{
                $return[$orderId]   = $val[0];
            }

        }

        if($errorList){

            Log::error(__METHOD__.'Error',$errorList);
        }

        \Log::info(__METHOD__.' : '.__LINE__.var_export($return,true));
        return $return;

    }


}