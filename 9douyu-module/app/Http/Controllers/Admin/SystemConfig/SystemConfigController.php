<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/13
 * Time: 下午4:10
 */

namespace App\Http\Controllers\Admin\SystemConfig;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\SystemConfig\SystemConfigDb;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

class SystemConfigController extends AdminController
{

    protected $homeName = '后台配置';

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 后台配置列表
     */
    public function index(Request $request){

        $configType = $request->input('config_type');

        $page = $request->input('page', 1);

        $keyWord = trim($request->input('key_word', ''));

        $size = 20;

        $logic = new SystemConfigLogic();

        $list = $logic->getAllList( $page, $size, $keyWord, $configType);

        $params = '?key_word='.$keyWord;

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/system_config'.$params);

        $paginate = $toolPaginate->getPaginate();

        if($configType == SystemConfigDb::TYPE_CORE){
            $configTypeTitle = '核心配置列表';
        }elseif($configType == SystemConfigDb::TYPE_SERVICE){
            $configTypeTitle = '服务配置列表';
        }else{
            $configTypeTitle = '模块配置列表';
        }
        $viewData = [
            'home'   => $this -> homeName,
            'title'  => $configTypeTitle,
            'list'   => $list['list'],
            'total'  => $list['total'],
            'configType'   => $configType,
            'paginate' => $paginate,
        ];

        return view('admin.system_config.index', $viewData);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 添加后台配置
     */
    public function create(){

        $viewData = [
            'home'   => $this -> homeName,
            'title'  => '添加配置',
        ];

        return view('admin.system_config.create', $viewData);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行添加配置
     */
    public function doCreate(Request $request){

        $data = $request -> all();

        $logic = new SystemConfigLogic();

        $result = $logic -> doCreate($data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            $redirectUrl = '/admin/system_config';

            if( $data['config_type'] ){

                $redirectUrl .= '?config_type='.$data['config_type'];

            }

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
    public function update( Request $request ){

        $id = $request->input('id');

        $configType = $request->input('config_type');

        $logic = new SystemConfigLogic();

        $info = $logic->getConfigById($id,$configType);

        $viewData = [
            'home'  => $this -> homeName,
            'title' => '修改配置',
            'info'  => $info,
            'configType'  => $configType
        ];

        return view('admin.system_config.update', $viewData);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行编辑配置
     */
    public function doUpdate(Request $request)
    {

        $data = $request -> all();

        $logic = new SystemConfigLogic();

        $id = (int)$request -> input('id');

        $result = $logic -> doUpdate($id, $data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            $redirectUrl = '/admin/system_config';

            if( $data['config_type'] ){

                $redirectUrl .= '?config_type='.$data['config_type'];

            }

            return redirect($redirectUrl)->with('message', '配置编辑成功！');

        }else {

            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);

        }


    }

}