<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/6
 * Time: 下午8:57
 */

namespace App\Http\Models\Ticket;

use App\Http\Dbs\Ticket\CheckingTicketDb;

use App\Http\Models\Model;

use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use Log;

/**
 * 检票 model
 * Class CheckingTicket
 * @package App\Http\Models\Ticket
 */
class CheckingTicketModel extends Model
{
    /**
     * 保存票据
     *
     * @param array $param
     * @return bool
     */
    public static function save($param = []){
        $ticketObj = new CheckingTicketDb($param, array_keys($param));
        return $ticketObj->save();
    }
}