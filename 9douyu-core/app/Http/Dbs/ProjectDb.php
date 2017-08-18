<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 上午11:18
 * Desc: 定期项目
 */

namespace App\Http\Dbs;

use App\Tools\ToolArray;
use App\Tools\ToolTime;

class ProjectDb extends JdyDb{

    const

        //前缀
        PRE_PRODUCT_LINE            = 'PROJECT_PRODUCT_LINE',
        PRE_INVEST_TIME             = 'INVEST_TIME',
        PRE_INVEST_TIME_MONTH       = 'INVEST_TIME_MONTH',
        PRE_INVEST_TIME_DAY         = 'INVEST_TIME_DAY',
        PRE_REFUND_TYPE             = 'REFUND_TYPE',

        //项目产品线
        PROJECT_PRODUCT_LINE_JSX    = 100,      //九省心
        PROJECT_PRODUCT_LINE_JAX    = 200,      //九安心
        PROJECT_PRODUCT_LINE_SDF    = 300,      //闪电付息

        //项目期限
        INVEST_TIME_MONTH_THREE     = 3,      //3月期
        INVEST_TIME_MONTH_SIX       = 6,      //6月期
        INVEST_TIME_MONTH_TWELVE    = 12,     //12月期
        INVEST_TIME_DAY_ONE         = 1,      //1月期
        INVEST_TIME_DAY             = 0,      //天

        //还款方式
        REFUND_TYPE_BASE_INTEREST   = 10,       //到期还本息
        REFUND_TYPE_ONLY_INTEREST   = 20,       //按月付息，到期还本
        REFUND_TYPE_FIRST_INTEREST  = 30,       //投资当日付息，到期还本
        REFUND_TYPE_EQUAL_INTEREST  = 40,       //等额本息

        //项目
        GUARANTEE_PROFIT            = 0.2,      //项目保证金为融资总额的百分之二十

        //项目状态
        STATUS_UNAUDITED            = 100,  //未审核
        STATUS_AUDITE_FAIL          = 110,  //未通过
        STATUS_UNPUBLISH            = 120,  //未发布

        STATUS_INVESTING            = 130,  //投资中

        STATUS_REFUNDING            = 150,  //还款中
        STATUS_FINISHED             = 160,  //已完结

        PLEDGE                      = 1,    //普付宝项目标志
        IS_NEW                      = 1,    //新定期
        BEFORE_REFUND               = 1,    //提前还款标志




        END                         = NULL;

    protected $table = 'project';
    public    $timestamps = false;




    /**
     * @return array
     * @desc 获取项目还款类型
     */
    public static function getRefundTypeArr()
    {

        return [
            self::REFUND_TYPE_BASE_INTEREST,
            self::REFUND_TYPE_ONLY_INTEREST,
            self::REFUND_TYPE_FIRST_INTEREST,
            self::REFUND_TYPE_EQUAL_INTEREST
        ];

    }

