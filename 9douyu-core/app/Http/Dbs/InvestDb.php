<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/15
 * Time: 下午1:46
 */

namespace App\Http\Dbs;

use App\Tools\ToolArray;


class InvestDb extends JdyDb{

    protected $table = 'invest';
    public $timestamps = false;

    const INVEST_TYPE = 0,              //定期项目投资类型
          INVSET_TYPE_CREDIT_ASSIGN = 1; //债权转让项目投资类型


    /**
     * @param $data
     * @return bool
     * @desc 创建资金记录
     */
    public function add($data)
    {

        $this->project_id = $data['project_id'];

        $this->user_id = $data['user_id'];

        $this->cash = abs($data['cash']);

        $this->invest_type = $data['invest_type'];

        $this->assign_project_id = $data['assign_project_id'];

        $this->save();

        return $this->id;

    }

    /**
     * @param $id
     * @return mixed
     * @desc 获取user对象
     */
    public function getObj($id)
    {

        return $this->find($id);

    }

    /**
     * @param $id
     * @return mixed
     * @desc 获取投资记录信息
     */
    public function getInfoById($id)
    {

        $res = $this->where('id', $id)
            ->get()
            ->toArray();

        return ToolArray::arrayToSimple($res);

    }

    /**
     * @param int $size
     * @return mixed
     * @desc 获取最新的投资记录
     */
    public function getInvestNew($size = 30){

        $res = $this->orderBy('created_at','desc')
                    ->skip(0)
                    ->take($size)
                    ->get()
                    ->toArray();

        return $res;
    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据开始结束日期获取投资记录总额
     */
    public function getInvestAmountByDate($start = '',$end = ''){

        $obj = $this->select(\DB::raw('sum(cash) as cash'), \DB::raw('count(id) as total') ,\DB::raw('DATE_FORMAT(created_at,\'%Y%m%d\') as date'));

        if(!empty($start) && !empty($end)){

            $end   = date('Y-m-d',strtotime($end)+86400);

            $obj   = $obj->whereBetween('created_at',[$start,$end]);
        }

        $res = $obj->groupBy('date')
                    ->orderBy('date','desc')
                    ->get()
                    ->toArray();

        return $res;

    }

    /**
     * @param string $start
     * @param string $end
     * @return mixed
     * @desc 根据时间段获取投资总额
     */
    public function getInvestTermTotal($start = '', $end = ''){

        $obj = $this->select(\DB::raw('sum(cash) as cash'));

        if(!empty($start) && !empty($end)){

            $end   = date('Y-m-d',strtotime($end)+86400);

            $obj   = $obj->whereBetween('created_at',[$start,$end]);
        };

        $res = $obj->first();

        return $res;

    }


    /**
     * @param $projectIds
     * @return mixed
     * 获取指定项目的投资记录
     */
    public function getInvestListByProjectIds($projectIds){

        return $this->whereIn('project_id',$projectIds)
            ->get()
            ->toArray();
    }

    /**
     * @desc 通过多个投资ID获取投资记录
     * @param $investIds
     * @return mixed
     */
    public function getInvestByIds($investIds){

        return $this->whereIn('id',$investIds)
            ->get()
            ->toArray();
    }

    /**
     * @param $ids
     * @return mixed
     * @desc 根据ids获取列表
     */
    public function getListByUserIdIds($userId, $ids)
    {

        return $this->where('user_id', $userId)
            ->whereIn('id', $ids)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * @desc 定期投资总额
     */
    public function getInvestTotalCash()
    {

        $return = $this->select(\DB::raw('sum(cash) as cash'))
            ->first();

        return $return->cash;

    }

    /**
     * @param $creditAssignId
     * @desc 根据债转Id获取债转项目的投资人
     * @return array
     */
    public function getInvestCreditAssign($creditAssignId)
    {

        $return = $this->where('assign_project_id',$creditAssignId)
            ->first();

        return $this->dbToArray($return);

    }

    /**
     * @param $projectId
     * @param $userId
     * @return mixed
     * 获取用户投资某个项目指定金额的所有记录
     */
    public function getByProjectIdAndUserId($projectId,$userId,$cash){

        return $this->where('project_id',$projectId)
            ->where('user_id',$userId)
            ->where('cash',$cash)
            ->get()
            ->toArray();
    }

    /**
     * @param $investId
     * @return array
     * @desc 确认转让信息页面数据
     */
    public function getInvestInfoById($investId){

        $result = self::select('invest.*', 'p.refund_type', 'p.product_line', 'p.type', 'p.profit_percentage', 'p.name', 'p.end_at')
            ->join("project as p", 'p.id', '=', "invest.project_id")
            ->where('invest.id', $investId)
            ->first();

        return $this->dbToArray($result);

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 获取正确投资的数据
     */
    public function getNormalInvestListByProjectIds($projectIds){

        return $this->whereIn('project_id',$projectIds)
                    ->where("invest_type",self::INVEST_TYPE)
                    ->get()
                    ->toArray();
    }

    /**
     * @param string $projectIds
     * @return array
     * @desc 从核心获取最后一次投资的数据(不包含原项目债转的记录)
     */
    public function getLastInvestTimeByProjectId( $projectIds = array())
    {
        return self::select(\DB::raw('max(created_at) as last_invest_time'), \DB::raw('max(id) as id'), 'project_id')
                    ->whereIn('project_id', $projectIds)
                    ->where("invest_type",self::INVEST_TYPE)
                    ->groupBy('project_id')
                    ->get()
                    ->toArray();
    }


    /**
     * @param $userId
     * @param int $page
     * @param int $size
     * @return array
     * @desc 投资记录
     */
    public function getInvestListByUserId($userId, $page = 1, $size = 10)
    {
        $offset = $this->getLimitStart($page, $size);

        $list   = $this->select('invest.*', 'p.refund_type', 'p.product_line', 'p.type', 'p.profit_percentage', 'p.name', 'p.end_at')
            ->join("project as p", 'p.id', '=', "invest.project_id")
            ->where('invest.user_id', $userId)
            ->where('p.pledge', '<>', ProjectDb::PLEDGE)
            ->orderBy('invest.id','desc')
            ->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

        $total  =   $this->where('invest.user_id', $userId)
            ->join("project as p", 'p.id', '=', "invest.project_id")
            ->where('invest.user_id', $userId)
            ->where("pledge" ,"<>" , ProjectDb::PLEDGE)
            ->count("invest.id");

        return [ 'list'=> $list, 'total'=> $total ];
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 用户总资产
     */
    public static function getUserNoFullAtProjectPrincipal( $userId ){

        $result = self::select('p.product_line', \DB::raw('sum(cash) as principal'))
            ->join('project as p', 'p.id', '=', 'invest.project_id')
            ->where('invest.user_id', $userId)
            ->where('invest.invest_type', self::INVEST_TYPE)
            ->where('p.new', ProjectDb::IS_NEW)
            ->where('p.status', ProjectDb::STATUS_INVESTING)
            ->groupBy('product_line')
            ->get()
            ->toArray();

        return $result;

    }

    /**
     * @param $userId
     * @return mixed
     * @desc  根据用户Id获取该用户投资记录（用来判断用户是否投资）
     */
    public static function getUserInvestDataByUserId($userId){
        $result = self::where('user_id', $userId)
            ->limit(1)
            ->get()
            ->toArray();

        return $result;
    }
}