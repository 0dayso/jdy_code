<?php
/**
 * 拆分对账数据
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/9
 * Time: 下午2:19
 */

namespace App\Http\Logics\Recharge;

use App\Http\Dbs\Order\CheckBatchDb;
use App\Http\Logics\Logic;
use App\Tools\ToolStr;
use Maatwebsite\Excel\Facades\Excel;
use Log;

class CheckOrderLogic extends Logic
{
    /**
     * @param $id
     * @return array
     * @desc 获取需要对账的数据
     */
    public static function checkBathEntrance( $checkInfo )
    {
        $path           =   env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com').$checkInfo['file_path'];
        $pathFile       =   base_path() . '/public/uploads'.substr($checkInfo['file_path'],strrpos($checkInfo['file_path'],'/'));
        @file_put_contents($pathFile,@file_get_contents($path));
        $fileExtension  =   self::setPathFileType($pathFile);

        $fileContent    =   self::doReadFileWithExtension($fileExtension,$pathFile,$checkInfo['pay_channel']);

        @unlink($pathFile);
        //return $fileContent;

        if( empty($fileContent) ){

            return self::callError("文件数据为空");
        }

        foreach ( $fileContent as $key => $content ){

            $result      =  self::doCheckBathRecord($content);

            if( $result['status'] == false ){

                return self::callError($content['order_id']."入队列失败");
            }

        }

        return self::callSuccess();
    }

