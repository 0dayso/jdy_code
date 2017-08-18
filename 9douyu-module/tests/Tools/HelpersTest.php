<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/11/22
 * Time: 下午2:54
 */

namespace Tests\Tools;

class HelpersTest extends \TestCase
{
    /**
     * AssetFromCdn 测试
     * https://www.9douyu.com/images/logo.png?v=20005
     * http://www.9douyu.com/aa/images/logo.png?v=11000
     * https://www.9douyu.com/aa/images/logo.png?v=10000
     * /images/logo.png?v=10000
     * /images/logo.png?v=11002
     * //images/logo.png?v=11002
     * //images/logo.css?v=11002
     * //images/logo.js?v=11002
     * //images/DIN.eot?v=11002
     */
    public function testAssetFromCdn(){
        echo "\n";
        echo "/** \n";
        echo " * AssetFromCdn 测试 \n";
        $url = assetFromCdn('images/logo.png','10005' ,'https://www.9douyu.com', true);
        echo " * " . $url . "\n";
        $this->assertEquals($url, 'https://www.9douyu.com/images/logo.png?v=' . (config('cdn.version')+10005));

        $url = assetFromCdn('images/logo.png','1000' ,'https://www.9douyu.com/aa', false);
        echo " * " . $url . "\n";
        $this->assertEquals($url, 'http://www.9douyu.com/aa/images/logo.png?v=' . (config('cdn.version')+1000));

        $url = assetFromCdn('images/logo.png',null ,'https://www.9douyu.com/aa', true);
        echo " * " . $url . "\n";
        $this->assertEquals($url, 'https://www.9douyu.com/aa/images/logo.png?v='. config('cdn.version'));

        $url = assetFromCdn('images/logo.png');
        echo " * " . $url . "\n";
        $this->assertEquals($url, '/images/logo.png?v='. config('cdn.version'));

        $url = assetFromCdn('/images/logo.png');
        echo " * " . $url . "\n";
        $this->assertEquals($url, '/images/logo.png?v='. config('cdn.version'));


        $url = assetFromCdn('images/logo.png','1002');
        echo " * " . $url . "\n";
        $this->assertEquals($url, '/images/logo.png?v=' . (config('cdn.version')+1002));

        $url = assetFromCdn('images/logo.png','1002','/');
        echo " * " . $url . "\n";
        $this->assertEquals($url, '//images/logo.png?v=' . (config('cdn.version')+1002));


        $url = assetFromCdn('images/logo.css','1002','/');
        echo " * " . $url . "\n";
        $this->assertEquals($url, '//images/logo.css?v=' . (config('cdn.version')+1002));

        $url = assetFromCdn('images/logo.js','1002','/');
        echo " * " . $url . "\n";
        $this->assertEquals($url, '//images/logo.js?v=' . (config('cdn.version')+1002));

        $url = assetFromCdn('images/DIN.eot','1002','/');
        echo " * " . $url . "\n";
        $this->assertEquals($url, '//images/DIN.eot?v=' . (config('cdn.version')+1002));

        echo "*/";

    }


}