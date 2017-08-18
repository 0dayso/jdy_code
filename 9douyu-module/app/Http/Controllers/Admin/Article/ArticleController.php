<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/15
 * Time: 下午4:22
 */

namespace App\Http\Controllers\Admin\Article;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Article\ArticleLogic;
use App\Http\Logics\Article\CategoryLogic;
use App\Http\Models\Picture\PictureModel;
use Illuminate\Http\Request;
use App\Tools\ToolPaginate;

class ArticleController extends AdminController
{

    protected $homeName = '文章管理';

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 文章列表
     */
    public function index(Request $request)
    {

        $page = $request->input('page', 1);

        $size = 20;

        $logic = new ArticleLogic();

        $list = $logic->getList( $page, $size );

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/article');

        $paginate = $toolPaginate->getPaginate();

        $viewDate = [
            'home'  => $this -> homeName,
            'title' => '文章列表',
            'list'   => $list['list'],
            'total'  => $list['total'],
            'paginate' => $paginate,
        ];

        return view('admin.article.article.index', $viewDate);

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 创建文章
     */
    public function create()
    {

        $logic = new CategoryLogic();

        $aLogic = new ArticleLogic();

        $viewDate = [
            'home'      => $this -> homeName,
            'title'     => '创建文章',
            'category'  => $logic -> getAllList(),
            'layout'    => $aLogic->getArticleLayouts(),
        ];

        return view('admin.article.article.create', $viewDate);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行创建
     */
    public function doCreate(Request $request)
    {

        $data = $request -> all();

        $data['file'] = $_FILES['img'];

        $logic = new ArticleLogic();

        $result = $logic -> doCreate($data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            return redirect('admin/article')->with('message', '文章添加成功！');

        }else {

            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);

        }


    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 编辑文章
     */
    public function update( Request $request)
    {

        $id = (int)$request->input('id',1);

        $logic = new ArticleLogic();

        $info = $logic -> getById($id);

        $cLogic = new CategoryLogic();

        $picModel = new PictureModel();

        $aLogic = new ArticleLogic();

        $viewDate = [
            'home'      => $this -> homeName,
            'title'     => '编辑文章',
            'info'      => $info,
            'pic'       => $picModel ->getById($info['picture_id']),
            'category'  => $cLogic -> getAllList(),
            'layout'    => $aLogic->getArticleLayouts(),
        ];

        return view('admin.article.article.update', $viewDate);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行编辑文章
     */
    public function doUpdate( Request $request)
    {

        $data = $request -> all();

        $data['file'] = $_FILES['img'];

        $logic = new ArticleLogic();

        $result = $logic -> doUpdate($data);

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            return redirect('admin/article')->with('message', '文章编辑成功！');

        }else {

            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);

        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @desc 删除文章内容
     */
    public function doDelete( $id ){

        $logic = new ArticleLogic();

        $result = $logic -> doDelete( $id );

        \Log::Info(__CLASS__.__METHOD__.__LINE__,[$result]);

        if($result['status']){

            return redirect('admin/article')->with('message', '文章删除成功！');

            die;

        }else{

            return redirect('admin/article')->with('message', $result['msg']);

        }

    }
}