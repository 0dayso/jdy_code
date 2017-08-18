<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/11
 * Time: 下午5:19
 */

namespace App\Http\Dbs\User;


use App\Http\Dbs\JdyDb;
use App\Tools\ToolTime;
use Illuminate\Support\Facades\DB;

class InviteDb extends JdyDb
{

    const
        TYPE_NORMAL         = 0 ,    //普通邀请
        TYPE_PHONE          = 1 ,    //短信邀请
        TYPE_WEIXIN         = 2 ,    //微信邀请
        TYPE_FEN            = 3 ,    //分享邀请
        TYPE_MEDIA          = 4 ,    //自媒体邀请
        TYPE_REAL           = 5 ,    //添加用户信息邀请
        TYPE_CALL           = 6 ,    //呼朋唤友
        TYPE_NEWYEAR        = 7 ,    //新年抢话费
        TYPE_PARTNER        = 8 ,    //合伙人邀请
        TYPE_CODE           = 9 ,    //邀请码
        TYPE_KEYWORD        = 10,    //推广关键词（新版）
        TYPE_PHONE_NUM      = 11,    //手机号邀请
        TYPE_PARTNER_ADMIN  = 12,    //后台添加合伙人邀请

        USER_TYPE_MEDIA     = 2,
        USER_TYPE_NORMAL    = 1,


        SOURCE_PC_360       = 7001,  //附加来源【pc端 360活动】
        SOURCE_APP          = 3002;  //App用户手动添加

    /**
     * @param $data
     * @return mixed
     * @desc 添加邀请关系
     */
    public function add( $data ){

        $this -> user_id        = $data['user_id'];
        $this -> other_user_id  = $data['other_user_id'];
        $this -> type           = empty($data['type']) ? 0 : $data['type'];
        $this -> user_type      = empty($data['user_type']) ? self::USER_TYPE_MEDIA : $data['user_type'];
        $this -> source         = empty($data['source']) ? 0 : $data['source'];
        $this -> created_at     = ToolTime::dbNow();

        $this->save();

        return $this -> id;

    }

    /**
     * @param $otherUserId
     * @return array
     * @desc 查询被邀请人的邀请记录
     */
    public function getByOtherUserId( $otherUserId ){

        return $this->dbToArray(
            self::where('other_user_id', $otherUserId)->first()
        );

    }

