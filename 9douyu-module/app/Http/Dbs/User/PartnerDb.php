<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午7:51
 */

namespace App\Http\Dbs\User;


use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;
use Illuminate\Support\Facades\DB;

class PartnerDb extends JdyDb
{

    const
        LAST_FLUSH_TIME                   = "LAST_FLUSH_TIME",                   //最后更新时间
        HISTORY_TOP_9_PARTNER             = "HISTORY_TOP_9_PARTNER",             //佣金排行榜
        PARTNER_INVITE_COUNT_TOP_5        = "PARTNER_INVITE_COUNT_TOP_5",        //邀请人数排名前5
        PARTNER_INVITE_INVEST_COUNT_TOP_5 = "PARTNER_INVITE_INVEST_COUNT_TOP_5"; //合伙邀人总投资前5名

    /**
     * @param $userId
     * @return mixed
     * @desc 成为合伙人
     */
    public function add( $userId )
    {

        $this->user_id = $userId;

        $this->save();

        return $this->id;

    }

    /**
     * @param $userId
     * @return array
     * @desc 通过userId查询合伙人信息
     */
    public function getByUserId( $userId ){

        return $this->dbToArray(
            self::where('user_id', $userId)->first()
        );

    }

    /**
     * @return mixed
     * @desc 获取所有合伙人列表信息
     */
    public static function getPartnerList(){

        return self::where('user_id','>', 0)->get()->toArray();

    }

    /**
     * @param int $page
     * @param int $size
     * @return mixed
     * @desc 分页获取合伙人列表
     */
    public function getPartnerListByPage($page = 1, $size = 1000)
    {

        $offset = $this->getLimitStart($page, $size);

        return $this->skip($offset)
            ->take($size)
            ->get()
            ->toArray();

    }

    /**
     * @param int $size 取前几名
     * @return bool
     * @desc 获取昨天待收益前$size名 默认前5名
     */
    public static function getYesterdayCashTopList($size=5){
        if($size < 1){ return false; }

        $return = self::select('user_id','yesterday_cash','interest','created_at')
            ->where('user_id', '>', 0)
            ->where('yesterday_cash', '>', 0)
            ->orderBy('yesterday_cash', 'desc')
            ->orderBy('created_at')
            ->take($size)
            ->get()
            ->toArray();
        return $return;
    }

    /**
     * @param $interestTotal
     * @return mixed
     * @desc 获取个人排名
     */
    public function getOneSort( $interestTotal ){

        $return = self::select(DB::raw('count(id) as sort'))
            ->where('interest', '>=', $interestTotal)
            ->first()
            ->toArray();

        return $return['sort'];

    }

    /**
     * @param $userId
     * @param $cash
     * @return mixed
     * @desc 转出佣金
     */
    public function delCash( $userId, $cash ){

        return self::where('user_id', $userId)
            ->where('cash', '>=', $cash)
            ->update(['cash' => \DB::raw(sprintf('`cash`-%f', $cash))]);

    }

    /**
     * @param $userId
     * @param $cash
     * @return mixed
     * @desc 增加收益
     */
    public function incCash( $userId, $interest, $cash, $inviteNum, $rate){

        return self::where('user_id', $userId)
            ->where('interest_time','<',ToolTime::dbDate())
            ->update([
                'cash'                  => \DB::raw(sprintf('`cash`+%f', $interest)),
                'interest'              => \DB::raw(sprintf('`interest`+%f', $interest)),
                'yesterday_interest'    => $interest,
                'yesterday_cash'        => $cash,
                'interest_time'         => ToolTime::dbNow(),
                'invite_num'            => $inviteNum,
                'rate'                  => $rate
            ]);

    }

    /**
     * @return mixed
     * @desc 获取合伙人总数
     */
    public function getUserTotal()
    {

        return $this->count();

    }

    /**
     * @desc 自定义条件查询合伙人信息[管理后台]
     * @param $where
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getPartnerInfo($where, $page, $pageSize){

        $start = $this->getLimitStart($page, $pageSize);
        $total = $this->where($where)->count();

        $list = $this->where($where)
            ->skip($start)
            ->take($pageSize)
            ->get()
            ->toArray();

        return ['list'=>$list,'total'=>$total];
    }


    public function getUserIds($userIds){

        return self::select('user_id')
            ->whereIn('user_id',$userIds)
            ->get()
            ->toArray();
    }

    /**
     * @param $data
     * @return mixed
     * 添加帐户数据
     */
    public function addRecord($data){

        return self::insert($data);
    }

    /**
     * @return mixed
     * 获取今日计息用户总数
     */
    public function getTodayInterestUserNum(){

        return self::where('interest_time','>',ToolTime::dbDate())
            ->count();
    }

    /**
     * @param $baseRate
     * @return mixed
     * 重置未计息用户信息
     */
    public function resetInterest($baseRate){

        $data = [
            'rate'                  => $baseRate,
            'yesterday_interest'    => 0,
            'yesterday_cash'        => 0,
            'interest_time'         => ToolTime::dbNow(),
            'invite_num'            => 0
        ];
        return self::where('interest_time','<',ToolTime::dbDate())
            ->update($data);
    }        
    /*
     * @param int $size
     * @return mixed
     * @desc 返回佣金收益排名
     */
    public static function getInterestCashSort($size=20){

        return self::where('interest', '>', 0)
            ->orderBy('interest', 'desc')
            ->take($size)
            ->get()
            ->toArray();

    }

    /**
     * @return mixed
     * 获取合伙汇总数据
     */
    public function getStatics(){

        $today = ToolTime::dbDate();
        $return = self::select(DB::raw('sum(yesterday_cash) as total_cash,
                                sum(yesterday_interest) as total_interest,
                                sum(if(yesterday_interest > 0 ,1,0)) as total_num'))
            ->where('interest_time', '>=', $today)
            ->first()
            ->toArray();

        return $return;
    }


}