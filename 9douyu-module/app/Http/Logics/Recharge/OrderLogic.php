<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/22
 * Time: 13:39
 */

namespace App\Http\Logics\Recharge;

use App\Http\Dbs\Bank\BankDb;
use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\OrderModel;
use App\Tools\ExportFile;
use App\Tools\ToolArray;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\User\UserModel;
use App\Tools\ToolTime;

class OrderLogic extends Logic{

    /**
     * @param $data
     * 后台充值列表
     */
    public function getAdminList($data){

        $data['type'] = OrderDb::RECHARGE_TYPE;

        if(isset($data['phone']) && $data['phone']){

            $phone = $data['phone'];

            try{

                ValidateModel::isPhone($phone);
                $userInfo = UserModel::getCoreApiBaseUserInfo($phone);


            }catch (\Exception $e){

                return [
                    'total' => 0,
                    'data' => [],
//                    'status_list' => OrderDb::orderStatusList(),
//                    'channel_list' => $typeList
                ];
            }

            if($userInfo){
                $data['userId'] = $userInfo['id'];
            }else{
                return [ 'total' => 0 , 'data' => [] ];
            }

        }

        $data      =   self::doSetAttrValue($data);

        $orderList = OrderModel::getAdminOrderList($data);

        $typeList   =   self::setOrderTypeList();

        $typeList   =   $typeList['channel_list'];

        if( isset($orderList['total']) && $orderList['total'] > 0){

            $bankList = self::setBankList();


            foreach($orderList['data'] as $k=>$val){

                $orderList['data'][$k]['type_name'] = isset($typeList[$val['type']]['name']) ? $typeList[$val['type']]['name'] : '';

                if(isset($bankList[$val['bank_id']])){
                    $orderList['data'][$k]['bank_name'] = $bankList[$val['bank_id']]['name'];

                }else{
                    $orderList['data'][$k]['bank_name'] = '未知';

                }
            }
        }

        return $orderList;
    }

    /**
     * @desc 获取充值订单的数据统计
     * @author lgh
     * @param $param
     * @return array|null|void
     */
    public function getRechargeStatistics($param){

        $rechargeStatistics = OrderModel::getRechargeStatistics($param);

        return $rechargeStatistics;
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return bool
     * @desc 数据导出
     */
    public function doExport( $statistics ){

        $list[] = [

            '订单号',	'金额',	'姓名',	'手机号码',    '交易流水号',	'时间',	'状态',	'充值类型' , '银行名称' , '银行卡号' ,'备注'
        ];

        $data       = $this->getAdminList($statistics);

        if( empty($data) ){ return false; }

        $formatData = self::formatExportStatistics($data['data']) ;

        $list = array_merge($list, $formatData);

        ExportFile::csv($list, 'recharge_order-'.ToolTime::dbDate());

    }

    /**
     * @param $statisticsData
     * @return array
     * @desc 格式化数据
     */
    protected static function formatExportStatistics($statisticsData)
    {
        $formatStatistics =   [];

        if( empty($statisticsData) ){

            return $formatStatistics;
        }
        $channelTypeList        =   self::setOrderTypeList()['channel_list'];

        $bankTypeList           =   self::setBankList();

        foreach ($statisticsData as $key => $statistics ){

            $formatStatistics[$key] =   [
                'order_id'      =>  $statistics['order_id'],
                'cash'          =>  $statistics['cash'],
                'name'          =>  isset($statistics['name']) ? $statistics['name'] : "",
                'phone'         =>  isset($statistics['phone']) ? $statistics['phone'] : '',
                'trade_no'      =>  isset($statistics['trade_no']) ? $statistics['trade_no'] : "",
                'updated_at'    =>  $statistics['created_at']."--".$statistics['updated_at'],
                'status_note'   =>  isset($statistics['status_note']) ? $statistics['status_note'] : "",
                'type_name'     =>  isset($statistics['type_name']) ? $statistics['type_name'] : $channelTypeList[$statistics['type']]['name'],
                'bank_name'     =>  isset($statistics['bank_name']) ? $statistics['bank_name'] : $bankTypeList[$statistics['bank_id']]['name'],
                'card_number'   =>  isset($statistics['card_number']) ? $statistics['card_number'] : "",
                'note'          =>  isset($statistics['note']) ? $statistics['note'] : "",
            ];
        }
        unset($statisticsData);
        return $formatStatistics;
    }

    /**
     * @return array
     * @desc 搜索状态
     */
    public static function setOrderTypeList()
    {
        $limitLogic     = new PayLimitLogic();

        $channelList    = $limitLogic->getPayTypeName() + $limitLogic->getOnlinePayTypeName();

        $channelList[0] = [ 'name' => '全部渠道'];

        ksort($channelList);

        $statusList     = OrderDb::orderStatusList();


        return ['status_list'=> $statusList,'channel_list'=> $channelList];
    }

    /**
     * @return array|mixed
     * @银行卡信息
     */
    public static function setBankList()
    {
        $bankDb     = new BankDb();

        $bankList   = $bankDb->getAllBank();

        $bankList   = ToolArray::arrayToKey($bankList,'id');

        return $bankList;
    }

    /**
     * @param array $data
     * @return array
     * @desc 格式化时间
     */
    public static function doSetAttrValue( $data = array() )
    {
        if( $data['start_time'] ){

            $data['start_time'] =   date("Y-m-d H:i:s",ToolTime::getUnixTime($data['start_time']));

        }
        if( $data['end_time'] ){

            $data['end_time']  =   date("Y-m-d H:i:s",ToolTime::getUnixTime($data['end_time'],'end'));
        }

        return $data;
    }
}