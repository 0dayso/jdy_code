<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/11
 * Time: 下午5:55
 */

namespace App\Http\Controllers\Admin;

use App\Http\Logics\Oss\OssLogic;
use Config;

use upload;
use Illuminate\Http\Request;
use App\Http\Dbs\Picture\PictureDb;
/**
 * 图片上传
 *
 * Class UploadController
 * @package App\Http\Controllers\Admin
 */
class UploadController extends AdminController
{

    protected $pictureLink = '/picture/%s';  //图片链接模板

    public function __construct(Request $request){

        parent::__construct($request);

        \Debugbar::disable();
    }
    /**
     *
     * 编辑器上传图片
     */
    public function editorImageUpload(){
        // 获取图片上传配置
        $imageConfig   = Config::get('upload.PICTURE');

        // 实例化上传类
        $handle        = new upload($_FILES['upload'], 'zh_CN');

        // 上传大小过滤
        if($handle->file_src_size > $imageConfig['MAX_SIZE']){
            $result = 'alert("上传文件大小不对'. $imageConfig["MAX_SIZE_DESC"] . '")';
            exit("<script>$result;</script>");
        }

        // 扩展名过滤
        if(!in_array($handle->file_src_name_ext, $imageConfig['TYPE'])){
            $result = 'alert("上传文件格式不对")';
            exit("<script>$result;</script>");
        }

        $ossLogic = new OssLogic();

        $upload   = $ossLogic->putFile($_FILES['upload'],'resources/images');

        if ($upload['status']) {
            $pictureDb       = new PictureDb;
            $subUploadFile   = substr($upload['data']['path'],strpos($upload['data']['path'],'/')+1).'/'.$upload['data']['name'];
            $pictureId       = $pictureDb->add($subUploadFile);
            $url             = assetUrlByCdn('/'.$upload['data']['path'].'/'.$upload['data']['name']);
            $CKEditorFuncNum = $_GET['CKEditorFuncNum'];
            $message         = '图片上传成功';
            $result          = "window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$message')";
            exit("<script>$result;</script>");
        } else {
            $result = 'alert("上传文件失败'. $handle->error . '")';
            exit("<script>$result;</script>");
        }
    }


    /**
     * 浏览服务器图片
     */
    public function imageManager(){
        // 子目录
        $subUploadDir  = 'images/';

        // 获取图片上传配置
        $imageConfig   = Config::get('upload.PICTURE');

        // 上传地址
        $uploadDir     = $imageConfig['PICTURE_SAVE_PATH'] . $subUploadDir;

        $imgDir        = isset($_POST['imgdr']) ? trim(trim(strip_tags($_POST['imgdr'])), '/') .'/' : '';

        try{
            $directoryIterator = new \DirectoryIterator($uploadDir . $imgDir);         // object of the dir
        }
        catch(Exception $e) {
            exit(json_encode('<h2>ERROR from PHP:</h2><h3>'. $e->getMessage() .'</h3><h4>Check the $uploadDir value in imageManager to see if it is the correct path to the image folder; RELATIVE TO ROOT OF YOUR WEBSITE ON SERVER</h4>'));
        }
        $content       = ['menu'=>'', 'imgs'=>''];

        $protocol      = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $site          = $protocol. $_SERVER['SERVER_NAME'] .'/';
        $pictureDb     = new PictureDb;

        foreach($directoryIterator as $fileObj) {
            $name      =  $fileObj->getFilename();
            if($fileObj->isFile() && in_array($fileObj->getExtension(), $imageConfig['TYPE'])) {
                $dbFileName = $subUploadDir.$imgDir.$name;
                $data       = $pictureDb->getByPath($dbFileName);
                if(isset($data['id'])){
                    $src = sprintf($this->pictureLink, $data['id']);
                    $content['imgs'] .= '<span><img src="' . $src . '" alt="' . $name . '" height="50" />' . $name . '</span>';
                }else{
                    \Log::info('imageManager '. $dbFileName . '：数据表 picture 找不到该图片！');
                }
            } else if($fileObj->isDir() && !$fileObj->isDot())
            {
                $content['menu'] .= '<li><span title="'. $imgDir . $name .'">'. $name .'</span></li>';
            }
        }
        if($content['menu'] != '') $content['menu'] = '<ul>'. $content['menu'] .'</ul>';
        if($content['imgs'] == '') $content['imgs'] = '<h1>No Images</h1>';

        exit(json_encode($content));
    }
}