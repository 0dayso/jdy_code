<?php

namespace App\Console\Commands\Day\Credit;

use Illuminate\Console\Command;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Logics\Credit\CreditDisperseLogic;
use Log;
use Cache;
use Redis;

class DoDisperseCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doDisperseCredit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天00:30对新活期账户进行分散债权匹配';

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
        $creditDisperseLogic  =  new CreditDisperseLogic();

        $creditDisperseLogic->doCreditMatchData();
    }
}
