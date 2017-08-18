<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 17/1/20
 * Time: 下午3:05
 */

namespace App\Http\Controllers\Admin\Activity;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Activity\ActivityConfigLogic;
use App\Tools\AdminUser;
use Illuminate\Http\Request;
use App\Tools\ToolPaginate;

class ActivityConfigController extends AdminController
{
    protected $homeName = '活动配置列表';

    const PAGE_SIZE = 20; //设置列表每页条数

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 后台配置列表
     */
    public function index(Request $request)
    {
        $page       =   $request->input('page', 1);

        $keyWord    =   trim($request->input('key_word', ''));

        $pageSize   =   self::PAGE_SIZE;

        $list       =   ActivityConfigLogic::getAllList( $page, $pageSize, $keyWord);

        $params     =   '?key_word='.$keyWord;

        $toolPaginate = new ToolPaginate($list['total'], $page, $pageSize, '/admin/activity_config'.$params);

        $paginate   =   $toolPaginate->getPaginate();

        $viewData   = [
            'home'      => $this->homeName,
            'title'     => $this->homeName,
            'list'      => $list['list'],
            'total'     => $list['total'],
            'paginate'  => $paginate,
        ];

        return view('admin.activity_config.index', $viewData);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 添加后台配置
     */
    public function create()
    {
        $viewData = [
            'home'   => $this->homeName,
            'title'  => '添加配置',
        ];

        return view('admin.activity_config.create', $viewData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行添加配置
     */
    public function doCreate(Request $request)
    {
        $data   = $request->all();
        $data['manage_id'] = AdminUser::getAdminUserId();

        $result = ActivityConfigLogic::doCreate($data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            $redirectUrl = '/admin/activity_config';

            return redirect($redirectUrl)->with('message', '配置添加成功！');

        } else {

            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 更新配置
     */
    public function update( Request $request )
    {

        $id = $request->input('id');
        
        $info = ActivityConfigLogic::getConfigById($id);

        $viewData = [
            'home'  => $this->homeName,
            'title' => '修改配置',
            'info'  => $info,
        ];

        return view('admin.activity_config.update', $viewData);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行编辑配置
     */
    public function doUpdate(Request $request)
    {
        $data   = $request->all();
        $data['manage_id'] = AdminUser::getAdminUserId();
        $id     = (int)$request->input('id');

        $result = ActivityConfigLogic::doUpdate($id, $data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            $redirectUrl = '/admin/activity_config';

            return redirect($redirectUrl)->with('message', '配置编辑成功！');

        }else {

            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }
    }
}