<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/5/31
 * Time: 下午5:03
 * Desc: 项目相关逻辑信息
 */

namespace App\Http\Logics\Project;

use App\Http\Dbs\CurrentProjectDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\ProjectRefundPlanDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\IncomeModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Project\ProjectModel;
use App\Lang\LangModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolTime;
use Log;
use Illuminate\Support\Facades\Lang;

/**
 * Class ProjectLogic
 * @package App\Http\Logics\Project
 */
class ProjectLogic extends Logic
{

    /**
     * @param string $name
     * @param int $totalAmount 融资总额
     * @param int $investDays 融资期限
     * @param int $investTime 投资期限
     * @param int $refundType 还款方式
     * @param int $type 产品线
     * @param int $productLine
     * @param int $baseProfit 基准利率
     * @param int $afterProfit 平台加息
     * @param int $createdBy 创建人
     * @param time $publishTime 发布时间
     * @return array
     */
    /**
     * @param array $data
     * @return array
     */
    public function create(array $data)
    {

        $return = self::callError();

        $model = new ProjectModel();

        $data = self::filterParams($data);

        try {

            //验证数据是否合法
            $this->checkCreateUpdate($data);

            //创建项目
            $projectId = $model->create($data);

            $return = self::callSuccess(['project_id' => $projectId], LangModel::SUCCESS_PROJECT_CREATE);

            Log::Info('CreateProjectSuccess', [$data, $return]);

        } catch (\Exception $e) {

            $return['msg'] = $e->getMessage();

            $data['code'] = $e->getCode();
            $data['msg'] = $e->getMessage();

            Log::Error('CreateProjectError', [$data, $return]);
        }

        return $return;

    }

    /**
     * @param int $page
     * @param int $size
     * @param string $status
     * @return mixed
     * @desc 获取九省心的项目列表
     */
    public function getJSXList($page = 1, $size = 6, $status = '')
    {

        $productLine = ProjectDb::getJSXProductLine();

        $projectModel = new ProjectModel();

        return $projectModel->getListByProductLine($productLine, $page, $size, $status);

    }

    /**
     * @desc 获取定期项目列表
     * @param $productLine string|array
     * @param $page int
     * @param $size int
     * @param $status string|array
     * @return mixed
     */
    public function getProjectList($productLine, $page, $size, $status){

        $projectModel = new ProjectModel();

        return $projectModel->getListByPublishTime($productLine, $page, $size, $status);

    }

    /**
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 获取普付宝列表
     */
    public function getPfbList($page = 1, $size = 6)
    {

        $projectDb = new ProjectDb();

        return $projectDb->getPfbList($page, $size);

    }

    /**
     * @return mixed
     * @desc 获取普付宝项目详情
     */
    public function getPfbProject()
    {

        $projectDb = new ProjectDb();

        return $projectDb->getPfbDetail();

    }

    /**
     * @param int $page
     * @param int $size
     * @param string $status
     * @return mixed
     * @desc 后台获取九省心的项目列表
     */
    public function getAdminJSXList($page = 1, $size = 6, $status = '')
    {

        $productLine = ProjectDb::getJSXProductLine();

        $projectModel = new ProjectModel();

        return $projectModel->getAdminListByProductLine($productLine, $page, $size, $status);

    }

    /**
     * @param int $page
     * @param int $size
     * @param string $status
     * @return mixed
     * @desc 获取九安心的项目列表
     */
    public function getJAXList($page = 1, $size = 6, $status = '')
    {

        $productLine = ProjectDb::getJAXProductLine();

        $projectModel = new ProjectModel();

        return $projectModel->getListByProductLine($productLine, $page, $size, $status);

    }

    /**
     * @param int $page
     * @param int $size
     * @param string $status
     * @return mixed
     * @desc 后台获取九安心的项目列表
     */
    public function getAdminJAXList($page = 1, $size = 6, $status = '')
    {

        $productLine = ProjectDb::getJAXProductLine();

        $projectModel = new ProjectModel();

        return $projectModel->getAdminListByProductLine($productLine, $page, $size, $status);

    }

    /**
     * @param int $page
     * @param int $size
     * @param string $status
     * @return mixed
     * @desc 获取闪电付息的项目列表
     */
    public function getSDFList($page = 1, $size = 6, $status = '')
    {

        $productLine = ProjectDb::getSDFProductLine();

        $projectModel = new ProjectModel();

        return $projectModel->getListByProductLine($productLine, $page, $size, $status);

    }

