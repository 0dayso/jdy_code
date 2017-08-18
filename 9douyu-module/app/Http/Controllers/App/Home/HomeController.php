<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午2:48
 * Desc: 用户登录
 */
namespace App\Http\Controllers\App\Home;

use App\Http\Controllers\App\AppController;

use App\Http\Logics\Ad\AdLogic;
use App\Http\Logics\Project\CurrentLogic;
use App\Http\Models\Invest\CurrentModel;
use Illuminate\Http\Request;

/**
 * app 首页
 * Class HomeController
 * @package App\Http\Controllers\App\Home
 */
class HomeController extends AppController{


    /**
     * @return array
     * @desc 首页零钱计划项目
     */
    public function index(){

        $currentLogic = new CurrentLogic();

        $current = $currentLogic->getShowProject();

        $current = $this->formatReturnCurrent($current);

        $currentModel = new CurrentModel();

        $current['invest_user'] = $currentModel->getUserNum();

        $play = AdLogic::getUseAbleListByPositionId(5);

        $play = $this->formatPlay($play);

        $down = AdLogic::getUseAbleListByPositionId(7);

        $down = $this->formatPlay($down);

        $data = [
            'play'      => $play,  //首页轮播图
            'down'      => $down,  //底部介绍
            'project'   => $current
        ];
        //返回数据格式化
        $data = $currentLogic->formatAppHomeData($data);

        return self::appReturnJson($data);
    }

    /**
     * @param $play
     * @return array
     * @desc 格式化首页banner广告位;banner的广告位需求真够变态的,重心不放在项目本身上,反而过度的追求广告的各种跳转,分享;
     * 客户端和服务器端也是醉了,不过脑子,直接就实现了。。。留给未来的人们填坑
     * 期待3.0能做出精简化的修改动作
     */
    private function formatPlay($play){

        if( empty($play) ){

            return [[]];

        }

        $return = [];

        foreach ($play as $key => $val){

            $return[] = [
                'name'          => $val['title'],
                'type'          => 1,
                'type_1'        => 0,
                'share_title'   => $val['title'],
                'share_desc'    => $val['title'],
                'url'           => $val['param']['url'],
                'position_id'   => 30,
                'purl'          => $val['param']['file'],
                'share_type'    => 1,
                'share_img'     => $val['param']['file'],
                'share_url'     => $val['param']['url'],
                'share_img_url' => $val['param']['file']
            ];

        }

        return $return;

    }


    /**
     * @param $current
     * @return mixed
     * @desc 格式化输出零钱计划字段
     */
    public function formatReturnCurrent($current)
    {

        $current['project_id'] = $current['id'];

        $current['project_name'] = $current['name'];

        $current['project_type'] = 'investing';

        $current['project_type_note'] = '立即投资';

        $current['min_invest_note'] = '1元起投';

        $current['interest_note'] = '当日计息';

        $current['process_up_note'] = '灵活存取';

        $current['button_down_note'] = '帐户资金享有银行级安全保障';

        $current['project_invest_type'] = '2';

        $current['process'] = floor($current['invested_amount'] / $current['total_amount']);

        $current['can_invest_amount'] = $current['total_amount'] - $current['invested_amount'];

        $current['can_invest_amount_note'] = round(($current['total_amount'] - $current['invested_amount']) / 10000, 2).'万';

        return $current;

    }

}