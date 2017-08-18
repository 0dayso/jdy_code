<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/25
 * Time: 下午4:43
 * Desc: 广告管理
 */

namespace App\Http\Controllers\Admin\Ad;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\Ad\AdPositionDb;
use App\Http\Logics\Ad\AdLogic;
use App\Http\Models\Ad\AdModel;
use App\Tools\AdminUser;
use App\Tools\FileUpload;
use App\Tools\ToolPaginate;
use App\Tools\ToolTime;
use Illuminate\Http\Request;
use App\Http\Logics\Oss\OssLogic;

class AdController extends AdminController
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 广告列表
     */
    public function adList( Request $request )
    {

        $positionId = $request->input('position_id');

        $page = $request->input('page', 1);

        $size = 50;

        $logic = new AdLogic();

        $list = $logic->getListByPositionId($positionId, $page, $size);

        $positionInfo = $logic->getPositionInfoById($positionId);

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/ad/adList?position_id='.$positionId);

        $paginate = $toolPaginate->getPaginate();

        $data = [
            'adList'        => $list['list'],
            'positionInfo'  => $positionInfo,
            'paginate'      => $paginate
        ];

        return view('admin.ad/adList', $data);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 删除广告
     */
    public function delAd( Request $request )
    {

        $id = $request->input('id');

        $logic = new AdLogic();

        $adInfo = $logic->editAd($id);

        $res = $logic->delAd($id);

        if( $res ){

            $logic::forgetAdCacheByPositionId($adInfo['position_id']);

            return redirect()->back()->withInput($request->input())->with('message', '删除成功！');

        }else{

            return redirect()->back()->withInput($request->input())->with('fail', '删除失败请重试！');

        }


    }

    /**
     * @param $id
     * @return mixed
     * 编辑广告
     */
    public function editAd($id){

        $logic = new AdLogic();

        $result = $logic->editAd($id);

        $result['param'] = json_decode($result['param'],true);

        $positionInfo = $logic->getPositionInfoById($result['position_id']);

        $data = [
            'positionInfo' => $positionInfo,
            'ad'           => $result
        ];

        return view('admin.ad/editAd', $data);

    }

    /**
     * @param Request $request
     * @return mixed
     * 编辑广告保存
     */
    public function doEditAd(Request $request){

        $manageId = AdminUser::getAdminUserId();

        $param = [
            'word'          => $request->input('word'),
            'url'           => $request->input('url',''),
            'show_type'     => $request->input('show_type'),
            'jump_to_type'  => $request->input('jump_to_type'),
            'share_url'     => $request->input('share_url'),
            'share_title'   => $request->input('share_title'),
            'share_desc'    => $request->input('share_desc')
        ];


        if( !$request->input('end_at') ){

            return redirect()->back()->withInput($request->input())->with('fail', '请填写下线时间！');

        }

        $fileInfo = [
            'name'              => $request->input('param_name'),
            'path'              => $request->input('param_path'),
            'share_image_name'  => $request->input('share_image_name'),
            'share_image_path'  => $request->input('share_image_path')
        ];


        $images = $_FILES;

        if($images){

            $toolsUpload = new OssLogic();

            foreach ($images as $key=>$image){

                if($image['error'] != 0){
                    continue;
                }

                $result = $toolsUpload->putFile($image);

                if( $result['status'] == false ){

                    return redirect()->back()->withInput($request->input())->with('fail', $result['msg']);

                }

                $path = $result['data']['path'];
                $name = $result['data']['name'];

                if($key == 'display_img'){
                    $fileInfo['name'] = $name;
                    $fileInfo['path'] = '/'.$path.'/';

                }else{
                    $fileInfo[$key.'_name'] = $name;
                    $fileInfo[$key.'_path'] = '/'.$path.'/';
                }

            }
        }


        $param = array_merge($param, $fileInfo);

        $data = [
            'title'         => $request->input('title'),
            'publish_at'    => $request->input('publish_at') ? $request->input('publish_at') : ToolTime::dbNow(),
            'end_at'        => $request->input('end_at'),
            'sort'          => $request->input('sort'),
            'group_sort'    => $request->input('group_sort',0),
            'param'         => json_encode($param),
            'position_id'   => $request->input('position_id'),
            'manage_id'     => $manageId
        ];


        $id = $request->input('id');

        $logic = new AdLogic();

        $res = $logic->doEditAd($id,$data);

        if( $res ){

            $logic::forgetAdCacheByPositionId($data['position_id']);

            return redirect('/admin/ad/adList?position_id='.$request->input('position_id'))->with('message', '编辑广告成功！');

        }else{

            return redirect()->back()->withInput($request->input())->with('fail', '编辑失败请重试！');

        }
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 添加广告
     */
    public function addAd( Request $request )
    {

        $logic = new AdLogic();

        $positionId = $request->input('position_id');

        $positionInfo = $logic->getPositionInfoById($positionId);

        $type = AdModel::getUrlType();

        $data = [
            'positionInfo' => $positionInfo,
            'jump_to_type' => $type
        ];

        return view('admin.ad/addAd', $data);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行添加广告
     */
    public function doAddAd( Request $request )
    {

        $manageId = AdminUser::getAdminUserId();

        $param = [
            'word'          => $request->input('word'),
            'url'           => $request->input('url'),
            'jump_to_type'  => $request->input('jump_to_type',''),
            'show_type'     => $request->input('show_type'),
            'share_url'     => $request->input('share_url'),
            'share_title'   => $request->input('share_title'),
            'share_desc'    => $request->input('share_desc')
        ];


        if( !$request->input('end_at')){

            return redirect()->back()->withInput($request->input())->with('fail', '请填写下线时间！');

        }

        $images = $_FILES;

        if( !$images['display_img']['tmp_name']){

            return redirect()->back()->withInput($request->input())->with('fail', '请选择上传图片！');

        }

        $fileInfo = [];

        if($images){

            $toolsUpload = new OssLogic();

            foreach ($images as $key=>$image){

                if($image['error'] != 0){
                    continue;
                }

                $result = $toolsUpload->putFile($image);

                if( $result['status'] == false ){

                    return redirect()->back()->withInput($request->input())->with('fail', $result['msg']);

                }

                $path = $result['data']['path'];
                $name = $result['data']['name'];

                if($key == 'display_img'){
                    $fileInfo['name'] = $name;
                    $fileInfo['path'] = '/'.$path.'/';

                }else{
                    $fileInfo[$key.'_name'] = $name;
                    $fileInfo[$key.'_path'] = '/'.$path.'/';
                }

            }
        }

        $param = array_merge($param, $fileInfo);

        $data = [
            'title'         => $request->input('title'),
            'position_id'   => $request->input('position_id'),
            'publish_at'    => $request->input('publish_at') ? $request->input('publish_at') : ToolTime::dbNow(),
            'end_at'        => $request->input('end_at'),
            'manage_id'     => $manageId,
            'sort'          => $request->input('sort'),
            'group_sort'    => $request->input('group_sort',0),
            'param'         => json_encode($param)
        ];

        $logic = new AdLogic();

        $res = $logic->addAd($data);

        if( $res ){

            $logic::forgetAdCacheByPositionId($data['position_id']);

            return redirect('/admin/ad/adList?position_id='.$request->input('position_id'))->with('message', '添加广告成功！');

        }else{

            return redirect()->back()->withInput($request->input())->with('fail', '添加失败请重试！');

        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 广告位列表
     */
    public function positionList( Request $request )
    {

        $type = $request->input('type', AdPositionDb::TYPE_PC);

        $adLogic = new AdLogic();

        $data = [
            'list'  => $adLogic->getPositionList($type),
            'type'  => $type
        ];

        return view('admin.ad/position', $data);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 添加广告位
     */
    public function doAddPosition( Request $request )
    {

        if( empty($request->input('position_name')) || empty($request->input('type')) ){

            return redirect()->back()->withInput($request->input())->with('fail', '请完善输入框信息！');

        }

        /*
        if( !isset($_FILES['display_img']['name']) || empty($_FILES['display_img']['name']) ){

            return redirect('/admin/ad/positionList?type='.$request->input('type'))->with('message', '请上传效果展示图！');

        }
        */

        $fileInfo = [];

        if( isset($_FILES['display_img']) && $_FILES['display_img']['error'] == 0 ) {

            //执行文件保存
            $toolsUpload = new OssLogic();

            $result = $toolsUpload->putFile($_FILES['display_img']);

            if ($result['status'] == false) {

                return redirect()->back()->withInput($request->input())->with('fail', $result['msg']);

            }

            $fileInfo = [
                'name' => $result['data']['name'],
                'path' => '/'.$result['data']['path'].'/'
            ];

        }
        $data = [
            'type'          => $request->input('type'),
            'name'          => $request->input('position_name'),
            'param'         => json_encode($fileInfo)
        ];

        $logic = new AdLogic();

        $res = $logic->addPosition($data);

        if( $res ){

            return redirect('/admin/ad/positionList?type='.$request->input('type'))->with('message', '创建广告位成功！');

        }else{

            return redirect()->back()->withInput($request->input())->with('fail', '创建失败请重试！');

        }

    }

    /**
     * @param $id
     * @return mixed
     * 编辑广告位显示页面
     */
    public function editPosition($id){

        $logic = new AdLogic();

        $data = $logic->getPositionInfoById($id);


        $img = json_decode($data['param'],true);

        if($img){

            $url = $img['path'].$img['name'];
        }else{

            $url = '';
        }

        $data['img_url'] = $url;

        return view('/admin/ad/editPosition',$data);

    }


    /**
     * @param Request $request
     * @return mixed
     * 编辑广告位保存
     */
    public function doEditPosition(Request $request){

        $id = $request->input('id');

        if( empty($request->input('position_name'))){

            return redirect()->back()->withInput($request->input())->with('fail', '请完善输入框信息！');

        }

        $data = [
            'name' => $request->input('position_name')
        ];


        if( isset($_FILES['display_img']) && $_FILES['display_img']['error'] == 0 ) {

            //执行文件保存
            $toolsUpload = new OssLogic();

            $result = $toolsUpload->putFile($_FILES['display_img']);

            if ($result['status'] == false) {

                return redirect()->back()->withInput($request->input())->with('fail', $result['msg']);

            }

            $fileInfo = [
                'name'  => $result['data']['name'],
                'path'  => '/'.$result['data']['path'].'/'
            ];

            $data['param'] = json_encode($fileInfo);

        }


        $logic = new AdLogic();

        $res = $logic->doEditPosition($id,$data);

        if( $res ){

            return redirect('/admin/ad/positionList?type='.$request->input('type'))->with('message', '编辑广告位成功！');

        }else{

            return redirect()->back()->withInput($request->input())->with('fail', '编辑失败请重试！');

        }
    }

    /**
     * @param Request $request
     * @desc 查看照片
     * @todo 做的粗糙
     */
    public function viewPic( Request $request )
    {

        $path = $request->input('path');

        $path = assetUrlByCdn($path);

        echo '<img src="'.$path.'" />';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 删除广告位
     */
    public function delPosition( Request $request )
    {

        $id = $request->input('id');

        $logic = new AdLogic();

        $res = $logic->delPosition($id);

        if( $res['status'] ){

            return redirect('/admin/ad/positionList?type='.$request->input('type'))->with('message', '删除成功！');

        }else{

            return redirect('/admin/ad/positionList?type='.$request->input('type'))->with('message', $res['msg']);

        }

    }



}
