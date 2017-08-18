<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/5/31
 * Time: 下午3:15
 */

namespace App\Http\Models\Project;


use App\Http\Dbs\ProjectDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use App\Tools\ToolTime;
use Log;

/**
 * Class ProjectModel
 * @package App\Http\Models\Project
 */
class ProjectModel extends Model
{

    public static $codeArr = [

        'create'                    => 1,
        'getProjectEndAt'           => 2,
        'checkProductLine'          => 3,
        'checkRefundType'           => 4,
        'doDelete'                  => 5,
        'doUpdate'                  => 6,
        'checkProjectIdIsExist'     => 7,
        'updateStatusRefunding'     => 8,
        'updateStatusFinished'      => 9,
        'updateStatusAuditeFail'    => 10,
        'updateStatusUnPublish'     => 11,
        'checkProjectEndDate'       => 12,
        'updateProjectBeforeRefund' => 13
    ];

    public static $defaultNameSpace = ExceptionCodeModel::EXP_MODEL_PROJECT;

    /**
     * @param string $name
     * @param int $totalAmount  融资总额
     * @param int $investDays   融资期限
     * @param int $investTime   投资期限
     * @param int $refundType   还款方式
     * @param int $type         产品线
     * @param int $productLine
     * @param int $baseProfit   基准利率
     * @param int $afterProfit  平台加息
     * @param int $createdBy     创建人
     * @param time $publishTime 发布时间
     * @return bool
     * @throws \Exception
     */
    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function create(array $data){

        $data['profit_percentage']  = $data['base_rate'] + $data['after_rate'];

        $data['end_at']             = (empty($data['new'])) ?$this->getProjectEndAt($data['publish_at'], $data['type'], $data['invest_time']):'';

        $db = new ProjectDb();

        $res = $db->add($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_CREATE'), self::getFinalCode('create'));

        }

        return $res;

    }


    /**
     * @param $id
     * @return mixed
     * 根据项目ID获取对应的项目信息,不存在抛出异常
     */
    public function getById($id){

        $db     = new ProjectDb();
        $result = $db->getObj($id);

        if(!$result){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_EXIST'), self::getFinalCode('getById'));

        }
        return $result;
    }

    /**
     * @param $projectInfo
     * 检测项目状态是否可以债转(闪电付息项目不允许此操作)
     */
    public function checkProjectSdf($projectInfo){

        if($projectInfo['product_line'] == ProjectDb::PROJECT_PRODUCT_LINE_SDF){

            throw new \Exception(LangModel::getLang('ERROR_SDF_PRODUCT_CAN_NOT_CREDIT_ASSIGN'), self::getFinalCode('checkProjectStatus'));

        }
    }


    /**
     * @param $projectInfo
     * @throws \Exception
     * 项目完结日 不允许债转
     */
    public function checkProjectEndDate($projectInfo){

        if($projectInfo['end_at'] == ToolTime::dbDate()){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_END_DATE_CAN_NOT_CREDIT_ASSIGN'), self::getFinalCode('checkProjectEndDate'));

        }

    }

    /**
     * @param $publishTime
     * @param $type
     * @param $investTime
     * @return bool|string
     * @throws \Exception
     * @desc 获取项目截止时间
     */
    public function getProjectEndAt($publishTime, $type, $investTime){

        $byMonthTypeArr = $this->getProjectTypeArrByMonth();
        $byDayTypeArr   = $this->getProjectTypeArrByDays();

        if(in_array($type, $byMonthTypeArr)){

            $endAt = date("Y-m-d", strtotime("+$investTime months", strtotime($publishTime)));

            return ToolTime::getNextMonthDate($publishTime, $endAt);

        }elseif(in_array($type, $byDayTypeArr)){

            return date("Y-m-d", strtotime("+$investTime days", strtotime($publishTime)));

        }else{

            throw new \Exception(LangModel::getLang('ERROR_INVEST_TIME_NOT_FIND'), self::getFinalCode('getProjectEndAt')); //项目类型未定义

        }

    }

