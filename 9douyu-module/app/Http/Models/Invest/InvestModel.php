<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/18
 * Time: 上午11:09
 */
namespace App\Http\Models\Invest;

use App\Http\Dbs\Invest\InvestDb;
use App\Http\Models\Model;
use App\Http\Models\Project\ProjectModel;
use App\Http\Models\User\UserModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use App\Tools\ToolStr;
use App\Tools\ToolTime;
use DB;

class InvestModel extends Model{

    /**
     * @param $data
     * @throws \Exception
     * @desc 增加投资数据
     */
    public function addRecord($data)
    {
        try{
            DB::table('invest')->insert([[
                'invest_id'=>$data['invest_id'],
                'user_id'=>$data['user_id'],
                'project_id'=>$data['project_id'],
                'cash'=>$data['cash'],
                'bonus_id'=>$data['bonus_id'],
                'bonus_type'=>$data['bonus_type'],
                'bonus_value'=>$data['bonus_value'],
                'app_request'=>$data['source'],
            ]]);
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 是否使用了红包加息券
     * @param  int      $investId 
     * @return boolean
     */
    public static function isUseBonus($investId) {

        $investDb = new InvestDb();

        $res = $investDb->getInfoByInvestId($investId);

        if(empty($res['bonus_type'])){

            return false;
        }
        return true;
    }


    /**
     * @param $projectId
     * @return array
     * @获投资动态
     */
    public static function getInvestNew(){
        $limit = 10;
        $investDb = new InvestDb();
        $list = $investDb->getInvestNew($limit);
        if(empty($list)) return [];
        //curl多个用户信息
        $userIds = array_column($list,'user_id');
        $userIds = array_unique($userIds);
        $usersInfo =  UserModel::getCoreUserListByIds($userIds);
        $usersInfo = ToolArray::arrayToKey($usersInfo);
        //curl多个项目信息
        $projectIds = array_column($list,'project_id');
        $projectIds = array_unique($projectIds);
        $projectsInfo =  ProjectModel::getProjectListByIds($projectIds);
        $projectsInfo = ToolArray::arrayToKey($projectsInfo);
        //处理数据
        $data = [];
        foreach($list as $key=>$val){
            $data[$key] = $val;
            $data[$key]['cash'] = ToolMoney::formatDbCashDelete($val['cash']);
            $data[$key]['phone'] = isset($usersInfo[$val['user_id']]['phone']) ? ToolStr::hidePhone($usersInfo[$val['user_id']]['phone']) : '暂无';
            $data[$key]['project_note'] = !isset($projectsInfo[$val['project_id']])? '' : $projectsInfo[$val['project_id']]['product_line_note'].' '.$projectsInfo[$val['project_id']]['invest_time_note'];
            $data[$key]['time'] = $val['created_at'];
            $data[$key]['time_note'] = ToolTime::intervalTime($val['created_at'],true);
        }
        return $data;
    }

    /**
     * 根据投资ID获取投资记录
     * @param  int      $investId
     * @return boolean
     */
    public static function getInvestByInvestId($investId) {

        $investDb = new InvestDb();

        $res = $investDb->getInfoByInvestId($investId);

        return $res;
    }

    /**
     * @param array $idArr
     * @return mixed
     * @desc 根据多个invest_id获取投资记录
     */
    public function getInvestByIdArr($idArr = array()){

        if(empty($idArr)){

            return [];
        }

        $investDb = new InvestDb();

        $res      = $investDb->getInvestByIdArr($idArr);

        return $res;
    }

    /**
     * @desc 定期投资数据统计
     * @author lgh
     * @param $where
     * @return mixed
     */
    public function getInvestStatistics($where){

        $start_time     =   $where['start_time'];
        $end_time       =   $where['end_time'];
        $app_request    =   $where['app_request'];
        $base_cash      =   $where['base_cash'];
        $user_id        =   $where['user_id'];
        $projectIds     =   $where['project_ids'];
        $bonusId        =   $where['bonusId'];

        $obj    = new InvestDb();

        //时间范围
        if($start_time && $end_time){
            $obj = $obj->where('created_at', '>=', $start_time);
            $obj = $obj->where('created_at', '<=', $end_time);
        }elseif($start_time && !$end_time){
            $obj = $obj->where('created_at', '>=', $start_time);
        }elseif(!$start_time && $end_time){
            $obj = $obj->where('created_at', '<=', $end_time);
        }
        if($app_request){
            $obj = $obj->where('app_request', '=', $app_request);
        }

        if( $base_cash ){
            $obj = $obj->where('cash','>=',$base_cash);
        }

        if( $user_id){
            $obj = $obj->where('user_id',$user_id);
        }
        if( $projectIds ){
            $obj = $obj->whereIn('project_id',$projectIds);
        }
       //判断投资使用红包的状态
        if( !empty($bonusId) || $bonusId =='0'){
            $obj = $obj->where('bonus_id',$bonusId);
        }

        $data['investTotal'] = $obj->count('id');
        $data['cash'] = $obj->sum('cash');
        $data['investNum'] = $obj->distinct()->count('user_id');
        return $data;
    }

    /**
     * @return mixed
     * @desc 获取累计投资额
     */
    public function getInvestCashTotal()
    {

        return DB::table('invest')->sum('cash');

    }

    /**
     * @desc 获取用户投资总额
     * @param array $userIds
     * @return mixed
     */
    public function getUserInvestCashTotal($userIds){

        $investDb = new InvestDb();
        return $investDb->select('user_id',DB::raw('sum(cash) as investAmount'))
            ->whereIn('user_id', $userIds)
            ->groupBy('user_id')
            ->get()
            ->toArray();
    }

    /**
     * @param $where
     * @return mixed
     * @desc 数据统计,排序,分组
     * select user_id,sum(cash) as total ,max(`created_at`) as max_invest_time from module_invest where created_at >= '2016-10-17 00:00:00' and created_at <='2016-10-17 23:59:59' and bonus_id = 0 group by user_id order by  total desc,max_invest_time asc limit 6
     */
    public function getInvestStatisticsExist( $where )
    {
        $start_time  = $where['start_time'];
        $end_time    = $where['end_time'];
        $projectIds  = $where['project_ids'];
        $groupBy     = $where['group'] ? $where['group'] : "user_id";
        $bonusType   = $where['bonusId'];
        $size        = $where['size'];
        $page        = $where['page'];
        $userId      = $where['user_id'];


        $obj = new InvestDb();

        $start = $obj->getLimitStart($page, $size);
        //时间范围
        if($start_time ){

            $obj = $obj->where('created_at', '>=', $start_time);
        }

        if($end_time){

            $obj = $obj->where('created_at', '<=', $end_time);
        }
        
        if( $projectIds ){
            $obj = $obj->whereIn('project_id',$projectIds);
        }

        if( $bonusType || $bonusType =="0" ){

            $obj = $obj->where('bonus_id', $bonusType);
        }

        //指定用户群
        if( !empty($userId) && is_array($userId)){

            $obj = $obj->whereIn('user_id', $userId);
        }
        //指定用户
        if(!empty($userId) && !is_array($userId) ){

            $obj = $obj->where('user_id', $userId);
        }
        
        $data   =   $obj->select("user_id",DB::raw('sum(cash) as total'),DB::raw('max(created_at) as max_invest_time'))
                        ->orderBy('total','desc')
                        ->orderBy('max_invest_time','asc')
                        ->groupBy($groupBy)
                        ->skip($start)
                        ->take($size)
                        ->get()
                        ->toArray();

        return $data;
    }
}