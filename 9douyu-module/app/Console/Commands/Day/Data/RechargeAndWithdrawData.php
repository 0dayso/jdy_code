<?php

namespace App\Console\Commands\Day\Data;

use Illuminate\Console\Command;
use App\Http\Logics\Data\RechargeAndWithdrawDayDataLogic;

class RechargeAndWithdrawData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendRechargeWithdrawDayDataEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天12点以后,每隔3小时发送当天充值及提现金额数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $rechargeWithdrawLogic  =  new RechargeAndWithdrawDayDataLogic();

        $rechargeWithdrawLogic->sendRechargeWithdrawData();
    }
}
