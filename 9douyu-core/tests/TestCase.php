<?php
use App\Http\Logics\Auth\SecurityAuthLogic;

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * cURL get请求
     * @param       $url
     * @param array $callback
     * @param array $opt
     * @param array $args
     */
    public function getRequest($url, $callback = array(), $opt = array(), $args = array()) {
        $url = preg_replace('/#.+$/i', '', $url);   //去掉#
        $sign = $this->_getSign($url);
        $addQuery = "sign={$sign}&name=" . $this->_getAuthUsername();
        if(stripos($url, '?') !== false) {
            $url = str_replace('?', "?{$addQuery}&", $url);
        } else {
            $url .= "?{$addQuery}";
        }
        return $this->_request($url, $callback, $opt, $args);
    }

    /**
     * cURL post请求
     * @param       $url
     * @param       $data   其中的int类型要转成string
     * @param array $callback
     * @param array $opt
     * @param array $args
     */
    public function postRequest($url, $data, $callback = array(), $opt = array(), $args = array()) {
        $url = preg_replace('/#.+$/i', '', $url);   //去掉#
        $sign = $this->_getSign($url, $data);
        $data['sign'] = $sign;
        $data['name'] = $this->_getAuthUsername();
        $defaultOpt = array(
            CURLOPT_POST        => true,
            CURLOPT_POSTFIELDS  => $data,
        );
        $opt = $defaultOpt + (array)$opt;   //不能使用array_merge，否则会导致键位重置为0开始
        
        return $this->_request($url, $callback, $opt, $args);
    }

    /**
     * cURL请求
     * @param       $url
     * @param array $callback
     * @param array $opt
     * @param array $args
     *
     * @throws \Ares333\CurlMulti\Exception
     */
    protected function _request($url, $callback = array(), $opt = array(), $args = array()) {
        if(empty($callback)) {
            $callback = array($this, 'response');
        }
        $curl = new \Ares333\CurlMulti\Core();

        $curl->add(array (
            'url'   => $url,
            'opt'   => $opt,
            'args'  => $args,
        ), $callback);

        $curl->start();
    }

    /**
     * 请求响应处理
     * @param $response
     * @param $args
     */
    public function response($response, $args) {
        //请定制响应处理
    }
    
    protected function _getAuthUsername() {
        return 'cli_test_user';
    }

    /**
     * 通过url获取sign
     * @param       $url
     * @param array $postData
     *
     * @return bool
     */
    protected function _getSign($url, $postData = array()) {
        $urlInfo = parse_url($url);
        if(!empty($urlInfo['query'])) {
            parse_str($urlInfo['query'], $_GET);
        }
        
        if(empty($postData)) {
            $data = $_GET;
        } else {
            $data = $postData;
        }
        
        $data['name'] = $this->_getAuthUsername();
        
        $info = SecurityAuthLogic::getInfoByName($this->_getAuthUsername());

        if(empty($info['name'])) {
            self::returnJson(['sign' => false]);
        }
        
        unset($data['sign']);

        $data = json_encode($data);
        

        $sign = SecurityAuthLogic::getMd5Sign($info['name'], $info['secret_key'], $data);
        
        return $sign;
    }
}
