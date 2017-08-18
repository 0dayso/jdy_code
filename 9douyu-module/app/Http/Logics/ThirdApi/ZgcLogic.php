<?php
/**
 * create by phpstorm
 * User: lgh-dev
 * Date: 16/10/13
 * Desc: 中关村数据接口逻辑层处理
 */

namespace App\Http\Logics\ThirdApi;

use App\Http\Dbs\Credit\CreditDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Credit\CreditFactoringLogic;
use App\Http\Logics\Credit\CreditGroupLogic;
use App\Http\Logics\Credit\CreditHousingLogic;
use App\Http\Logics\Credit\CreditLoanLogic;
use App\Http\Logics\Credit\CreditThirdLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Credit\CreditModel;
use App\Http\Models\Credit\CreditAllModel;
use App\Tools\ToolStr;
use Illuminate\Support\Facades\Log;

class ZgcLogic extends Logic{
    //每页显示大小
    private static $pageSize = 50;
    //post url
    private static $postUrl = 'http://api.zaif.org:8456/debtShare.do?_t=json';
    //不同来源logic请求
    private static $creditSource = [
        CreditDb::SOURCE_FACTORING        =>CreditFactoringLogic::class,
        CreditDb::SOURCE_CREDIT_LOAN      =>CreditLoanLogic::class,
        CreditDb::SOURCE_HOUSING_MORTGAGE =>CreditHousingLogic::class,
        CreditDb::SOURCE_THIRD_CREDIT     =>CreditThirdLogic::class,
        CreditDb::TYPE_PROJECT_GROUP      =>CreditGroupLogic::class,
    ];
    private static $loanStatus = [
        ProjectDb::STATUS_UNAUDITED =>1,
        ProjectDb::STATUS_UNPUBLISH =>3,
        ProjectDb::STATUS_FINISHED =>4,
    ];
    //考拉密码
    public static $kaoLaKey = '1j7gs63d';

    //客户rsa私钥
    private static $rsaPrivateKeyPem =
        '-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBANJi3+4a9ttJfPXG
IqJIuxDpVfErqeEATndO7Mk3WcPWOl8RvTzKb7192d9OH8Tl3C7/4QIWgRiV8B22
hIprxqLUB1QzB9/ywfyMN9gN9v6OuIlrQW8H3l/Ifd7g/9nIYHFDYySHgLyaaXnl
gztlODVv3n3JXDZTh6N09DVKRJ6PAgMBAAECgYATl3+Q9dPdMefAH3ZkNG+vMHt/
XPiR6mxkMeZdCpmCYjXHWqiLu7JSLCEY6XaN6HXrropunlfhST02FyQam9TAc9CX
Hz/SfpqzH5f6KnKgIAguQcdOCtV2VNArsBQebHCPeWwWElxO2iuBXHDGivijOuJW
Va0bdRzA4D5/5JHdUQJBAOz5z3pT4Uu6rYOp4ZJQar94ehxx90l8nOGEFV7Nt7D0
K87yvI4CghrG10x8ps/tWAqrRM+Slhi+dsFc+uq9gZcCQQDjRpxCoDTp12ANKhkc
8a3/pM2pxNze/sKuU6hcPgZiIWZ3r/n4zlZqOj/xENumvBH5dRyJDv/8BmALU8Ws
wvnJAkAabqrTzDNfDQ15mCNO/KVLghaswZGBouKkzOTNVEje9f1E8hJSDLmSXwd4
wpagrqqZVg0w0frn+6anXsWmFUk1AkEAt2dFteY+jO3GjxkHxvmopgHCDVvVuQXw
6GiOFlHfKNU3MSKLICKyTWrQKqKl/jkKcDn3WwsFu8URQFLL1AxLMQJADlnzgsNQ
AkCkr+kJ5Rc6hqvbg5N5WVKg4Tweqvd5C/ZEAuMuXNBOCJ5BJJpWDevQyFPSVgkN
uFbG/43Vf/u3HA==
-----END PRIVATE KEY-----';  //rsa验证私钥
    //客户rsa公钥
    private static $rsaPublicRsaPem =
        '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDSYt/uGvbbSXz1xiKiSLsQ6VXx
K6nhAE53TuzJN1nD1jpfEb08ym+9fdnfTh/E5dwu/+ECFoEYlfAdtoSKa8ai1AdU
Mwff8sH8jDfYDfb+jriJa0FvB95fyH3e4P/ZyGBxQ2Mkh4C8mml55YM7ZTg1b959
yVw2U4ejdPQ1SkSejwIDAQAB
-----END PUBLIC KEY-----';
    //协会Rsa公钥
    private static $zgcPublicRsaPem =
        '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCvULSnuHiFWVZ3nKltuf8JDq0yu
TgYFA1pPmPOD5Awgb8rJjQgJHASQ6mL+kHGiB/eHxT7Kh6DGhiM1XluE3P1ODs+3X
nkwtPxoUinpdDmn787tJVU965Vk3sqzZwGLIeVngZZiGi5Om5ientcy6nn/yRNIGf
pg/G0sXbJyISyRwIDAQAB
-----END PUBLIC KEY-----';

