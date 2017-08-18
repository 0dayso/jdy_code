<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/16
 * Time: 下午4:54
 */

namespace App\Http\Models\Picture;


use App\Http\Dbs\Picture\PictureDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;
use Config;
class PictureModel extends Model
{

    public static $codeArr            = [
        'doCreate' => 1,
        'doUpdate' => 2,
        'findById' => 3,
    ];

    public static $expNameSpace       =  ExceptionCodeModel::EXP_MODEL_PICTURE;

    /**
     * @param $imgPath
     * @return mixed
     * @throws \Exception
     * @desc 创建图片
     */
    public function doCreate($imgPath){

        $db = new PictureDb();

        $result = $db -> add($imgPath);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_PICTURE_ADD'), self::getFinalCode('doCreate'));
        }

        return $result;

    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     * @throws \Exception
     * @desc 更新文章
     */
    public function doUpdate($id, $data){

        $db = new PictureDb();

        $result = $db -> edit($id, $data);

        if(!$result){
            throw new \Exception(LangModel::getLang('ERROR_PICTURE_EDIT'), self::getFinalCode('doUpdate'));
        }

        return $result;

    }

    /**
     * @param $id
     * @return array
     * @desc 通过id获取数据
     */
    public function getById( $id ){

        $db = new PictureDb();

        $result = $db -> getById($id);

        if(!$result) return [];

        return $result;

    }

    /**
     * @param $file
     * @return array
     * @throws \Exception
     * @desc 上传图片
     */
    public function uploadImg( $file ){

        $allowed_extensions = ["png", "jpg", "gif", "jpeg"];

        $extension = $file->getClientOriginalExtension();

        if ($extension && !in_array($extension, $allowed_extensions)) {
            return ['error' => 'You may only upload png, jpg, jpeg or gif.'];
        }

        $destinationPath = 'resources/images/'.date("Y").date("m").date("d");

        $fileName = time().str_random(10).'.'.$extension;

        $file->move($destinationPath, $fileName);

        $destinationPath = 'images/'.date("Y").date("m").date("d");

        $filePath = $destinationPath.'/'.$fileName;

        $result = $this -> doCreate($filePath);

        return [
            'success' => true,
            'pic' => asset($filePath),
            'id'    => $result,
        ];

    }

    /**
     * 获取图片
     *
     * 移植自九斗鱼[略微改动
     *
     * @param $id
     * @return mixed
     */
    function getPicture($id) {
        $pictureDb  = new PictureDb();

        $picture    = $pictureDb->getPicture($id);

        return $picture;
    }


    /**
     * 根据IDS批量获取图片
     *
     * @param array $ids
     * @return array|bool
     */
    public function getMutiPicturePathsByIds($ids=array()){
        $pictureDb    = new PictureDb();
        $picturePaths = $pictureDb->getPicturePaths($ids);
        if(!empty($picturePaths)){
            $picturePaths = array_reverse($picturePaths);
        }
        return $picturePaths;
    }


    /**
     * 移植自九斗鱼
     *
     * 获取错误图片路径
     */
    function getErrorPicture() {
        // 获取图片上传配置
        $file   = Config::get('upload.PICTURE.ERROR_PICTURE');

        if(!file_exists($file)) {
            $existFile = Config::get('upload.PICTURE.NO_EXISTS_PICTURE');
            if(!file_exists($existFile)) {
                header("Content-type: image/png");
                $im = imagecreate(100, 100);
                $background_color = imagecolorallocate($im, 255, 255, 255);
                $text_color = imagecolorallocate($im, 155, 155, 155);
                imagestring($im, 2, 0, 45,  "Image Not Exists.", $text_color);
                ob_start();//启用输出缓存，暂时将要输出的内容缓存起来
                imagepng($im);//输出
                $data = ob_get_contents();//获取刚才获取的缓存
                ob_end_clean();//清空缓存
                imagedestroy($im);
                file_put_contents($existFile, $data);
            }
            $file =  $existFile;
        }
        $errorArr = array(
            0 => str_replace(public_path(), '', $file),
            1 => str_replace(public_path(), '', $file)
        );
        return $errorArr;
    }

    /**
     * 移植自九斗鱼[略微改动]
     *
     * 获取图片路径
     * @param $pictuer_id
     * @param $type big :大图，thumb：获取缩略图（没有缩略图返回大图）
     */
    function getPath($pictuer_id = '',$type='big') {
        if(empty($pictuer_id))
            return $this->getErrorPicture();
        //正则获取图片ID
        $pattern = '#picture\/(\d+)#is';
        if(!preg_match($pattern, $pictuer_id, $matches)) {
            return $this->getErrorPicture();
        }
        $id = $matches[1];
        if(empty($id)) {
            return $this->getErrorPicture();
        }
        $picture = $this->getPicture($id);

        if($type == 'thumb') { //缩略图
            $fileName = substr($picture, strrpos($picture, '/') + 1);
            $path = substr($picture, 0, strrpos($picture, '/') + 1);
            $fileName='thumb_'.$fileName;
            $thumb = $path.$fileName;
            if(file_exists(Config::get('upload.PICTURE.PICTURE_SAVE_PATH') .$thumb))
                $picture = $thumb;
        }

        if(!file_exists(Config::get('upload.PICTURE.PICTURE_SAVE_PATH') . $picture)) {
            return $this->getErrorPicture();
        }
        $picArr = array(
            0 =>  Config::get('upload.PICTURE.PICTURE_WEB_URL').'/resources/'.$picture,
            1 =>  Config::get('upload.PICTURE.PICTURE_WEB_URL_HTTPS').'/resources/'.$picture,
        );

        return $picArr;
    }


}
