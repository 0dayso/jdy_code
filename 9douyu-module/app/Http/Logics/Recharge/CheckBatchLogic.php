<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/8
 * Time: 上午11:00
 */

namespace App\Http\Logics\Recharge;


use App\Http\Dbs\Order\CheckBatchDb;
use App\Http\Logics\Logic;
use App\Http\Logics\Oss\OssLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Models\Order\CheckBatchModel;
use App\Tools\AdminUser;
use App\Tools\FileUpload;
use Log;
use Event;

class CheckBatchLogic extends Logic
{


    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 对账的文件列表
     */
    public function getList( $page , $size )
    {
        $db         =   new CheckBatchDb();

        return $db->getList($page,$size);
    }

    /**
     * @param $id
     * @return array
     * @desc 查询当状态
     */
    public function getAdoptBatchById( $id )
    {
        $db         =   new CheckBatchDb();

        return  $db->getAdoptBatch($id);
    }
    /**
     * @param $data
     * @return array
     * desc 添加数据
     */
    public function doAdd( $data )
    {
        $model      =   new CheckBatchModel();

        $fileInfo = $_FILES['file'];

        self::beginTransaction();

        try{

            $model->checkBillsFile($fileInfo);

            $ossLogic = new OssLogic();

            $result = $ossLogic->putFile($fileInfo,'uploads/billsFile');

            if(!$result['status']){
                return self::callError('文件上传失败');
            }

            $data['name']   =   $fileInfo['name'];

            $data           =   self::filterParams($data);

            $data['file_path']= '/'.$result['data']['path'].'/'.$result['data']['name'];

            $model->doAdd($data);

            self::commit();

        }catch(\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());
        }

