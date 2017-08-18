<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/7/15
 * Time: 上午11:14
 */
namespace App\Tools;

use App\Http\Models\Picture\PictureModel;

class EditorImage
{

    /**
     * 格式化 编辑器内图片【移植九斗鱼老系统】
     *
     * @param $html
     * @return array
     */
    function _parseImageLinks($html) {
        $html  = htmlspecialchars_decode($html);
        $links = array();
        $pattern = '#<img[^>]*?(?:src="([^"]*?)"[^>]*?(?:alt|title)="([^"]*?)"|(?:alt|title)="([^"]*?)"[^>]*?src="([^"]*?)"|src="([^"]*?)")[^>]*?/>#is';
        if(preg_match_all($pattern, $html, $matches)) {
            $pictureModel = new PictureModel;
            foreach($matches[0] as $key => $value) {
                $srcArray  = array(
                    $matches[1][$key],
                    $matches[4][$key],
                    $matches[5][$key],
                );
                $titleArray  = array(
                    $matches[2][$key],
                    $matches[3][$key],
                );

                $links[$key] = array(
                    'alt'   => $this->_getArrayFirst($titleArray),
                    'src'   => $pictureModel->getPath($this->_getArrayFirst($srcArray)),
                    'thumb' => $pictureModel->getPath($this->_getArrayFirst($srcArray),'thumb'),
                );
                $links[$key]['title'] = $links[$key]['alt'];
            }
        }

        return $links;
    }

    function _getArrayFirst($array) {
        foreach($array as $key => $value) {
            if(!empty($value)) return $value;
        }

        return false;
    }


}