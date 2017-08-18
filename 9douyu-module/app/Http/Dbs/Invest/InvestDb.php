<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/15
 * Time: 下午7:03
 * Desc: 投资记录
 */

namespace App\Http\Dbs\Invest;

use App\Http\Dbs\Bonus\BonusDb;
use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;

class InvestDb extends JdyDb
{

    protected $table = 'invest';


    /**
     * @param int $size
     * @return mixed
     * @desc 获取投资风云榜
     */
    public function getFulWinList($size=12)
    {

        return self::select('user_id', \DB::raw('sum(cash) as cash'))
            ->groupBy('user_id')
            ->orderBy('cash', 'desc')
            ->take($size)
            ->get()
            ->toArray();

    }

    /**
     * 获取投资信息
     * @param  int $investId 
     * @return array
     */
    public function getInfoByInvestId($investId){

        $res = self::where('invest_id',$investId)->first();

        return $this -> dbToArray($res);
    }

    /**
     * @param $projectId
     * @return mixed
     * @desc 获取项目的投资列表
     */
    public function getInvestList($projectId, $page = 0 ,$size = 10){
        $db = self::select('invest_id','created_at','cash','app_request','user_id')
            ->where('project_id',$projectId)
            ->orderBy('created_at', 'desc');
            if($page == 0) {
                $data = $db->get()->toArray();
            }else{
                //$size = 10;
                $skip = ($page-1) * $size;
                $data = $db->skip($skip)->take($size)->get()->toArray();
            }
        return $data;
    }

    /**
     * @param $projectId
     * @return mixed
     * @desc 获取项目的投资概况
     */
    public function getInvestBrief($projectId){
        return self::select(\DB::raw('sum(cash) as cash'),\DB::raw('count(user_id) as num'))
            ->where('project_id',$projectId)
            ->first()
            ->toArray();
    }

    /**
     * @param $projectId
     * @param $limit
     * @return mixed
     * @desc 项目单笔投资排行
     */
    public function getMaxInvestTop($projectId,$limit){
        return self::select('cash','user_id', 'created_at')
            ->where('project_id',$projectId)
            ->orderBy('cash','desc')
            ->take($limit)
            ->get()
            ->toArray();
    }

    /**
     * @param $projectId
     * @param $limit
     * @return mixed
     * @desc 项目单笔投资排行
     */
    public function getInvestNew($limit){
        return self::select('cash','user_id','project_id','created_at')
            ->orderBy('id','desc')
            ->take($limit)
            ->get()
            ->toArray();
    }

    /**
     * @param string $date
     * 获取指定日期以来的投资总额
     */
    public function getAfterInvestAmountByDate($date = ''){

        if($date === ''){
            $date = ToolTime::dbDate();
        }

        return self::where('created_at','>=',$date)
            ->sum('cash');
    }

    /**
     * @param string $date
     * @return mixed
     * 获取指定日期之前的投资总额
     */
    public function getBeforeInvestAmountByDate($date = ''){

        if($date === ''){
            $date = ToolTime::dbDate();
        }

        return self::where('created_at','<=',$date)
            ->sum('cash');

    }

    /**
     * @param array $idArr
     * @return mixed
     * @desc 根据多个invest_id获取投资记录
     */
    public function getInvestByIdArr($idArr = array()){

        return self::whereIn('invest_id',$idArr)
                    ->get()
                    ->toArray();

    }

    /**
     * @desc [管理后台]获取投资记录
     * @param $where
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getAdminInvestList($where, $page,$pageSize){

        $start = $this->getLimitStart($page, $pageSize);

        $investList = $this->where($where)
            ->orderBy('id','desc')
            ->skip($start)
            ->paginate($pageSize)
            ->toArray();

        return $investList;
    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 通过项目ids获取红包投资列表
     */
    public function getInvestCashListByProjectIds($projectIds)
    {

        return self::select(\DB::raw('sum(cash) as cash'), 'project_id')
            ->whereIn('project_id', $projectIds)
            ->groupBy('project_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 获取红包、加息券投资的金额
     */
    public function getBonusCashListByProjectIds($projectIds, $type=BonusDb::TYPE_CASH)
    {

        return self::select(\DB::raw('sum(bonus_value) as bonus_cash'), 'project_id')
            ->whereIn('project_id', $projectIds)
            ->where('bonus_type', $type)
            ->groupBy('project_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectIds
     * @return mixed
     * @desc 获取项目的最后一笔投资记录
     */
    public function getLastInvestListByProjectIds($projectIds){

        return self::select(\DB::raw('max(id) as id'), 'created_at', 'project_id')
            ->whereIn('project_id', $projectIds)
            ->groupBy('project_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     * @desc 通过起始时间获取每个项目最后一笔的投资记录
     */
    public function getLastInvestListByStartTimeEndTime($startTime, $endTime){

        return self::select(\DB::raw('max(id) as id'), 'created_at', 'project_id')
            ->whereBetween('created_at', [$startTime, $endTime])
            ->groupBy('project_id')
            ->get()
            ->toArray();

    }

    /**
     * @param $projectId
     * @param $userId
     * @return mixed
     * @desc 查询用户投资某项目的投资记录
     */
    public function getInvestProjectByUserId($projectId, $userId){

        return self::where('project_id', $projectId)
            ->where('user_id', $userId)
            ->get()
            ->toArray();

    }

    /**
     * @param array $projectIds
     * @return mixed
     * @desc 获取投资的笔数
     */
    public function getInvestTotalByProject( $projectIds = array() )
    {
        return  self::whereIn('project_id',$projectIds)->count("id");
    }

    /**
     * @param   array $projectIds
     * @return  mixed
     * @desc    获取投资的不同人总数
     */
    public function getInvestPeopleByProject( $projectIds = array() )
    {
        $result =  self::select(\DB::raw('count( distinct(user_id) ) userNum '))
            ->whereIn('project_id',$projectIds)
            ->first();
        return $this->dbToArray($result);
    }

    /**
     * @desc    获取投资来源信息
     **/
    public function getInvestSource(){
        $result = self::select(
                        \DB::raw("
                            COUNT('id') AS totalNum,
                            sum(if(app_request='pc', 1,0))  AS pcNum,
                            sum(if(app_request='ios',1,0))  AS iosNum,                
                            sum(if(app_request='android',1,0))  AS androidNum,
                            sum(if(app_request='wap',1,0))      AS wapNum
                        ")
                    )
                    ->first();
        return $this->dbToArray($result);

    }

}