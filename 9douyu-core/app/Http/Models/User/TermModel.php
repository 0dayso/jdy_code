<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/14
 * Time: 下午3:15
 */

namespace App\Http\Models\User;

use App\Http\Dbs\CreditAssignDb;
use App\Http\Dbs\JdyDb;
use App\Http\Dbs\ProjectDb;
use App\Http\Dbs\RefundRecordDb;
use App\Http\Dbs\UserDb;
use App\Http\Models\Model;
use DB;

class TermModel extends Model
{

    /**
     * @param int   $userId
     * @return bool
     * @throws \Exception
     * @desc 未完结项目数据（投资中＋还款中） for app
     */
    public static function getNoFinish($userId,$size){
        try{
            $res = DB::table('refund_record')
                ->join('project', 'refund_record.project_id', '=', 'project.id')
                ->join('invest', 'invest.id', '=', 'refund_record.invest_id')
                ->select('refund_record.invest_id',DB::raw('sum(interest) as total'),DB::raw('round(round(sum(principal)),2) as principal'),'refund_record.project_id','invest.created_at','project.name','project.end_at','project.profit_percentage','project.refund_type','project.type','project.invest_time', 'invest.invest_type', 'invest.assign_project_id')
                ->where('refund_record.user_id',$userId)
                ->whereIn('project.status',[ProjectDb::STATUS_REFUNDING,ProjectDb::STATUS_INVESTING])
                ->groupBy('refund_record.invest_id')
                ->orderBy('refund_record.invest_id', 'desc')
                ->paginate($size)
                ->toArray();
            return $res;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * @param int $userId
     * @return bool
     * @throws \Exception
     * @desc 未回款项目数据
     */
    public static function getRefunding($userId,$size){
        try{
            $res = DB::table('refund_record')
                ->join('project', 'refund_record.project_id', '=', 'project.id')
                ->join('invest', 'invest.id', '=', 'refund_record.invest_id')
                ->select('refund_record.invest_id',DB::raw('sum(interest) as total'),DB::raw('sum(principal) as principal'),'refund_record.project_id','invest.created_at','project.name','project.end_at','project.profit_percentage','project.refund_type','project.type','project.invest_time', 'invest.invest_type', 'invest.assign_project_id')
                ->where('refund_record.user_id',$userId)
                ->where('project.status',ProjectDb::STATUS_REFUNDING)
                ->groupBy('refund_record.invest_id')
                ->orderBy('refund_record.invest_id', 'desc')
                ->paginate($size)
                ->toArray();
            return $res;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }


    /**
     * @param int $userId
     * @return bool
     * @throws \Exception
     * @desc 已回款项目数据
     */
    public static function getRefunded($userId,$size){
        try{
            $res = DB::table('refund_record')
                ->join('project', 'refund_record.project_id', '=', 'project.id')
                ->join('invest', 'invest.id', '=', 'refund_record.invest_id')
                ->select('refund_record.invest_id',DB::raw('sum(interest) as total'),DB::raw('sum(principal) as principal'),'refund_record.project_id','invest.created_at','project.name','project.end_at','project.profit_percentage','project.refund_type','project.type','project.invest_time', 'invest.invest_type', 'invest.assign_project_id')
                ->where('refund_record.user_id',$userId)
                ->where('project.status',ProjectDb::STATUS_FINISHED)
                ->groupBy('refund_record.invest_id')
                ->orderBy('refund_record.invest_id', 'desc')
                ->paginate($size)
                ->toArray();
            return $res;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }


    /**
     * @param int $userId
     * @return bool
     * @throws \Exception
     * @desc 投资中项目数据
     */
    public static function getInvesting($userId,$size){
        try{
            $res = DB::table('project')
                ->join('invest', 'invest.project_id', '=', 'project.id')
                ->select('invest.id','project.name','project.invested_amount','project.total_amount','project.profit_percentage','project.refund_type','project.type','project.invest_time','invest.cash','invest.created_at')
                ->where('project.status',ProjectDb::STATUS_INVESTING)
                ->where('invest.user_id',$userId)
                ->paginate($size)
                ->toArray();
            return $res;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $userId
     * @param $investId
     * @return array
     * @desc 用户项目的下期回款
     */
    public static function getNextRefund($userId,$investId){
        try{
            $date = date('Y-m-d');
            $res = DB::table('refund_record')
                ->select('times','cash')
                ->where('user_id',$userId)
                ->where('invest_id',$investId)
                ->where('times','>',$date)
                ->first();
            return $res;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @desc 多个投资ID获取用户下一个回款列表
     * @param $userId
     * @param $investIds
     * @return mixed
     * @throws \Exception
     */
    public static function getNextRefundByInvestIds($userId,$investIds){

        try{
            $refundRecordDb = new RefundRecordDb();

            $res = $refundRecordDb->getUserNextRefundByInvests($userId, $investIds);

        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

        return $res;
    }

    /**
     * @param $investId
     * @return array
     * @desc 回款计划
     */
    public static function getRefundPlan($investId){
        try{
            $res = DB::table('refund_record')
                ->join('project', 'refund_record.project_id', '=', 'project.id')
                ->select('refund_record.user_id','refund_record.invest_id','refund_record.principal','refund_record.cash','refund_record.interest','refund_record.status','refund_record.times','refund_record.type','project.refund_type','refund_record.project_id')
                ->where('invest_id',$investId)
                ->get();
            return $res;
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * @param $userId
     * @param $page
     * @param $size
     * @return mixed
     * @desc 获取普付宝用户的投资
     */
    public function getPfbInvestList($userId, $page, $size)
    {

        $db = new JdyDb();

        $offset = $db->getLimitStart($page, $size);

        return DB::table('invest')
            ->join('project', 'invest.project_id', '=', 'project.id')
            ->select('invest.id','invest.cash','invest.project_id','project.name','project.end_at')
            ->where('invest.user_id',$userId)
            ->where('project.pledge',ProjectDb::PLEDGE)
            ->orderBy('invest.id', 'asc')
            ->skip($offset)
            ->take($size)
            ->get();

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取普付宝投资投资总额
     */
    public function getPfbInvestTotalCash($userId)
    {

        return DB::table('invest')
            ->join('project', 'invest.project_id', '=', 'project.id')
            ->where('invest.user_id',$userId)
            ->where('project.pledge',ProjectDb::PLEDGE)
            ->sum('invest.cash');

    }
    /**
     * @desc 用户数据统计
     * @author lgh
     * @param $where
     * @return mixed
     */
    public function getUserStatistics($where){

        //当前帐户存量余额
        $data['totalBalance'] = $this->getUserCondition($where)->sum('balance');
        //余额用户总数
        $data['balanceNum']   = $this->getUserCondition($where)->where('balance','>','0')->distinct()->count();
        //注册用户数
        $data['registerNum']  = $this->getUserCondition($where)->distinct()->count();
        //实名用户数
        $data['realNameNum']  = $this->getUserCondition($where)->where('real_name','!=','')->where('identity_card','!=','')->distinct()->count();

        return $data;
    }

    /**
     * @desc 用户统计条件
     * @param $where
     * @return UserDb
     */
    public function getUserCondition($where){
        $startTime = $where['start_time'];
        $endTime   = $where['end_time'];
        $obj = new UserDb();
        //时间控制
        if($startTime){
            $obj = $obj->where('created_at', '>=', $startTime);
        }
        if($endTime){
            $obj = $obj->where('created_at', '<=', $endTime);
        }

        return $obj;
    }

    //TODO: APP4.0-新增

    /**
     * @desc    APP4.0  我的资产-定期资产-持有中  未完结项目数据（投资中＋还款中） for app
     * @param   int   $userId
     * @param   int   $page
     * @param   int   $size
     * @return  bool
     * @throws  \Exception
     *
     */
    public static function getAppV4UserNoFinish($userId, $page, $size){
        try{

            $db     = new JdyDb();
            $offset = $db->getLimitStart($page, $size);

            $list = DB::table('refund_record')
                ->join('project','project.id', '=', 'refund_record.project_id')
                ->join('invest', 'invest.id',  '=', 'refund_record.invest_id')
                ->leftJoin('credit_assign_project', 'credit_assign_project.id', '=', 'invest.assign_project_id')
                ->select("invest.id AS invest_id", "invest.project_id", "invest.user_id", "invest.cash AS invest_principal",
                    'invest.created_at', 'invest.invest_type', 'invest.assign_project_id',
                    'project.name', 'project.end_at', 'project.profit_percentage','project.product_line',
                    'project.refund_type', 'project.type','project.invest_time',
                    DB::raw("sum(interest)  as invest_interest")
                )
                ->where('refund_record.user_id',$userId)
                ->whereIn('project.status', [ProjectDb::STATUS_REFUNDING,ProjectDb::STATUS_INVESTING])
                ->where(function($query) {
                    $query->whereNull('credit_assign_project.status')
                        ->orWhere(function ($query) {
                            $query->where('credit_assign_project.status', '<>' ,CreditAssignDb::STATUS_FINISHED);
                        });
                })
                ->groupBy('refund_record.invest_id')
                ->orderBy('refund_record.invest_id', 'desc')
                ->skip($offset)
                ->take($size)
                ->get();

            $total  = DB::table('refund_record')
                ->join('project','project.id', '=', 'refund_record.project_id')
                ->join('invest', 'invest.id',  '=', 'refund_record.invest_id')
                ->leftJoin('credit_assign_project', 'credit_assign_project.id',  '=', 'invest.assign_project_id')
                ->where('refund_record.user_id',   $userId)
                ->whereIn('project.status', [ProjectDb::STATUS_REFUNDING,ProjectDb::STATUS_INVESTING])
                ->where(function($query) {
                    $query->whereNull('credit_assign_project.status')
                        ->orWhere(function ($query) {
                            $query->where('credit_assign_project.status','<>' ,CreditAssignDb::STATUS_FINISHED);
                        });
                })
                ->distinct("refund_record.invest_id")
                ->count("refund_record.invest_id");

            return [ 'list'=> $list, 'total'=> $total ];

        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }


    /**
     * @desc    APP4.0  我的资产-定期资产-已完结
     * @param   int   $userId
     * @param   int   $page
     * @param   int   $size
     * @return  bool
     * @throws  \Exception
     *
     */
    public static function getAppV4UserFinish($userId, $page, $size){
        try{

            $db     = new JdyDb();
            $offset = $db->getLimitStart($page, $size);

            $list = DB::table('refund_record')
                ->join('project','project.id', '=', 'refund_record.project_id')
                ->join('invest', 'invest.id',  '=', 'refund_record.invest_id')
                ->leftJoin('credit_assign_project', 'credit_assign_project.id',  '=', 'invest.assign_project_id')
                ->select("invest.id AS invest_id", 'invest.project_id',"invest.user_id","invest.cash AS invest_principal",
                    'invest.created_at','invest.invest_type', 'invest.assign_project_id',
                    'project.name', 'project.end_at', 'project.profit_percentage','project.product_line',
                    'project.refund_type', 'project.type','project.invest_time',
                    DB::raw('sum(interest)  as invest_interest')
                )
                ->where('refund_record.user_id',$userId)
                ->where(function($query) {
                    $query->where('project.status',ProjectDb::STATUS_FINISHED)
                        ->orWhere(function ($query) {
                            $query->where('credit_assign_project.status', CreditAssignDb::STATUS_FINISHED);
                        });
                })
                #->where('project.status', ProjectDb::STATUS_FINISHED)
                ->groupBy('refund_record.invest_id')
                ->orderBy('refund_record.invest_id', 'desc')
                ->skip($offset)
                ->take($size)
                ->get();


            $total  = DB::table('refund_record')
                ->join('project','refund_record.project_id', '=', 'project.id')
                ->join('invest', 'refund_record.invest_id',  '=', 'invest.id')
                ->leftJoin('credit_assign_project', 'credit_assign_project.id',  '=', 'invest.assign_project_id')
                ->where('refund_record.user_id',$userId)
                ->where(function($query) {
                    $query->where('project.status',ProjectDb::STATUS_FINISHED)
                        ->orWhere(function ($query) {
                            $query->where('credit_assign_project.status', CreditAssignDb::STATUS_FINISHED);
                        });
                })
                #->where('project.status', ProjectDb::STATUS_FINISHED)
                ->distinct("refund_record.invest_id")
                ->count("refund_record.invest_id");

            return [ 'list'=> $list, 'total'=> $total ];

        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

}
