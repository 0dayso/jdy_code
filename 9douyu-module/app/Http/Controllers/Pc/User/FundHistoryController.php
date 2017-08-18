<?php

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/6/12
 * Time: 下午6:35
 */
namespace App\Http\Controllers\Pc\User;


use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\Fund\FundHistoryLogic;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

/**
 * 资金历史记录
 * Class FundHistoryController
 * @package App\Http\Controllers\Pc\User
 */
class FundHistoryController extends UserController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 获取列表
     */
    public function getListByType( Request $request ){

        $data = $request->all();

        $data['page'] = $request->input('page', 1);

        $data['size'] = 10;

        $data['user_id'] = $this->getUserId();

        //$data['user_id'] = 10;

        $return = FundHistoryLogic::getListByType($data);
        
        $list = (isset($return['data']) && !empty($return['data']['data'])) ? $return['data'] : '';

        $paginate = '';

        if( $list ){

            $pageTool = new ToolPaginate($list['total'], $data['page'], $data['size'], '/user/fundhistory');

            $paginate = $pageTool->getPaginate();

        }

        $data = [
            'list'      => (isset($list['data']) && !empty($list['data'])) ? $list['data'] : '',
            'paginate'  => $paginate
        ];

        return view('pc.user/fundhistory', $data);

    }
}