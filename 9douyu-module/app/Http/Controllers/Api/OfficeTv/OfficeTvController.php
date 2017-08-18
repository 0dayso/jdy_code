<?php
/**
 * 电视墙数据
 * User: bihua
 * Date: 16/8/30
 * Time: 16:27
 */
namespace App\Http\Controllers\Api\OfficeTv;

use App\Http\Controllers\Controller;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Project\RefundRecordLogic;
use App\Http\Logics\User\UserLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\ToolArray;
use Illuminate\Http\Request;

class OfficeTvController extends Controller
{
    protected $key = '9douyu_inner_key';

    /**
     * 周期刷新的动态数据
     * 用ajax轮询获得jsonp
     * @param $key
     * @param string $date
     */
    public function index( Request $request)
    {
        $key = $request->input('key');

        $date = $request->input('date');
        if($key!=$this->key)
        {
            exit();
        }
        $now  = time();
        $date = empty($date) ? date("Y-m-d H:i:s",$now) : $date ;

        $time = strtotime($date);
        $data = array();

        $termLogic    = new TermLogic();
        $userLogic    = new UserLogic();
        $refundLogic  = new RefundRecordLogic();

        //获取最新30条用户的投资记录
        $investRecords  = $termLogic->getNewInvest(30);

        //获取时间范围内按天计算用户的投资总额,包括定期,债转,零钱计划
        $end   = date('Y-m-d 23:59:59',$time );
        $start = date('Y-m-d 00:00:00',($time-86400*3));
        $investAmounts  = $termLogic->getInvestAmountByDate($start , $end);


        //获取每日注册用户数 2015-10-27 不再需要用户注册信息
        $registerAmounts    = $userLogic->getUserAmountByDate($start,$end);

        //投资趋势,按天分解记录
        foreach($investAmounts as $amount)
        {

            //每日投资的总额
            $data['trend'][$amount['date']]['cash'] = $amount['cash'];
            //每日定期投资的额度
            $data['trend'][$amount['date']]['term'] = isset($amount['invest']['cash']) ? $amount['invest']['cash'] : 0;
        }

        foreach($registerAmounts as $amount)
        {
            $data['trend'][$amount['date']]['register'] = $amount['total'];
        }

        //当日成交额与注册用户数
        $today = date('Ymd',$time);
        $data['today']['obv']     = $data['trend'][$today]['cash'];
        $data['today']['term']    = money_format($data['trend'][$today]['term'],0);

        //昨日成交额
        $data['yesterday']['obv'] = money_format($data['trend'][date('Ymd',$time-86400)]['cash'],0);
        $data['yesterday']['register'] = money_format($data['trend'][date('Ymd',$time-86400)]['register'],0);
        $data['yesterday']['term'] = money_format($data['trend'][date('Ymd',$time-86400)]['term'],0);
        //用户信息列表
        $userIds = implode(',', ToolArray::arrayToIds($investRecords, 'user_id'));
        $username = ToolArray::arrayToKey(UserModel::getUserListByIds($userIds), 'id');
        //最新的用户投资记录
        foreach($investRecords as $record)
        {
            //$username = $userLogic->getUserInfoById($record['user_id']);
            if(isset($username[$record['user_id']])){
                $data['invests'][] = array(
                    'username'  => $this->_userNameStr($username[$record['user_id']]['phone']),
                    'invest'    => money_format($record['cash'],0),
                    'time'      => $this->_timeDistance( strtotime($record['created_at']) , $now ),
                );
            }
        }

        //投资的历史总数
        $investTotalAmounts         = $termLogic->getInvestTotalAmounts();
        $data['history']['obv']     = money_format($investTotalAmounts['total'],0);

        //注册用户总数
        $data['history']['register']    = money_format($userLogic->getUserTotal(),0);


        //本月成交额
        $monthDate          = date('M Y',$time);
        $monthStart         = date('Y-m-d 00:00:00',strtotime('first day of '.$monthDate));
        $monthEnd           = date('Y-m-d 23:59:59',strtotime('last day of'.$monthDate));
        $monthTotalAmounts  = $termLogic->getInvestTotalAmounts($monthStart, $monthEnd);
        $monthSum           = $monthTotalAmounts['total'];

        //累计待收本息
        $data['history']['refunding'] = money_format($refundLogic->getRefundingTotal(),0);

        $data['complete'] = round($monthSum/10000,1);

        return self::returnJson($data);
    }

    /**
     * 每次刷新页面时得到的默认数据
     * @param $key
     * @param string $date
     */
    public function defaultData($key ,$date = '')
    {
        if($key!=$this->key)
        {
            exit();
        }

        $date = empty($date) ? date("Y-m-d H:i:s") : $date ;

        $time = strtotime($date);

        $termLogic    = new TermLogic();

        $end   = date('Y-m-d 23:59:59',$time );
        $start = date('Y-m-d 00:00:00',($time-86400*10));

        //十日内,每日投资金额
        $investAmounts  = $termLogic->getInvestAmountByDate($start , $end);
        $data = array(
            'max' => 0
        );
        $backwardAmounts = array_reverse($investAmounts);
        $i=0;
        foreach($backwardAmounts as $amount)
        {
            $cash =(int)round($amount['cash']/10000);
            $data['max'] = $data['max'] > $cash ? $data['max'] : $cash;
            $data['cash'][] = $cash;
            $data['date'][] = substr($amount['date'],-2).'日';
            $i++;
            if($i==10)
            {
                break;
            }
        }
        //展示图所用的投资金额最高上限
        $max = $data['max'];
        $maxArray = str_split($max);
        $more = false;
        $tail = '';
        for($i=0;$i<count($maxArray);$i++)
        {
            if($i==0)
            {
                $num = (int)$maxArray[$i];
                continue;
            }
            elseif((int)$maxArray[$i]>0 && !$more)
            {
                $more = true;
            }
            $tail.='0';
        }
        $num += $more ? 1 : 0;

        $data['max'] = $num.$tail;

        return self::returnJson($data);
    }


    /**
     * 时间显示的文本
     * @param $time
     * @param $now
     * @return string
     */
    protected function _timeDistance( $time , $now )
    {
        $dis =($now - $time);
        switch($dis)
        {
            case $dis < 60 :
                $str = "1分钟内";
                break;
            case $dis < 1800 :
                $str = round($dis/60)."分钟前";
                break;
            default:
                $str = "30分钟前";
        }
        return $str;
    }

    /**
     * 用户名替换的文本
     * @param $str
     * @return string
     */
    protected function _userNameStr($str)
    {
        return $return = substr($str,0,2).'***'.  substr($str,-2,2);
    }
}