    /**
     * @param int $page
     * @param int $size
     * @param string $status
     * @return mixed
     * @desc 后台获取闪电付息的项目列表
     */
    public function getAdminSDFList($page = 1, $size = 6, $status = '')
    {

        $productLine = ProjectDb::getSDFProductLine();

        $projectModel = new ProjectModel();

        return $projectModel->getAdminListByProductLine($productLine, $page, $size, $status);

    }

    /**
     * @param int $page
     * @param int $size
     * @param string $status
     * @param array $ids
     * @return mixed
     * @desc 通过状态判断
     */
    public function getListByStatus($page = 1, $size = 6, $status = '', $ids=false){

        $projectModel = new ProjectModel();

        return $projectModel->getListByStatus($page, $size, $status, $ids);

    }

    /**
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 获取已完结项目的债权列表
     */
    public function getFinishedList($page=1, $size=6){

        $projectModel = new ProjectModel();

        return $projectModel->getFinishedList($page, $size);

    }

    /**
     * @param int $id
     * @return mixed|string
     * @desc 获取项目详情
     */
    public function getDetailById($id = 0)
    {

        if (empty($id)) {

            return '';

        }

        $projectDb = new ProjectDb();

        return $projectDb->getInfoById($id);

    }

    /**
     * @param $id
     * @return array
     * @desc 删除项目
     */
    public function doDelete($id)
    {

        $return = self::callError();

        $model = new ProjectModel();

        try {

            //检测项目是否存在
            $model->checkProjectIdIsExist($id);

            $result = $model->doDelete($id);

            $return = self::callSuccess([$result], LangModel::SUCCESS_PROJECT_DELETE);

            Log::Info(__CLASS__ . __METHOD__ . 'Success', $return);

        } catch (\Exception $e) {

            $return['msg'] = $e->getMessage();

            Log::Error(__CLASS__ . __METHOD__ . 'Error', [$return, $e->getCode()]);

        }

        return $return;

    }

    /**
     * @param int $id 项目id
     * @param string $name
     * @param int $totalAmount 融资总额
     * @param int $investDays 融资期限
     * @param int $investTime 投资期限
     * @param int $refundType 还款方式
     * @param int $type 产品线
     * @param int $productLine
     * @param int $baseRate 基准利率
     * @param int $afterRate 平台加息
     * @param int $createdBy 创建人
     * @param time $publishTime 发布时间
     * @return array
     */
    /**
     * @param $id
     * @param array $data
     * @return array
     */
    public function doUpdate($id, array $data)
    {

        $return = self::callError();

        $model = new ProjectModel();

        $data = self::filterParams($data);

        try {

            //验证数据是否合法
            $this->checkCreateUpdate($data);

            //检测项目是否存在
            $model->checkProjectIdIsExist($id);

            $result = $model->doUpdate($id, $data);

            $return = self::callSuccess($result, LangModel::SUCCESS_PROJECT_EDIT);


            Log::Info(__CLASS__ . __METHOD__ . 'Success', [$data, $return, $result]);

        } catch (\Exception $e) {

            $data['code'] = $e->getCode();
            $data['msg'] = $return['msg'] = $e->getMessage();

            Log::Error(__CLASS__ . __METHOD__ . 'Error', [$data, $return]);

        }

        return $return;

    }

    /**
     * @param array $data
     * @throws \Exception
     * @desc 验证数据是否合法
     */
    public function checkCreateUpdate(array $data)
    {

        $model = new ProjectModel();

        //项目名称
        ValidateModel::isNullName($data['name']);

        //验证融资总金额
        ValidateModel::isTotalAmount($data['total_amount']);

        //融资天数
        ValidateModel::isInvestDays($data['invest_days']);

        //投资期限
        ValidateModel::isInvestTime($data['invest_time']);

        //发布时间是否正确
        ValidateModel::isDate($data['publish_at']);

        //利率是否正确
        ValidateModel::isProfit($data['base_rate']);

        //产品线
        $model->checkProductLine($data['product_line']);

        //还款方式
        $model->checkRefundType($data['refund_type']);

        return true;

    }

    /**
     * @param $id
     * @return array
     * @desc 更新状态为回款中
     */
    public function updateStatusRefunding($id)
    {

        $return = self::callError();

        $model = new ProjectModel();

        try {

            $model->updateStatusRefunding($id);

            $return = [
                'status' => true,
                'code' => self::CODE_SUCCESS,
                'data' => $id,
            ];

            Log::Info(__CLASS__ . __METHOD__ . 'Success', $return);

        } catch (\Exception $e) {

            $return['msg'] = $e->getMessage();

            $log = [
                'data' => $id,
                'code' => $e->getCode(),
                'msg' => $e->getMessage()
            ];

            Log::Error(__CLASS__ . __METHOD__ . 'Error', $log);

            \App\Http\Logics\Warning\ProjectLogic::updateStatusRefundingWarning($log);

        }

        return $return;

    }

