<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/10/24
 * Time: 13:11
 */

namespace App\Tools;

class ToolVersion{

    /**
     * 手机端版本号判断
     * @param $compare
     * @return int
     */

    public static function compare_version($current, $compare){
        $currentArray = explode('.',$current);
        $compareArray = explode('.',$compare);

        $max = count($currentArray);
        if($max < count($compareArray)){
            $max = count($compareArray);
        }
        for($i=0; $i < $max; $i++){
            if(!isset($currentArray[$i])){
                return -1;
            }
            if(!isset($compareArray[$i])){
                return 1;
            }
            if($currentArray[$i] > $compareArray[$i]){
                return 1;
            }else if($currentArray[$i] < $compareArray[$i]){
                return -1;
            }else{
                continue;
            }
        }
        return 0;
    }

}