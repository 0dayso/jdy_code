<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/8/22
 * Time: 17:28
 */

namespace  App\Http\Controllers\Admin\Fund;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Fund\FundHistoryLogic;
use App\Http\Logics\DbKvdb\DbKvdbLogic;
use App\Http\Logics\Statistics\StatLogic;
use App\Tools\ExportFile;
use App\Tools\ToolPaginate;
use App\Tools\ToolTime;
use Illuminate\Http\Request;

class GetController extends AdminController{


    const
        DEFAULT_PAGE_SIZE   =   20;
    /**
     * @param Request $request
     * @return mixed
     * 资金流水页面
     */
    public function index(Request $request){


        $all = $request->all();
        $page = $request->get('page',1);
        $size = $request->get('size',20);

        $logic = new FundHistoryLogic();

        $list['data']['total'] = 0;

        $list['data']['data'] = [];

        $pageParam      =   '';

        if( !empty($all) ){

            if( isset($all['export']) && $all['export'] ){

                $all['size'] = "100000";

                $list = $logic->getListByType($all);

                $data[] = ['ID', '用户ID', '变更前金额', '变更金额', '变更后金额', '类型编码', '备注', '创建时间', '手机号', '姓名'];

                $data = array_merge($data, $list['data']['data']);

                ExportFile::csv($data, 'fund-history-'.ToolTime::dbDate());

                die;

            }

            $list = $logic->getListByType($all);

            $pageParam  =   "?".http_build_query($all);

        }

        $toolPaginate = new ToolPaginate($list['data']['total'], $page, $size, '/admin/fund/lists'.$pageParam);

        $paginate = $toolPaginate->getPaginate();

        $transactionType    =   $logic->setTransactionType();
        $viewData = [
            'list'              => isset($list['data']['data']) ? $list['data']['data'] : [],
            'total'             => $list['data']['total'],
            'paginate'          => $paginate,
            'transactionType'   => $transactionType,
            'params'            => $all,
            'summary'           => isset($list['data']['Summary']) ? $list['data']['Summary'] : []
        ];

        return view('admin.fund.list', $viewData);
    }


    /**
     * @param   Request $request
     * @date    2016年11月24日
     * @desc    账户资金统计列表
     */
    public function fundStat(Request $request)
    {
        $page       = $request->input('page', 1);
        $startTime  = $request->input('start_time');
        $endTime    = $request->input('end_time');
        $export     = $request->input('export','');
        $rawkey     = "FUND_STATISTICS";

        if( empty($request->all()) ){
            $viewDate   = [
                'paginate'=>'',
            ];

            return view('admin.fund.fundstat',$viewDate);
        }

        $size   = 20;

        $logic  = new StatLogic();

        $list   = $logic->getFundStatList( $rawkey ,$page, $size, $startTime, $endTime);


        if($export){

            $exportList   =   $logic->doFormatExportFundStatList($list['list']);
            
            ExportFile::csv($exportList, 'fund-history-'.ToolTime::dbDate());

            die;
        }
        #var_dump($list);
        #exit;

        $toolPaginate= new ToolPaginate($list['total'], $page, $size, '/admin/fund/fundStat');

        $paginate   = $toolPaginate->getPaginate();

        $viewDate   = [
            'home'  => '资金管理',
            'title' => '账户资金',
            'list'  => $list['list'],
            'total' => $list['total'],
            'paginate'  => $paginate,
            'start_time'=> $startTime,
            'end_time'  => $endTime,
        ];

        return view('admin.fund.fundstat', $viewDate);

    }
    /**
     * @param   Request $request
     * @date    2016年11月24日
     * @desc    账户资金统计列表
     */
    public function fundHistoryStat(Request $request)
    {
        $page       = $request->input('page', 1);
        $startTime  = $request->input('start_time');
        $endTime    = $request->input('end_time');
        $export     = $request->input('export','');
        $dbRawKey   = "FUND_HISTORY_STATISTICS";

        $statLogic  = new StatLogic();

        $list       = $statLogic->getFundStatList( $dbRawKey ,$page, self::DEFAULT_PAGE_SIZE, $startTime, $endTime);

        $eventWord  = FundHistoryLogic::getEventIdToExplain();

        $list['list']= $statLogic->doFormatStatisticsFundHistory($list['list']);

        unset($eventWord['500']);

        if($export){

            $exportList   =   $statLogic->doFormatExportStatisticsFundHistory($eventWord,$list['list']);

            ExportFile::csv($exportList, 'fund_history_total_'.ToolTime::dbDate());

            die;
        }

        $toolPaginate= new ToolPaginate($list['total'], $page, self::DEFAULT_PAGE_SIZE, '/admin/fund/fundStat');

        $paginate   = $toolPaginate->getPaginate();

        $viewDate   = [
            'home'      => '资金管理',
            'title'     => '账户资金',
            'list'      =>  $list['list'],
            'total'     =>  $list['total'],
            'paginate'  =>  $paginate,
            'eventTitle'=>  $eventWord,
            'start_time'=>  $startTime,
            'end_time'  =>  $endTime,
        ];

        return view('admin.fund.fundHistoryStat', $viewDate);

    }


    /**
     * @param   Request $request
     * @date    2017年06月15日
     * @desc    中金云数据统计
     */
    public function investRefundStat(Request $request)
    {
        $page       = $request->input('page', 1);
        $startTime  = $request->input('start_time');
        $endTime    = $request->input('end_time');
        $export     = $request->input('export','');
        $rawkey     = "INVEST_STATISTICS";

        if( empty($request->all()) ){
            $viewDate   = [
                'paginate'=>'',
            ];

            return view('admin.fund.investrefund',$viewDate);
        }

        $size   = 20;
        $logic  = new StatLogic();
        $list   = $logic->getFundStatList( $rawkey ,$page, $size, $startTime, $endTime);

        if($export){

            $exportList   =   $logic->doFormatExportInvestRefundStat($list['list']);
            ExportFile::csv($exportList, 'invest-refund-'.ToolTime::dbDate());
            die;
        }
        $toolPaginate= new ToolPaginate($list['total'], $page, $size, '/admin/fund/investrefund');

        $paginate   = $toolPaginate->getPaginate();

        $viewDate   = [
            'home'  => '资金管理',
            'title' => '借款投资统计',
            'list'  => $list['list'],
            'total' => $list['total'],
            'paginate'  => $paginate,
            'start_time'=> $startTime,
            'end_time'  => $endTime,
        ];

        return view('admin.fund.investrefund', $viewDate);

    }

}