    /**
     * @param int $id
     * @return array|string
     * @desc 更新状态为发布
     */
    public function updateStatusInvesting($id = 0)
    {

        $return = self::callError();

        $projectDb = new ProjectDb();

        $projectInfo = $projectDb->getInfoById($id);

        if (empty($projectInfo)) {

            Log::Error(__CLASS__ . __METHOD__ . 'Error', '项目不存在');

            return '';

        }

        $model = new ProjectModel();

        try {

            $model->updateStatusInvesting($id);

            $return = [
                'status' => true,
                'code' => self::CODE_SUCCESS,
                'data' => $id,
            ];

            Log::Error(__CLASS__ . __METHOD__ . 'Success', $return);

        } catch (\Exception $e) {

            $return['code'] = $e->getCode();

            $return['msg'] = $e->getMessage();

            $return = self::callError();

            Log::Error(__CLASS__ . __METHOD__ . 'Error', $return);

            $log = [
                'data' => $id,
                'code' => $return['code'],
                'msg' => $return['msg']
            ];

            \App\Http\Logics\Warning\ProjectLogic::updateStatusInvestingWarning($log);

        }

        return $return;

    }

    /**
     * @param int $id
     * @return array|string
     * @desc 更新状态为发布
     */
    public function autoUpdateStatusInvesting($id = 0, $publishTime = '')
    {

        $return = self::callError();

        $model = new ProjectModel();

        try {

            $model->autoUpdateStatusInvesting($id, $publishTime);

            $return = [
                'status' => true,
                'code' => self::CODE_SUCCESS,
                'data' => $id,
            ];

            Log::Info(__CLASS__ . __METHOD__ . 'Success', $return);

        } catch (\Exception $e) {

            $return['code'] = $e->getCode();

            $return['msg'] = $e->getMessage();

            $return = self::callError();

            Log::Error(__CLASS__ . __METHOD__ . 'Error', $return);

            $log = [
                'data' => $id,
                'code' => $return['code'],
                'msg' => $return['msg']
            ];

            \App\Http\Logics\Warning\ProjectLogic::updateStatusInvestingWarning($log);

        }

        return $return;

    }

    /**
     * @param $projectId
     * @return array
     * @return 获取项目的还款计划
     */
    public function getRefundPlan($projectId){

        $model = new IncomeModel();

        $res = $model->getRefundPlan($projectId);

        $res = self::filterRefundPlan($res);

        //$db = new ProjectRefundPlanDb();
        //$res = $db->getObjByProjectId($projectId);
        $return = self::callSuccess($res);
        return $return;
    }

    /**
     * @param $res
     * @return array
     * @desc 项目还款计划金额输出格式化
     */
    public static function filterRefundPlan($res){

        if(empty($res) && !is_array($res)){

            return [];

        }

        foreach ($res as $key => $item) {

            $res[$key]['refund_cash'] = ToolMoney::formatDbCashDelete($item['refund_cash']);

        }

        return $res;

    }

    /**
     * @param $requestData
     * @return array
     * 参数格式化
     */
    public static function filterParams($requestData)
    {

        //格式化金额
        $totalAmount = (int)$requestData['total_amount'];

        $data = [

            'total_amount'  => ToolMoney::formatDbCashAdd($totalAmount),     //项目总额
            'invest_days'   => (int)$requestData['invest_days'],             //融资时间
            'invest_time'   => (int)$requestData['invest_time'],             //投资期限
            'refund_type'   => (int)$requestData['refund_type'],             //还款方式
            'type'          => !empty($requestData['type'])?(int)$requestData['type']:ProjectDb::INVEST_TIME_DAY,                    //项目类型
            'product_line'  => (int)$requestData['product_line'],            //项目类型
            'base_rate'     => (float)$requestData['base_rate'],               //基准利率
            'after_rate'    => !empty($requestData['after_rate'])?(float)$requestData['after_rate']:0,              //平台加息
            'created_by'    => !empty($requestData['created_by'])?(int)$requestData['created_by']:0,              //创建人
            'publish_at'    => $requestData['publish_time'],            //发布时间
            'name'          => $requestData['name'],                    //项目名称
            'status'        => !empty($requestData['status'])?(int)$requestData['status']:ProjectDb::STATUS_UNAUDITED,   //更新状态
            'pledge'        => !empty($requestData['pledge']) ? ProjectDb::PLEDGE : 0,  //普付宝标示
            'new'           => !empty($requestData['new']) ? ProjectDb::IS_NEW : 0,     //新定期
        ];

        if(isset($requestData['id'])){
            $data['id'] = $requestData['id'];
        }

        return $data;

    }

