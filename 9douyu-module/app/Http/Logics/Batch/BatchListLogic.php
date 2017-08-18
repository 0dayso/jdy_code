<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/18
 * Time: 下午1:52
 * Desc: 推送列表
 */

namespace App\Http\Logics\Batch;

use App\Http\Dbs\Batch\BatchListDb;
use App\Http\Logics\Bonus\UserBonusLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Project\RefundRecordLogic;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Common\ServiceApi\SmsModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use Log;
use Config;
use App\Http\Logics\Oss\OssLogic;

class BatchListLogic extends Logic
{

    /**
     * @param $data
     * @return array
     * @desc 执行添加
     */
    public function doAdd($data)
    {

        if( empty($data) || empty($data['type']) || empty($data['admin_id']) || empty($data['file_path']) || empty($data['note']) || empty($data['content'])){

            return self::callError('信息不完整');

        }

        $typeArr = BatchListDb::getTypeArr();

        if( !in_array($data['type'], $typeArr) ){

            return self::callError('未知操作类型');

        }

        $db = new BatchListDb();

        $res = $db->doAdd($data);

        if( $res ){

            return self::callSuccess($res);

        }else{

            return self::callError('添加数据失败');

        }

    }

    /**
     * @param $type
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 通过type分页获取列表
     */
    public function getListByType($type, $page=1, $size=1000)
    {

        $db = new BatchListDb();

        $typeArr = BatchListDb::getTypeArr();

        if( $type && in_array($type, $typeArr) ){

            return $db->getListByType($type, $page, $size);

        }

        return [];

    }

    /**
     * @param $id
     * @return mixed
     * @desc 标记为审核通过
     */
    public function doAuditById($id)
    {

        $db = new BatchListDb();

        $res = $db->doUpdateStatus($id, BatchListDb::STATUS_AUDIT);

        if( $res ){

            \Event::fire('App\Events\Batch\AuditSuccessEvent', [$id]);

            return $res;

        }

        return false;

    }

    /**
     * @param $id
     * @return mixed
     * @desc 标记为成功
     */
    public function doSuccessById($id)
    {

        $db = new BatchListDb();

        return $db->doUpdateStatus($id, BatchListDb::STATUS_SUCCESS);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 删除
     */
    public function doDelById($id)
    {

        $db = new BatchListDb();

        return $db->doDelById($id);

    }

    /**
     * @param $id
     * @desc 拆分
     */
    public function doBatch($id)
    {

        $db = new BatchListDb();

        $list = $db->getInfoById($id);

        if( isset($list['status']) && $list['status'] == BatchListDb::STATUS_AUDIT ){

            switch ($list['type'])
            {
                case BatchListDb::TYPE_PHONE:

                    $this->sendSMS($list);

                    break;

                case BatchListDb::TYPE_BONUS:

                    $this->sendBonus($list);

                    break;

            }

            $this->doSuccessById($id);

        }

        return false;

    }

    /**
     * @param array $list
     * @return bool
     * @desc 发送短信
     */
    private function sendSMS($list=[])
    {

        if( empty($list) ){

            return false;

        }

        $fileArr = $this->getFileArr($list['file_path']);

        //@todo 需要优化前后文字
        $msg = '【九斗鱼】'.$list['content'].'回复TD退订';

        if( empty($fileArr) ){

            return false;

        }

        foreach ($fileArr as $item){

            $result = SmsModel::sendMarket($item, $msg);

            if( isset($result['status']) && $result['status'] ){

                \Log::Error('doSplitToJobSendMarketError', ['phones' => $item, 'msg' => $msg, 'errorMsg' => $result['msg']]);

            }

        }

    }

    /**
     * @param array $list
     * @return bool
     * @desc 批量发送红包加息券
     */
    private function sendBonus($list=[])
    {

        if( empty($list) ){

            return false;

        }

        $fileArr = $this->getFileArr($list['file_path']);

        if( empty($fileArr) ){

            return false;

        }

        $userBonus = new UserBonusLogic();

        foreach ($fileArr as $item){

            $result = $userBonus->adminBatchSendBonusByUserIds(trim($list['content']), $item);

            if( !$result['status'] ){

                \Log::Error(__METHOD__."sendBonusError",[$result['msg']]);

                $receiveEmails = Config::get('email.monitor.accessToken');

                $title = '【Warning】发送红包加息券失败';

                $msg = '红包ID:'.trim($list['content']).'; 失败原因:'.$result['msg'];

                $model = new EmailModel();

                $model->sendHtmlEmail($receiveEmails, $title, $msg);
            }

        }


    }

    /**
     * @param $filePath
     * @return array|bool
     * @desc 获取文件内容
     */
    private function getFileArr($filePath)
    {

        $fileStr = @file_get_contents(env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com').$filePath);

        if( !empty($fileStr) ){

            $fileArr = explode("\n", $fileStr);

            //过滤数组中的空格
            if(!empty($fileArr)){
                foreach($fileArr as $key=>$value){
                    $fileArr[$key] = trim($value);
                }
            }

            $fileArr = array_chunk($fileArr, 1000);

            return $fileArr;

        }

        return false;

    }

    /**
     * @desc 添加发送回款用户的提醒短信
     * @return array|bool
     */
    public function sendRefundBonusSms(){

        $refundRecordLogic = new RefundRecordLogic();

        $todayRefundUser = $refundRecordLogic->getTodayRefundUser();
        if (empty($todayRefundUser)) {

            return false;
        }
        $userIdArr = ToolArray::arrayToIds($todayRefundUser, 'phone');

        Log::info(__CLASS__.__METHOD__."当日回款用户phone:",$userIdArr);

        //组装当日生日的用户id
        $userIdStr = ToolArray::arrayToStr($userIdArr,"\n");

        //写入的文件路径
        $filePath = '/uploads/' . ToolTime::dbDate() . '/' . strtolower(ToolStr::getRandStr(13)) . ".txt";
        //不存在目录时创建目录
        //$dirPath = base_path() . '/public/uploads/'.ToolTime::dbDate();
        //if (!is_dir($dirPath)) @mkdir($dirPath);
        //写入文件
       // @file_put_contents(base_path() . '/public/' . $filePath, $userIdStr);

        //改为Oss上传处理
        $oss = new OssLogic();
        $oss->writeFile( $userIdStr , $filePath );

        $data = [
            'type' => 'phone',
            'admin_id' => 1, //todo 计划任务
            'content' => SystemConfigModel::getConfig('USER_REFUND_SMS_CONTENT'),
            'note' => LangModel::USER_REFUND_SMS.ToolTime::dbDate(),
            'file_path' => $filePath,
        ];

        try{
            $res = $this->doAdd($data);
            if($res['status']){
                $this->doAuditById($res['data']);
            }
        }catch(\Exception $e){
            Log::info(__CLASS__.__METHOD__.__LINE__."sendRefundBonusSms", $e->getMessage());
            return self::callError($e->getMessage());
        }
        return self::callSuccess();

    }

}
