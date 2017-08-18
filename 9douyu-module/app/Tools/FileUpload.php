<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/29
 * Time: 下午3:01
 * Desc: 上传工具类
 */

namespace App\Tools;

use App\Http\Logics\Logic;
use upload;


class FileUpload
{

    /**
     * @param $file
     * @param string $savePath
     * @param string $fileName
     * @return array
     * @desc 保存文件,返回文件名称 文件保存路径,文件的保存路径以斜线结尾
     */
    public function saveFile($file, $savePath='', $fileName='')
    {

        $return = Logic::callError('信息不正确');

        if( !$file ){

            return $return;

        }

        $foo = new upload($file);

        $savePath = $savePath ? $savePath : '/uploads/'.\App\Tools\ToolTime::dbDate().'/';

        if( $foo->uploaded ){

            $foo->file_new_name_body = $fileName ? $fileName : uniqid();

            $saveName = $foo->file_new_name_body;

            $foo->process($_SERVER['DOCUMENT_ROOT'].$savePath);

            if (!$foo->processed) {

                $return['msg'] = $foo->error;

                return $return;

            }

            $fileInfo = [
                'name'  => $saveName.'.'.$foo->file_dst_name_ext,
                'path'  => $savePath
            ];

            $return = Logic::callSuccess($fileInfo);

        }else{

            $return['msg'] = '上传失败';

        }

        return $return;

    }

}