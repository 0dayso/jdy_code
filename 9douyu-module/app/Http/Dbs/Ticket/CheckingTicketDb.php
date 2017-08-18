<?php
namespace App\Http\Dbs\Ticket;

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/9/12
 * Time: 下午2:44
 */
use App\Http\Dbs\JdyDb;

/**
 * 检票
 * Class CheckingTicketDb
 */
class CheckingTicketDb extends JdyDb
{

    const
        FROM_CODE_YMF = 100, //一码付


        END = true;

    protected $table = "ticket_checking";


    public $timestamps = false;
}