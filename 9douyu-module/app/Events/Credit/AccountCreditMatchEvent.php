<?php
/**
 * Created by PhpStorm.
 * User: linguanghui
 * Date: 17/04/01
 * Time: 19:45
 * Desc: 用户债权数据数据匹配
 */

namespace App\Events\Credit;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Dbs\CurrentNew\UserCurrentNewFundHistoryDb;
use App\Tools\ToolArray;
use Log;

class AccountCreditMatchEvent extends Event
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $data = [] )
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    /**
     * @desc 获取要匹配的债权
     * @return array
     */
    public function getUserAccount()
    {

        $userCurrentNewDb = new UserCurrentNewFundHistoryDb();

        $userIds = ToolArray::arrayToIds( $userCurrentNewDb->getAllUserIds(), 'user_id' );

        $userAccounts = $userCurrentNewDb->getUsersAmountByUserIds( $userIds );

        Log::info( '债权匹配的用户数据', $userAccounts );

        return $userAccounts;
    }
}
