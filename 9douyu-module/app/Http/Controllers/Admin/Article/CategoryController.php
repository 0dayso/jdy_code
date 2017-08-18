<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午4:22
 */

namespace App\Http\Controllers\Admin\Article;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Article\CategoryLogic;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

class CategoryController extends AdminController
{

    protected $homeName = '文章管理';

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 文章分类管理列表
     */
    public function index(Request $request)
    {

        $page = $request->input('page', 1);

        $size = 20;

        $logic = new CategoryLogic();

        $list = $logic->getList( $page, $size );

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/article/category');

        $paginate = $toolPaginate->getPaginate();

        $viewDate = [
            'home'  => $this -> homeName,
            'title' => '文章分类列表',
            'list'   => $list['list'],
            'total'  => $list['total'],
            'paginate' => $paginate,
        ];

        return view('admin.article.category.index', $viewDate);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 创建文章分类
     */
    public function create()
    {

        $logic = new CategoryLogic();

        $viewDate = [
            'home'      => $this -> homeName,
            'title'     => '创建文章分类',
            'category'  => $logic -> getAllList(),
        ];

        return view('admin.article.category.create', $viewDate);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行创建
     */
    public function doCreate(Request $request)
    {

        $data = $request -> all();

        $logic = new CategoryLogic();

        $result = $logic -> doCreate($data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            return redirect('admin/article/category')->with('message', '文章分类添加成功！');

        }else {

            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);

        }


    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 编辑文章分类
     */
    public function update( Request $request)
    {

        $id = (int)$request->input('id',1);

        $logic = new CategoryLogic();

        $viewDate = [
            'home'      => $this -> homeName,
            'title'     => '编辑文章分类',
            'info'      => $logic -> getById($id),
            'category'  => $logic -> getAllList(),
        ];

        return view('admin.article.category.update', $viewDate);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行编辑文章分类
     */
    public function doUpdate( Request $request)
    {

        $data = $request -> all();

        $logic = new CategoryLogic();

        $result = $logic -> doUpdate($data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            return redirect('admin/article/category')->with('message', '文章分类编辑成功！');

        }else {

            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);

        }

    }

}