    /**
     * @param $uids
     * @param $size
     * @return mixed
     * @desc 邀请人数排行榜
     */
    public function getCountInviteSortByUids($userIds,$size){
        $return = $this->select('user_id', \DB::raw('count(DISTINCT other_user_id) as total'))
            ->whereIn('user_id', $userIds)
            ->where('user_type', self::USER_TYPE_NORMAL)
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->take($size)
            ->get()
            ->toArray();
        return $return;

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 通过Id获取邀请总人数
     */
    public function getInviteUserCountByUserId( $userId ){

        $return = $this->dbToArray(
            $this->select(\DB::raw('count(DISTINCT other_user_id) as total'))
            ->where('user_id', $userId)
            ->where('user_type', self::USER_TYPE_NORMAL)
            ->first()
            );

        return empty($return['total'])?0:$return['total'];

    }

    /**
     * @param $userId
     * @return array
     * @desc 通过用户id获取邀请用户Id列表
     */
    public function getInviteListByUserId( $userId ){

        $return = $this->select(\DB::raw('DISTINCT other_user_id as user_id'))
                ->where('user_id', $userId)
                ->where('user_type', self::USER_TYPE_NORMAL)
                ->get()
                ->toArray();

        return empty($return)?[]:$return;

    }

    /**
     * @param $userId
     * @param $otherUserId
     * @return mixed
     * @desc 通过user_id,other_user_id获取信息
     */
    public function getInfoByUserIdOtherUserId($userId, $otherUserId)
    {

        return $this->where('user_id', $otherUserId)
            ->where('other_user_id', $userId)
            ->first();

    }

    /**
     * @desc 自定义条件查询合伙人信息[管理后台]
     * @param $where
     * @param $page
     * @param $pageSize
     * @return array
     */
    public function getInviteListByUser($where, $page, $pageSize){

        $start = $this->getLimitStart($page, $pageSize);
        $total = $this->where($where)->count();

        $list = $this->where($where)
            ->skip($start)
            ->take($pageSize)
            ->orderBy('id','desc')
            ->get()
            ->toArray();

        return ['list'=>$list,'total'=>$total];
    }

    /**
     * @param $userIds
     * @return mixed
     * @desc 通过多个邀请人获取被邀请人的列表信息,邀请人是合伙人
     */
    public function getPartnerInviteOtherUserIdListByUserIds($userIds)
    {

        return $this->select('user_id', 'other_user_id')
            ->whereIn('user_id', $userIds)
            ->where('user_type', self::USER_TYPE_NORMAL)
            ->get()
            ->toArray();

    }

    /**
     * @param array $params
     * @param int $size
     * @return array
     * @desc 获取合伙人累计投资收益的排行榜
     */
    public function getPartnerInvestmentRanking($params =array(),$userType =self::USER_TYPE_NORMAL, $size = 5)
    {
        $obj    =   self::select('invite.user_id',DB::raw('sum(cash) as invest_cash'))
                        ->join('invest', 'invest.user_id', '=', 'invite.other_user_id');

        //统计开始时间
        if( !empty($params['start_time']) ){

            $obj=   $obj->where('invite.created_at','>=',$params['start_time']);
        }
        //统计结束时间
        if( !empty($params['end_time']) ){

            $obj=   $obj->where('invite.created_at','<=',$params['end_time']);
        }

        //投资统计开始时间
        if( !empty($params['invest_start_time']) ){

            $obj=   $obj->where('invest.created_at','>=',$params['invest_start_time']);
        }
        //投资统计结束时间
        if( !empty($params['invest_end_time']) ){

            $obj=   $obj->where('invest.created_at','<=',$params['invest_end_time']);
        }
        //合伙人渠道类型
        if( !empty($params['invite_type']) ){

            $obj=   $obj->where('invite.type',$params['invite_type']);
        }

        $obj    =   $obj->where('invite.user_id','<>' ,1)
                        ->where('user_type', $userType)
                        ->groupBy('invite.user_id')
                        ->orderBy('invest_cash', 'desc')
                        ->orderBy('invest.created_at', 'desc');

        //个人的合伙人
        if(!empty($params['user_id']) ){

            $return =   $obj->where('invite.user_id',$params['user_id'])
                            ->first();
            return $this->dbToArray($return);
        }
        $return =   $obj->take($size)
                        ->get()
                        ->toArray();
        return  $return;
    }
    /*
     * @param $userIds | array
     * @param $startTime| time
     * @param $endTime| time
     * @return array
     * @desc  获取指定邀请人的邀请书记
     */
    public function getCountInviteSortByUidsTime($userIds,$startTime = '',$endTime = ''){
        $obj = $this->select('user_id', \DB::raw('count(DISTINCT other_user_id) as total'))
                    ->whereIn('user_id', $userIds)
                    ->where('user_type', self::USER_TYPE_NORMAL);
        if(!empty($startTime) ){

            $obj    =   $obj->where('created_at','>=',$startTime);
        }

        if(!empty($endTime)){

            $obj    =   $obj->where('created_at','<=',$endTime);
        }

        $return     =   $obj->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->get()
            ->toArray();

        return $return;
    }


    /**
     * @desc    解绑合伙人
     * @param   $userId     用户ID
     * @param   $ouserId    被解绑用户ID
     **/
    public function unbindInvite($userId, $ouserId){

        return $this->where('other_user_id', $ouserId)
                    ->where('user_id',$userId)
                    ->delete();
    }

    /*
     * @param $startTime| time
     * @param $endTime| time
     * @return array
     * @desc  限制时间查询邀请人的排名
     */
    public function getCountInviteSortByTime($params =array(),$userType =self::USER_TYPE_NORMAL){
        $obj = $this->select('user_id', \DB::raw('count(DISTINCT other_user_id) as total'))
                    ->where('user_type', $userType)
                    ->where('user_id' , '<>' ,1);
        if( !empty($params['start_time']) ) {

            $obj    =   $obj->where('created_at','>=',$params['start_time']);
        }

        if( !empty($params['end_time']) ) {

            $obj    =   $obj->where('created_at','<=',$params['end_time']);
        }

       return  $obj->groupBy('user_id')
                   ->orderBy('total', 'desc')
                   ->get()
                   ->toArray();
    }

    /**
     * @param array $params
     * @param int $size
     * @return array
     * @desc 获取被邀请人累计投资收益的排行榜
     */
    public function getInviteInvestList($params =array(),$userType =self::USER_TYPE_NORMAL, $size = 5)
    {
        $obj    =   self::select('invite.other_user_id as user_id',DB::raw('sum(cash) as invest_cash'))
                        ->join('invest', 'invest.user_id', '=', 'invite.other_user_id');

        //统计开始时间
        if( !empty($params['start_time']) ){

            $obj=   $obj->where('invite.created_at','>=',$params['start_time'])
                        ->where('invest.created_at','>=',$params['start_time']);
        }
        //统计结束时间
        if( !empty($params['end_time']) ){

            $obj=   $obj->where('invite.created_at','<=',$params['end_time'])
                        ->where('invest.created_at','<=',$params['end_time']);
        }
        //合伙人渠道类型
        if( !empty($params['invite_type']) ){

            $obj=   $obj->where('invite.type',$params['invite_type']);
        }

        return $obj->where('invite.user_id','<>' ,1)
                    ->where('user_type', $userType)
                    ->groupBy('invite.other_user_id')
                    ->orderBy('invest_cash', 'desc')
                    ->orderBy('invest.created_at', 'desc')
                    ->take($size)
                    ->get()
                    ->toArray();
    }
}
