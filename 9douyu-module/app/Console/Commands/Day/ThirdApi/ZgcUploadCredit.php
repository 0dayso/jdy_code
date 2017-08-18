<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/10/14
 * Time: 11:39
 */

namespace App\Console\Commands\Day\ThirdApi;

use App\Http\Logics\ThirdApi\ZgcLogic;
use Illuminate\Console\Command;

class ZgcUploadCredit extends Command{

    //计划任务唯一标识
    protected $signature = 'UploadCreditZgc';

    //计划任务描述
    protected $description = '每天下午18:00上传债权数据到中关村协会共享数据平台';

    public function handle(){

        $zgcLogic = new ZgcLogic();

        $zgcLogic->creditDataUploads();
        //$zgcLogic->saveDataToFile('{"resultList":[{"message":"上传成功","result":"00","jnlNo":"20161111_5185183050"}]}');
    }
}