    /**
     * @param $preStr
     * @return array|bool
     * @desc 通过前缀获取项目const配置
     */
    public function getConstArrByPrefix( $preStr ){

        if(empty($preStr)){ return false; }

        $constArr   = ProjectDb::getConstants();

        $result     = [];

        foreach($constArr as $key => $val){

            if(strpos($key, $preStr) === 0){

                $result[] = $val;

            }

        }

        return $result;

    }

    /**
     * @param $productLine
     * @return bool
     * @throws \Exception
     * @desc 验证产品线是否合法
     */
    public function checkProductLine($productLine){

        $preStr = ProjectDb::PRE_PRODUCT_LINE;

        $productLineArr = $this->getConstArrByPrefix( $preStr );

        if( !$productLineArr ){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_PRODUCT_LINE_UNDEFINED'), self::getFinalCode('checkProductLineArr')); //DB层未定义产品线
        }

        if(!in_array($productLine, $productLineArr)){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_PRODUCT_LINE_UNDEFINED'), self::getFinalCode('checkProductLineArr')); //产品线不存在
        }

        return true;

    }

    /**
     * @param $refundType
     * @return bool
     * @throws \Exception
     * @desc 验证回款方式
     */
    public function checkRefundType($refundType){

        $preStr = ProjectDb::PRE_REFUND_TYPE;

        $refundTypeArr = $this->getConstArrByPrefix( $preStr );

        if( !$refundTypeArr ){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_REFUND_TYPE_UNDEFINED'), self::getFinalCode('checkRefundType')); //DB层未定义产品线
        }

        if(!in_array($refundType, $refundTypeArr)){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_REFUND_TYPE_UNDEFINED'), self::getFinalCode('checkRefundType')); //产品线不存在
        }

        return true;


    }

    /**
     * @return array|bool
     * @项目按月计算类型
     */
    public function getProjectTypeArrByMonth(){

        $preStr = ProjectDb::PRE_INVEST_TIME_MONTH;

        $investTimeMonthArr = $this->getConstArrByPrefix( $preStr );

        return $investTimeMonthArr;

    }

    /**
     * @return array|bool
     * @项目按天计算类型
     */
    public function getProjectTypeArrByDays()
    {

        $preStr = ProjectDb::PRE_INVEST_TIME_DAY;

        $investTimeDayArr = $this->getConstArrByPrefix( $preStr );

        return $investTimeDayArr;
    }

    /**
     * @param int $p
     * @param int $size
     * @return mixed
     * @desc 获取项目类型列表
     */
    public function getListByProductLine($productLine, $p = 1, $size = 6, $status='')
    {

        $projectDb = new ProjectDb();

        $start = $projectDb->getLimitStart($p, $size);

        $total = $projectDb->getProductLineParam($productLine)
            ->getStatusParam($status)
            ->getPledgeParam()
            ->getSqlBuilder()
            ->count('id');

        $list = $projectDb->getSqlBuilder(true)
            ->getProductLineParam($productLine)
            ->getStatusParam($status)
            ->getPledgeParam()
            ->getSqlBuilder()
            ->orderBy('status', 'asc')
            ->orderBy('type', 'asc')
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }

