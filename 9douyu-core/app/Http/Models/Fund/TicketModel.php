<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/14
 * Time: 11:59
 */

namespace App\Http\Models\Fund;

use App\Http\Dbs\FundTicketDb;
use App\Http\Models\Model;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Lang\LangModel;

class TicketModel extends Model{

    public static $codeArr = [
        'doCreate'                 => 1,
        'checkTicketExist'          => 2

    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_FUND_TICKET;
    
    /**
     * @param $ticketId
     * @param $fundId
     * @throws \Exception
     * 添加检票记录
     */
    public function doCreate($ticketId,$fundId){

        $db = new FundTicketDb();

        $data = [
            'ticket_id' => $ticketId,
            'fund_id'   => $fundId
        ];
        $return  = $db->addRecord($data);

        if(!$return){

            throw new \Exception(LangModel::getLang('ERROR_FUND_TICKET_ADD_FAILED'), self::getFinalCode('doCreate'));
        }

    }


    /**
     * @param $ticketId
     * 根据ticket id获取记录
     */
    public function getByTicketId($ticketId){

        $db  = new FundTicketDb();
        
        $return = $db->getByTicketId($ticketId);

        return $return;

    }

    /**
     * @param $ticketId
     * @throws \Exception
     * @desc 检测票据是否存在
     */
    public function checkTicketExist($ticketId){

        if( empty($ticketId) ){

            throw new \Exception('ticketId不能为空', self::getFinalCode('checkTicketExist'));

        }

        $result = $this->getByTicketId($ticketId);

        if( $result ){

            throw new \Exception($ticketId.'已经存在,请勿重复操作', self::getFinalCode('checkTicketExist'));

        }

        return true;

    }
}