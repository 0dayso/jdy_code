<?php

namespace App\Console\Commands\Day\User;

use Illuminate\Console\Command;

use App\Http\Logics\User\TokenLogic;

class DeleteExpireRecord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jdy:day-user-DeleteExpireRecord';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天凌晨两点删除过期token.';

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
        TokenLogic::deleteExpire();
    }
}
