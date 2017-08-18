<?php

namespace App\Http\Controllers\Picture;

use App\Http\Controllers\Controller as BaseController;

use App\Http\Dbs\Picture\PictureDb;
use Config;
/**
 * 图片展示 移植子老系统【略微调整】
 * Class PictureController
 * @package App\Http\Controllers\picture
 */
class PictureController extends BaseController
{
    /**
     * 展示图片
     *
     * @param int $id
     */
    public function index($id = 0){
        if(empty($id)) {
            $this->_showErrorPicture();
        }

        $pictureDb       = new PictureDb();

        $pictureDb       = $pictureDb->getById($id);

        if(isset($pictureDb['path'])) {
            $this->_showPic($pictureDb['path']);
        }else{
            $this->_showErrorPicture() ;
        }
    }

    //图片不存在，显示错误图片
    protected function _showErrorPicture() {
        $file   = Config::get('upload.PICTURE.ERROR_PICTURE');
        if(!file_exists($file)) {
            header("Content-type: image/png");
            $im = imagecreate(100, 100);
            $background_color = imagecolorallocate($im, 255, 255, 255);
            $text_color = imagecolorallocate($im, 155, 155, 155);
            imagestring($im, 2, 0, 45,  "Image Not Exists.", $text_color);
            imagepng($im);
            imagedestroy($im);
        } else {
            $this->_showPic($file);
        }
    }

    //根据图片后缀，header相应Content-Type
    protected function _headerType($file) {
        $typeExt = array(
            'png'   => 'png',
            'jpg'   => 'jpg',
            'jpeg'  => 'jpg',
            'gif'   => 'gif',
            'bmp'   => 'bmp',
        );

        if(preg_match('#\.([^.]+)$#is', $file, $match)) {
            $imgExt = strtolower($match[1]);

            if(!isset($typeExt[$imgExt])) {
                $type = 'png';
            }else{
                $type   = $typeExt[$imgExt];
            }

            header("Content-Type: image/{$type}");
        }
    }

    //获取图片内容并输出，显示图片
    protected function _showPic($file) {
        $imageConfig   = Config::get('upload.PICTURE');
        $uploadDir     = $imageConfig['PICTURE_SAVE_PATH'];
        if(file_exists($uploadDir . $file)) {
            $this->_headerType($uploadDir . $file);
            echo file_get_contents($uploadDir. $file);
        } else {
            $this->_showErrorPicture();
        }
        exit;
    }
}
