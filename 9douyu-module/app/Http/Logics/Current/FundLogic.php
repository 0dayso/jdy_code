<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/15
 * Time: 11:02
 */

namespace App\Http\Logics\Current;

use App\Http\Dbs\Current\FundStatisticsDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\CoreApi\CurrentModel;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Current\FundModel;
use App\Tools\ExportFile;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
use Log;

class FundLogic extends Logic{

    /**
     * 更新零钱计划总资金汇总数据
     */
    public function updateFundStatistics(){

        try{
            
            $model  = new FundModel();
            
            $data = $model->getFundBaseData();

            $model->addFundRecord($data);
            
        }catch (\Exception $e){

            Log::error(__METHOD__.'Error',['msg' => $e->getMessage(),'code' => $e->getCode()]);
            
        }
    }
    

    public function updateInterest(){
        
        $model = new FundModel();
        $data   = $model->updateFundInterest();
        
        
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return array|mixed
     * @desc 获取列表
     */
    public function getList( $startTime, $endTime ){

        if( empty($startTime) || empty($endTime) ){

            return [];

        }

        $db = new FundStatisticsDb();

        $list = $db->getListByTimesParam($startTime, $endTime);

        return $list;

    }

    /**
     * @param $startTime
     * @param $endTime
     * @return bool
     * @desc 数据导出
     */
    public function doExport($startTime, $endTime){

        $list[] = [
            '日期',	'转入',	'转出',	'零钱计划金额',    '当日利息',	'总利息',	'加息券成本',	'利率'
        ];

        $data = $this->getList($startTime, $endTime);

        if( empty($data) ){

            return false;

        }

        $list = array_merge($list, $data);
        
        ExportFile::csv($list, 'current_fund_statistics-'.ToolTime::dbDate());

    }

    /**
     * @desc  获取活期利息列表【管理后台】
     * @param $page
     * @param $size
     * @param $param
     * @return array
     */
    public function getAdminCurrentInterestHistory($page,$size, $param){

        if(isset($param['phone'])){
            $phone = $param['phone'];
            $userInfo = UserModel::getBaseUserInfo($phone);
        }
        if(!empty($userInfo)){
            $param['user_id']= $userInfo['id'];
            unset($param['phone']);
        }

        $return = CurrentModel::getAdminCurrentInterestHistory($page,$size, $param);

        //格式化返回结果
        $return = $this->formatCurrentInterestHistory($return);

        return $return;
    }

    /**
     * @desc 格式化活期计息列表
     * @param $return
     * @return array
     */
    public function formatCurrentInterestHistory($return){
        $userIds = implode(',',ToolArray::arrayToIds($return['data'],'user_id'));

        $userLists = ToolArray::arrayToKey(UserModel::getUserListByIds($userIds), 'id');

        if(!empty($return['data'])){
            foreach($return['data'] as $key => $value){
                if(isset($userLists[$value['user_id']])){
                    $return['data'][$key]['phone'] = $userLists[$value['user_id']]['phone'];
                    $return['data'][$key]['real_name'] = $userLists[$value['user_id']]['real_name'];
                }
            }
        }else{
            $return = [
                'data'  => [],
                "total"         => 0,
                "last_page"     => '',
                "per_page"      => '',
                "current_page"  => ''
            ];
        }
        return $return;
    }
    
}