        return self::callSuccess();
    }

    /**
     * @param $files
     * @return array
     * @desc 上次文件
     */
    protected static function doUpload( $files )
    {
        $fileUpload   = new FileUpload();

        return $fileUpload->saveFile($files);
    }

    /**
     * @param $data
     * @return array
     * @desc 更新数据
     */
    public function doEdit( $id, $data = '' )
    {
        $model      =   new CheckBatchModel();

        self::beginTransaction();

        try{

            $data   =   self::filterParams($data);

            $model->doEdit($id,$data);

            self::commit();

        } catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

        //创建成功 [记录附加信息、邀请关系、活动]
        Event::fire(new \App\Events\Pay\RechargeBatchEvent(
            ['batch_id' => $id]
        ));

        return self::callSuccess();
    }

    /**
     * @param $id
     * @return array
     * @desc 删除记录
     */
    public function doDelete( $id )
    {
        $model      =   new CheckBatchModel();

        self::beginTransaction();

        try{

            $model->doValidation($id);

            $model->doDelete($id);

            self::commit();

        } catch (\Exception $e){

            self::rollback();

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess();
    }
    /**
     * @param $params
     * @return array
     * @desc 格式化数据
     */
    protected static function filterParams( $params )
    {
        $attributes = [
            'status'    =>  isset($params['status']) ? $params['status'] : CheckBatchDb::STATUS_PENDING,
            'admin_id'  =>  AdminUser::getAdminUserId(),
        ];
        //充值通道类型
        if( isset($params['pay_channel']) && !empty($params['pay_channel'])) {

            $attributes['pay_channel'] =   $params['pay_channel'];
        }

        //备注
        if( isset($params['note']) ){

            $attributes['note']      =  $params['note'];

            if( empty($params['note']) ){

                $attributes['note']   =  $params['name'];
            }
        }

        //文件名
        if( !empty($params['name']) ){

            $attributes['name']      =  $params['name'];
        }

//        //存储路径
//        if(!empty ($uploads )){
//
//            $attributes['file_path'] =  $uploads;
//        }
        return $attributes;
    }

    /**
     * @return array
     * @desc 对账的通道类型
     */
    public static function getRechargeType()
    {
        $configList     =   self::setFormatFileConfig();

        if( empty($configList) ){

            $db         =   new CheckBatchDb();

            return $db->setRechargeType();
        }

        $rechargeType   =   [];

        foreach ($configList as $key => $item ){

            $rechargeType[$key]  =  $item['name'];
        }

        return $rechargeType;
    }

    /**
     * @return array
     * @desc 对账的状态
     */
    public static function getReviewStatus()
    {
        $db     =   new CheckBatchDb();

        return $db->serReviewStatus();
    }
    /**
     * @return array
     * @desc 设置文件的类型和名称
     */
    protected static function setFileInfo( )
    {
        $fileName   =   $_FILES['file']['name'];

        $fileInfo   =   pathinfo($fileName);

        return ['name'=>$fileInfo['filename'],'type'=>$fileInfo['extension']];
    }

    /**
     * 7种3方支付配置参数 order_id：订单号所在列序号，cash：金额所在列序号，type，time...
     * 支持金额相加'cash' => '5,7'
     * @return array
     *  RECHARGE_CBPAY_TYPE         = 1000, //网银在线充值标记
        RECHARGE_QDBPAY_AUTH_TYPE   = 1201, //钱袋宝代扣充值标记
        RECHARGE_YEEPAY_AUTH_TYPE   = 1102, //易宝认证充值标记
        RECHARGE_LLPAY_AUTH_TYPE    = 1101, //连连认证充值标记
        RECHARGE_UMP_AUTH_TYPE      = 1202, //联动优势充值标记
        RECHARGE_REAPAY_AUTH_TYPE   = 1204, //融宝支付充值标记
        RECHARGE_BEST_AUTH_TYPE     = 1203  //翼支付充值标记
     */
    public function setPayChannelConfig()
    {
        return [
                CheckBatchDb::RECHARGE_CBPAY_TYPE => [
                    'name'      => '网银在线',
                    'start'     => '7',
                    'order_id'  => '0',
                    'cash'      => '1',
                    'time'      => '7',
                    'sec_time'  => '8' ,
                    'type'      => CheckBatchDb::RECHARGE_CBPAY_TYPE
                ],
                CheckBatchDb::RECHARGE_QDBPAY_AUTH_TYPE => [
                    'name'      => '钱袋宝',
                    'start'     => '1',
                    'order_id'  => '0',
                    'cash'      => '1',
                    'time'      => '5',
                    'type'      => CheckBatchDb::RECHARGE_QDBPAY_AUTH_TYPE
                ],
                CheckBatchDb::RECHARGE_YEEPAY_AUTH_TYPE => [
                    'name'      => '易宝',
                    'start'     => '3',
                    'order_id'  => '4',
                    'cash'      => '5,7',
                    'time'      => '1',
                    'type'      => CheckBatchDb::RECHARGE_YEEPAY_AUTH_TYPE
                ],
                CheckBatchDb::RECHARGE_LLPAY_AUTH_TYPE => [
                    'name'      => '连连支付',
                    'start'     => '1',
                    'order_id'  => '1',
                    'cash'      => '5',
                    'time'      => '3',
                    'type'      => CheckBatchDb::RECHARGE_LLPAY_AUTH_TYPE
                ],
                CheckBatchDb::RECHARGE_UMP_AUTH_TYPE => [
                    'name'      => '联动优势',
                    'start'     => '1',
                    'order_id'  => '0',
                    'cash'      => '6',
                    'time'      => '2',
                    'type'      => CheckBatchDb::RECHARGE_UMP_AUTH_TYPE
                ],
                CheckBatchDb::RECHARGE_REAPAY_AUTH_TYPE => [
                    'name'      => '融宝',
                    'start'     => '2',
                    'order_id'  => '0',
                    'cash'      => '3',
                    'time'      => '6',
                    'type'      => CheckBatchDb::RECHARGE_REAPAY_AUTH_TYPE
                ],
                CheckBatchDb::RECHARGE_BEST_AUTH_TYPE => [
                    'name'      => '翼支付',
                    'start'     => '7',
                    'order_id'  => '3',
                    'cash'      => '10',
                    'time'      => '12',
                    'type'      => CheckBatchDb::RECHARGE_BEST_AUTH_TYPE
                ],
            ];
    }

    /**
     * @return array
     * @desc 格式化数据
     */
    public static function setFormatFileConfig()
    {
        $config         =   self::setRechargeCheckConfig();

        $formatConfig   =   [];

        foreach ($config as $id =>  $item ){

            $formatConfig[$id]  =   self::doFormatCheckConfig($item);
        }

        return $formatConfig;
    }

    /**
     * @param string $payChannelStr
     * @return array
     * @desc  解析数值
     */
    protected static function doFormatCheckConfig( $payChannelStr = '')
    {

        if( empty($payChannelStr)) return [];

        $payChannelArr   =   explode("|",$payChannelStr);

        if( empty($payChannelArr) ) return [];

        $returnConfig   =   [];

        foreach ($payChannelArr as $key =>  $item ){

            $config     =   explode("=",$item);

            $returnConfig[$config[0]]=$config[1];
        }

        return $returnConfig;

    }

    /**
     * @return array|mixed
     * @desc 获取对账文件的格式配置文件
     */
    protected static function setRechargeCheckConfig()
    {
        return SystemConfigLogic::getConfig('RECHARGE_CHECK_ORDER_CONFIG');

    }
}