    // 测试公钥
    private static $testPublicRsaPem =
        '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBr4tqGGBbO99n8AzOu0ZBuWhE
1kXcSbyzqG3XVvB4alGFNZuk8WsPIKfcaCcrI4D3ShTeIB9EcEXcLGHPhWLIXEuY
nNgJxJqLsXLY2xITrG226V2iLt2+y4TfsKHHX5yDTyhfgP+5nHv6DLNGRCsc1EBK
bMoHv/vPirA1ZVSdjwIDAQAB
-----END PUBLIC KEY-----';
    /*##################[债权查询逻辑处理]######################*/
    /**
     * @desc 债权查询相关逻辑处理
     * @param $reqData
     * @param $sign
     * @return array
     */
    public function searchCreditData($reqData, $sign){
        $return = [];
        //RSA验签
        $verify = self::rsaVerify($reqData, $sign, self::$zgcPublicRsaPem);

        if($verify){
            //DES解密
            $jsonData = self::desDecrypt($reqData, self::$kaoLaKey);
            //数据格式转换
            $reqData = self::formatJson($jsonData);

            $debtList = [];
            //获取债权信息
            $loanName = $reqData['idCardName'];
            if(!empty($loanName)){
                $loanName        = addslashes(json_encode($loanName));
            }
            $creditModel = new CreditModel();

            //$creditInfo = $creditModel->getCreditByLoanName($loanName);
            $creditInfo = CreditAllModel::getAllCreditByLoanName( $loanName );

            if(!empty($creditInfo) && !empty($jsonData)){//查询到有数据
                $debtList = [
                    'jnlNo' => $reqData['jnlNo'],
                    'idCardName' => $reqData['idCardName'],
                    'idCardCode' => $reqData['idCardCode'],
                    'retStatus' => '00',
                    'retMsg' => '查询成功,有数据'
                ];
                $debtList['debtList'] = $this->formatSearchCredit($creditInfo);

            }else{//没有数据返回
                $debtList = [
                    'jnlNo' => $reqData['jnlNo'],
                    'idCardName' => $reqData['idCardName'],
                    'idCardCode' => $reqData['idCardCode'],
                    'retStatus' => '01',
                    'retMsg' => '查询无数据',
                    'debtList' => [],
                ];
            }
            $retData = $this->formatJson($debtList);
            //DES加密
            $retData = self::desEncrypt($retData, self::$kaoLaKey);
            //RSA签名
            $sign = self::rsaSign($retData, self::$rsaPrivateKeyPem);
            $return = [
                'retCode' => '000000',
                'retMsg'  => 'Success',
                //业务数据
                'retData'=>$retData,
                // 签名结果
                'sign'=>$sign,
            ];
        }else{
            $return = [
                'retCode' => 'A10002',
                'retMsg' => '验签失败',
            ];
        }
        return $return;
    }
    /*##################[债权查询逻辑处理]######################*/

    /*##################[债权数据上传逻辑处理]######################*/

