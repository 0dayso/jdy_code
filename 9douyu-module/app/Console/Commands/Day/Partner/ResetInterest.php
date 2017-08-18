<?php
/**
 * Created by PhpStorm.
 * User: gyl
 * Date: 16/7/22
 * Time: 下午6:19
 * Desc: 未邀请合伙人用户利息置0
 */

namespace App\Console\Commands\Day\Partner;

use App\Http\Logics\Partner\PartnerLogic;
use Illuminate\Console\Command;


class ResetInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jdy:day-partner-resetInterest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日凌晨4:00,重置未邀请合伙人用户利息数据.';

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

        $logic->resetInterest();

    }
}
