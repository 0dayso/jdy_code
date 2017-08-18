<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/6/17
 * Time: 11:41
 * Desc: 核心定期项目操作调用model
 */
namespace App\Http\Models\Common\CoreApi;

use App\Http\Dbs\Project\ProjectDb;
use App\Http\Models\Common\CoreApiModel;
use App\Lang\LangModel;
use Carbon\Carbon;
use Config;
use App\Http\Models\Common\HttpQuery;
use App\Tools\ToolMoney;
use Log;
use Cache;

class ProjectModel extends CoreApiModel{

    /**
     * @param $params
     * 参数列表
     * total_amount         项目金额        必填
     * invest_days          融资时间        必填
     * invest_time          投资期限        必填
     * refund_type          还款方式(10-到期还本息 20-先息后本 30-前置付息)        必填
     * type                 项目类型(0-XX天 1-一月期 3-三月期 6-六月期 12-十二月期)    必填
     * name                 项目名称        必填
     * product_line         产品线(100-九省心 200-九省心 300-前置付息)   必填
     * base_rate            基准利率        必填
     * after_rate           平台加息        非必填
     * create_by            创建人         必填
     * publish_time         项目发布时间      必填
     * status
     * @return null|void
     * @desc 创建定期项目
     */
    public static function doCreateProject($params){

        $api  = Config::get('coreApi.moduleProject.doCreate');

        $params['total_amount'] = ToolMoney::formatDbCashDelete($params['total_amount']);

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }


    /**
     * @param $projectId        项目ID    必填
     * @return array
     * 删除定期项目
     */
    public static function doDeleteProject($projectId){

        $api  = Config::get('coreApi.moduleProject.doDelete');


        $params = [
            'project_id' => $projectId
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }

    /**
     * @param $params
     * 参数列表
     * project_id           项目ID        必填
     * total_amount         项目金额        必填
     * invest_days          融资时间        必填
     * invest_time          投资期限        必填
     * refund_type          还款方式(10-到期还本息 20-先息后本 30-前置付息)        必填
     * type                 项目类型(0-XX天 1-一月期 3-三月期 6-六月期 12-十二月期)    必填
     * name                 项目名称        必填
     * product_line         产品线(100-九省心 200-九省心 300-前置付息)   必填
     * base_rate            基准利率        必填
     * after_rate           平台加息        非必填
     * create_by            创建人         必填
     * publish_time         项目发布时间      必填
     *
     * @return null|void
     *
     * @desc 编辑定期项目
     */
    public static function doUpdateProject($params){

        $api  = Config::get('coreApi.moduleProject.doUpdate');

        $params['total_amount'] = ToolMoney::formatDbCashDelete($params['total_amount']);

        $return = HttpQuery::corePost($api,$params);

        return $return;
    }




    /**
     * @param $projectId        项目ID    必填
     * @return array
     * 获取项目详情
     */
    public static function getProjectDetail($projectId){

        $api  = Config::get('coreApi.moduleProject.detail');

        $params = [
            'project_id' => $projectId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];

        }
    }

    /**
     * @return null|void
     * @desc 获取投资中普付宝项目详情（一个）
     */
    public static function getPfbProjectDetail(){
        $api  = Config::get('coreApi.moduleProject.getPfbProjectDetail');

        $return = HttpQuery::corePost($api);

        return $return;
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return array
     * @desc 根据项目的最后更新时间获取项目列表,主要功能为,后台按时间查询项目满标的列表
     */
    public static function getRefundingProjectListByUpdateTime($startTime, $endTime){

        $api  = Config::get('coreApi.moduleProject.getRefundingProjectListByUpdateTime');

        $params = [
            'start_time'    => $startTime,
            'end_time'      => $endTime,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];

        }

    }