    public function creditDataUploads(){

        $creditModel = new CreditModel();

        //$creditUploadData = $creditModel->getCreditArrayBySize(self::$pageSize);

        $creditUploadData = CreditAllModel::getCreditBySize(self::$pageSize);

        $reqData = $this->formatUploadData($creditUploadData);
        //如果为空直接返回不在执行下面的操作
        if(empty($reqData)){
            return false;
        }
        Log::info($reqData);
        //Des加密
        $reqData = self::desEncrypt($reqData,self::$kaoLaKey);
        //Rsa签名
        $sign = self::rsaSign($reqData, self::$rsaPrivateKeyPem);

        $submitData = array(
            //customerId = 客户号
            'customerId'=>'201609210000000003',
            //prdGrpId = 产品组编码
            'prdGrpId'=>'debtShare',
            //prdId = 产品编码
            'prdId'=>'batchUploadObligor',
            //reqData = 业务数据
            'reqData'=>$reqData,
            //sign = 签名结果
            'sign'=>$sign,
        );

        //提交数据
        $post = self::postData(array(
            'url'=>self::$postUrl,
            'data'=>$submitData,
        ));
        $retData    = isset($post['retData'])? $post['retData']:[];
        $retSign    = isset($post['sign'])?    $post['sign']:"";
        //Rsa验签
        $verify = self::rsaVerify($retData, $retSign, self::$zgcPublicRsaPem);
        if($verify){
            //DES解密
            $jsonData = self::desDecrypt($post['retData'],self::$kaoLaKey);
            $this->saveDataToFile($jsonData);
            Log::info($jsonData);
        }else{
            Log::info("验签失败");
        }

    }

    /**
     * @desc 把上传成功的数据保存到文件中，避免重复上传
     * @param $jsonData
     */
    public function saveDataToFile($jsonData){
        //不存在目录时创建目录
        $dirPath = base_path() . '/public/uploads/zgcData/';
        if (!is_dir($dirPath)) @mkdir($dirPath);

        $fileContentStr = "";
        //格式化接口返回数据
        $returnData =$this->formatJson($jsonData);
        //选择上传成功的数据组装
        foreach($returnData['resultList'] as $key=>$value){
            if($value['result'] == '00'){
                $jnlNo = explode("_",$value['jnlNo']);
                $fileContentStr .= $jnlNo[1]."\n";
            }
        }
        //写入文件
        @file_put_contents($dirPath."zgcUploadRecord.txt", $fileContentStr, FILE_APPEND);
    }
    /*##################[债权数据上传逻辑处理]######################*/

    /*############################[Crypt加密方式的函数]############################*/

    /**
     * @desc DES加密 - 提交数据
     * @param $str
     * @param $key
     * @return string
     */
    public static function desEncrypt($str, $key){
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = $block - (strlen($str) % $block);
        $str .= str_repeat(chr($pad), $pad);
        $str = mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        return base64_encode($str);
    }

