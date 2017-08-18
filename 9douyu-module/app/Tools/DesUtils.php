<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:29
 */
namespace App\Tools;

/**
 * des
 * pkcs 美国RSA数据安全公司及其合作伙伴制定的一组公钥密码学标准
 * Class DesUtils
 * @package App\Tools
 */
use Log;

class DesUtils{

    /**
     * encrypt data
     * @param string $input
     * @param string $key
     * @return string
     */
    public function encrypt($input, $key)
    {
        $key = base64_decode($key);
        $key = $this->pad2Length($key, 8);

        $size = mcrypt_get_block_size('des', 'ecb');
        $input = $this->pkcs5_pad($input, $size);
        $td = mcrypt_module_open('des', '', 'ecb', '');
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        @mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * decrypt data when failed return false
     * @param string $encrypted
     * @param string $key
     * @return bool|string
     */
    public function decrypt($encrypted, $key)
    {
        $encrypted = base64_decode($encrypted);
        $key = base64_decode($key);
        $key = $this->pad2Length($key, 8);
        $td = mcrypt_module_open('des', '', 'ecb', '');
        // 使用MCRYPT_DES算法,cbc模式
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        // 初始处理
        $decrypted = mdecrypt_generic($td, $encrypted);
        // 解密
        mcrypt_generic_deinit($td);
        // 结束
        mcrypt_module_close($td);
        $y = $this->pkcs5_unpad($decrypted);
        return $y;
    }

    /**
     * pad string to decrypt string
     * @param string $text
     * @param int $padLen
     * @return string
     */
    function pad2Length($text, $padLen)
    {
        $len = strlen($text) % $padLen;
        $res = $text;
        $span = $padLen - $len;
        for ($i = 0; $i < $span; $i++) {
            $res .= chr($span);
        }
        return $res;
    }

    /**
     *
     * @param $text
     * @param $blockSize
     * @return string
     */
    function pkcs5_pad($text, $blockSize)
    {
        $pad = $blockSize - (strlen($text) % $blockSize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * @param $text
     * @return bool|string
     */
    function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text))
            return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
            return false;
        return substr($text, 0, -1 * $pad);
    }

    /**
     * 生成签名
     *
     * @param array $param
     * @param string $apiKey
     * @return string
     */
    public static function signMd5($param = [], $apiKey = '')
    {
        // 排序
        ksort($param);

        $sign = '';
        foreach ($param as $key => $val) {
            // 去除空值
            if (!empty($key) && !empty($val)) {
                $sign .= $key . '=' . $val . '&';
            }
        }
        $sign = rtrim($sign, '&');

        // 去除末尾 &

        Log::debug('signMd5 原始字符串：', [$sign]);

        // 加密
        $sign   = md5($sign . $apiKey);
        // 转化大写
        $sign   = strtoupper($sign);

        Log::debug('signMd5 加密后的字符串：', [$sign]);

        return $sign;
    }
}