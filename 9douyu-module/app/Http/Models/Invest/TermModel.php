<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/8
 * Time: 上午11:09
 */
namespace App\Http\Models\Invest;

use App\Http\Dbs\Invest\InvestDb;
use App\Http\Logics\Logic;
use App\Http\Models\Common\HttpQuery;
use App\Http\Models\Common\NationalModel;
use App\Http\Models\Model;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Models\SystemConfig\SystemConfigModel;
use Carbon\Carbon;
use Validator;
use Cache;
use Config;

class TermModel extends Model{

    /**
     * @param int $projectId
     * @param int $cash
     * @param int $profit
     * @return array
     * @desc 获取收益
     */
    public function getProfit($projectId,$cash,$profit){
        $fee = array();
        if(empty($projectId))   return $fee;
        $api = Config::get('coreApi.moduleProject.getPlanInterest');
        $res = HttpQuery::corePost($api,array('project_id'=>$projectId,'cash'=>$cash,'profit'=>(float)$profit));
        if($res['code']==Logic::CODE_SUCCESS){
            $fee = $res['data'];
        }
        return $fee;
    }

    /**
     * @param $userId
     * @param $projectId
     * @param $cash
     * @param $redCash
     * @return array
     * @desc 定期投资
     */
    public function invest($userId,$projectId,$cash,$redCash){
        $api = Config::get('coreApi.moduleProject.doInvest');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId,'project_id'=>$projectId,'cash'=>$cash,'bonus_cash'=>$redCash));
        if($res['code']==Logic::CODE_SUCCESS){
            return $res['data'];
        }
        throw new \Exception($res['msg']);
    }

    /**
     * [检测金额]
     * @param  [int] $cash      [投资金额]
     * @param  [int] $balance   [账户余额]
     * @param  [int] $projectId [项目ID]
     * @return [bool] 
     * @throws \Exception          
     */
    public static function checkCash($cash,$balance,$projectId){
        $balance = $balance;
        $projectId = $projectId;

        $projectLogic     = new ProjectLinkCreditModel();
        $project = $projectLogic->getCoreProjectDetail($projectId);
        $canInvestCash = $project['left_amount'];
        //最小投资额
        $config = SystemConfigModel::getConfig('INVEST_MIN_CASH');
        $minInvestCash = $config['PROJECT_'.$project['type']];;
        //最大投资额
        $maxInvestCash = $balance<$canInvestCash?$balance:$canInvestCash;
        $rules = [
            'cash' => 'required|numeric|between:'.$minInvestCash.','.$maxInvestCash,
        ];
        $validator = Validator::make(['cash'=>$cash],$rules);
        if ($validator->fails()){
            throw new \Exception($validator->messages()->first());
        }
        return true;
    }

    /**
     * @param $userId
     * @return array
     * @desc 未完结记录
     */
    public function getNoFinish($userId,$page=1,$size=1){
        $api = Config::get('coreApi.moduleUser.getNoFinishList');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId,'page'=>$page,'size'=>$size));
        if($res['code']==Logic::CODE_SUCCESS){
            return $res['data'];
        }
        throw new \Exception($res['msg']);
    }

    /**
     * @param $userId
     * @return array
     * @desc 回款中记录
     */
    public function getRefunding($userId,$page=1,$size=1){
        $api = Config::get('coreApi.moduleUser.getRefundingList');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId,'page'=>$page,'size'=>$size));
        if($res['code']==Logic::CODE_SUCCESS){
            return $res['data'];
        }
        throw new \Exception($res['msg']);
    }

    /**
     * @param $userId
     * @return array
     * @desc 已回款记录
     */
    public function getRefunded($userId,$page=1,$size=1){
        $api = Config::get('coreApi.moduleUser.getRefundedList');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId,'page'=>$page,'size'=>$size));
        if($res['code']==Logic::CODE_SUCCESS){
            return $res['data'];
        }
        throw new \Exception($res['msg']);
    }

    /**
     * @param $userId
     * @return array
     * @desc 投资中记录
     */
    public function getInvesting($userId,$page=1,$size=1){
        $api = Config::get('coreApi.moduleUser.getInvestingList');
        $res = HttpQuery::corePost($api,array('user_id'=>$userId,'page'=>$page,'size'=>$size));
        if($res['code']==Logic::CODE_SUCCESS){
            return $res['data'];
        }
        throw new \Exception($res['msg']);
    }

    /**
     * @param int $size
     * @return bool|mixed|string
     * @desc 投资风云榜
     */
    public function getCacheFulWinList($size=12)
    {

        $cacheKey = 'INVEST_FUL_WIN_LIST';

        $list = Cache::get($cacheKey);

        $list = json_decode($list, true);

        if( empty($list) ){

            //执行数据查询
            $investDb = new InvestDb();

            $list = $investDb->getFulWinList($size);

            if( empty($list) ){

                return [];

            }

            $listCache = json_encode($list);

            //2小时的缓存
            $expiresAt = Carbon::now()->addMinutes(120);

            Cache::put($cacheKey, $listCache, $expiresAt);

        }

        return $list;

    }

    //TODO：APP4.0-新增

    /**
     * @desc    APP4.0-我的资产-定期资产-持有中项目
     * @desc    APP4.0-我的资产-定期资产-转让中项目
     * @desc    APP4.0-我的资产-定期资产-已完结项目
     * @param   $userId
     * @param   $type   investing-持有中   finish-已完结  assignment-转让
     * @param   $page
     * @param   $size
     * @return  array
     *
     */
    public function getAppV4UserTerm($userId, $type='', $page=1, $size=10){
        switch ($type){
            case 'investing':
                $api = Config::get('coreApi.moduleUser.getAppV4UserTermNoFinish');
                break;
            case 'finish':
                $api = Config::get('coreApi.moduleUser.getAppV4UserTermFinish');
                break;
            case 'assignment':
                $api = Config::get('coreApi.moduleUser.getAppV4UserTermAssignment');
                break;
        }

        $params = [
            'user_id'=>$userId,
            'page'  => $page,
            'size'  => $size
        ];

        $res = HttpQuery::corePost($api,    $params);

        if($res['code']==Logic::CODE_SUCCESS){
            return $res['data'];
        }
        throw new \Exception($res['msg']);
    }


    /**
     * @desc    通过投资ID，获取用户投资详情
     * @param   $userId       用户ID        必填
     * @param   $investId     投资ID        必填
     * @return  array
     * 获取用户投资项目的回款计划
     */
    public static function getAppV4UserTermDetail($userId,$investId){

        $api  = Config::get('coreApi.moduleUser.getAppV4UserTermDetail');

        $params = [
            'user_id'     => $userId,
            'invest_id'   => $investId
        ];

        $return = HttpQuery::corePost($api,$params);

        if($return['status'] && !empty($return['data'])){

            return $return['data'];

        }else{
            return [];
        }
    }

}