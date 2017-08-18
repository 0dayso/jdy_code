<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 下午6:35
 */
namespace App\Http\Controllers\Pc\User;


use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\Contract\ContractLogic;
use App\Http\Logics\Fund\FundHistoryLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Oss\OssLogic;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;
use Redirect;

/**
 * 投资记录合同下载
 * Class FundHistoryController
 * @package App\Http\Controllers\Pc\User
 */
class DownloadFileController extends UserController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 获取投资记录列表
     */
    public function userInvestList( Request $request ){

        $data = $request->all();

        $page = $request->input('page', 1);

        $size = $request->input('size', 10);

        $userId = $this->getUserId();

        $record = TermLogic::getInvestListByUserId($userId, $page, $size);

        $logic  =   new ContractLogic();

        $paginate = '';

        $contractList= '' ;

        if( !empty($record['list']) ){

            $pageTool = new ToolPaginate($record['total'], $page, $size, '/user/investList');

            $paginate = $pageTool->getPaginate();

            $contractList   =   $logic  ->getContractListByInvestId( array_column ($record['list'],'id'));

        }

        $data = [
            'list'      => (isset($record['list']) && !empty($record['list'])) ? $record['list'] : [],
            'contract'  => $contractList,
            'paginate'  => $paginate
        ];

        return view('pc.user.downloadfile', $data);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 合同下载
     */
    public function doCreateDownLoad(Request $request)
    {

        $logic = new ContractLogic();

        $data = $request->all();

        $dataType   =   $request->input ('dataType','');

        $result     = $logic->doDownLoadWay( $data );

        if($dataType == 'json') {

            return    $result;
        }

        if(!empty($result['status']) && $result['status'] == true){
            $ossLogic = new OssLogic('oss_2');
            $contents = $ossLogic->getObject($result['data']['down_load_url']);
            header('Content-type: application/pdf');
            header("Cache-Control: no-cache, private");
            header('Content-Disposition: attachment;filename='.$result['data']['file_name']);
            echo $contents;
            exit;
        }
    }

    /**
     * @param Request $request
     * @return array
     * @desc 检测合同生成状态
     */
    public function checkContractStatus(Request $request)
    {
        $logic = new ContractLogic();

        $data = $request->all();

        return $logic->doCheckContractStatus ($data['invest_id']);
    }
}
