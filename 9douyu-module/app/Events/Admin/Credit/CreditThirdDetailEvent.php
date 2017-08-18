<?php
/**
 * Created by PhpStorm.
 * User: lgh－dev
 * Date: 16/11/15
 * Time: 11:25
 * Desc: 创建第三方债权人详情
 */

namespace App\Events\Admin\Credit;

use \App\Events\Event;
use App\Http\Dbs\Credit\CreditDb;
use Log;

class CreditThirdDetailEvent extends Event
{
    public $data = [];


    public function __construct($data = [])
    {
        $this->data = $data['data'];
        Log::info("create_credit_third_detail: ".json_encode($this->data));
    }

    /**
     * @desc 获取第三方债权的债权id
     * @return int
     */
    public function getCreditThirdId(){

        if(!empty($this->data['credit_id'])) {
            return $this->data['credit_id'];
        }
        return 0;
    }

    /**
     * @desc 获取第三方债权人详情的数据
     * @return array|mixed
     */
    public function getThreeCreditList(){

        if(!empty($this->data['credit_list'])){
            return json_decode($this->data['credit_list']);
        }
        return [];
    }

    /**
     * @desc 格式化要插入的债权人的数据
     * @return array
     */
    public function formatInsertData(){
        $insertData =[];

        $threeCreditData = $this->getThreeCreditList();

        $thirdCreditId = $this->getCreditThirdId();

        if(!empty($threeCreditData)){
            foreach($threeCreditData as $key=>$val){

                $insertData[$key] =[
                    'credit_third_id' => $thirdCreditId,
                    'name'            => $val->realname,
                    'id_card'         => $val->identity_card,
                    'amount'          => $val->amount,
                    'usable_amount'   => $val->amount,
                    'loan_time'       => $val->time,
                    'refund_time'     => $val->refund_time,
                    'status'          => CreditDb::STATUS_CODE_UNUSED,
                ];

            }
        }
        return $insertData;
    }
}