    /**
     * @param $ids
     * @return mixed
     * @desc 通过多个id获取项目列表
     */
    public function getListByIds($ids)
    {

        $ids = explode(',', $ids);

        $projectDb = new ProjectDb();

        $list = $projectDb->getListByProjectIds($ids);

        foreach ($list as $key => $val) {

            $list[$key] = $this->formatProject($val);

        }

        return $list;

    }

    /**
     * @param array $project
     * @return array|string
     * @desc 格式化项目数据
     */
    public function formatProject($project = [])
    {

        if (empty($project)) {

            return '';

        }

        //总金额
        $project['total_amount']    = ToolMoney::formatDbCashDelete($project['total_amount']);
        //保证金
        $project['guarantee_fund']  = ToolMoney::formatDbCashDelete($project['guarantee_fund']);

        //投资金额
        $project['invested_amount'] = ToolMoney::formatDbCashDelete($project['invested_amount']);
        //剩余可投
        $project['left_amount']     = $project['total_amount'] - $project['invested_amount'];
        //项目还款方式
        $project['refund_type_note']= Lang::get('messages.PROJECT.REFUND_TYPE_' . $project['refund_type']);
        //项目状态
        $project['status_note']     = Lang::get('messages.PROJECT.STATUS_' . $project['status']);

        //产品类型
        $project['product_line_note']   = Lang::get('messages.PROJECT.PRODUCT_LINE_' . $project['product_line']);
        //项目期限
        $project['invest_time_note']    = $project['invest_time'] . Lang::get('messages.PROJECT.TYPE_' . $project['type']);

        $project['invest_time_unit']    = Lang::get('messages.PROJECT.TYPE_' . $project['type']);

        if( ($project['refund_type']    == ProjectDb::REFUND_TYPE_BASE_INTEREST || $project['refund_type'] == ProjectDb::REFUND_TYPE_FIRST_INTEREST) && $project['new'] != ProjectDb::IS_NEW){

            $project['format_invest_time']  = $this->formatInvestTime(ToolTime::getDate($project['publish_at']), $project['end_at'], $project['status'], $project['refund_type']);

            $project['invest_time_unit']    = '天';

        }else{

            $project['format_invest_time'] = $project['invest_time'];

            //$project['invest_time_unit'] = '月期';

        }

        if( $project['status'] != ProjectDb::STATUS_INVESTING ){

            $project['format_invest_time']  = $project['invest_time'];

        }

        //如果前置付息切状态为投资中
        if($project['status'] == ProjectDb::STATUS_INVESTING && $project['refund_type'] == ProjectDb::REFUND_TYPE_FIRST_INTEREST){
            $project['invest_time_unit'] = '天';
        }
        return $project;

    }


    /**
     * @return array
     * @desc 获取首页的项目列表
     */
    public function getHomeList()
    {

        $projectDb = new ProjectDb();

        //闪电付
        //$list['sdf'] = $projectDb->getHomeSdf();

        //九安心
        $list['jax'] = $projectDb->getHomeJax();

        //九省心-1月期
        $list['one'] = $projectDb->getHomeJsxOne();

        //九省心-3月期
        $list['three'] = $projectDb->getHomeJsxThree();

        //九省心-6月期
        $list['six'] = $projectDb->getHomeJsxSix();

        //九省心-12月期
        $list['twelve'] = $projectDb->getHomeJsxTwelve();

        foreach( $list as $key => $val ){


            $list[$key] = $this->formatProject($val);

        }

        $pcIndexStat = \Cache::get('PC_INDEX_STAT');

        if( empty($pcIndexStat) ){

            //获取零钱计划\定期收益投资相关统计数据
            $model = new \App\Http\Models\Invest\ProjectModel();

            $stat = $model->getData();

            $pcIndexStat = [
                'totalInterest'         => $stat['totalInterest'],
                'currentInvestAmount'   => $stat['currentInvestAmount'],
                'userTotal'             => $stat['userTotal']
            ];

            \Cache::put('PC_INDEX_STAT', $pcIndexStat, 60);

        }

        $list['totalInterest']        = $pcIndexStat['totalInterest'];        //总收益

        $list['currentInvestAmount']  = $pcIndexStat['currentInvestAmount'];    //零钱计划转入总金额

        $list['userTotal']            = $pcIndexStat['userTotal'];    //零钱计划转入总金额

        return self::callSuccess($list);

    }