    /**
     * @param $id
     * @return mixed
     * @desc id获取对象信息
     */
    public function getObj($id)
    {

        return $this->find($id);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 获取项目信息
     */
    public function getInfoById($id)
    {

        $res = $this->where('id', $id)
            ->get()
            ->toArray();

        return ToolArray::arrayToSimple($res);

    }

    /**
     * @param $id
     * @param $cash
     * @return mixed
     * @desc 投资项目，更新已投资金额
     */
    public function invest($id, $cash)
    {

        $obj = $this->getObj($id);

        $res = $obj
            ->where('id', '=', $obj->id)
            ->where('status', '=', self::STATUS_INVESTING)
            ->where('total_amount', '>=', \DB::raw(sprintf('`invested_amount`+%d', abs($cash))))
            ->update(array(
                'invested_amount' => \DB::raw(sprintf('`invested_amount`+%d', abs($cash)))
            ));

        return $res;

    }


    /**
     * @param $data
     * @return bool
     * @desc 创建项目
     */
    public function add($data)
    {

        $this->id                   = isset($data['id'])?$data['id']:'';
        $this->name                 = $data['name'];
        $this->total_amount         = abs($data['total_amount']);
        $this->guarantee_fund       = abs($data['total_amount'] * self::GUARANTEE_PROFIT);
        $this->invest_days          = $data['invest_days'];
        $this->invest_time          = $data['invest_time'];
        $this->refund_type          = $data['refund_type'];
        $this->profit_percentage    = $data['profit_percentage'];
        $this->base_rate            = $data['base_rate'];
        $this->after_rate           = $data['after_rate'];
        $this->type                 = $data['type'];
        $this->product_line         = $data['product_line'];
        $this->created_by           = $data['created_by'];
        $this->publish_at           = $data['publish_at'];
        $this->end_at               = $data['end_at'];
        $this->status               = $data['status'];
        $this->pledge               = $data['pledge'];
        $this->new                  = $data['new'];

        $this->save();

        return $this->id;

    }

    /**
     * @return array
     * @desc 获取九省心项目产品线
     */
    public static function getJSXProductLine()
    {

        return self::PROJECT_PRODUCT_LINE_JSX;

    }

    /**
     * @return array
     * @desc 获取九安心项目产品线
     */
    public static function getJAXProductLine()
    {

        return self::PROJECT_PRODUCT_LINE_JAX;

    }

    /**
     * @return array
     * @desc 获取闪电付项目产品线
     */
    public static function getSDFProductLine()
    {

        return self::PROJECT_PRODUCT_LINE_SDF;

    }

    /**
     * @param string $productLine
     * @return $this
     * @desc 获取项目所属产品线类型
     */
    public function getProductLineParam($productLine='')
    {

        if( !$productLine ){

            return $this;

        }

        $this->_sql_builder = $this->_sql_builder->where('product_line', $productLine);

        return $this;
    }


    /**
     * @desc 获取项目多个产品线类型
     * @param array $production
     * @return $this
     */
    public function getMoreProductLineParam( $productLine = [] )
    {

        if( empty( $productLine ) ){

            return $this;

        }

        $this->_sql_builder = $this->_sql_builder->whereIn('product_line', $productLine);

        return $this;

    }

    /**
     * @param string $status
     * @return $this
     * @desc 获取状态
     */
    public function getStatusParam($status='')
    {

        if( !$status ){

            return $this;

        }

        if(is_string($status))
            $this->_sql_builder = $this->_sql_builder->where('status', $status);
        if(is_array($status))
            $this->_sql_builder = $this->_sql_builder->whereIn('status', $status);

        return $this;
    }

    /**
     * @return $this
     * @desc 去除普付宝的项目标志
     */
    public function getPledgeParam()
    {

        $this->_sql_builder = $this->_sql_builder->where('pledge', '=', 0);

        return $this;

    }

    /**
     * @return $this
     * @desc 去除普付宝的项目标志
     */
    public function getSdfParam()
    {

        $this->_sql_builder = $this->_sql_builder->where('product_line', '!=', self::PROJECT_PRODUCT_LINE_SDF);

        return $this;

    }

    /**
     * @return $this
     * @desc 已完结
     */
    public function getFinishedStatusParam(){

        $this->_sql_builder = $this->_sql_builder->where('status', '>=', self::STATUS_REFUNDING);

        return $this;

    }

    /**
     * @return array
     * @desc 获取全部定义常量
     */
    public static function getConstants() {
        $constArr = new \ReflectionClass(__CLASS__);
        return $constArr->getConstants();
    }

    /**
     * @param string $status
     * @return $this
     * @desc 获取Id
     */
    public function getIdParam($id = ''){

        if( !$id ){
            return $this;
        }
        $this->_sql_builder = $this->_sql_builder->where('id', $id);
        return $this;

    }

    /**
     * @param ids
     * @return $this
     * @desc 获取Id
     */
    public function getIdsParam($ids = false){
        if($ids === false)
            return $this;

        if(!is_array($ids)) {
            return $this;
        }

        if(empty($ids)){
            $ids = [0];
        }

        $this->_sql_builder = $this->_sql_builder->whereIn('id', $ids);
        return $this;
    }

    /**
     * @param $id
     * @return mixed
     * @desc 删除操作记录
     */
    public function doDelete($id){

        return $this->where('id',$id)->delete();

    }

    /**
     * @param $id
     * @return mixed
     * @desc 更新项目为审核失败
     */
    public function updateStatusAuditeFail($id)
    {

        $obj = $this->getObj($id);

        return $obj->where('id', $obj->id)
            ->where('status', self::STATUS_UNAUDITED)
            ->update(
                ['status' => self::STATUS_AUDITE_FAIL]
            );

    }

    /**
     * @param $id
     * @return mixed
     * @desc 更新项目为审核通过，即未发布
     */
    public function updateStatusUnPublish($id)
    {

        $obj = $this->getObj($id);

        return $obj->where('id', $obj->id)
            ->where('status', self::STATUS_UNAUDITED)
            ->update(
                ['status' => self::STATUS_UNPUBLISH]
            );

    }



    /**
     * @param $id
     * @return mixed
     * @desc 更新项目为还款中
     */
    public function updateStatusInvesting($id)
    {

        return $this->where('id', $id)
            ->where('status', self::STATUS_UNPUBLISH)
            ->update([
                'status'=> self::STATUS_INVESTING
            ]);

    }

    /**
     * @param $id
     * @param $publishTime
     * @param $endAt
     * @param $investTime
     * @return mixed
     * @desc 自动更新项目为投资中，需要定期发布时间
     */
    public function autoUpdateStatusInvesting($id,$publishTime, $endAt='', $investTime='')
    {

        $setArr = [
            'status'        => self::STATUS_INVESTING,
            'publish_at'    => $publishTime,
        ];

        if(!empty($endAt)){
            $setArr['end_at'] = $endAt;
        }

        if(!empty($investTime)){
            $setArr['invest_time'] = $investTime;
        }

        return $this::where('id', $id)
            ->where('status', self::STATUS_UNPUBLISH)
            ->update($setArr);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 更新项目为还款中
     */
    public function updateStatusRefunding($id)
    {

        return self::where('id', $id)
            ->where('status', self::STATUS_INVESTING)
            ->update(
                [
                    'status' => self::STATUS_REFUNDING,
                    'full_at'=> ToolTime::dbNow()
                ]
            );

    }

    /**
     * @param $id
     * @param $endAt
     * @return mixed
     * @desc 更新项目为还款中
     */
    public function updateNewStatusRefunding($id, $endAt)
    {

        return self::where('id', $id)
            ->where('status', self::STATUS_INVESTING)
            ->where('new', self::IS_NEW)
            ->update(
                [
                    'status' => self::STATUS_REFUNDING,
                    'full_at'=> ToolTime::dbNow(),
                    'end_at' => $endAt
                ]
            );

    }

    /**
     * @param $id
     * @return mixed
     * @desc 更新项目为完结
     */
    public function updateStatusFinished($id, $times)
    {

        $obj = $this->getObj($id);

        return $obj->where('id', $obj->id)
            ->where('end_at', '<=', $times)
            ->where('status', self::STATUS_REFUNDING)
            ->update(
                ['status' => self::STATUS_FINISHED]
            );

    }

    /**
     * @desc 通过多个项目id更改项目完结状态
     * @author lgh-dev
     * @param $projectIds
     * @param $times
     * @return mixed
     */
    public function updateStatusFinishedByIds($projectIds, $times){

        return $this->whereIn('id',$projectIds)
            ->where('end_at', '<=', $times)
            ->where('status', self::STATUS_REFUNDING)
            ->update(
                ['status' => self::STATUS_FINISHED]
            );
    }

    /**
     * @param $type
     * @return $this|string
     * @desc 获取type条件
     */
    public function getTypeParam($type){

        if( !$type ){

            return '';

        }

        $this->_sql_builder = $this->_sql_builder->where('type', $type);

        return $this;

    }

    /**
     * @param $type
     * @return string
     * @desc 通过类型，获取一个未发布的项目
     */
    public function getOneUnPublishByType($type)
    {

        $res = $this->where('type', $type)
                    ->where('status', self::STATUS_UNPUBLISH)
                    ->where('pledge', 0)
                    ->orderBy('id')
                    ->get()
                    ->toArray();

        return ToolArray::arrayToSimple($res);

    }

    /**
     * @param $data
     * @return mixed
     * @desc 通过$id,更新记录
     */
    public function doUpdate($id,$data){

        return $this->where('id',$id)->update($data);

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 通过ids 获取项目列表
     */
    public function getListByProjectIds($projectIds)
    {

        return $this->whereIn('id', $projectIds)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取最新的一个闪电付息的六月期项目信息
     */
    public function getHomeSdfSix()
    {

        return self::where('product_line', self::PROJECT_PRODUCT_LINE_SDF)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->where('type', self::INVEST_TIME_MONTH_SIX)
            ->orderBy('status')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取最新的一个闪电付息的十二月期项目信息
     */
    public function getHomeSdfTwelve()
    {

        return self::where('product_line', self::PROJECT_PRODUCT_LINE_SDF)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->where('type', self::INVEST_TIME_MONTH_TWELVE)
            ->orderBy('status')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取最新的一个九省心一月期
     */
    public function getHomeJsxOne()
    {

        return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->where('type', self::INVEST_TIME_DAY_ONE)
            ->where('pledge', '=', 0)
            ->orderBy('status')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取最新的一个九省心三月期
     */
    public function getHomeJsxThree()
    {

        return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->where('type', self::INVEST_TIME_MONTH_THREE)
            ->where('invest_time', self::INVEST_TIME_MONTH_THREE)
            ->where('pledge', '=', 0)
            ->orderBy('status')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取最新的一个九省心六月期
     */
    public function getHomeJsxSix()
    {

        return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->where('type', self::INVEST_TIME_MONTH_SIX)
            ->where('invest_time', self::INVEST_TIME_MONTH_SIX)
            ->where('pledge', '=', 0)
            ->orderBy('status')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取最新的一个九省心十二月期
     */
    public function getHomeJsxTwelve()
    {

        return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->where('type', self::INVEST_TIME_MONTH_TWELVE)
            ->where('invest_time', self::INVEST_TIME_MONTH_TWELVE)
            ->where('pledge', '=', 0)
            ->orderBy('status')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取最新的一个九安心的项目信息
     */
    public function getHomeJax()
    {

        return self::where('product_line', self::PROJECT_PRODUCT_LINE_JAX)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->where('pledge', '=', 0)
            ->orderBy('status')
            ->orderBy('id', 'desc')
            ->take(1)
            ->get()
            ->toArray();

    }

    /**
     * @param $time
     * @desc 通过完结日期获取项目ids
     */
    public function getFinishedIds($time)
    {

        return $this->select('id')
            ->where('end_at', $time)
            ->where('status', self::STATUS_FINISHED)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

    }

    /**
     * @param $page
     * @param $size
     * @return mixed
     * @desc 获取普付宝的项目列表
     */
    public function getPfbList($page, $size){

        $offset = $this->getLimitStart($page, $size);

        return $this->where('pledge', self::PLEDGE)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->orderBy('id', 'desc')
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

    }

    /**
     * @return array
     * @desc 获取普付宝投资中项目详情
     */
    public function getPfbDetail(){
        $result =  $this->where('pledge', self::PLEDGE)
            ->where('status', '>=', self::STATUS_INVESTING)
            ->orderBy('id', 'desc')
            ->first();

        return $this->dbToArray($result);
    }

    /**
     *
     * 获取投资中状态，有投资且未投满 3,6,12月的九省心项目
     * 还款状态为先息后本
     */
    public function getUnRefundProject(){

        $investTimeArr = [
            self::INVEST_TIME_MONTH_THREE,
            self::INVEST_TIME_MONTH_SIX,
            self::INVEST_TIME_MONTH_TWELVE
        ];

        return $this->select('id')
            ->where('status',self::STATUS_INVESTING)
            ->where('product_line',self::PROJECT_PRODUCT_LINE_JSX)
            ->where('refund_type',self::REFUND_TYPE_ONLY_INTEREST)
            ->where('publish_at','<',date('Y-m-d H:i:s'))
            ->where('invested_amount','>',0)
            ->whereIn('invest_time',$investTimeArr)
            ->get()
            ->toArray();
    }

    /**
     * 通过发布时间,九省心
     * @param string $times publish_at时间
     * @param array $investTime 项目期限
     * @return mixed
     * @desc,为秒杀活动读取的项目数据
     */
    public function getTimingProject( $times ,$investTime)
    {
        $status     =   [self::STATUS_UNPUBLISH,self::STATUS_INVESTING];
        $projectLine=   [self::PROJECT_PRODUCT_LINE_JAX,self::PROJECT_PRODUCT_LINE_JSX];
        return $this->whereIn('status',$status)
                    ->whereIn('product_line',$projectLine)
                    ->whereIn('publish_at',$times)
                    ->where('type',$investTime)
                    ->orderBy('status','desc')
                    ->orderBy('publish_at','asc')
                    ->take(1)
                    ->get()
                    ->toArray();
    }

    /*
     * 理财项目期限对应的关系
     * 只限制在九省心,九安心,不对应普付宝和闪电付息
     */
    public static function investTimeMappedString()
    {
        return [
            'one'   =>  self::INVEST_TIME_DAY_ONE,
            'three' =>  self::INVEST_TIME_MONTH_THREE,
            'six'   =>  self::INVEST_TIME_MONTH_SIX,
            'twelve'=>  self::INVEST_TIME_MONTH_TWELVE,
            'jax'   =>  self::INVEST_TIME_DAY,
        ];
    }


    public function getProjectIdsStatistics( $statistics )
    {

        $status = [self::STATUS_REFUNDING, self::STATUS_INVESTING, self::STATUS_FINISHED];
        $projectLine = [self::PROJECT_PRODUCT_LINE_JAX, self::PROJECT_PRODUCT_LINE_JSX];

        $obj = $this->select("id")->whereIn('status', $status)
            ->whereIn('product_line', $projectLine);

        //发布时间
        if (!empty($statistics['start_time'])) {
            $obj = $obj->where("publish_at", ">=", $statistics['start_time']);
        }
        if (!empty($statistics['end_time'])) {
            $obj = $obj->where("publish_at", "<=", $statistics['end_time']);
        }
        //产品期限
        if (!empty($statistics['project_type'])) {
            $obj = $obj->whereIn("type", $statistics['project_type']);
        }

        return $obj->get()->toArray();
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @desc 根据项目的最后更新时间获取项目列表,主要功能为,后台按时间查询项目满标的列表
     */
    public function getRefundingProjectListByUpdateTime($startTime, $endTime)
    {

        $timesArr = [$startTime, $endTime];

        return $this->whereIn('status', [self::STATUS_REFUNDING, self::STATUS_FINISHED])
            ->whereBetween('updated_at', $timesArr)
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();

    }

    /**
     * @param $startTime
     * @param $endTime
     * @param $page
     * @param $size
     * @return array
     * @desc 通过时间获取非普付宝的项目(九安心,九省心,闪电付息),
     *
     */
    public function getProjectWithTime( $startTime,$endTime ,$pageIndex=0,$pageSize=30)
    {
        $dbPrefix   = env('DB_PREFIX');

        $start      = ( max(0, $pageIndex -1) ) * $pageSize;

        $sql        =   "select t2.*,max(t1.created_at) as max_invest_time from ".$dbPrefix."invest as t1,".$dbPrefix."project as t2
        where t1.project_id=t2.id and pledge <>".self::PLEDGE." and status in(".self::STATUS_REFUNDING.",".self::STATUS_FINISHED.") and t1.invest_type=".InvestDb::INVEST_TYPE." group by project_id
        having max_invest_time >='".$startTime."' and max_invest_time < '".$endTime."' limit ".$start.",".$pageSize;

        $list       =   app('db')->select($sql);

        $list       =   ToolArray::objectToArray($list);

        $sqlTotal   =   "select t2.*,max(t1.created_at) as max_invest_time from ".$dbPrefix."invest as t1,".$dbPrefix."project as t2
        where t1.project_id=t2.id and pledge <>".self::PLEDGE." and status in(".self::STATUS_REFUNDING.",".self::STATUS_FINISHED.") and t1.invest_type=".InvestDb::INVEST_TYPE." group by project_id
        having max_invest_time >='".$startTime."' and max_invest_time < '".$endTime."' ";

        $listTotal   =   app('db')->select($sqlTotal);

        return [ 'list'=> $list, 'total'=> count($listTotal) ];
    }

    /**
     * @param $projectIds
     * @param $endAt
     * @return mixed
     * @desc 更新项目的完结日期
     */
    public function updateProjectBeforeRefund($projectIds, $endAt){

        return self::whereIn('id', $projectIds)
            ->whereIn('status', [self::STATUS_REFUNDING, self::STATUS_INVESTING])
            ->update(
                [
                    'status'        => self::STATUS_REFUNDING,
                    'end_at'        => $endAt,
                    'before_refund' => 1
                ]
            );

    }

    /**
     * @desc    获取时间段内已完结项目
     * @date    2016年11月21日
     * @author  @llper
     * @param   $startTime
     * @param   $endTime
     * @return
     *
     */
    public function getFinishedProjectByTime($startTime, $endTime , $isBefore ="")
    {
        $dbObj  =   $this->where('status', self::STATUS_FINISHED)
                    ->whereBetween('end_at', [$startTime, $endTime]);

        if($isBefore == self::BEFORE_REFUND){

            $dbObj  =   $dbObj->where("before_refund",self::BEFORE_REFUND);
        }

        return $dbObj->get()->toArray();

    }

    /**
     * @param $startTime
     * @param $endTime
     * @param int $pageIndex
     * @param int $pageSize
     * @return mixed
     * @desc 获取投资中的项目
     */
    public function getInvestIngProject($startTime , $endTime ,$pageIndex = 1,$pageSize = 50)
    {
        $offset = $this->getLimitStart($pageIndex, $pageSize);

        $list   = $this->where('status', self::STATUS_INVESTING)
            ->whereBetween('publish_at', [$startTime, $endTime])
            ->where('status',self::STATUS_INVESTING)
            ->where("pledge" ,"<>" , self::PLEDGE)
            ->skip($offset)
            ->take($pageSize)
            ->get()
            ->toArray();

        $total  =   $this->where('status', self::STATUS_INVESTING)
                    ->whereBetween('publish_at', [$startTime, $endTime])
                    ->where('status',self::STATUS_INVESTING)
                    ->where("pledge" ,"<>" , self::PLEDGE)
                    ->count("id");
        return [ 'list'=> $list, 'total'=> $total ];
    }

    /**
     * @param string $startTime
     * @param string $endTime
     * @return mixed
     * @desc 通过项目满标时间获取项目的信息
     */
    public function getProjectByFullTime($startTime = '' , $endTime = '',$isPledge=0)
    {
        $dbObj      =   self::whereIn('status',[self::STATUS_FINISHED,self::STATUS_INVESTING,self::STATUS_REFUNDING]);

        if( !empty( $startTime ) ){
            $dbObj  =   $dbObj->where('full_at','>=',$startTime);
        }

        if( !empty($endTime) ){
            $dbObj  =   $dbObj->where('full_at','<=',$endTime);
        }
        //普付宝项目
        if( $isPledge == self::PLEDGE ){

            $dbObj  =   $dbObj->where('pledge',self::PLEDGE);
        }
        //非普付宝项目
        if( $isPledge == 2 ){

            $dbObj  =   $dbObj->where('pledge','<>',self::PLEDGE);
        }

        return $dbObj->get()->toArray();
    }

    /**
     * @param string $investTime
     * @param string $projectLine
     * @param string $startTime
     * @param string $endTime
     * @return mixed
     * @desc 返回项目Id
     */
    public static function getProjectIdByProductLineAndTime($projectLine='' ,$investTime = '', $startTime = '',$endTime = '')
    {
        $dbObj      =   self::select('id');

        if( !empty($projectLine) ){

            $dbObj  =   $dbObj->where('product_line',$projectLine);
        }
        if( !empty($investTime) ){

            $dbObj  =   $dbObj->where('type',$investTime);
        }
        if( !empty($startTime) ){

            $dbObj  =   $dbObj->where('publish_at','>=',$startTime);
        }
        if( !empty($endTime) ){

            $dbObj  =   $dbObj->where('publish_at','<=',$endTime);
        }

        return $dbObj->where('status' ,'<>' ,self::STATUS_UNPUBLISH)
                      ->where('pledge','<>',self::PLEDGE)
                      ->get()
                      ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取满标项目的占比数
     */
    public static function getProjectTotalHundredPercent()
    {

        $return =   self::select('product_line' , 'type', \DB::raw('count(id) as total'), \DB::raw('sum( UNIX_TIMESTAMP(full_at)- UNIX_TIMESTAMP(publish_at)) as full_date'))
                    ->whereIn('status',[self::STATUS_INVESTING,self::STATUS_REFUNDING,self::STATUS_FINISHED])
                    ->where(\DB::raw('total_amount-invested_amount') , '<=' ,'0' )
                    ->groupBy('type')
                    ->orderBy('type','asc')
                    ->get()
                    ->toArray();

        return $return;
    }

    /**
     * @return mixed
     * @desc 获取项目总数
     */
    public static function getProjectTotal()
    {
        return self::whereIn('status', [self::STATUS_INVESTING,self::STATUS_REFUNDING,self::STATUS_FINISHED])->count("id");
    }

    /**
     * @desc    获取投资、还款中项目
     * @author  @linglu
     *
     **/
    public function getInvestProjectIds(){

        $result = self::select('id')->whereIn('status', [self::STATUS_INVESTING, self::STATUS_REFUNDING])
                ->get()
                ->toArray();
        return $result;
    }

    /**
     * @desc    保理一月期项目
     * @author  @linglu
     *
     **/
    public function getProjectInvestOne(){

        $result = self::select('id')->whereIn('status', [self::STATUS_INVESTING, self::STATUS_REFUNDING])
            ->where('invest_time','>',12)
            ->where('invest_time','<',32)
            ->get()
            ->toArray();
        return $result;
    }

    /**
     * @desc    九安心、三、六、十二、月期项目
     * @author  @linglu
     **/
    public function getProjectInvestStat(){
        $result = self::select(
                        \DB::raw(" SUM(invested_amount*profit_percentage) / SUM(invested_amount) AS loanAvgRate "),
                        \DB::raw(" SUM(IF( invest_time > 31,1,0 ))*2 AS twoMonth "),
                        \DB::raw(" SUM(IF( invest_time > 2 AND invest_time <= 12, invest_time,0 )) AS moreMonth")
                    )
                    ->whereIn('status', [self::STATUS_INVESTING, self::STATUS_REFUNDING])
                    ->first();

        return self::dbToArray($result);

    }


    /** TODO: APP4.1.3-首页改版项目信息 **/
    /**
     *
     * @desc    获取新手标项目
     * @param   $status     项目状态    在投、已满标、已完结
     * @param   $orderBy    排序
     * @return  array
     *
     * 逻辑：
     * 1、新手项目
     *      未满标项目、
     *      最后满标项目
     */
    public function getApp413HomeNovice( $type='invest' )
    {

        if($type == "invest"){
            #TODO:最早发布的未满标项目
            return self::where('pledge',self::PLEDGE)
                ->where('status',   self::STATUS_INVESTING)
                ->orderBy('publish_at')
                ->take(1)
                ->get()
                ->toArray();

        }elseif($type == "refund"){
            #TODO: 最晚发布的满标项目
            return self::where('pledge',self::PLEDGE)
                ->where('status', '>=',  self::STATUS_REFUNDING)
                ->orderBy('status')
                ->orderBy('publish_at', 'desc')
                ->take(1)
                ->get()
                ->toArray();

        }else{
            #TODO: 最新项目
            return self::where('pledge',self::PLEDGE)
                ->where('status',       '>=', self::STATUS_INVESTING)
                ->orderBy('status')
                ->orderBy('publish_at', 'desc')
                ->take(1)
                ->get()
                ->toArray();
        }


    }

    /**
     * @return mixed
     * @desc 获取最新的一个九省心一月期
     */
    public function getApp413HomeJsxOne( $type='invest' )
    {

        if($type == "invest"){
            #TODO:最早发布的未满标项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   self::STATUS_INVESTING)
                ->where('type',     self::INVEST_TIME_DAY_ONE)
                ->where('pledge',   0)
                ->take(1)
                ->get()
                ->toArray();

        }elseif($type == "refund"){
            #TODO: 最晚发布的满标项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   self::STATUS_REFUNDING)
                ->where('type',     self::INVEST_TIME_DAY_ONE)
                ->where('pledge',   0)
                ->orderBy('publish_at', 'desc')
                ->take(1)
                ->get()
                ->toArray();

        }else{
            #TODO: 最新项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   '>=', self::STATUS_INVESTING)
                ->where('type',     self::INVEST_TIME_DAY_ONE)
                ->where('pledge',   '=', 0)
                ->orderBy('status')
                ->orderBy('id',     'desc')
                ->take(1)
                ->get()
                ->toArray();
        }

    }

    /**
     * @return mixed
     * @desc 获取最新的一个九省心三月期
     */
    public function getApp413HomeJsxThree( $type='invest' )
    {

        if($type == "invest"){
            #TODO:最早发布的未满标项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   self::STATUS_INVESTING)
                ->where('type',     self::INVEST_TIME_MONTH_THREE)
                ->where('pledge',   0)
                ->take(1)
                ->get()
                ->toArray();

        }elseif($type == "refund"){
            #TODO: 最晚发布的满标项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   self::STATUS_REFUNDING)
                ->where('type',     self::INVEST_TIME_MONTH_THREE)
                ->where('pledge',   0)
                ->orderBy('publish_at', 'desc')
                ->take(1)
                ->get()
                ->toArray();

        }else{
            #TODO: 最新项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   '>=', self::STATUS_INVESTING)
                ->where('type',     self::INVEST_TIME_MONTH_THREE)
                ->where('pledge',   '=', 0)
                ->orderBy('status')
                ->orderBy('id',     'desc')
                ->take(1)
                ->get()
                ->toArray();
        }


    }

    /**
     * @return mixed
     * @desc 获取最新的一个九省心六月期
     */
    public function getApp413HomeJsxSix( $type='invest' )
    {


        if($type == "invest"){
            #TODO:最早发布的未满标项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   self::STATUS_INVESTING)
                ->where('type',     self::INVEST_TIME_MONTH_SIX)
                ->where('pledge',   0)
                ->take(1)
                ->get()
                ->toArray();

        }elseif($type == "refund"){
            #TODO: 最晚发布的满标项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   self::STATUS_REFUNDING)
                ->where('type',     self::INVEST_TIME_MONTH_SIX)
                ->where('pledge',   0)
                ->orderBy('publish_at', 'desc')
                ->take(1)
                ->get()
                ->toArray();

        }else{
            #TODO: 最新项目
            return self::where('product_line', self::PROJECT_PRODUCT_LINE_JSX)
                ->where('status',   '>=', self::STATUS_INVESTING)
                ->where('type',     self::INVEST_TIME_MONTH_SIX)
                ->where('pledge',   '=', 0)
                ->orderBy('status')
                ->orderBy('id',     'desc')
                ->take(1)
                ->get()
                ->toArray();
        }



    }



}
