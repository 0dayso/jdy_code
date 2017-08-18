<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/11/14
 * Time: 19:12
 * desc: 添加第三方债权一月期成功执行事件
 */
namespace App\Listeners\Admin\Credit;



use App\Events\Admin\Credit\CreditThirdDetailEvent;
use App\Http\Logics\Credit\CreditThirdDetailLogic;

class AddCreditThirdSuccessListener
{

    /**
     * AddCreditThirdSuccessListener constructor.
     */
    public function __construct()
    {
        //
    }

    public function handle(CreditThirdDetailEvent $event){

        //清除之前的关联的数据
        $creditId = $event->getCreditThirdId();
        CreditThirdDetailLogic::delDetailByCreditId( $creditId );

        $thirdDetailData = $event->formatInsertData();
        //如果数据不为空执行插入操作
        if(!empty($thirdDetailData)){
            //拆分数据
            $chunkArray = array_chunk($thirdDetailData,100);

            foreach($chunkArray as $key=>$value){
                $return = CreditThirdDetailLogic::doCreateDetail($value);
            }
        }
        return $return;
    }

}
