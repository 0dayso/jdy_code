<?php
/**
 * Created by PhpStorm.
 * User: lgh189491
 * Date: 16/12/9
 * Time: 17:57
 */

namespace App\Http\Logics\OutCall;
use App\Http\Logics\Logic;
use App\Http\Logics\User\UserLogic;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Excel;

class OutCallLogic extends Logic
{

    /**
     * @desc 获取组装后的数据
     * @param $request
     * @return array|bool
     */
    public function getOutCallData($request){

        $phones = [];
        $ouCallData = [];
        $userLogic = new UserLogic();

        //获取要上传的文件
        $file  = $request->file('outCall');
        //临时文件的绝对路径
        $realPath = $file->getRealPath();

        //获取临时文件的内容
        $reader = Excel::load($realPath,'GB2312');
        $records = $reader->getSheet(0)->toArray();

        //获取手机集合
        foreach($records as $key=>$value){
            $phones[] = (int)$value[1];
        }
        $userInfo = ToolArray::arrayToKey($userLogic->getUserByPhones($phones),'phone');

        foreach($records as $key=>$value){
            $ouCallData[$key] = $value;
            $phone = (int)$value[1];
            if(isset($userInfo[$phone])){
                $ouCallData[$key][7] = $userInfo[$phone]['id'];
                if(!empty($userInfo[$phone]['identity_card'])){
                    $ouCallData[$key][8] = $userInfo[$phone]['real_name'];
                    $subBirthday = substr($userInfo[$phone]['identity_card'], (strlen($userInfo[$phone]['identity_card']) == 18 ? 6 : 4),8);
                    $birthYmd = substr($subBirthday, 0, 4)."-".substr($subBirthday, 4, 2)."-".substr($subBirthday, 6, 2);
                    $ouCallData[$key][9]      =  (substr($userInfo[$phone]['identity_card'], (strlen($userInfo[$phone]['identity_card']) == 15 ? -1 : -2), 1) % 2) ? '男' : '女';
                    $ouCallData[$key][10] = ToolTime::getDateDiff($birthYmd)['y'];
                }
            }
        }
        return $ouCallData;
    }

}