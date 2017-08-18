<?php

namespace App\Http\Controllers\Pc\User;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\Pc\UserController;
use App\Http\Logics\Invest\TermLogic;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

/**
 * 用户定期资产类
 * Class TermRecordController
 * @package App\Http\Controllers\User
 */
class RefundController extends UserController
{
    const PAGE_SIZE = 10;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 投资中项目
     */
    public function getInvesting(Request $request)
    {
        $userId = $this -> getUserId();

        $page = $request->input('page', 1);
        $size = SELF::PAGE_SIZE;
        $assign = [];
        $termLogic = new TermLogic();
        $list = $termLogic->getInvesting($userId, $page, $size);
        if(!empty($list)){
            $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/user/term/investing');
            $paginate = $toolPaginate->getPaginate();
            $assign['list'] = $list['record'];
            $assign['paginate'] = $paginate;
            $assign['page'] = ceil($paginate['total']/$size);
        }
        return view('pc.user.termInvesting', $assign);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 还款中项目
     */
    public function getRefunding(Request $request)
    {
        $page = $request->input('page', 1);
        $size = SELF::PAGE_SIZE;

        $userId = $this -> getUserId();
        $termLogic = new TermLogic();

        $list = $termLogic->getRefunding($userId, $page, $size);

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/user/term/refunding');
        $paginate = $toolPaginate->getPaginate();

        $assign['list'] = $list['record'];
        $assign['paginate'] = $paginate;
        $assign['page'] = ceil($paginate['total']/$size);

        return view('pc.user.termRecord', $assign);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 已还款项目
     */
    public function getRefunded(Request $request)
    {
        $page = $request->input('page', 1);
        $size = SELF::PAGE_SIZE;

        $userId = $this -> getUserId();
        $termLogic = new TermLogic();

        $list = $termLogic->getRefunded($userId, $page, $size);

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/user/term/refunding');
        $paginate = $toolPaginate->getPaginate();

        $assign['list'] = $list['record'];
        $assign['paginate'] = $paginate;
        $assign['page'] = ceil($paginate['total']/$size);

        return view('pc.user.termRecord', $assign);
    }

}