    /**
     * @desc 获取新的首页项目列表
     * @param $projectLine array 产品线
     * @param $status array 状态
     * @param $limit int
     * @return array
     */
    public function getNewHomeList( $producttLine = '', $status = '', $limit = 5 )
    {

        $projectModel = new ProjectModel();

        $productLine = ( !empty( $productLine ) ? $productLine : [ ProjectDb::PROJECT_PRODUCT_LINE_JSX,ProjectDb::PROJECT_PRODUCT_LINE_JAX ] );
        $status = ( !empty( $status ) ? $status : [ ProjectDb::STATUS_INVESTING, ProjectDb::STATUS_REFUNDING, ProjectDb::STATUS_FINISHED ] );

        $list =  $projectModel->getNewHomeListByPublishTime($productLine, $status, $limit );

        foreach( $list as $key => $val ){

            $list[$key] = $this->formatProject($val);
        }

        $pcIndexStat = \Cache::get('PC_NEW_INDEX_STAT');

        if( empty($pcIndexStat) ){

            //获取零钱计划\定期收益投资相关统计数据
            $model = new \App\Http\Models\Invest\ProjectModel();

            $stat = $model->getData();

            $pcIndexStat = [
                'totalInterest'         => $stat['totalInterest'],
                'currentInvestAmount'   => $stat['currentInvestAmount'],
                'userTotal'             => $stat['userTotal']
            ];

            \Cache::put('PC_NEW_INDEX_STAT', $pcIndexStat, 60);

        }

        $list['totalInterest']        = $pcIndexStat['totalInterest'];        //总收益

        $list['currentInvestAmount']  = $pcIndexStat['currentInvestAmount'];    //零钱计划转入总金额

        $list['userTotal']            = $pcIndexStat['userTotal'];    //零钱计划转入总金额

        return self::callSuccess( $list );
    }

    /**
     * @param $projectIds
     * @param $endTime
     * @return array|bool
     * @desc 更新项目已经完结
     */
    public function doProjectEnd($projectIds, $endTime)
    {

        Log::Info('doProjectEndInfo', [$projectIds, $endTime]);

        if( empty($projectIds) || empty($endTime) ){

            return false;

        }

        $projectModel = new ProjectModel();

        try{

            /*foreach( $projectIds as $id ){

                //项目更新为已经完结
                $projectModel->updateStatusFinished($id, $endTime);

                //@todo 债权回收

            }*/
            //批量处理项目为完结状态
            $projectModel->updateStatusFinishedByIds($projectIds, $endTime);
            $returnData = [
                'ids'       => $projectIds,
                'end_time'  => $endTime
            ];

            Log::Info('doProjectEndSuccess', [$returnData]);

            $return = self::callSuccess($returnData);

        }catch (\Exception $e) {

            $log = [
                'project_ids'   => $projectIds,
                'end_time'      => $endTime,
                'msg'           => $e->getMessage(),
                'error_code'    => $e->getCode()
            ];

            \App\Http\Logics\Warning\ProjectLogic::updateStatusFinishedWaring($log);

            Log::Error('doProjectEndError', $log);

            $return  = self::callError($log['msg']);

        }

        return $return;

    }

    /**
     * @param $time
     * @desc 获取已经完结的项目ids
     */
    public function getFinishedIds($time)
    {

        $db = new ProjectDb();

        $res = $db->getFinishedIds($time);

        $res = ToolArray::arrayToIds($res);

        return self::callSuccess($res);

    }

    /**
     * @return array
     * @desc 前置付息项目列表
     */
    public function getSdfProject(){

        $db = new ProjectDb();

        $list['six']    = $db->getHomeSdfSix();

        $list['twelve'] = $db->getHomeSdfTwelve();

        foreach( $list as $key => $val ){

            $val = ToolArray::arrayToSimple($val);

            $list[$key] = $this->formatProject($val);

            $list[$key]['em_money'] = 100000;

            $model = new IncomeModel();

            if( $val['status'] != ProjectDb::STATUS_INVESTING || $val['refund_type'] ==  ProjectDb::REFUND_TYPE_ONLY_INTEREST){

                $list[$key]['em_profit'] = ($model -> getInterestByMonth($list[$key]['em_money'], $val['profit_percentage']))*$list[$key]['format_invest_time'];

            }else{

                $list[$key]['em_profit'] = $model -> getInterestByDay($list[$key]['em_money'], $val['profit_percentage'],$list[$key]['format_invest_time']);

            }

        }

        return self::callSuccess($list);

    }

