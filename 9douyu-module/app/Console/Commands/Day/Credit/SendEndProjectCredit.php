<?php

namespace App\Console\Commands\Day\Credit;

use App\Http\Logics\Data\CreditListOutLogic;
use App\Tools\ToolTime;
use Illuminate\Console\Command;

class SendEndProjectCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendEndProjectCredit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天5:00发送今日到期项目的债权信息';

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

        $creditListLogic = new CreditListOutLogic();

        //项目相关的sql条件
        $data = [
            'start_time'        => ToolTime::dbDate(),
            'end_time'          => ToolTime::dbDate(),
            'is_before'         => 0,
        ];

        $creditData  =  $creditListLogic->getOutCreditData( '', $data );

        $title = '当天到期项目的的债权信息';

        $creditListLogic->sendCreditEmailData( $creditData, $title );

    }
}
