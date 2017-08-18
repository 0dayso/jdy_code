<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/31
 * Time: 18:59
 */

namespace App\Listeners\User\ContractFile;

use App\Events\User\CheckContractDownEvent;
use App\Http\Logics\Contract\ContractLogic;
use App\Http\Models\Common\ServiceApi\SmsModel;
use App\Http\Logics\Logic;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
/**
 * 静默方式自动生成保全合同
 * Class CreateContractListeners
 * @package App\Listeners\User\ContractFile
 */
class CheckContractDownUrlListeners  implements ShouldQueue
{
    const MAX_EXE_TIMES = 3;//最大调用次数
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //

    }

    /**
     * Handle the event.
     *
     * @param  CheckContractDownEvent  $event
     * @return void
     */
    public function handle(CheckContractDownEvent $event){

        $data               =   $event->getDataByKey('contract') ;

        $attempts           =   $this->attempts();    //请求的次数

        $limitTime          =   isset($data['limit_time']) && !empty($data['limit_time']) ? $data['limit_time'] : 15 ;

        $leftTime           =   $limitTime - 1 >= 0 ? $limitTime - 1 : '0' ;

        $checkData          =   [
            'apply_no'      =>  $data['apply_no'] ,
            'project_id'    =>  $data['project_id'] ,
            'real_name'     =>  $data['real_name'] ,
            'identity'      =>  $data['identity'] ,
            'title'         =>  $data['title'] ,
            'phone'         =>  $data['phone'],
            'invest_id'     =>  $data['invest_id'] ,
            'email'         =>  isset($data['email']) && !empty($data['email']) ? $data['email'] : '' ,
            'outPutFile'    =>  $data['outPutFile'] ,
            'limit_time'    =>   $leftTime ,
            ];

        if( $attempts >= self::MAX_EXE_TIMES){

            $this->delete() ;

        } else {

            try{

                $contractLogic  =   new ContractLogic() ;

                $return         =   $contractLogic->getDownUrl( $checkData ) ;

                if( $return['status'] == true && !empty($checkData['email']) ) {

                    $this->sendToEmail ($checkData);
                }

            }catch (\Exception $e){

                Log::info(__METHOD__ , ['msg' => $e->getMessage()]);

                // 延迟通知【连续通知1次 和 2次 然后 第3次延迟2分钟、第4次延迟3分钟、依次类推】
                if ($attempts >= 1 && $attempts <= self::MAX_EXE_TIMES) {

                    $delay = $attempts * 10;//5秒钟

                    $this->release($delay);
                }
            }
        }
    }

    /**
     * @desc 发送合同文件到用户邮箱
     */
    protected function sendToEmail($params = array() )
    {
        $contractLogic  =   new ContractLogic();

        $email          =   $params['email'];

        $user           =   ['identity_card' => $params['identity'] , 'real_name' => $params['real_name'] ];

        $contractLogic->doSendContractEmail($email,$user,$params['outPutFile']);
    }

    /**
     * @desc 给用户短信提示
     */
    protected function sendToSms($phone ,$projectId )
    {
        //发短信通知用户
        $msg    =   '【九斗鱼】您投资的项目：' . $projectId . ' ,合同文件已经生成，请登录官网点击下载查看！！谢谢';

        $postData   = [
            'phone' => $phone,
            'msg'   => $msg
        ];
        $return     =    SmsModel::sendNotice($phone,$msg);

        if( $return['code'] == Logic::CODE_ERROR ){

            Log::info('checkContractError',$postData);
        }
    }
}