    /**
     * @param $publishTime
     * @param $endTime
     * @param $status
     * @param $refundType
     * @return float|string
     * @desc 项目剩余时间
     */
    public function formatInvestTime($publishTime, $endTime, $status, $refundType){

        if($refundType != ProjectDb::REFUND_TYPE_BASE_INTEREST && $refundType != ProjectDb::REFUND_TYPE_FIRST_INTEREST){

            return '';

        }

        if($status != ProjectDb::STATUS_INVESTING){

            return ToolTime::getDayDiff($publishTime, $endTime);

        }

        return ToolTime::getDayDiff(ToolTime::dbDate(), $endTime);

    }

    /**
     * @param $id
     * @return array
     * @desc 审核不通过
     */
    public function updateStatusAuditeFail($id){

        if( empty($id) ){
            return self::callError('params is empty');
        }

        try{

            $model = new ProjectModel();

            $result = $model->updateStatusAuditeFail($id);

        }catch (\Exception $e){

            Log::error(__CLASS__.__METHOD__, [$e->getMessage()]);

            return self::callError($e->getMessage());

        }


        return self::callSuccess($result);

    }

    /**
     * @param $id
     * @return array
     * @desc 审核通过
     */
    public function updateStatusUnPublish($id){

        if( empty($id) ){
            return self::callError('params is empty');
        }

        try{

            $model = new ProjectModel();

            $result = $model->updateStatusUnPublish($id);

        }catch (\Exception $e){

            Log::error(__CLASS__.__METHOD__, [$e->getMessage()]);

            return self::callError($e->getMessage());

        }

        return self::callSuccess($result);

    }

    /**
     *
     * 获取投资中状态，有投资且未投满 3,6,12月的九省心项目
     * 还款状态为先息后本
     */
    public function getUnRefundProject(){

        $db = new ProjectDb();
        $list = $db->getUnRefundProject();

        return ToolArray::arrayToIds($list,'id');
    }

    /**
     * 通过发布时间,九省心,九安心
     * @param string $times publish_at时间
     * @param string $investTime 项目期限
     * @return mixed
     * @desc,为秒杀活动读取的项目数据
     */
    public function getTimingProject( $time, $investTimes ='')
    {
        $investTimeArr      =   explode(",",$investTimes);
        $timeArr            =   explode(',',$time);

        $investTimeMapped   =   ProjectDb::investTimeMappedString();

        $investTime         =   [];

        $db                 =   new ProjectDb();

        $projects           =   [];
        //读取数据
        foreach ($investTimeArr as $key=>$times){

            if($investTimeMapped[$times] || $investTimeMapped[$times]==ProjectDb::INVEST_TIME_DAY){

                $investTime[$times]=$investTimeMapped[$times];

                $project          =   ToolArray::arrayToSimple($db->getTimingProject($timeArr, $investTimeMapped[$times]));

                $projects[$times] =   $this->formatProject($project);
            }
        }
        return self::callSuccess($projects);
    }

    /**
     * 通过发布时间,九省心,九安心
     * @param string $times publish_at时间
     * @param string $investTime 项目期限
     * @return mixed
     * @desc,为秒杀活动读取的项目数据
     */
    public function AppointJsxTimingProject( $times)
    {
        $timeArr            =   explode(",",$times);

        $db                 =   new ProjectDb();

        $investTimes        =   ProjectDb::investTimeMappedString();

        $projectType        =   array_keys($investTimes);

        $projects           =   [];
        //读取数据
        foreach ($timeArr as $key=>$time ){

            if( in_array($time,$projectType) ){

                $method =   'getHomeJsx'.strtoupper($time);

                $project=    call_user_func_array([$db,$method],[]);

                $project          =   ToolArray::arrayToSimple($project);

                $projects[$time] =   $this->formatProject($project);

            }

        }

        return self::callSuccess($projects);
    }

    /**
     * @param $params
     * @return array
     * @desc 获取项目ID集合
     */
    public function getProjectIdsStatistics($params)
    {
        $statistics   =   $this->doFormatStatistics($params);

        $db     =   new ProjectDb();

        $list   =   $db->getProjectIdsStatistics($statistics);

        return self::callSuccess(ToolArray::arrayToIds($list,'id'));
        //return self::callSuccess($statistics);
    }