    /**
     * @param $fileContent
     * @return array
     * @对账数据入队列
     */
    public static function doCheckBathRecord($fileContent)
    {
        if( empty($fileContent) ){

            return [];
        }

        try{
            self::beginTransaction();

            /*
             * 'order_id'  => $statistics[$i][$payChannel['order_id']],
                'cash'      => $statistics[$i][$payChannel['cash']],
                'time'      => $statistics[$i][$payChannel['time']],
                'channel'   => $payChannel['type'],
                'channel_name'=> $payChannel['name'],
             */
            $params = [
                'event_name'        => 'App\Events\Pay\RechargeCheckEvent',
                'event_desc'        => '充值对账事件',
                'order_id'          =>  $fileContent['order_id'],
                'cash'              =>  $fileContent['cash'],                   //结算标示
                'time'              =>  $fileContent['time'],                 //结算的参数
                'channel'           =>  $fileContent['channel'],                      //结算参数
                'ticket_id'         =>  ToolStr::getRandTicket()
            ];

            Log::info(__METHOD__,$params);

            \Event::fire(new \App\Events\Pay\RechargeCheckEvent($params));

            //写日志
            Log::info(__CLASS__.'success', $fileContent);

            self::commit();

        } catch (\Exception $e){

            self::rollback();

            Log::error(__CLASS__, [$e->getMessage()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();

    }

    /**
     * @param $fileExtension
     * @param $pathFile
     * @param $channel
     * @return array|void
     * @desc 处理文件的方式
     */
    protected static function doReadFileWithExtension($fileExtension,$pathFile,$channel)
    {
        $fileContent    =   [];

        switch ($fileExtension){

            case "xls":
            case "xlsx":

                $fileContent    =   self::loadExcel($pathFile , $channel);

                break;

            case "csv":

                $fileContent    =   self::loadCsv($pathFile , $channel);

                break;

            case "txt":

                $fileContent    =   self::loadText($pathFile , $channel);

                break;

            default:

                break;
        }

        return $fileContent;

    }
    /**
     * @param $pathFile
     * @return array
     * @desc 获取文件类型
     */
    protected static function setPathFileType( $pathFile )
    {
        return pathinfo($pathFile,PATHINFO_EXTENSION);
    }

    /**
     * @param $pathFile
     * @param $payChannel
     * @desc 读取excl的文件
     */
    public static function loadExcel( $pathFile ,$payChannel)
    {

        $readInfo   =   Excel::load($pathFile,'GBK');

        $result     =   $readInfo->getSheet(0)->toArray();

        $result     =   self::formatStatistics($result , $payChannel);

        return $result;
    }

    /**
     * @param $statistics
     * @param $payChannel
     * @return array
     * @desc 格式化数据
     */
    protected static function formatStatistics( $statistics , $payChannel )
    {
        if( empty($statistics) ) return [];

        $payConfig  =   self::getPayChannelConfig();

        $payChannel =   $payConfig[$payChannel];

        $formatItem =   [];

        $allRows    =   count($statistics);

        for ($i = $payChannel['start']; $i < $allRows; $i++){

            $orderCash   =  self::doFormatOrderCash($payChannel['cash'],$statistics[$i]);

            $orderId     =  self::doFormatOrderId($payChannel,$statistics[$i]);

            if( $orderCash !='0.00' || $orderId || $statistics[$i][$payChannel['time']] ) {

                $formatItem[] = [
                    'order_id' => $orderId,
                    'cash' => $orderCash,
                    'time' => isset($statistics[$i][$payChannel['time']]) ? $statistics[$i][$payChannel['time']] : "",
                    'channel' => $payChannel['type'],
                    'channel_name' => $payChannel['name'],
                ];
            }
        }

        return $formatItem;
    }
    /**
     * @param $pathFile
     * @param $payChannel
     * @desc 读取Csv格式的
     */
    public static function loadCsv($pathFile , $payChannel)
    {

    }

    /**
     * @param $pathFile
     * @param $payChannel
     * @desc  读取TXT文档的
     */
    public static function loadText($pathFile , $payChannel )
    {

    }

    /**
     * @param $orderLine
     * @param $statistics
     * @return mixed
     * @格式化订单号
     */
    protected static function doFormatOrderId($channelConfig,$statistics)
    {
        $orderId        = isset($statistics[$channelConfig['order_id']]) ? $statistics[$channelConfig['order_id']] : "";

        if( empty( $orderId) ){

            return "";
        }

        if (strpos($orderId, 'E+') !== FALSE) {

            $orderNum   = "";

            while ( $orderId > 0 ) {
                $val    = $orderId - floor($orderId / 10) * 10;

                $orderId = floor($orderId / 10);

                $orderNum = $val . $orderId;
            }

            $orderId    =   $orderNum;
        }

        if( strstr(strtolower($orderId),"jdy") ){

            return $orderId;    //有的是JDY_201511040922569130"'
        }

        return  preg_replace('/\D/', '', $orderId); //有的是'="201511040922569130"'
    }
    /**
     * @param $cashConfig
     * @param $statistics
     * @return string
     * @处理订单金额
     */
    protected static function doFormatOrderCash($cashLine,$statistics)
    {
        $orderCash = 0;

        if (strpos($cashLine, ',') !== FALSE) {

            $cashArr = explode(',', $cashLine);

            foreach ($cashArr as $number) {

                $orderCash += $statistics[$number];
            }
        } else {

            $orderCash = $statistics[$cashLine];
        }

        return  sprintf("%.2f", $orderCash);
    }
    /**
     * @return array
     * @desc 获取对账文件的格式
     */
    protected static function getPayChannelConfig()
    {
        $logic      =   new CheckBatchLogic();

        //$result     =   $logic->setPayChannelConfig();
        $result     =   CheckBatchLogic::setFormatFileConfig();  //使用新的动态配置

        return $result;
    }

    /**
     * @param $orderId
     * @param $cash
     * @param $time
     * @param $channel
     * @param array $orderInfo
     * @return array
     * @desc 订单数据的对比
     */
    public function doVerification($orderId , $cash,$time,$channel,$orderInfo=array() )
    {
        if( empty($orderId) && empty($cash) && empty($time) && empty($channel) ){

            return [];
        }

        if( empty($orderInfo) ){

            return self::callError("我们库里订单丢失");

        }

        if( $orderInfo['cash'] != $cash ){

            return self::callError('订单金额不一致');
        }

        $checkOrderTime  =   date("Y-m-d",strtotime($time));

        $checkTime       =   date("Y-m-d",strtotime($orderInfo['success_time']));

        if( $checkOrderTime != $checkTime ){

            return self::callError('订单时间不一致');
        }

        return self::callSuccess();
    }
}
