<?php
/**
 * Created by PhpStorm.
 * User: lgh－dev
 * Date: 16/11/15
 * Time: 10:35
 * Desc: 第三方债权人详情Logic
 */

namespace App\Http\Logics\Credit;

use App\Http\Dbs\Credit\CreditThirdDetailDb;
use App\Http\Logics\Oss\OssLogic;
use App\Http\Models\Credit\CreditThirdDetailModel;
use App\Tools\ToolArray;
use Log;

class CreditThirdDetailLogic extends CreditLogic{

    /**
     * @desc 添加第三方债权人详情
     * @param array $data
     * @return array
     */
    public static function doCreateDetail($data = []){
        $attributes = [];

        if(!empty($data)){
            $attributes = $data;
        }

        try{
            $return = CreditThirdDetailModel::doCreateDetail($attributes);
        }catch(\Exception $e){
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }
        return self::callSuccess($return);
    }

    /**
     * @desc 获取可使用的债权信息
     * @param $creditId
     * @return array
     */
    public function getAbleCreditDetailList($creditId){

        try{
            $return = CreditThirdDetailModel::getAbleCreditDetailList($creditId);
        }catch(\Exception $e){
            $attributes['data']           = [];
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError($e->getMessage());
        }
        return self::callSuccess($return);
    }

    /**
     * @desc 通过多个ID获取基本信息
     * @param $creditIds
     * @return mixed
     */
    public static function getCreditListByIds($creditIds){

        $creditDetailDb = new CreditThirdDetailDb();

        $return = $creditDetailDb->getListByIds($creditIds);

        return $return;
    }
    /**
     * @desc 批量更新分散投资后组装的债权数据
     * @param $creditArr
     * @return array
     */
    public static function updateCreditDiversInvest($creditArr){

        $creditResult = [];
        //获取更新的债权信息组装数组
        foreach($creditArr as $key =>$val){
            if(isset($val['invest'])){
                $creditResult[$key]['id'] = $val['id'];
                $creditResult[$key]['usable_amount'] = $val['usable_amount'];
                isset($val['status']) ? ($creditResult[$key]['status'] = 200) : "";
                $return  = CreditThirdDetailDb::doUpdate($val['id'],$creditResult[$key]);
            }
        }

        return self::callSuccess($return);
    }

    /**
     * @desc 获取第三方债权匹配结果
     * @param $amount
     * @param $creditId
     * @param $userId
     * @param $investId
     * @return array
     */
    public static function getThirdCreditMatch($amount, $creditId,$userId,$investId){
        $matchResult = [];
        $fileName = 'thirdCredit_'.$userId.'_'.$investId.'.txt';
        $dirName = '/uploads/thirdCredit/';
        $oss = new OssLogic();
        $exit = $oss->checkPathExit($dirName.$fileName);
        //文件是否存在，存在直接读取文件的内容，否则计算分配算法返回结果
        if($exit){
            $fileContent = $oss->getObject($dirName.$fileName);
            $matchResult  = json_decode($fileContent, true);
        }else {
            $creditLogic = new CreditThirdDetailLogic();

            $ableCreditList = $creditLogic->getAbleCreditDetailList($creditId)['data'];

            if (empty($ableCreditList)) {
                return [];
            }

            $investResult = CreditLogic::doDiversificationInvest($amount, $ableCreditList);

            $res = self::updateCreditDiversInvest($ableCreditList);

            if ($res['code']) {

                $matchResult = self::formatMatchData($investResult, $fileName);

            }
        }
        return $matchResult;
    }

    /**
     * @desc 格式化债权匹配数据
     * @param $investResult
     * @param $fileName
     * @return mixed
     */
    public static function formatMatchData($investResult,$fileName){

        //不存在目录时创建目录
        $dirPath = '/uploads/thirdCredit/';

        $investIds = ToolArray::arrayToIds($investResult, 'id');

        $creditList = self::getCreditListByIds($investIds);
        $investResult = ToolArray::arrayToKey($investResult,'id');

        foreach($creditList as $key=>$value){
            if(isset($investResult[$value['id']])){
                $creditList[$key]['used_amount'] = $investResult[$value['id']]['invest_amount'];
            }
        }
        $oss = new OssLogic();
        $oss->writeFile(json_encode($creditList),$dirPath.$fileName);

        return $creditList;
    }

    /**
     * @desc 删除已经关联关联的第三方债权数据
     * @param $creditId int
     * @return array
     */
    public static function delDetailByCreditId( $creditId )
    {
        try{
            CreditThirdDetailModel::delDetailByCreditId( $creditId );
        }catch( \Exception $e ){

            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);
            return self::callError( $e->getMessage() );
        }

        return self::callSuccess();
    }

}
