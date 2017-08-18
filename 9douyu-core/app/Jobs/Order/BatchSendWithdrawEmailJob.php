<?php
/**
 * User: zhangshuang
 * Date: 16/4/21
 * Time: 18:10
 * Desc: 提现短信批量发送
 */
namespace App\Jobs\Order;

use App\Http\Dbs\WithdrawRecordDb;
use App\Jobs\Job;
use App\Http\Logics\Order\OperateLogic;

class BatchSendWithdrawEmailJob extends Job{

    protected  $data = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $db = new WithdrawRecordDb();
        
        $record = $db->getRecord($this->data['id']);
        if($record){
            
            $logic = new OperateLogic();
            $payChannel = isset($this->data['payChannel'])?$this->data['payChannel']:"";
            if( $payChannel == "suma" || $payChannel == "ucf" ){
                $logic->sendWithdrawEmailNew($record['start_time'],$record['end_time'],[$this->data['email'] => 'admin'],$payChannel);
            }else{
                $logic->sendWithdrawEmail($record['start_time'],$record['end_time'],[$this->data['email'] => 'admin']);
            }

        }
       

    }
}