    /**
     * @desc DES解密 - 返回数据
     * @param $str
     * @param $key
     * @return string
     */
    public static function desDecrypt($str, $key){
        $str = base64_decode($str);
        $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);
        $block = mcrypt_get_block_size('des', 'ecb');
        $pad = ord($str[($len = strlen($str)) - 1]);
        return substr($str, 0, strlen($str) - $pad);
    }

    /**
     * @desc RSA签名
     * @param $content
     * @param $rsaPrivateKeyPem
     * @return string
     */
    public static function rsaSign($content, $rsaPrivateKeyPem){

        $priKey = openssl_pkey_get_private($rsaPrivateKeyPem);

        if(!$priKey){
            exit('不能使用private.pem');
        }

        $res = openssl_get_privatekey($priKey);
        openssl_sign($content, $sign, $res);
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * @desc  RSA验签
     * @param $data
     * @param $sign
     * @param $publicRsaPath
     * @return bool
     */
    public static function rsaVerify($data, $sign, $publicRsaPath){

        $res = openssl_get_publickey($publicRsaPath);
        //调用openssl内置方法验签，返回bool值
        $result = (bool) openssl_verify($data, base64_decode($sign), $res);
        //释放资源
        openssl_free_key($res);
        //返回资源是否成功
        return $result;
    }
    /**
     * @desc 对json进行编码或解码
     * @param null $data
     * @param int  $DataType
     * @return mixed|string
     */
    public static function formatJson($data=null,$DataType=1){
        if($data){
            if(is_array($data)){
                header('Content-type:application/json');
                return json_encode($data);
            }else{
                return json_decode($data,$DataType);
            }
        }else{
            is_exit(lang('is_json'));
        }
    }
    /**
     * @desc 把内容进行unicode转码
     * @param $name
     * @return string
     */
    public function unicode_encode($name)
    {
        $name = iconv('UTF-8', 'UCS-2', $name);
        $len = strlen($name);
        $str = '';
        for ($i = 0; $i < $len - 1; $i = $i + 2)
        {
            $c = $name[$i];
            $c2 = $name[$i + 1];
            if (ord($c) > 0)
            {    // 两个字节的文字
                $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
            }
            else
            {
                $str .= $c2;
            }
        }
        return $str;
    }
    /*############################[Crypt加密方式的函数]############################*/

    /*############################[数据上报函数]############################*/
    /**
     * 普通Curl的方法:post,get
     * @param array $_param
     * url = 访问的地址
     * data = 提交的数据
     * type = 提交模式:GET,POST
     * header = 头协议
     * @return array|bool|json
     */
    public static function postData($_param = []){
        $url = isset($_param['url']) ? $_param['url'] : '';
        $data = isset($_param['data']) ? $_param['data'] : array();
        $type = isset($_param['type']) ? $_param['type'] : 'POST';
        $header = isset($_param['header']) ? $_param['header'] : null;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        if($type=='POST'){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//数据
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $body = curl_exec($ch);
        curl_close($ch);
        return self::formatJson($body);

    }
    /*############################[数据上报函数]############################*/

    /**
     * @desc 格式化上传负债信息
     * @param $getUploadData
     * @return array
     */
    public function formatUploadData($getUploadData){
        $reqData = [];
        foreach($getUploadData as $key=>$value){
            if($value['type'] != CreditDb::TYPE_NINE_CREDIT){//九省心债权没有借款人


              //  if($value['type'] == CreditDb::TYPE_PROJECT_GROUP){
              //      $creditSource = self::$creditSource[$value['type']];
              //      $creditInfo = $creditSource::findById($value['credit_id']);
              //  }else{
              //      $creditSource = self::$creditSource[$value['source']];
              //      $creditInfo = $creditSource::findById($value['credit_id']);
              //  }

              //  if(!empty($creditInfo['data']['obj']['attributes']['loan_user_identity']) || !empty($creditInfo['data']['obj']['attributes']['loan_username'])){
              //      $identityCardName = array_filter(json_decode($creditInfo['data']['obj']['attributes']['loan_username']))[0];
              //      $identityCard = array_filter(json_decode($creditInfo['data']['obj']['attributes']['loan_user_identity']))[0];
              //      $status = CreditModel::getStatusCode()[$creditInfo['data']['obj']['attributes']['status_code']];
              //  }

                if(!empty($value['loan_user_identity']) || !empty($value['loan_username'])){
                    $identityCardName = $value['loan_username'];
                    $identityCard = $value['loan_user_identity'];
                    $status = CreditModel::getStatusCode()[$value['status_code']];
                }
            }
            $jnlNo = $value['id'].$value['source'].$value['type'];

            $successRecord = $this->getFileUploadData();
            //已经上传成功，跳出循环体
            if(in_array($jnlNo, $successRecord)){
                continue;
            }

            $reqData['reqList'][$key]['jnlNo'] = date("Ymd",time())."_".$jnlNo;
            $reqData['reqList'][$key]['contractNo'] = (!empty($value['contract_no']))? $value['contract_no'] : "JDY-".date("Ym").rand(1,10000);
            $reqData['reqList'][$key]['idCardName'] = (!empty($identityCardName)) ? $identityCardName : "九斗鱼";
            $reqData['reqList'][$key]['idCardCode'] = (!empty($identityCard)) ? $identityCard : "410927198010011234";
            $reqData['reqList'][$key]['mobilePhone'] = '13300006955';
            $reqData['reqList'][$key]['loanDate'] = date("Y-m-d", strtotime($value['created_at']));
            $reqData['reqList'][$key]['loanAmount'] = $value['loan_amounts'];
            $reqData['reqList'][$key]['loanPeriod']   = $value['loan_deadline'] > 12 ? 1 : $value['loan_deadline'];
            $reqData['reqList'][$key]['loanDays']  = (($value['repayment_method'] == ProjectDb::REFUND_TYPE_BASE_INTEREST) || ($value['repayment_method'] == ProjectDb::REFUND_TYPE_FIRST_INTEREST)) ? $value['loan_deadline'] : $value['loan_deadline'] * 30;
            $reqData['reqList'][$key]['loanStatus']   = self::$loanStatus[160];
            $reqData['reqList'][$key]['refundStatus']   = 1;
            $reqData['reqList'][$key]['loanType']   = 3;
            $reqData['reqList'][$key]['lawDay'] = $value['expiration_date'];
            $reqData['reqList'][$key]['realSquareDate'] = '';
            $reqData['reqList'][$key]['overdueDate'] = '';
            $reqData['reqList'][$key]['overdueLevel'] = 'M0';
            $reqData['reqList'][$key]['overdueAmount'] = '0';
            $reqData['reqList'][$key]['company'] = $value['company_name'];
            $reqData['reqList'][$key]['companyTel'] = '';
            $reqData['reqList'][$key]['fixphone'] = '';
            $reqData['reqList'][$key]['linkman'] = '';
            $reqData['reqList'][$key]['linkmanPhone'] = '';
        }
        if(!empty($reqData)){
            return $this->formatJson($reqData);
        }else{
            return [];
        }

    }

    /**
     * @desc 获取上传成功的记录组装成数组
     * @return array
     */
    public function getFileUploadData(){
        $successRecordArr = [];
        $fileContent = '';
        //不存在目录时创建目录
        $fileName = base_path() . '/public/uploads/zgcData/zgcUploadRecord.txt';
        //if (!is_dir($dirPath)) @mkdir($dirPath);
        if(file_exists($fileName)){
            $fileContent = file_get_contents($fileName);
        }
        if(!empty($fileContent)){
            $successRecordArr = explode("\n",$fileContent);
        }
        return $successRecordArr;
    }

    /**
     * @desc 格式化查询债权查询数据
     * @param $creditInfo
     * @return mixed
     */
    public function formatSearchCredit($creditInfo){

        foreach ($creditInfo as $key => $value) {
            $debtList[$key]['contractNo'] = (!empty($value['contract_no']))? $value['contract_no'] : "JDY-".date("Ym").rand(1,10000);
            $debtList[$key]['mobilePhone'] = '13300006955';
            $debtList[$key]['loanDate'] = date("Y-m-d", strtotime($value['created_at']));
            $debtList[$key]['loanAmount'] = $value['loan_amounts'];
            $debtList[$key]['loanPeriod'] =  $value['loan_deadline'] > 12 ? 1 : $value['loan_deadline'];
            $debtList[$key]['loanDays'] = (($value['repayment_method'] == ProjectDb::REFUND_TYPE_BASE_INTEREST) || ($value['repayment_method'] == ProjectDb::REFUND_TYPE_FIRST_INTEREST)) ? $value['loan_deadline'] : $value['loan_deadline'] * 30;
            $debtList[$key]['loanStatus'] = self::$loanStatus[160];
            $debtList[$key]['refundStatus'] = 1;
            $debtList[$key]['loanType'] = 3;
            $debtList[$key]['company'] = $value['company_name'];
            $debtList[$key]['companyTel'] = '';
            $debtList[$key]['lawDay'] = $value['expiration_date'];
            $debtList[$key]['realSquareDate'] = '';
            $debtList[$key]['overdueDate'] = '';
            $debtList[$key]['overdueLevel'] = 'M0';
            $debtList[$key]['overdueAmount'] = '0';
            $debtList[$key]['fixphone'] = '';
            $debtList[$key]['linkman'] = '';
            $debtList[$key]['linkmanPhone'] = '';
        }
        return $debtList;
    }

}
