<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 2016/12/13
 * Time: 下午2:27
 */

namespace App\Tools;

class ToolDomainCookie{

    /**
     * @return string
     * @desc 获取domain的cookie域
     */
    public static function getDomain(){

        $domain = \Config::get('domain');

        $re_domain = '';

        $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");

        $array_domain = explode(".", $domain);

        $array_num = count($array_domain) - 1;

        if ($array_domain[$array_num] == 'cn') {

            if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {

                $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];

            } else {

                $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];

            }

        } else {

            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];

        }

        return '.'.$re_domain;

    }

}