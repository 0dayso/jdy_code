<?php

namespace App\Http\Dbs;


class FundTicketDb extends JdyDb
{

    //表名
    protected $table = 'fund_ticket';

    /**
     * @param $userId
     * @return mixed
     * 根据ticket ID获取绑卡信息
     */
    public function getByTicketId($ticketId)
    {

        return self::where('ticket_id', $ticketId)->first();

    }



    /**
     * @param $cardNo
     * @return mixed
     * 添加数据
     */
    public function addRecord($data)
    {

        return self::insert($data);
    }
}

