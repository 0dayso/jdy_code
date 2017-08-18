<?php
/**
 * Created by PhpStorm.
 * User: gyl
 * Date: 16/7/22
 * Time: 下午6:19
 * Desc: 合伙人佣金收益
 */

namespace App\Console\Commands\Day\Partner;

use App\Http\Logics\Partner\PartnerLogic;
use Illuminate\Console\Command;


class BackInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jdy:day-partner-BackInterest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日凌晨5点,合伙人佣金收益计息拆分.';

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

        $logic = new PartnerLogic();

        $logic->splitPartner();

    }
}
