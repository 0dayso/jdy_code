<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/31
 * Time: 18:59
 */

namespace App\Listeners\User\ContractFile;


use App\Events\User\CreateContractFileEvent;
use App\Http\Dbs\Contract\ContractDb;
use App\Http\Logics\Contract\ContractLogic;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
/**
 * 静默方式自动生成保全合同
 * Class CreateContractListeners
 * @package App\Listeners\User\ContractFile
 */
class CreateContractListeners  implements ShouldQueue
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
     * @param  RegisterSuccessEvent  $event
     * @return void
     */
    public function handle(CreateContractFileEvent $event){

        $data       =   $event->data ;

        $params     =   [
            'invest_id'     =>  $data['invest_id'] ,
            'user_id'       =>  $data['user_id'] ,
            'projectId'     =>  $data['project_id'] ,
            'cash'          =>  $data['cash']  ,
        ];

        $attempts           = $this->attempts();    //请求的次数

        if( $attempts >= self::MAX_EXE_TIMES){

            $this->delete() ;

        } else{
            $contractDb     =   new ContractDb();

            $contractStatus =  $contractDb->getByInvestId($data['invest_id']);

            if( $contractStatus ) {

                $this->delete() ;
            }

            try {

                $contractLogic  =   new ContractLogic() ;

                $contractLogic->doBuildContract( $params );

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
}
