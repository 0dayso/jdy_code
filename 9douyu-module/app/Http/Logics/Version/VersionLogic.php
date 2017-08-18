<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/6
 * Time: 下午2:58
 */

namespace App\Http\Logics\Version;

use App\Http\Logics\Logic;

use App\Http\Models\SystemConfig\SystemConfigModel;

use Log;
/**
 * 版本logic
 *
 * Class VersionLogic
 * @package App\Http\Logics\Version
 */
class VersionLogic extends Logic
{

    /**
     * 检测版本['检测逻辑移植 老版本2.0']
     *
     * @param null $client
     * @param null $version
     *
     * @return array
     */
    public function checkVersion($client = null, $version = null, $userId = 0){

        $config = SystemConfigModel::getConfig('APP_DOWNLOAD');
        Log::info('checkVersion', ['version' => $version, 'client' => $client]);
        if($client == 'ios'){
            $result = array(
                'version'       => $config['IOS_VERSION'],
                'log'           => str_replace('\\r\\n', "\r\n", $config['IOS_LOG']),
                'forceUpdate'   => $config['IOS_FORCE_UPDATA'],
                'url'           => $config['APPSTORE_URL'],
            );
        } else if($client == 'android'){
            if($version == '2.0.10') {
                $result = array(
                    'version'       => "2.0.10",
                    'log'           => "",
                    'forceUpdate'   => 0,
                    'mustUpdate'    => 0,
                    'url'           => env('WEB_URL_HTTPS') . "/app/dl/jiudouyu_2.0.10.apk",
                );
            } else if($version == '2.0.107' || $version == '2.0.106') {
                $result = array(
                    'version'       => $version,
                    'log'           => "",
                    'forceUpdate'   => 0,
                    'mustUpdate'    => 0,
                    'url'           => env('WEB_URL_HTTPS') . "/app/dl/2.1.101.apk",
                );
            } else {
                $result = array(
                    'version'       => $config['ANDROID_VERSION'],
                    'log'           => str_replace('\\r\\n', "\r\n", $config['ANDROID_LOG']),
                    'forceUpdate'   => $config['ANDROID_FORCE_UPDATA'],
                    'mustUpdate'    => $config['ANDROID_FORCE_UPDATA'],
                    'url'           => env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com') . $config['ANDROID_APK'],
                );
            }
        }

        //APP指定用户更新
        $assignUpgrade = $this->checkAssignUpgrade($client, $version, $userId);

        if(!empty($assignUpgrade)) {
            $result = $assignUpgrade;
        }

        return self::callSuccess($result);
    }

    /**
     * [逻辑移植 老版本2.0']
     * 检测是否指定用户更新
     * @param $currentClient 用户当前设备类型
     * @param $currentVersion 用户当前版本
     * @return array|bool
     */
    protected function checkAssignUpgrade($currentClient = null, $currentVersion = null, $userId = 0) {
        //APP指定用户更新
        //数据格式：升级版本号 => 设备类型|是否强制更新|下载链接|升级日志|用户id串（英文逗号分隔）
        $upgradeInfoArr    = SystemConfigModel::getConfig("APP_ASSIGN_USER_UPGRADE");
        $sortedVersionArr  = $this->sortVersion(array_keys($upgradeInfoArr), 'desc');
        foreach($sortedVersionArr as $upgradeVersion) {
            //升级版本没有高于用户当前版本，直接退出（版本号已倒序，后面的不会有更高版本）
            if(!$this->compareVersion($upgradeVersion, $currentVersion) || $upgradeVersion == $currentVersion) {
                break;
            }
            list($clientType, $forceUpdate, $downloadLink, $upgradeLog, $idString) = explode('|', $upgradeInfoArr[$upgradeVersion]);

            if(strtolower($clientType) != strtolower($currentClient)) continue;    //客户端类型不一致，跳过更新

            if(strpos(",{$idString},", ",{$userId},") !== false) {  //前后补全逗号限定用户id
                $result = array(
                    'version'       => "{$upgradeVersion}",
                    'log'           => str_replace('\\r\\n', "\r\n", $upgradeLog),
                    'forceUpdate'   => $forceUpdate,
                    'mustUpdate'    => $forceUpdate,
                    'url'           => env('ALIYUN_OSS_PUBLIC','http://9douyu.oss-cn-beijing.aliyuncs.com') . $downloadLink,
                );
                return $result;
            }
        }

        return false;
    }

    /**
     * [逻辑移植 老版本2.0']
     * 版本号排序
     * @author txh
     */
    function sortVersion($versionArr, $sort = "asc") {
        for($i=0;$i<count($versionArr)-1;$i++ ){
            for($j=0; $j<count($versionArr)-1-$i; $j++){
                if( $this->compareVersion($versionArr[$j],$versionArr[$j+1])){
                    $tmp                = $versionArr[$j];
                    $versionArr[$j]     = $versionArr[$j+1];
                    $versionArr[$j+1]   = $tmp;
                }
            }
        }
        if($sort == "desc") $versionArr = array_reverse($versionArr);
        return $versionArr;
    }

    /**
     * [逻辑移植 老版本2.0']
     * 比较两个版本大小
     * @param $oldVersion  比较版本
     * @param $newVersion  参照版本
     * @return boolean
     */
    public function compareVersion($oldVersion, $newVersion){
        $oldArr = explode(".", $oldVersion);
        $newArr = explode(".", $newVersion);
        $length = count($newArr);
        for($i = 0; $i < $length; $i++) {
            if($oldArr[$i] < $newArr[$i]) {
                return false;
            } else if($oldArr[$i] > $newArr[$i]) {
                return true;
            }
        }
        return true;
    }
}