    /**
     * @param $statistics
     * @return array
     * @desc 格式化数据条件
     */
    protected function doFormatStatistics($statistics)
    {

        $projectType    =   [];

        if( !empty($statistics['times']) ){

            $timeArr        =   explode(",",$statistics['times']);

            $investTimes    =   ProjectDb::investTimeMappedString();

            foreach ( $timeArr as $item ){

                if( $investTimes[$item] ){

                    $projectType[]=$investTimes[$item];
                }
            }
        }

        return [
            'start_time'    => isset($statistics['start_time']) ? $statistics['start_time'] : null,
            'end_time'      => isset($statistics['end_time']) ? $statistics['end_time'] : null,
            'project_type'  => !empty($projectType) ? $projectType : null,
        ];
    }

    /*
     * @param $startTime
     * @param $endTime
     * @return array
     * @desc 根据项目的最后更新时间获取项目列表,主要功能为,后台按时间查询项目满标的列表
     */
    public function getRefundingProjectListByUpdateTime($startTime, $endTime){

        $db = new ProjectDb();

        $list = $db->getRefundingProjectListByUpdateTime($startTime, $endTime);

        if( !empty($list) ){

            foreach ($list as $key => $project){

                $list[$key] = $this->formatProject($project);

            }

        }

        $data['total'] = count($list);

        $data['list'] = $list;

        return self::callSuccess($data);

    }

    /**
     * @param $startTime
     * @param $endTime
     * @param $page
     * @param $size
     * @return array
     * @desc 通过时间获取非普付宝项目
     */
    public function getProjectWithTime($startTime , $endTime ,$pageIndex = 1,$pageSize = 30)
    {
        $db     =   new ProjectDb();

        $list   =   $db->getProjectWithTime($startTime ,$endTime,$pageIndex,$pageSize);

        if( $list['list'] ){
            foreach ($list['list'] as $key => $project){

                $list['list'][$key] = $this->formatProject($project);
            }
        }

        return self::callSuccess($list);
    }
    /**
     * @param $startTime
     * @param $endTime
     * @param $page
     * @param $size
     * @return array
     * @desc 通过时间正在投资中的项目
     */
    public function getInvestIngProject($startTime , $endTime ,$pageIndex = 1,$pageSize = 50)
    {
        $db     =   new ProjectDb();

        $list   =   $db->getInvestIngProject($startTime ,$endTime,$pageIndex,$pageSize);

        if( $list['list'] ){
            foreach ($list['list'] as $key => $project){

                $list['list'][$key] = $this->formatProject($project);
            }
        }

        return self::callSuccess($list);
    }

    /**
     * @return array
     * @desc 获取每一个产品中最新的一个项目数据
     */
    public function getNewestProjectEveryType()
    {
        $projectDb      =   new ProjectDb();

        //九安心
        $list['jax']    =   $projectDb->getHomeJax();

        //九省心-1月期
        $list['one']    =   $projectDb->getHomeJsxOne();

        //九省心-3月期
        $list['three']  =   $projectDb->getHomeJsxThree();

        //九省心-6月期
        $list['six']    =   $projectDb->getHomeJsxSix();

        //九省心-12月期
        $list['twelve'] =   $projectDb->getHomeJsxTwelve();

        //闪电付 6月期
        $list['sdfsix']    =   $projectDb->getHomeSdfSix();

        $list['sdftwelve'] =   $projectDb->getHomeSdfTwelve();

        foreach( $list as $key => $val ){

            $val        =   ToolArray::arrayToSimple($val);

            $list[$key] =   $this->formatProject($val);

        }

        return self::callSuccess($list);
    }

    /**
     * @desc    获取时间段内已完结项目
     * @date    2016年11月22日
     * @author  @llper
     * @param   $startTime
     * @param   $endTime
     * @return  mixed
     *
     */
    public function getFinishedProjectByTime($startTime,$endTime,$isBefore='')
    {

        $projectDb = new ProjectDb();

        $list   = $projectDb->getFinishedProjectByTime($startTime, $endTime,$isBefore);
        if( !empty($list) ) {
            foreach ($list as $key => $val) {

                $list[$key] = $this->formatProject($val);

            }
        }
        return self::callSuccess($list);

    }

    /**
     * @param $startTime
     * @param $endTime
     * @return array
     * @desc 通过项目的满标时间获取项目的 数据
     */
    public function getProjectByFullTime($startTime,$endTime,$isPledge )
    {
        $projectDb      = new ProjectDb();

        $projectList    =   $projectDb->getProjectByFullTime($startTime,$endTime,$isPledge);

        if( !empty($projectList) ){

            foreach ($projectList as $key => $val) {

                $projectList[$key] = $this->formatProject($val);

            }
        }

        return self::callSuccess($projectList);
    }