    /**
     * @desc 按照发布时间获取项目列表
     * @param $productLine 产品线
     * @param $page int
     * @param $size int
     * @param $status string|array
     * $return mixed
     */
    public function getListByPublishTime($productLine, $page =1, $size = 6, $status =''){

        $projectDb = new ProjectDb();

        $start = $projectDb->getLimitStart($page, $size);

        $total = $projectDb->getMoreProductLineParam($productLine)
            ->getStatusParam($status)
            ->getPledgeParam()
            ->getSqlBuilder()
            ->count('id');

        $list = $projectDb->getSqlBuilder(true)
            ->getMoreProductLineParam($productLine)
            ->getStatusParam($status)
            ->getPledgeParam()
            ->getSqlBuilder()
            ->orderBy('status', 'asc')
            ->orderBy('publish_at', 'asc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }

    /**
     * @desc 按照发布时间获取新版改动的首页项目列表
     * @param $productLine 产品线
     * @param $status string|array
     * @param $limit int 条数
     * $return mixed
     */
    public function getNewHomeListByPublishTime( $productLine, $status = '', $limit )
    {

        $projectDb = new ProjectDb();

        $list = $projectDb->getSqlBuilder(true)
            ->getMoreProductLineParam($productLine)
            ->getStatusParam($status)
            ->getPledgeParam()
            ->getSqlBuilder()
            ->orderBy('status', 'asc')
            ->orderBy('publish_at', 'desc')
            ->limit( $limit )
            ->get()
            ->toArray();
        return $list;
    }

    /**
     * @param $productLine
     * @param int $p
     * @param int $size
     * @param string $status
     * @return array
     * @desc 后台项目列表
     */
    public function getAdminListByProductLine($productLine, $p = 1, $size = 6, $status='')
    {
        $projectDb = new ProjectDb();

        $start = $projectDb->getLimitStart($p, $size);

        $total = $projectDb->getProductLineParam($productLine)
            ->getStatusParam($status)
            ->getSqlBuilder()
            ->count('id');

        $list = $projectDb->getSqlBuilder(true)
            ->getProductLineParam($productLine)
            ->getStatusParam($status)
            ->getSqlBuilder()
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];
    }

    /**
     * @param int $p
     * @param int $size
     * @param string $status
     * @param array $ids
     * @return array
     * @desc 获取项目类型列表
     */
    public function getListByStatus($p = 1, $size = 6, $status='', $ids=false){

        $projectDb = new ProjectDb();

        $start = $projectDb->getLimitStart($p, $size);


        $total = $projectDb->getStatusParam($status)
            ->getIdsParam($ids)
            ->getPledgeParam()
            ->getSqlBuilder()
            ->count('id');

        $list = $projectDb->getSqlBuilder(true)
            ->getStatusParam($status)
            ->getIdsParam($ids)
            ->getPledgeParam()
            ->getSdfParam()
            ->getSqlBuilder()
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];
    }

    /**
     * @param int $p
     * @param $size
     * @return array
     * @desc 已完结项目列表
     */
    public function getFinishedList($p = 1, $size = 6){

        $projectDb = new ProjectDb();

        $start = $projectDb->getLimitStart($p, $size);

        $total = $projectDb->getFinishedStatusParam()
            ->getPledgeParam()
            ->getSqlBuilder()
            ->count('id');

        $list = $projectDb->getSqlBuilder(true)
            ->getFinishedStatusParam()
            ->getPledgeParam()
            ->getSqlBuilder()
            ->orderBy('id', 'desc')
            ->skip($start)
            ->take($size)
            ->get()
            ->toArray();

        return [ 'total' => $total, 'list' => $list];

    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @desc 删除项目通过id
     */
    public function doDelete( $id ){

        $projectDb = new ProjectDb();

        $result = $projectDb->doDelete( $id );

        if( !$result ){
            throw new \Exception(LangModel::getLang('ERROR_PROJECT_DELETE_FAIL'), self::getFinalCode('doDelete'));
        }

        return $result;

    }

    /**
     * @param $id
     * @param $name
     * @param $totalAmount
     * @param $investDays
     * @param $investTime
     * @param $refundType
     * @param $type
     * @param $baseProfit
     * @param $afterProfit
     * @param $productLine
     * @param $createdBy
     * @param $publishTime
     * @return bool
     * @throws \Exception
     */
    /**
     * @param $id
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function doUpdate( $id, array $data){

        $db = new ProjectDb();

        $data['profit_percentage']  = $data['base_rate']+$data['after_rate'];

        $data['end_at']             = (empty($data['new'])) ? $this->getProjectEndAt($data['publish_at'], $data['type'], $data['invest_time']) : '';

        $data['guarantee_fund']     = abs($data['total_amount'] * $db::GUARANTEE_PROFIT);

        $data['updated_at']         = ToolTime::dbNow();

        $res = $db->doUpdate($id,$data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('doUpdate'));

        }

        return $res;

    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function checkProjectIdIsExist( $id ){

        $db = new ProjectDb();

        $res = $db -> getObj($id);

        if(empty($res)){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_NOT_FIND'), self::getFinalCode('checkProjectIdIsExist'));

        }

        return $res;

    }

    /*--------------------------项目状态更新begin------------------------------*/
    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @desc 项目审核不通过
     */
    public function updateStatusAuditeFail($id=0){

        $projectDb = new ProjectDb();

        $res = $projectDb->updateStatusAuditeFail($id);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('updateStatusAuditeFail'));

        }

