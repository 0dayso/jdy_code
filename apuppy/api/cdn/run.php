<?php
require_once __DIR__ . '/autoload.php';

use Qiniu\Auth;
use Qiniu\Cdn\CdnManager;
use Qiniu\Storage\BucketManager;

$accessKey   = 'QvVq_4RV45ceiyNLqk-B-VCw4PXE4L5geyCUUv0f';
$secretKey   = '1T6SO583HaxX5T2vrfgLXhVnG7yw5bhfMVpvtlZQ';

$refreshHost = '';

$auth = new Auth($accessKey,$secretKey);
$fileLines = explode("\n", file_get_contents(__DIR__."/../tmp/git.log"));
foreach($fileLines as $line){
    $line = str_replace('9douyu-static/','',$line);
    $gulpFile = array('elixir.json','gulpfile.js','manager.sh','package.json');
    if(!empty($line) && !in_array($line,$gulpFile)){
        $list[] = $line;
    }
}

if(empty($list)){
    echo "Nothing to do\n";
    return;
}

$newList = array_map(function($v){
    return 'http://iduys35.qiniudns.com/'.$v;
},$list);

$bucket = 'cdn-9douyu-static';
$bucketMgr = new BucketManager($auth);
echo "#############Delete file##########\n";
foreach($list as $item){
    $fetchRet = $bucketMgr->prefetch($bucket,$item);
    print_r($fetchRet);
    echo "----$item is Ok ----\n";
}
echo "#############Refresh file##########\n";
$cdnManager = new CdnManager($auth);
$ret = $cdnManager->refreshUrlsAndDirs($newList,null);
print_r($ret);

/*
$bucketMgr = new BucketManager($auth);
$buckets = $bucketMgr->buckets();
$files = $bucketMgr->listFiles('cdn-9douyu-static');
$bucket = 'cdn-9douyu-static';
$key = 'resources/qrcode/0c37cfc6c0f836111621f2b21f420e8a.png';
$stat = $bucketMgr->stat($bucket,$key);
print_r($stat);
if(!empty($stat[0]['putTime'])){
    $time = substr($stat[0]['putTime'],0,10);
    echo date('Y-m-d H:i:s',$time);
}
$prefetch = $bucketMgr->prefetch($bucket,$key);
print_r($prefetch);
$stat = $bucketMgr->stat($bucket,$key);
print_r($stat);
if(!empty($stat[0]['putTime'])){
    $time = substr($stat[0]['putTime'],0,10);
    echo date('Y-m-d H:i:s',$time);
}
*/


/*
$cdnManager = new CdnManager($auth);

$urls = array(
    'http://iduys35.qiniudns.com/static/activity/finance/images/page-bg.jpg',
    'http://iduys35.qiniudns.com/static/css/pc2.css',
);
$ret = $cdnManager->refreshUrlsAndDirs($urls,null);
print_r($ret);
*/