    /**
     * @param string $startTime
     * @param string $endTime
     * @return mixed
     * @desc  获取指定时间内的的项目Id,按照ProductLine进行分组
     */
    public static function getAllProjectIdByTime($startTime = '',$endTime = '')
    {
        //九安心
        $projectIds['jax']        =   self::forMatProjectId(ProjectDb::PROJECT_PRODUCT_LINE_JAX,ProjectDb::INVEST_TIME_DAY,$startTime ,$endTime );
        //九省心-1月期
        $projectIds['one']        =   self::forMatProjectId(ProjectDb::PROJECT_PRODUCT_LINE_JSX,ProjectDb::INVEST_TIME_DAY_ONE,$startTime ,$endTime);
        //九省心-3月期
        $projectIds['three']      =   self::forMatProjectId(ProjectDb::PROJECT_PRODUCT_LINE_JSX,ProjectDb::INVEST_TIME_MONTH_THREE,$startTime ,$endTime);
        //九省心-6月期
        $projectIds['six']        =   self::forMatProjectId(ProjectDb::PROJECT_PRODUCT_LINE_JSX,ProjectDb::INVEST_TIME_MONTH_SIX,$startTime ,$endTime);
        //九省心-12月期
        $projectIds['twelve']     =   self::forMatProjectId(ProjectDb::PROJECT_PRODUCT_LINE_JSX,ProjectDb::INVEST_TIME_MONTH_TWELVE,$startTime ,$endTime);
        //闪电付 6月期
        $projectIds['sdfsix']     =   self::forMatProjectId(ProjectDb::PROJECT_PRODUCT_LINE_SDF,ProjectDb::INVEST_TIME_MONTH_THREE,$startTime ,$endTime);
        //闪电付 12月期
        $projectIds['sdftwelve']  =   self::forMatProjectId(ProjectDb::PROJECT_PRODUCT_LINE_SDF,ProjectDb::INVEST_TIME_MONTH_TWELVE,$startTime ,$endTime);

        return self::callSuccess($projectIds);
    }

    /**
     * @param string $projectLine
     * @param string $investTime
     * @param string $startTime
     * @param string $endTime
     * @return array
     * @desc 提取id
     */
    protected static function forMatProjectId($projectLine='' ,$investTime = '', $startTime = '',$endTime = '')
    {
        $returnArr  =   ProjectDb::getProjectIdByProductLineAndTime($projectLine ,$investTime ,$startTime ,$endTime);

        if( empty($returnArr) ){

            return [];
        }

        return ToolArray::arrayToIds($returnArr,'id');
    }

    //TODO: linglu - APP4.1.3首页项目
    /**
     * @desc    APP4.1.3-首页项目信息
     * @author  linglu
     * @param   $projectLine    array 产品线
     * @param   $status         array 状态
     * @param   $limit  int
     * @return  array
     *
     * 逻辑：
     * 1、新手项目
     *      未满标项目、
     *      最后满标项目
     *
     * 2、1、3、6月期项目
     *      三个期限，均展示最早发布的未满标项目，
     *      若对应期限无可投项目，展示最晚发布满标项目
     *
     *
     */
    public function getProjectPackAppV413()
    {

        $projectDb      = new ProjectDb();

        $type           = "refund";
        //新手标
        $novice         = $projectDb->getApp413HomeNovice();
        if( empty($novice) ){
            $novice     = $projectDb->getApp413HomeNovice($type);
        }
        $list['novice'] = $novice;
        //九安心
        //$list['jax']  = $projectDb->getHomeJax();
        //九省心-1月期
        $one            = $projectDb->getApp413HomeJsxOne();
        if(empty($one)){
            $one        = $projectDb->getApp413HomeJsxOne($type);
        }
        $list['one']    = $one;
        //九省心-3月期
        $three          = $projectDb->getApp413HomeJsxThree();
        if(empty($three)){
            $three      = $projectDb->getApp413HomeJsxThree($type);
        }
        $list['three']  = $three;
        //九省心-6月期
        $six            = $projectDb->getApp413HomeJsxSix();
        if(empty($six)){
            $six        = $projectDb->getApp413HomeJsxSix($type);
        }
        $list['six']    = $six;

        //九省心-12月期
        //$list['twelve'] = $projectDb->getHomeJsxTwelve();
        \Log::info(__METHOD__.' : '.__LINE__.' APP4.1.3-', $list);
        foreach( $list  as $key => $val ){
            if(!empty($val[0])){
                $list[$key] = $this->formatProject($val[0]);
            }else{
                $list[$key] = [];
            }

        }

        return  self::callSuccess( $list );

    }


}
