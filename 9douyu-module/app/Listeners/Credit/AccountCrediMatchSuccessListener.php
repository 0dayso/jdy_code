<?php

namespace App\Listeners\Credit;

use App\Events\Credit\AccountCreditMatchEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Logics\Credit\CreditDisperseLogic;

class AccountCrediMatchSuccessListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccountCreditMatchEvent  $event
     * @return void
     */
    public function handle(AccountCreditMatchEvent $event)
    {

        $creditDisperseLogic = new CreditDisperseLogic();

        $account = $event->getUserAccount();

        if( !empty( $account ))
        {
            $cutAccounts = array_chunk( $account, 500 );

            foreach( $cutAccounts as $key => $accounts )
            {
                $investResult  =  $creditDisperseLogic->doAccountCreditMatch( $accounts );
            }

            $creditDisperseLogic->formatMatchInvestData( $investResult );

        }
    }
}
