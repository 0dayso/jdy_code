<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/18
 * Time: 14:37
 */

namespace App\Http\Logics\Recharge;

use App\Http\Dbs\Bank\BankDb;
use App\Http\Dbs\Order\PayLimitDb;
use App\Http\Dbs\OrderDb;
use App\Http\Logics\Logic;
use App\Tools\ToolArray;
use EasyWeChat\Payment\Order;

class PayLimitLogic extends Logic{


    /**
     * @param $type
     * @return mixed
     * 根据支付通道获取对应的限额列表数据
     */
    public function getListByType($type,$bankId){

        $db = new PayLimitDb();

        $payArr = $this->getPayType($type);

        $payType = $payArr[$type];

        $list =  $db->getLimitByType($payType,$bankId);

        $bankList = $this->getAllBank();

        $bankList = ToolArray::arrayToKey($bankList,'id');

        $nameList = $this->getPayTypeName();

        if(!empty($list['data'])){

            foreach($list['data'] as $k=>$val){

                $list['data'][$k]['bank_name'] = $bankList[$val['bank_id']]['name'];
                $list['data'][$k]['type_name'] = $nameList[$val['pay_type']]['name'];
            }
        }

        return $list;
    }

    /**
     * @return mixed
     * 获取银行列表
     */
    public function getBankList(){

        $bankDb = new BankDb();
        return $bankDb->getAllBank();
    }

    /**
     * @param $id
     * @return mixed
     * 根据主键ID获取限额信息
     */
    public function getById($id){

        $db = new PayLimitDb();

        $data = $db->getById($id);

        $bankDb = new BankDb();

        $bank = $bankDb->getBankName($data['bank_id']);
        $data['bank_name'] = $bank['name'];

        $typeArr = $this->getPayTypeName();

        $data['type_name'] = $typeArr[$data['pay_type']]['name'];

        return $data;
    }

    /**
     * @param $data
     * 添加银行限额
     */
    public function doCreate($data){

        $db = new PayLimitDb();

        $bankList = $db->getListByType($data['pay_type']);

        $bankIds = ToolArray::arrayToIds($bankList,'bank_id');

        if(in_array($data['bank_id'],$bankIds)){

            return self::callError('该银行已存在,请勿重复添加');
        }

        unset($data['_token']);
        $result = $db->doCreate($data);
        if($result){

            return self::callSuccess();
        }else{

            return self::callError('添加失败');
        }
    }

    /**
     * @param $data
     * @return array
     * 编辑限额信息
     */
    public function doEdit($data){

        $id = $data['id'];

        unset($data['_token'],$data['id']);

        $db = new PayLimitDb();

        $result = $db->doEdit($id,$data);

        if($result){
            return self::callSuccess();

        }else{

            return self::callError('编辑失败');
        }

    }


    /**
     * @param $id
     * @param $status
     * @return array
     * 启用或禁用通道
     */
    public function doEditStatus($id,$status){

        if($status == PayLimitDb::STATUS_FORBIDDEN){

            $dbStatus = PayLimitDb::STATUS_NORMAL;
        }else{

            $dbStatus = PayLimitDb::STATUS_FORBIDDEN;
        }

        $db = new PayLimitDb();
        $result = $db->doEdit($id,['status' => $dbStatus]);
        if($result){
            return self::callSuccess();
        }else{

            return self::callError('操作成功');
        }

    }

    public function getAllBank(){

        $bankDb = new BankDb();

        $bankList = $bankDb->getAllBank();

        return $bankList;
    }


    /**
     * @param $type
     * @return array
     * 所有的限额通道
     */
    public function getPayType($type){


        return [

            'all' => [
                OrderDb::RECHARGE_LLPAY_AUTH_TYPE,       //连连认证充值标记
                OrderDb::RECHARGE_YEEPAY_AUTH_TYPE,      //易宝认证充值标记
                OrderDb::RECHARGE_BFPAY_AUTH_TYPE,      //宝付认证充值标记
                OrderDb::RECHARGE_UCFPAY_AUTH_TYPE,     //先锋认证充值标记
                OrderDb::RECHARGE_SUMAPAY_AUTH_TYPE,    //丰付认证充值标记
                OrderDb::RECHARGE_QDBPAY_WITHHOLD_TYPE,  //钱袋宝认证充值标记
                OrderDb::RECHARGE_UMPPAY_WITHHOLD_TYPE,  //联动优势充值标记
                OrderDb::RECHARGE_REAPAY_WITHHOLD_TYPE,  //融宝支付充值标记
            ],
            'LLAuth'            => OrderDb::RECHARGE_LLPAY_AUTH_TYPE,
            'YeeAuth'           => OrderDb::RECHARGE_YEEPAY_AUTH_TYPE,
            'BFAuth'            => OrderDb::RECHARGE_BFPAY_AUTH_TYPE,
            'UCFAuth'           => OrderDb::RECHARGE_UCFPAY_AUTH_TYPE,
            'SumaAuth'          => OrderDb::RECHARGE_SUMAPAY_AUTH_TYPE,
            'QdbWithholding'    => OrderDb::RECHARGE_QDBPAY_WITHHOLD_TYPE,
            'UmpWithholding'    => OrderDb::RECHARGE_UMPPAY_WITHHOLD_TYPE,
            'ReaWithholding'    => OrderDb::RECHARGE_REAPAY_WITHHOLD_TYPE,
        ];
    }


    /**
     * @return array
     * 非网银支付通道
     */
    public function getPayTypeName(){

        return [
            OrderDb::RECHARGE_LLPAY_AUTH_TYPE   => [
                'name' => '连连支付',       //连连认证充值标记
                'alias' => 'LLAuth'

            ],
            OrderDb::RECHARGE_YEEPAY_AUTH_TYPE  => [
                'name' => '易宝支付',      //易宝认证充值标记
                'alias' => 'YeeAuth',
            ],
            OrderDb::RECHARGE_BFPAY_AUTH_TYPE => [
                'name' => '宝付支付',
                'alias' => 'BFAuth',
            ],
            OrderDb::RECHARGE_UCFPAY_AUTH_TYPE => [
                'name' => '先锋支付',
                'alias' => 'UCFAuth',
            ],
            OrderDb::RECHARGE_SUMAPAY_AUTH_TYPE => [
                'name' => '丰付支付',
                'alias' => 'SumaAuth',
            ],
            OrderDb::RECHARGE_QDBPAY_WITHHOLD_TYPE => [
                'name' => '钱袋宝支付',  //钱袋宝认证充值标记
                'alias' => 'QdbWithholding'
            ],
            OrderDb::RECHARGE_UMPPAY_WITHHOLD_TYPE => [
                'name' => '联动优势支付',  //联动优势充值标记
                'alias' => 'UmpWithholding'
            ],
            OrderDb::RECHARGE_REAPAY_WITHHOLD_TYPE => [
                'name' => '融宝支付',  //融宝支付充值标记
                'alias' => 'ReaWithholding'
            ],

            OrderDb::RECHARGE_REAPAY_WITHHOLD_OTHER => [
            'name'  => '其他通道',  //老系统中未知的充值通道
            'alias' => ''
        ]
        ];
    }

    /**
     * @return array
     * 网银支付通道
     */
    public function getOnlinePayTypeName(){

        return [
            OrderDb::RECHARGE_CBPAY_ONLINE_TYPE => [
                'name' => '京东网银支付',
                'alias' => 'JdOnline'
            ],
            OrderDb::RECHARGE_SUMAAPAY_ONLINE_TYPE => [
                'name'  => '丰付网银支付',
                'alias'  => 'SumaOnline',
            ]
        ];
    }





}
