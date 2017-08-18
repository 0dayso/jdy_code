<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/9/27
 * Time: 下午2:46
 * Desc: 还款公告
 */

namespace App\Console\Commands\Day\Article;

use App\Http\Logics\Article\ArticleLogic;
use App\Tools\ToolTime;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class RefundNotice extends Command
{

    //计划任务唯一标识
    protected $signature = 'jdy:RefundArticleNotice {times?}';

    //计划任务描述
    protected $description = '每天上午5:00生成当日项目回款公告';

    /**
     *
     * Handle the event.
     * @param  $event
     * @throws \Exception
     */
    public function handle(){

        $articleLogic = new ArticleLogic();

        $times = $this->argument('times') ? $this->argument('times') : ToolTime::dbDate();
        
        $articleLogic->doAddRefundSuccessNotice($times);

    }

}