        return $res;

    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @desc 项目审核通过, 待发布
     */
    public function updateStatusUnPublish($id=0){

        $projectDb = new ProjectDb();

        $res = $projectDb->updateStatusUnPublish($id);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('updateStatusUnPublish'));

        }

        return $res;

    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @desc 更新项目为发布即投资中
     */
    public function updateStatusInvesting($id=0)
    {

        $projectDb = new ProjectDb();

        $res = $projectDb->updateStatusInvesting($id);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('updateStatusInvesting'));

        }

        return $res;

    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @desc 自动更新项目为发布即投资中，需求为当前时间
     */
    public function autoUpdateStatusInvesting($id=0, $publishTime='')
    {

        $publishTime = $publishTime ? $publishTime : ToolTime::dbNow();

        $projectDb = new ProjectDb();

        $projectInfo = $projectDb->getInfoById($id);

        Log::info('autoUpdateStatusInvesting', [$projectInfo]);

        $endAt = $investTime = '';

        if( !$projectInfo['new'] ){

            if($projectInfo['product_line'] == ProjectDb::PROJECT_PRODUCT_LINE_JAX || ($projectInfo['product_line'] == ProjectDb::PROJECT_PRODUCT_LINE_JSX && $projectInfo['type'] == ProjectDb::INVEST_TIME_DAY_ONE) ){

                $investTime = ToolTime::getDayDiff($publishTime, $projectInfo['end_at']);

            }else{

                $endAt = $this->getProjectEndAt($publishTime, $projectInfo['type'], $projectInfo['invest_time']);

            }

        }

        $res = $projectDb->autoUpdateStatusInvesting($id, $publishTime, $endAt, $investTime);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('updateStatusInvesting'));

        }

        return $res;

    }

    /**
     * @param int $id
     * @throws \Exception
     * @desc 更新项目为还款中状态
     * @return mixed
     */
    public function updateStatusRefunding($id=0)
    {

        $projectDb = new ProjectDb();

        $projectInfo = $projectDb->getInfoById($id);

        if( empty($projectInfo) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_NOT_FUND'), self::getFinalCode('updateStatusRefunding')); //DB层未定义产品线

        }

        if( $projectInfo['total_amount'] > $projectInfo['invested_amount'] ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_UNINVESTED_ALL'), self::getFinalCode('updateStatusRefunding')); //DB层未定义产品线

        }

        if( $projectInfo['new'] ){

            $endAt = self::getProjectEndAt(ToolTime::dbDate(), $projectInfo['type'], $projectInfo['invest_time']);

            $res = $projectDb->updateNewStatusRefunding($id, $endAt);

        }else{

            $res = $projectDb->updateStatusRefunding($id);

        }

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('updateStatusRefunding'));

        }

        return $res;

    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @desc 更新项目为已完结
     */
    public function updateStatusFinished($id=0, $times='')
    {

        $projectDb = new ProjectDb();

        $res = $projectDb->updateStatusFinished($id, $times);

        if( $res === false ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('updateStatusFinished'));

        }

        return $res;

    }

    /**
     * @desc 通过多个项目ID更新项目为完结状态
     * @param array  $projectIds
     * @param string $times
     * @return mixed
     * @throws \Exception
     */
    public function updateStatusFinishedByIds($projectIds=[], $times=''){

        $projectDb = new ProjectDb();

        $res = $projectDb->updateStatusFinishedByIds($projectIds,$times);

        if( $res === false ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('updateStatusFinishedByIds'));

        }

        return $res;

    }
    /*--------------------------项目状态更新ending------------------------------*/

    /**
     * @param $projectIds
     * @param $endAt
     * @throws \Exception
     * @desc 更新项目提前还款标志
     */
    public function updateProjectBeforeRefund( $projectIds, $endAt ){

        $projectDb = new ProjectDb();

        $res = $projectDb->updateProjectBeforeRefund($projectIds, $endAt);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_RECORD_UPDATE'), self::getFinalCode('updateProjectBeforeRefund'));

        }

        return true;

    }

}
