<?php

/**
 * Created by PhpStorm.
 * User: jinzhuotao
 * Date: 2016/12/11
 * Time: 下午2:26
 */

namespace App\Console\Commands\OssFile;

use App\Http\Logics\Oss\OssLogic;
use Illuminate\Console\Command;

class OssFileImport extends Command
{

    //计划任务唯一标识

    protected $signature = 'OssFileImport:send {localDirectory} {prefix?} {bucket=oss_1}';

    //计划任务描述

    protected $description = '提交文件目录到Oss';

    public function handle(){

        //要上传的文件目录

        $localDirectory = $this->argument('localDirectory');
        $prefix = $this->argument('prefix');
        $bucket = $this->argument('bucket');
        $localDirectory   = trim($localDirectory, '\\/');
//        $this->getDir($localDirectory,$bucket,$prefix);
        $this->uploadDir($localDirectory,$bucket,$prefix);

    }

//    private function getDir($localDirectory,$bucket,$prefix=''){
//
//        $ossLogic = new OssLogic($bucket);
//        $array=scandir($localDirectory);
//        foreach ($array as $val){
//            $dir = $localDirectory."/".$val;
//            if($val!="." && $val!=".." && $val!='.svn' && $val!='.git' && $val!='uploads' && is_dir($dir)){
//                if($prefix){
//                    $prefixOss = $prefix.'/'.$val;
//                }else{
//                    $prefixOss = substr($dir,strpos($dir,'/')+1);
//                }
//                $result = $ossLogic->uploadDir($dir,$prefixOss);
//                if($result['status'] === false){
//                    echo $result['msg']."\n";
//                }else{
//                    echo $dir."  上传成功\n";
//                }
//                $this->getDir($dir,$bucket,$prefixOss);
//            }
//        }
//
//    }

    private function uploadDir($localDirectory,$bucket,$prefix=''){
        $ossLogic = new OssLogic($bucket);
        if($prefix == ''){
            $prefix = $localDirectory;
        }
        $result = $ossLogic->uploadDir($localDirectory,$prefix);

        if($result['status']){
            echo $localDirectory."  上传成功\n";
        }else{
            echo "上传失败: ".$result['msg']."\n";
        }

    }


}