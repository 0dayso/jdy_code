<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/18
 * Time: 下午1:35
 */

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use Session;

class CaptchaController extends Controller{

    public function create($tmp) {

        $num = null;
        //WeChat校验码为4位数字
        if(strpos($tmp,'wx_') !== false){
            $num = (string)rand(1000,9999);
        }

        //生成验证码图片的Builder对象，配置相应属性
        $builder = new CaptchaBuilder($num);

        //注册校验码无干扰线,背景色固定
        if(strpos($tmp,'register') !== false){
            $builder->setBackgroundColor(220, 210, 230);
            $builder->setMaxBehindLines(0);
            $builder->setMaxFrontLines(0);
        }

        $builder->setMaxAngle(0);

        //可以设置图片宽高及字体
        $builder->build($width = 90, $height = 36, $font = null);
        //获取验证码的内容
        $phrase = $builder->getPhrase();

        //把内容存入session
        Session::put('captcha', $phrase, 3);

        //生成图片
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();

    }



}