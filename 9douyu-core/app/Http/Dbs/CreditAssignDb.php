<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/25
 * Time: 15:10
 */

namespace App\Http\Dbs;

use App\Tools\ToolTime;

class CreditAssignDb extends JdyDb{

    protected $table = "credit_assign_project";


    CONST
          CREDIT_ASSIGN_NAME = '变现宝',
          STATUS_INVESTING = 100,   //投资中
          STATUS_CANCEL    = 110,   //已取消
          STATUS_FINISHED  = 120,   //原项目已完结
          STATUS_SELL_OUT  = 130;   //已售罄

    /**
     * @param $data
     * @return mixed
     * 添加记录
     */
    public function addRecord($data){

        return self::insert($data);
    }


    /**
     * @param $id
     * @param $cash
     * @return mixed
     * 债转项目投资
     */
    public function invest($id,$cash,$isFull=true){

        $data = ['invested_amount' => \DB::raw(sprintf('`invested_amount`+%.2f', $cash))];

        if($isFull){
            $data['status'] = self::STATUS_SELL_OUT;
        }
        return self::where('id',$id)
                ->where('status',self::STATUS_INVESTING)
                ->update($data);
    }


    /**
     * @param $id
     * @return mixed
     * 取消债转
     */
    public function cancel($id){

        return self::where('id',$id)
            ->where('status',self::STATUS_INVESTING)
            ->update(['status' => self::STATUS_CANCEL]);
    }

    /**
     * @param $id
     * @return mixed
     * 根据主键获取项目信息
     */
    public function getObj($id){

        return self::find($id);
    }

    /**
     * @param $investId
     * 根据投资ID获取对应的债权项目信息
     */
    public function getByInvestId($investId){

        return self::where('invest_id',$investId)
            ->where('status','<>',self::STATUS_CANCEL)
            ->first();
    }