    /**
     * @param $projectIds
     * @return array
     * @desc 通过项目ids获取项目的利息
     */
    public static function getSumInterestByProjectIds($projectIds){

        $api  = Config::get('coreApi.moduleRefund.getSumInterestByProjectIds');

        $projectIds = implode(',', $projectIds);

        $params = [
            'project_ids'    => $projectIds,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];

        }

    }

    /**
     * @param $page     页码      必填
     * @param $size     每页显示条数      必填
     * @param $status      项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)    必填
     * @param $type
     * @return array
     * 分页获取九省心项目列表
     */
    public static function getJsxProjectList($page,$size,$status='',$type=false){

        if($type == true){
            $api  = Config::get('coreApi.moduleProject.getAdminJsxList');
        }else{
            $api  = Config::get('coreApi.moduleProject.getJsxList');
        }

        $params = [
            'page'      => $page,
            'size'      => $size,
            'status'    => $status
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            if($data['total'] > 0){

                $list = $data['list'];

                foreach ($list as $k=>$val){
                    $list[$k]['total_amount']       = ToolMoney::formatDbCashAdd($val['total_amount']);
                    $list[$k]['guarantee_fund']     = ToolMoney::formatDbCashAdd($val['guarantee_fund']);
                    $list[$k]['invested_amount']    = ToolMoney::formatDbCashAdd($val['invested_amount']);
                    $list[$k]['left_amount']        = ToolMoney::formatDbCashAdd($val['left_amount']);
                }

                $data['list'] = $list;

                return $data;

            }else{
                return [];
            }


        }else{
            return [];
        }

    }


    /**
     * @param $page     页码      必填
     * @param $size     每页显示条数      必填
     * @param $status      项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)    必填
     * @param $type
     * @return array
     * 分页获取九安心项目列表
     */
    public static function getJaxProjectList($page,$size,$status='',$type=''){

        if($type == 'admin'){
            $api  = Config::get('coreApi.moduleProject.getAdminJaxList');
        }else{
            $api  = Config::get('coreApi.moduleProject.getJaxList');
        }

        $params = [
            'page'      => $page,
            'size'      => $size,
            'status'    => $status
        ];

        $return = HttpQuery::corePost($api,$params);


        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            if($data['total'] > 0){

                $list = $data['list'];

                foreach ($list as $k=>$val){
                    $list[$k]['total_amount']       = ToolMoney::formatDbCashAdd($val['total_amount']);
                    $list[$k]['guarantee_fund']     = ToolMoney::formatDbCashAdd($val['guarantee_fund']);
                    $list[$k]['invested_amount']    = ToolMoney::formatDbCashAdd($val['invested_amount']);
                    $list[$k]['left_amount']        = ToolMoney::formatDbCashAdd($val['left_amount']);
                }

                $data['list'] = $list;

                return $data;

            }else{
                return [];
            }


        }else{
            return [];
        }

    }


    /**
     * @param $page     页码      必填
     * @param $size     每页显示条数      必填
     * @param $status      项目状态(100-未审核 110-未通过 120-未发布 130-投资中 150-还款中 160-已完结)    必填
     * @param $type
     * @return array
     * 分页获取闪电付息项目列表
     */
    public static function getSdfProjectList($page,$size,$status='',$type=''){

        if($type == 'admin'){
            $api  = Config::get('coreApi.moduleProject.getAdminSdfLists');
        }else{
            $api  = Config::get('coreApi.moduleProject.getSdfLists');
        }

        $params = [
            'page'      => $page,
            'size'      => $size,
            'status'    => $status
        ];

        $return = HttpQuery::corePost($api,$params);


        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            if($data['total'] > 0){

                $list = $data['list'];

                foreach ($list as $k=>$val){
                    $list[$k]['total_amount']       = ToolMoney::formatDbCashAdd($val['total_amount']);
                    $list[$k]['guarantee_fund']     = ToolMoney::formatDbCashAdd($val['guarantee_fund']);
                    $list[$k]['invested_amount']    = ToolMoney::formatDbCashAdd($val['invested_amount']);
                    $list[$k]['left_amount']        = ToolMoney::formatDbCashAdd($val['left_amount']);
                }

                $data['list'] = $list;

                return $data;

            }else{
                return [];
            }


        }else{
            return [];
        }

    }

    /**
     * @param $ids
     * @return array
     * @desc 通过多个id获取项目列表
     */
    public static function getProjectListByIds($projectId){

        $api  = Config::get('coreApi.moduleProject.getLists');

        $params = [
            'project_ids' => implode(',', $projectId)
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];

        }
    }

    /**
     * @param $page
     * @param $size
     * @param string $status
     * @param mixed $projectIds
     * @return array
     * @desc 通过状态获取项目列表
     */
    public static function getProjectListByStatus($page,$size,$status='', $projectIds =false){

        $api  = Config::get('coreApi.moduleProject.getListByStatus');


        $params = [
            'page'      => $page,
            'size'      => $size,
            'status'    => $status
        ];
        if($projectIds !== false){
            $params['ids'] =  $projectIds;
        }

        $return = HttpQuery::corePost($api,$params);


        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            if($data['total'] > 0){

                $list = $data['list'];

                foreach ($list as $k=>$val){
                    $list[$k]['total_amount']       = ToolMoney::formatDbCashAdd($val['total_amount']);
                    $list[$k]['guarantee_fund']     = ToolMoney::formatDbCashAdd($val['guarantee_fund']);
                    $list[$k]['invested_amount']    = ToolMoney::formatDbCashAdd($val['invested_amount']);
                    $list[$k]['left_amount']        = ToolMoney::formatDbCashAdd($val['left_amount']);
                }

                $data['list'] = $list;

                return $data;

            }else{
                return [];
            }


        }else{
            return [];
        }

    }

    /**
     * @param $page
     * @param $size
     * @return array
     * @desc 获取已完结的项目列表
     */
    public static function getProjectFinishList($page, $size){

        $api  = Config::get('coreApi.moduleProject.getFinishedList');


        $params = [
            'page'      => $page,
            'size'      => $size,
        ];

        $return = HttpQuery::corePost($api,$params);


        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            if($data['total'] > 0){

                $list = $data['list'];

                foreach ($list as $k=>$val){
                    $list[$k]['total_amount']       = ToolMoney::formatDbCashAdd($val['total_amount']);
                    $list[$k]['guarantee_fund']     = ToolMoney::formatDbCashAdd($val['guarantee_fund']);
                    $list[$k]['invested_amount']    = ToolMoney::formatDbCashAdd($val['invested_amount']);
                    $list[$k]['left_amount']        = ToolMoney::formatDbCashAdd($val['left_amount']);
                }

                $data['list'] = $list;

                return $data;

            }else{
                return [];
            }


        }else{
            return [];
        }

    }

    /**
     * @param $projectId    项目ID    必填
     * @param $userId       用户ID    必填
     * @param $cash         金额      必填
     * @return array
     * 定期投资
     */
    public static function doProjectInvest($projectId,$userId,$cash){


        $api  = Config::get('coreApi.moduleProject.doInvest');


        $cash   = ToolMoney::formatDbCashDelete($cash);

        $params = [
            'project_id'   => $projectId,
            'user_id'      => $userId,
            'cash'         => $cash,
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }


    /**
     * @param $projectId            项目ID        必填
     * @param $cash                 金额          必填
     * @param $profit               加息券利率     必填
     * @return array
     * 投资确认页面预期收益
     */
    public static function getBonusPlanInterest($projectId,$cash,$profit){


        $api  = Config::get('coreApi.moduleProject.getPlanInterest');


        $cash   = ToolMoney::formatDbCashDelete($cash);

        $params = [
            'project_id'    => $projectId,
            'cash'          => $cash,
            'profit'        => $profit,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            $data['cash_interest'] = ToolMoney::formatDbCashAdd($data['cash_interest']);
            $data['rate_interest'] = ToolMoney::formatDbCashAdd($data['rate_interest']);

            return $data;
        }else{

            return ['cash_interest'=>0,'rate_interest'=>0];
        }


    }


    /**
     * @param $investId     投资ID    必填
     * @param $profit       加息券利率   必填
     * @return array
     * 创建加息券的回款记录
     */
    public static function doCreateBonusRefundRecord($investId,$profit){


        $api  = Config::get('coreApi.moduleProject.doCreateBonusRefundRecord');


        $params = [
            'invest_id'      => $investId,
            'profit'         => $profit,
        ];

        $return = HttpQuery::corePost($api,$params);

        return $return;

    }

    /**
     * @return array
     * @desc 首页项目数据包
     */
    public static function getIndexProjectPack()
    {

        $api = Config::get('coreApi.moduleProject.getIndexProjectPack');

        $return = HttpQuery::corePost($api);

        $data = [];

        //总收益
        $data['totalInterest']          = isset($return['data']['totalInterest']) ? ToolMoney::formatDbCashAdd($return['data']['totalInterest']) : 0;

        //零钱计划投资总额
        $data['currentInvestAmount']    = isset($return['data']['currentInvestAmount']) ? ToolMoney::formatDbCashAdd($return['data']['currentInvestAmount']) : 0;

        //注册用户总数
        $data['userTotal']    = isset($return['data']['userTotal']) ? $return['data']['userTotal'] : 0;

        unset($return['data']['totalInterest'],$return['data']['currentInvestAmount'],$return['data']['userTotal']);

        foreach ($return['data'] as $key => $record) {

            $data[$key] = $record;

            if (empty($record)) continue;

            $data[$key]['total_amount'] = ToolMoney::formatDbCashAdd($record['total_amount']);
            $data[$key]['guarantee_fund'] = ToolMoney::formatDbCashAdd($record['guarantee_fund']);
            $data[$key]['invested_amount'] = ToolMoney::formatDbCashAdd($record['invested_amount']);
            $data[$key]['left_amount'] = ToolMoney::formatDbCashAdd($record['left_amount']);
        }
        return $data;
    }

    /**
     * 获取首页平台数据明细
     */
    public static function getHomeStatisticsDetail(){

        $api  = Config::get('coreApi.moduleStatistics.getHomeStatistics');

        $params = [];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            $data['totalInterest']          = ToolMoney::formatDbCashAdd($data['totalInterest']);
            $data['currentInvestAmount']    = ToolMoney::formatDbCashAdd($data['currentInvestAmount']);
            $data['refundAmount']           = ToolMoney::formatDbCashAdd($data['refundAmount']);

            return $data;
        }else{

            return [];
        }

    }

    /**
     * @param int $page
     * @param int $size
     * @return array
     * @desc 获取还款中的项目列表
     */
    public static function getRefundingList($page=1, $size = 10){

        $api  = Config::get('coreApi.moduleProject.getRefundingList');

        $params = [
            'page'      => $page,
            'size'      => $size,
            'status'    => ProjectDb::STATUS_REFUNDING
        ];

        $return = HttpQuery::corePost($api,$params);

        if(isset($return['data']['list']) && !empty($return['data']['list'])){

            return $return['data']['list'];

        }else{

            return [];
        }

    }

    /**
     * @return array
     * @desc 获取前台页面前置付息列表
     */
    public static function getSdfProject(){

        $api = Config::get('coreApi.moduleProject.getSdfList');

        $return = HttpQuery::corePost($api);

        Log::info(__CLASS__.__METHOD__, [$return]);

        if( empty($return['data']) ){

            return [];

        }

        $list = $return['data'];

        foreach($list as $key => $item){

            if(empty($value)){
                continue;
            }

            $list[$key]['total_amount']     = ToolMoney::formatDbCashAdd($item['total_amount']);
            $list[$key]['guarantee_fund']   = ToolMoney::formatDbCashAdd($item['guarantee_fund']);
            $list[$key]['invested_amount']  = ToolMoney::formatDbCashAdd($item['invested_amount']);
            $list[$key]['left_amount']      = ToolMoney::formatDbCashAdd($item['left_amount']);

        }

        return $list;

    }



    /**
     * @param $id
     * @return bool
     * @desc 项目审核通过
     */
    public static function doPass( $id ){

        $api = Config::get('coreApi.moduleProject.doPass');

        $params = [
            'id' => $id
        ];

        $return = HttpQuery::corePost($api, $params);

        Log::info(__CLASS__.__METHOD__, [$return]);

        if($return['status']){

            return true;

        }

        return false;

    }

    /**
     * @param $id
     * @return bool
     * @desc 项目审核不通过
     */
    public static function doNoPass( $id ){

        $api = Config::get('coreApi.moduleProject.doNoPass');

        $params = [
            'id' => $id
        ];

        $return = HttpQuery::corePost($api, $params);

        Log::info(__CLASS__.__METHOD__, [$return]);

        if($return['status']){

            return true;

        }

        return false;

    }

    /**
     * @param $id
     * @return bool
     * @desc 项目发布
     */
    public static function doPublish( $id ){

        $api = Config::get('coreApi.moduleProject.doPublish');

        $params = [
            'id' => $id
        ];

        $return = HttpQuery::corePost($api, $params);

        Log::info(__CLASS__.__METHOD__, [$return]);

        if($return['status']){

            return true;

        }

        return false;

    }

    /**
     * @param int $page
     * @param int $size
     * @return array
     * @desc 获取普付宝项目列表
     */
    public static function getPfbList($page = 1,$size = 6){

        $api    = Config::get('coreApi.moduleProject.getPfbList');

        $params = [
            'page'  => $page,
            'size'  => $size
        ];

        $return = HttpQuery::corePost($api,$params);

        Log::info(__CLASS__.__METHOD__,[$return]);

        if(empty($return['data'])){

            return [];
        }

        return $return['data'];
    }

    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return array
     * @desc 获取普付宝用户的投资列表
     */
    public static function getPfbInvestList($userId,$page = 1,$size = 100){

        $api   = Config::get('coreApi.moduleProject.getPfbInvestList');

        $param = [
            'user_id'   => $userId,
            'page'      => $page,
            'size'      => $size
        ];

        $return = HttpQuery::corePost($api,$param);

        Log::info(__CLASS__.__METHOD__,[$return]);

        if(empty($return['data'])){

            return [];
        }

        return $return['data'];
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取普付宝用户投资总额
     */
    public static function getPfbInvestTotal($userId){

        $api    = Config::get('coreApi.moduleProject.getPfbInvestTotal');

        $param  = [
            'user_id'   => $userId
        ];

        $return = HttpQuery::corePost($api,$param);

        Log::info(__CLASS__.__METHOD__,[$return]);

        if(empty($return['data'])){

            return 0;
        }

        return $return['data'];
    }

    /**
     * @param int $size
     * @return mixed
     * @desc 获取最新投资记录
     */
    public function getNewInvest($size = 0){

        $cacheKey = 'INVEST_NEW_LIST';

        $list     = Cache::get($cacheKey);

        $list     = json_decode($list, true);

        if(empty($list)){

            $api = Config::get('coreApi.moduleProject.getNewInvest');

            $res = HttpQuery::corePost($api,['size'=>$size]);

            if(!empty($res)){

                $list = $res;

                $listCache = json_encode($list);

                $expire    = Carbon::now()->addMinutes(30);

                Cache::put($cacheKey,$listCache,$expire);
            }

        }

        return $list;
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据日期获取投资总额
     */
    public function getInvestAmountByDate($start = '',$end = ''){

        $cacheKey = 'INVEST_AMOUNT_DATE';

        $list     = Cache::get($cacheKey);

        $list     = json_decode($list,true);

        if(empty($list)){

            //定期投资列表
            $api = Config::get('coreApi.moduleProject.getInvestAmountByDate');

            $res = HttpQuery::corePost($api,['start'=>$start,'end'=>$end]);

            if(!empty($res)){

                $list = $res;

                $listCache = json_encode($list);

                $expire    = Carbon::now()->addMinutes(5);

                Cache::put($cacheKey,$listCache,$expire);
            }
        }

        return $list;

    }

    /**
     * @param string $start
     * @param string $end
     * @return int
     * @desc 获取定期的总投资额
     */
    public function getInvestTotalAmounts($start = '',$end = ''){

        $api = Config::get('coreApi.moduleProject.getInvestAmount');

        $res = HttpQuery::corePost($api,['start'=>$start,'end'=>$end]);

        if(!empty($res['data'])){

            return $res['data']['cash'];
        }else{

            return 0;
        }
    }

    /**
     * @param $investIds
     * @return array
     */
    public static function getListByIds($investIds){

        $api  = Config::get('coreApi.moduleProject.getListByIds');

        $params = [
            'invest_ids' => implode(',', $investIds)
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];

        }

    }

    /**
     * @param string $start
     * @param string $end
     * @return int
     * @desc 获取定期的总投资额
     */
    public static function getTimingProject($times = '', $investTime = '')
    {
        $api = Config::get('coreApi.moduleProject.getTimingProject');

        $res = HttpQuery::corePost($api,['times'=>$times,'invest_time'=>$investTime]);

        if(!empty($res['data'])){

            return $res['data'];
        }else{

            return 0;
        }
    }

    /**
     * @param string $start
     * @param string $end
     * @return int
     * @desc 获取九省心指定的项目
     */
    public static function getAppointJsxProject($times = '')
    {
        $api = Config::get('coreApi.moduleProject.getAppointJsxProject');

        $res = HttpQuery::corePost($api,['times'=>$times]);

        if( $res['status'] && !empty($res['data'])){

            return $res['data'];
        }else{

            return [];
        }
    }

    /**
     * @param string $start
     * @param string $end
     * @return int
     * @desc 获取九省心指定的项目
     */
    public static function getProjectIdsStatistics($start_time = '',$end_time='',$times='')
    {
        $api = Config::get('coreApi.moduleProject.getProjectIdsStatistics');

        $statistics =   [
            'start_time'  =>  $start_time,
            'end_time'    =>  $end_time,
            'times'       =>  $times,
        ];
        $res = HttpQuery::corePost($api,$statistics);

        if( $res['status'] && !empty($res['data'])){

            return $res['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $start_time
     * @param $end_time
     * @param int $page
     * @param int $size
     * @return array
     * @desc 根据时间获取非普付宝的项目(可见的项目)
     */
    public static function getProjectWithTime($start_time,$end_time,$pageIndex =1, $pageSize = 50)
    {
        $api = Config::get('coreApi.moduleProject.getProjectWithTime');

        $statistics =   [
            'start_time'  =>  $start_time,
            'end_time'    =>  $end_time,
            'page'        =>  $pageIndex,
            'page_size'   =>  $pageSize,
        ];
        $res = HttpQuery::corePost($api,$statistics);

        if( $res['status'] && !empty($res['data'])){

            return $res['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $start_time
     * @param $end_time
     * @param int $pageIndex
     * @param int $pageSize
     * @return array
     * @desc 获取投资中的非普付宝的项目
     */
    public static function getInvestIngProject($start_time,$end_time,$pageIndex =1, $pageSize = 50)
    {
        $api = Config::get('coreApi.moduleProject.getInvestIngProject');

        $statistics =   [
            'start_time'  =>  $start_time,
            'end_time'    =>  $end_time,
            'page'        =>  $pageIndex,
            'page_size'   =>  $pageSize,
        ];
        $res = HttpQuery::corePost($api,$statistics);

        if( $res['status'] && !empty($res['data'])){

            return $res['data'];
        }else{

            return [];
        }
    }
    /**
     * @return array
     * @desc 获取所以产品类型中最新的项目
     */
    public static function getNewestProjectEveryType()
    {
        $api = Config::get('coreApi.moduleProject.getNewestProjectEveryType');

        $res = HttpQuery::corePost($api);

        if( $res['status'] && !empty($res['data'])){

            return $res['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $projectStr 项目Id字符串,多个项目Id以逗号隔开 例: sigle 1 或  minu 1,2,3
     * @return array
     * @desc 提前还款
     */
    public static function beforeRefundRecord( $projectStr ){

        $api = Config::get('coreApi.moduleProject.beforeRefundRecord');

        $params = [
            'project_id'  => $projectStr,
        ];

        $res = HttpQuery::corePost($api, $params);

        if( $res['status'] && !empty($res['data'])){

            return $res['data'];
        }else{

            return [];
        }

    }

    /**
     * @param   string $start
     * @param   string $end
     * @return  array
     * @desc    获取时间段内， 已完结的项目
     */
    public static function getFinishedProjectList($start = '',$end = '',$isBefore = ''){

        $api = Config::get('coreApi.moduleProject.getFinishedProjectByTime');

        $res = HttpQuery::corePost($api,['start_time'=>$start,'end_time'=>$end,"is_before"=>$isBefore]);


        if(!empty($res)){

            return $res['data'];
        }else{

            return [];
        }
    }

    /**
     * @param array $projectIds
     * @return array
     * @desc 获取项目的正常投资记录
     */
    public static function getNormalInvestByProjectIds( $projectIds = array() )
    {
        $projectIds     =   implode(",",$projectIds);

        $coreApi        =   Config::get('coreApi.moduleProject.getNormalInvestByProjectIds');

        $result         =   HttpQuery::corePost($coreApi,['project_ids'=>$projectIds]);

        if( $result['status'] && !empty($result['data']) ) {

            return $result['data'];
        }

        return [];
    }

    /**
     * @param array $projectIds
     * @return array
     * @desc  从核心获取最后一次投资的数据(不包含原项目债转的记录)
     */
    public static function getLastInvestTimeByProjectIdFromCore( $projectIds = array())
    {
        $projectIds     =   implode(",",$projectIds);

        $api = Config::get('coreApi.moduleProject.getLastInvestTimeByProjectId');

        $res = HttpQuery::corePost($api,["project_ids"=>$projectIds]);

        if(!empty($res)){

            return $res['data'];
        }else{

            return [];
        }
    }


    /**
     * @desc    小微金融-获取债权对应项目需要回款信息
     */
    public static function getCreditProjectById( $projectIds = array())
    {
        $projectIds     =   implode(",",$projectIds);

        $api = Config::get('coreApi.moduleProject.getCreditProjectById');

        $res = HttpQuery::corePost($api,["project_ids"=>$projectIds]);

        if(!empty($res)){

            return $res['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $projectIds
     * @return array
     * @desc 通过项目ids获取项目的利息
     */
    public static function getInterestTypeByProjectIds($projectIds){

        $api  = Config::get('coreApi.moduleRefund.getInterestTypeByProjectIds');

        $projectIds = implode(',', $projectIds);

        $params = [
            'project_ids'    => $projectIds,
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{

            return [];

        }

    }

    /**
     * @param $start_time
     * @param $end_time
     * @param int $page
     * @param int $size
     * @return array
     * @desc 根据项目满标时间获取项目信息
     */
    public static function getProjectByFullTime($startTime,$endTime,$isPledge=0)
    {
        $coreApi = Config::get('coreApi.moduleProject.getProjectByFullTime');

        $statistics =   [
            'start_time'  =>  $startTime,
            'end_time'    =>  $endTime,
            'is_pledge'   =>  $isPledge,
        ];
        $res = HttpQuery::corePost($coreApi,$statistics);

        if( $res['status'] && !empty($res['data'])){

            return $res['data'];
        }else{

            return [];
        }
    }

    /**
     * @param $start_time
     * @param $end_time
     * @param int $page
     * @param int $size
     * @return array
     * @desc 通过时间,按照ProductLine获取项目id
     */
    public static function getAllProjectIdByTime($startTime,$endTime)
    {
        $coreApi = Config::get('coreApi.moduleProject.getAllProjectIdByTime');

        $statistics =   [
            'start_time'  =>  $startTime,
            'end_time'    =>  $endTime,
        ];
        $res = HttpQuery::corePost($coreApi,$statistics);

        if( $res['status'] && !empty($res['data'])){

            return $res['data'];
        }else{

            return [];
        }
    }

    /**
     * @desc 获取理财列表定期项目列表
     * @param $productLine array|string 按照项目产品线
     * @param $page int
     * @param $size int
     * @param $status array|string
     * @return array
     */
    public static function getProjectList($productLine, $page, $size, $status){


        $api  = Config::get('coreApi.moduleProject.getProjectList');

        $params = [
            'page'      => $page,
            'size'      => $size,
            'product_line' => $productLine,
            'status'    => $status
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            $data = $return['data'];

            if($data['total'] > 0){

                $list = $data['list'];

                foreach ($list as $k=>$val){
                    $list[$k]['total_amount']       = ToolMoney::formatDbCashAdd($val['total_amount']);
                    $list[$k]['guarantee_fund']     = ToolMoney::formatDbCashAdd($val['guarantee_fund']);
                    $list[$k]['invested_amount']    = ToolMoney::formatDbCashAdd($val['invested_amount']);
                    $list[$k]['left_amount']        = ToolMoney::formatDbCashAdd($val['left_amount']);
                }

                $data['list'] = $list;

                return $data;

            }else{
                return [];
            }


        }else{
            return [];
        }


    }

    /**
     * @param $projectId
     * @throws \Exception
     * @desc 判断项目是否为普付宝质押项目
     */
    public static function checkProjectIsPledge($projectId){

        $projectInfo = self::getProjectDetail($projectId);

        if(empty($projectInfo) || $projectInfo['pledge'] != 1){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_DETAIL_GET_FAIL'));

        }
    }


    /***TODO: APP413-linglu ************/
    /**
     * @desc    APP413-首页项目数据包
     * @author  linglu
     * @return  array
     *
     */
    public static function getProjectPackAppV413()
    {

        $api        = Config::get('coreApi.moduleProject.getProjectPackAppV413');
        $return     = HttpQuery::corePost($api);
        $data       = [];

        foreach ($return['data'] as $key => $record) {

            $data[$key] = $record;
            if (empty($record)) continue;

            $data[$key]['total_amount']     = ToolMoney::formatDbCashAdd($record['total_amount']);
            $data[$key]['guarantee_fund']   = ToolMoney::formatDbCashAdd($record['guarantee_fund']);
            $data[$key]['invested_amount']  = ToolMoney::formatDbCashAdd($record['invested_amount']);
            $data[$key]['left_amount']      = ToolMoney::formatDbCashAdd($record['left_amount']);

        }
        return $data;
    }

}