    /**
     * @param $userId
     * @param int $minCash
     * @param array $CAInvestIds
     * @return mixed
     * @desc 用户可转让列表
     */
    public function getUserAbleCreditAssign( $userId, $minCash=0, $CAInvestIds=[], $limitDays=30)
    {
        $dbPrefix = env('DB_PREFIX');

        $date = ToolTime::dbDate();

        $CAInvestIds = empty($CAInvestIds)?0:implode(',',$CAInvestIds);

        $sql = "select DISTINCT i.id as invest_id, i.cash, i.created_at as i_created_at, i.user_id, p.* from {$dbPrefix}invest i
                inner join {$dbPrefix}refund_record r on i.id=r.invest_id and i.`project_id`=r.project_id and i.cash=r.principal
                inner join {$dbPrefix}project p on i.`project_id` = p.id
                where r.user_id={$userId}
                and r.`principal`>{$minCash}
                and r.`status`=".RefundRecordDb::STATUS_ING.
                " and date_add(date(i.created_at), INTERVAL {$limitDays} DAY) <= '{$date}'
                and p.product_line!=".ProjectDb::PROJECT_PRODUCT_LINE_SDF.
                " and p.pledge!=".ProjectDb::PLEDGE.
                " and p.end_at > '{$date}'
                 and i.id not in ({$CAInvestIds})
                 and p.refund_type!=".ProjectDb::REFUND_TYPE_EQUAL_INTEREST;

        return app('db')->select($sql);

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 通过用户id获取债转中或已债转的记录
     */
    public function getNoCancelByUserId( $userId ){

        return self::where('user_id',$userId)
            ->where('status', '!=', self::STATUS_CANCEL)
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 转让中的项目
     */
    public function getUserDoingCreditAssign( $userId ){

        $date = ToolTime::dbDate();

        return self::where('user_id', $userId)
            ->where('status', self::STATUS_INVESTING)
            ->where('end_at', '>', $date)
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 已转让的项目
     */
    public function getUserDoneCreditAssign( $userId ){

        return self::where('user_id', $userId)
            ->where('status', self::STATUS_SELL_OUT)
            ->get()
            ->toArray();

    }

    /**
     * @param $page
     * @param $size
     * @return mixed
     * @desc 前台显示债权列表接口
     */
    public function getList($page, $size){

        $offset = $this->getLimitStart($page, $size);

        return self::select('credit_assign_project.*','p.refund_type','p.product_line','p.type','p.profit_percentage')
            ->join("project as p", 'p.id','=',"credit_assign_project.project_id")
            ->where('credit_assign_project.status', '!=', self::STATUS_CANCEL)
            ->where('credit_assign_project.status', '!=', self::STATUS_FINISHED)
            ->where('credit_assign_project.end_at', '>' ,ToolTime::dbDate())
            ->orderBy('credit_assign_project.status')
            ->orderBy('credit_assign_project.id', 'desc')
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

    }

    /**
     * @param $id
     * @return array
     * @desc 债转详情
     */
    public function getDetailById($id)
    {

        $result = self::select('credit_assign_project.*', 'p.refund_type', 'p.product_line', 'p.type', 'p.profit_percentage', 'p.name')
            ->join("project as p", 'p.id', '=', "credit_assign_project.project_id")
            ->where('credit_assign_project.id', $id)
            ->where('credit_assign_project.status', '!=', self::STATUS_CANCEL)
            ->where('credit_assign_project.status', '!=', self::STATUS_FINISHED)
            ->first();

        return $this->dbToArray($result);
    }

    /**
     * @param $investIds
     * @return mixed
     * 获取指定投资ID里面不可以债转的记录
     */
    public function getUnusableByInvestIds($investIds){

        return self::select('invest_id')
            ->whereIn('invest_id',$investIds)
            ->where('status','<>',self::STATUS_CANCEL)
            ->get()
            ->toArray();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户已转让的投资Id数组
     */
    public function getCreditAssignInvestIds( $userId ){

        return self::select('invest_id')
            ->where('user_id',$userId)
            ->where('status','=',self::STATUS_SELL_OUT)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 获取债权转让可投资个数
     */
    public function getInvestingCount(){

        return self::where('status',self::STATUS_INVESTING)
            ->where('end_at', '>', ToolTime::dbDate())
            ->count();

    }

    /**
     * @desc 统计债权转让的投资金额
     * @return mixed
     */
    public function getInvestTotalAmount(){

        $return = $this->select(\DB::raw('sum(invested_amount) as cash'))
            ->first();

        return $return->cash;
    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 提前还款,取消正在转让的债权项目
     */
    public function cancelByProjectIds($projectIds){

        $result = self::whereIn('project_id',$projectIds)
            ->where('status',self::STATUS_INVESTING)
            ->where('invested_amount', 0)
            ->get()
            ->toArray();

        if(!empty($result)){
            return self::whereIn('project_id',$projectIds)
                ->where('status',self::STATUS_INVESTING)
                ->where('invested_amount', 0)
                ->update(['status' => self::STATUS_CANCEL]);
        }

        return true;

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 提前还款更新债转项目的完结时间
     */
    public function beforeUpdateEndAt($projectIds){

        $result = self::whereIn('project_id',$projectIds)
            ->get()
            ->toArray();

        if(!empty($result)){
            return self::whereIn('project_id',$projectIds)
                ->update(['end_at'=>ToolTime::dbDate()]);
        }

        return true;
    }

    /**
     * @param $investId
     * @param $projectId
     * @param $userId
     * @return array
     * @desc 此条记录是否为已债转记录
     */
    public function isCreditAssign($investId, $projectId, $userId){

        $result = self::where('invest_id', $investId)
            ->where('project_id', $projectId)
            ->where('user_id', $userId)
            ->where('invested_amount', '>', 0)
            ->first();

        return $this->dbToArray($result);
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 转让中的项目
     */
    public function getUserDoingAssignmentRecord( $userId ,$page=1, $size=10){
        $offset = $this->getLimitStart($page, $size);
        $date = ToolTime::dbDate();

        $list = self::select('credit_assign_project.*','p.name','p.invest_time',"p.type AS project_type",'p.product_line')
            ->join("project as p", 'p.id','=',"credit_assign_project.project_id")
            ->where('credit_assign_project.user_id',$userId)
            ->where('credit_assign_project.status', self::STATUS_INVESTING)
            ->where('credit_assign_project.end_at', '>', $date)
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

        $total  =  self::where('user_id', $userId)
            ->where('status', self::STATUS_INVESTING)
            ->where('end_at', '>', $date)
            ->count();

        return [ 'list'=> $list, 'total'=> $total ];
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 转让中的项目
     */
    public function getUserDoingAssignmentTotalAmount( $userId ){

        $date = ToolTime::dbDate();

        return self::select(\DB::raw('sum(total_amount) as cash'))
            ->where('user_id', $userId)
            ->where('status', self::STATUS_INVESTING)
            ->where('end_at', '>', $date)
            ->get()
            ->first();

    }

}