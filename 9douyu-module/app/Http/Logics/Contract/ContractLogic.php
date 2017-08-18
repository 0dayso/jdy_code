<?php

namespace App\Http\Logics\Contract;

use App\Http\Dbs\Project\ProjectDb;
use App\Http\Dbs\Contract\ContractDb;
use App\Http\Dbs\Credit\CreditDb;
use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Logics\SystemConfig\SystemConfigLogic;
use App\Http\Logics\Credit\CreditThirdDetailLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\Oss\OssLogic;
use App\Http\Logics\Project\ProjectDetailLogic;
use App\Http\Logics\User\SessionLogic;
use App\Http\Models\Common\CoreApi\CreditAssignProjectModel;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Http\Models\Common\ServiceApi\EmailModel;
use App\Http\Models\Common\ValidateModel;
use App\Http\Models\Contract\ContractModel;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\Common\CoreApi\UserModel as CoreApiUserModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Tools\ToolArray;
use App\Tools\ToolIdCard;
use App\Tools\ToolUrl;

/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/10/18
 * Time: 下午3:00
 */
class ContractLogic extends Logic
{

    CONST

        CONTRACT_INPUT_PATH  = 'contract_file/ebaoquan/input/',
        CONTRACT_OUTPUT_PATH = 'contract_file/ebaoquan/output/',
        CONTRACT_BASE_PATH   = '/contract_file/',


        END=true;
    //老系统 应收账款转让及回购协议 新老合同模板分界点
//    Const NEW_CONTRACT_CREDIT_PROJECT_ID = 70;//项目编号；区分合同模板

//    protected static $typeData = [
//        'argument'      => '投资咨询与管理服务协议',
//        'factor'        => '九斗鱼债权转让协议',
//        'free'          => '九斗鱼九省心投资协议',
//        'homeloan'      => '债权转让协议',
//        'preinterest'   => '九斗鱼闪电付息投资协议',
//        'group_credit'  => '应收账款转让及回购协议',
//        'credit'        => '应收账款转让及回购协议',
//        'credit_assign' => '站内债权转让协议',
//    ];
//易保全的相关的通道
    protected static $contractMethod = [
        'EBQ'             => 'doDownLoad',         //易宝全合同
        'JZQ'             => 'doDownLoadJzq',         //易宝全的君子签章
    ];
    protected static $contractSendEmailMethod = [
        'EBQ'             => 'doContractSendEmail',         //易宝全合同
        'JZQ'             => 'doContractSendEmailJzq',         //易宝全的君子签章
    ];

    protected static $buildContractMethod = [
        'EBQ'             => 'doCreateDownLoad',         //易宝全合同
        'JZQ'             => 'doCreateDownLoadJzq',         //易宝全的君子签章
    ];

    protected static $getDownContractMethod = [
        'EBQ'             => 'getEbqDownUrl',         //易宝全合同
        'JZQ'             => 'getJzqDownUrl'         //易宝全的君子签章
    ];
    protected static $checkDownContractMethod = [
        'EBQ'             => 'getDownEbqFile',         //易宝全合同
        'JZQ'             => 'getDownJzqFile'         //易宝全的君子签章
    ];
    /*
     * 获取合同[模板、标题、数据]
     *
     * @param string $type
     * @param int $projectId
     */
    public static function getContent($type='', $projectId = 0, $investId = 0, $isLogin=true)
    {
        $data   =   [];
        //站内债权转让
        if($type == 40){

            $data['data']    = self::getCreditAssignData($projectId, $investId, $isLogin);
        }else {
            //其他项目
             $data['data']    = self::getDataByNewCredit($projectId, $investId , $isLogin);
            //$data['data']    = self::getData($projectId, $investId , $isLogin);
            //第三方债权设置模版
            if(isset($data['data']['credit']['source']) && $data['data']['credit']['source'] == CreditDb::SOURCE_THIRD_CREDIT){
                $data['data']['type']= 'third';
            }
        }
        return $data;
    }

    public function getContractListByInvestId($investIds = '')
    {
        if( empty( $investIds ) ) {

            return [];
        }
        $contractDb =   new ContractDb();

        $list       =   $contractDb->getByInvestIds($investIds);

        if( empty($list) ) {

            return [];
        }

        return ToolArray::arrayToKey ($list,'invest_id');
    }
    /**
     * 获取合同需要的数据 - new credit
     * @param int $projectId
     * @param int $investId
     * @return array
     */
    public static function getDataByNewCredit($projectId = 0, $investId = 0, $isLogin=true){

        try {

            $projectDetailLogic = new ProjectDetailLogic();

            // 甲方资料
            if($isLogin){
                $user = SessionLogic::getTokenSession();
            }
            // 项目信息
            $project = $projectDetailLogic->get($projectId);

            // 债权信息
            $creditDetail   =  $projectDetailLogic->getCreditByProjectId($projectId);

            $creditType     =   $creditDetail['type'] == CreditDb::TYPE_NINE_CREDIT ? CreditDb::TYPE_NINE_CREDIT : $creditDetail['source'];

            $projectWay     =   ProjectLinkCreditModel::getOldProjectWay( $creditType );

            if( $project['product_line'] == ProjectDb::PROJECT_PRODUCT_LINE_SDF ) {

                $projectWay = 'pre';
            }

            if($creditDetail['source'] == CreditDb::SOURCE_THIRD_CREDIT) {

                $projectWay = 'third';
            }

            $investRecord   =   [];

            $matchResult    =   [];

            if ( !empty($investId) ) {
                // 投资信息
                $investRecord = InvestModel::getInvestByInvestId($investId);

                if(!$isLogin){
                    $user = CoreApiUserModel::getCoreApiUserInfo($investRecord['user_id']);
                }

                // 指定投资回款信息
                $userId = isset($user['id']) ? $user['id'] : 0;

                $refundPlan = CoreApiUserModel::getRefundDetail($userId, $investId);
                // 指定投资首次回款日期
                $refundTime = isset($refundPlan['plan'][0]['times']) ? $refundPlan['plan'][0]['times'] : null;

                //第三方债权获取匹配结果
                if($creditDetail['source'] == CreditDb::SOURCE_THIRD_CREDIT){

                    $matchResult = CreditThirdDetailLogic::getThirdCreditMatch($investRecord['cash'],$creditDetail['id'], $userId, $investId);
                }
            } else {
                //项目还款计划
                $refundPlan = $projectDetailLogic->getRefundPlan($projectId);
                $refundTime = isset($refundPlan[0]['refund_time']) ? $refundPlan[0]['refund_time'] : null;
            }
            return [
                'loginUser'          => $user,
                'project'            => $project,
                'credit'             => $creditDetail,
                'signDay'            => self::getSignDay($investRecord),
                'invested'           => $investRecord,
                'matchResult'        => $matchResult,
                'refundTime'         => $refundTime,
                'refundPlan'         => $refundPlan,
                //'projectLinkCredit'  => $projectLinkCredit,
                'projectWay'         => $projectWay,
            ];
        }catch (\Exception $e){
            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);
        }
        return [];
    }

    /**
     * 获取合同需要的数据
     *
     * @param int $projectId
     * @param int $investId
     * @return array
     */
    public static function getData($projectId = 0, $investId = 0, $isLogin=true){

        try {
            $projectDetailLogic = new ProjectDetailLogic();

            // 甲方资料
            if($isLogin){
                $user = SessionLogic::getTokenSession();
            }

            // 项目信息
            $project = $projectDetailLogic->get($projectId);

            // 债权关联表
            $projectLinkCredit = $projectDetailLogic->getProjectLineCreditDetail($projectId);

            $projectWay        = ProjectLinkCreditModel::getProjectWay($projectLinkCredit);

            $projectWay        = ProjectLinkCreditModel::getOldProjectWay($projectWay);

            if($project['product_line'] == 300){
                $projectWay = 'pre';
            }

            // 债权信息
            $creditDetail = $projectDetailLogic->getCreditData($projectId);

            if($creditDetail[0]['source'] == CreditDb::SOURCE_THIRD_CREDIT){
                $projectWay = 'third';
            }

            $investRecord = [];
            if (!empty($investId)) {
                // 投资信息
                $investRecord = InvestModel::getInvestByInvestId($investId);

                if(!$isLogin){
                    $user = CoreApiUserModel::getCoreApiUserInfo($investRecord['user_id']);
                }

                // 指定投资回款信息
                $userId = isset($user['id']) ? $user['id'] : 0;
                $refundPlan = CoreApiUserModel::getRefundDetail($userId, $investId);
                // 指定投资首次回款日期
                $refundTime = isset($refundPlan['plan'][0]['times']) ? $refundPlan['plan'][0]['times'] : null;

                //第三方债权获取匹配结果
                if($creditDetail[0]['source'] == CreditDb::SOURCE_THIRD_CREDIT){
                    $matchResult = CreditThirdDetailLogic::getThirdCreditMatch($investRecord['cash'],$creditDetail[0]['id'], $userId, $investId);
                }else{
                    $matchResult = [];
                }
            } else {
                //项目还款计划
                $refundPlan = $projectDetailLogic->getRefundPlan($projectId);
                $refundTime = isset($refundPlan[0]['refund_time']) ? $refundPlan[0]['refund_time'] : null;
            }
            return [
                'loginUser'          => $user,
                'project'            => $project,
                'credit'             => $creditDetail,
                'signDay'            => self::getSignDay($investRecord),
                'invested'           => $investRecord,
                'matchResult'        => !empty($matchResult) ? $matchResult : [],
                'refundTime'         => $refundTime,
                'refundPlan'         => $refundPlan,
                'projectLinkCredit'  => $projectLinkCredit,
                'projectWay'         => $projectWay,
            ];
        }catch (\Exception $e){
            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);
        }
        return [];
    }


    /**
     * 获取债权
     *
     * @param int $projectId
     * @param int $investId
     * @return array
     */
    public static function getCreditAssignData($projectId = 0, $investId = 0, $isLogin=true){
        try {
            $projectDetailLogic     = new ProjectDetailLogic();

            $user = '';
            if($isLogin){
                // 甲方资料
                $user                   = SessionLogic::getTokenSession();
            }

            $investRecord = $creditAssignProject = $refundTime = $investRecord = null;

            if(!empty($investId)) {
                // 乙方资料、甲方投资记录、乙方转让投资信息
                $creditAssignProject = CreditAssignProjectModel::getCreditAssignByInvestId($investId);
                // 投资信息
                $investRecord           = InvestModel::getInvestByInvestId($investId);
                if(!$isLogin){
                    $user = CoreApiUserModel::getCoreApiUserInfo($investRecord['user_id']);
                }
                // 指定投资回款信息
                $userId                 = isset($user['id']) ? $user['id'] : 0;
                if(!empty($userId)) {
                    $refundPlan = CoreApiUserModel::getRefundDetail($userId, $investId);
                    // 指定投资首次回款日期
                    $refundTime = isset($refundPlan['plan'][0]['times']) ? $refundPlan['plan'][0]['times'] : null;
                }

            }

            // 项目信息
            $project                = $projectDetailLogic->get($projectId);

            // 债权关联表
            //$projectLinkCredit      = $projectDetailLogic->getProjectLineCreditDetail($projectId);

            // 债权信息
            //$creditDetail           = $projectDetailLogic->getCreditData($projectId);

            // 债权信息getCreditByProjectId
            $creditDetail   =  $projectDetailLogic->getCreditByProjectId($projectId);


            return [
                'loginUser'          => $user,
                'assignUser'         => !empty($creditAssignProject['user']) ? $creditAssignProject['user'] : [],
                'invested'           => $investRecord,
                'assignCredit'       => !empty($creditAssignProject['creditAssignDetail']) ? $creditAssignProject['creditAssignDetail'] : [],
                'project'            => $project,
                'credit'             => $creditDetail,
                //'projectLinkCredit'  => $projectLinkCredit,
                'signDay'            => self::getSignDay($investRecord),
                'refundTime'         => $refundTime,
                'projectWay'         => 40,

            ];
        }catch (\Exception $e){
            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);
        }
        return [];

    }


    /**
     * 获取签合同日期
     *
     * @param array $investRecord
     * @return bool|string
     */
    protected static function getSignDay($investRecord=[]) {
        $signDay    = date(' Y 年__月__日');

        if(!empty($investRecord['created_at'])){
            $signDay = date(' Y 年 m 月 d 日', strtotime($investRecord['created_at']));
        }

        return $signDay;
    }

    /**
     * @param $data
     * @return array
     * @desc 生成保全合同
     * 1. 通过InvestId获取投资详细信息
     * 2. 生成pdf内容并保存
     * 3. 创建保全
     * 4. 存储保全信息
     * 5. 获取下载地址并返回
     */
    public function doCreateDownLoad( $data , $isLogin=true ){

        $investId       =   $data['invest_id'];

        $contractInfo   =   $this->getContractInfo( $investId, $isLogin );

        if($contractInfo['status'] == false ) {

            return $contractInfo;
        }

        $investInfo     =   $contractInfo['data']['investInfo'];
        $content        =   $contractInfo['data']['content'];
        $userId         =   $investInfo['user_id'];
        $projectId      =   $investInfo['project_id'];
        $cash           =   $investInfo['cash'];

        $type           =   $content['data']['projectWay'];
        //获取合同pdf的具体内容
        $htmlRender = AgreementLogic::getPdfAgreementByType($type, $content);

        $contractPath   =   $this->setContractFilePath( $userId,$projectId,$investId,$type ,$htmlRender );

        $title          =   $contractPath['title'];

        $contractNum    =   $contractPath['contractNum'];

        $ossPdfPath     =   $contractPath['ossPdfPath'];

        $userInfo       =   $content['data']['loginUser'];

        $ebqData        =   [
            'contract_num'    => 'JDY-'.$contractNum,
            'cash'            => $cash,
            'user_id'         => $userId,
            'identity'        => $userInfo['identity_card'],
            'real_name'       => $userInfo['real_name'],
            'phone'           => $userInfo['phone'],
            'file_path'       => $ossPdfPath ,
            'file_name'       => $title,
            'type'            => $type,
        ];

        $preservationId =  EbqLogic::cfPreservationCreate( $ebqData );

        if( $preservationId ){

            $return =  $this->doDownContractFile($investInfo,$contractPath ,$preservationId,'EBQ');

            if( $return['status'] == true ) {

                $checkParams    =   [
                    'apply_no'      =>  $preservationId,
                    'phone'         =>  $userInfo['phone'],
                    'real_name'     =>  $userInfo['real_name'],
                    'project_id'    =>  $projectId,
                    'identity'      =>  $userInfo['identity_card'],
                    'title'         =>  $title,
                    'email'         =>  isset($data['email']) ? $data['email'] : '',
                    'outPutFile'    =>  $contractPath['outPutFile'],
                    'invest_id'     =>  $data['invest_id'] ,
                    'limit_time'    =>  15 ,
                ];

                \Event::fire( new \App\Events\User\CheckContractEvent(['contract' => $checkParams ]) );
            }

            return self::callSuccess ();
        }

        return self::callError('下载合同失败');
    }

    /**
     * @param $investId
     * @param $isLogin
     * @return array
     * @desc 获取合同的内容
     */
    protected function getContractInfo($investId ,$isLogin)
    {
        //通过投资Id获取投资记录
        $projectModel   =   new ProjectModel();

        $investInfo     =   $projectModel->getListByIds( [$investId] );

        if( empty($investInfo) ){

            return self::callError('投资记录不存在');
        }
        $investInfo     =   $investInfo[0];

        $investInfo['invest_id']    =   $investId;

        $projectId      =   $investInfo['project_id'];

        //获取合同所需要的数据
        $type           =   $investInfo['invest_type'] ? 40 : 0 ;

        $content        =   self::getContent($type, $projectId, $investId, $isLogin);

        $contractInfo = [
                'investInfo'=>  $investInfo ,
                'content'   =>  $content
             ];
        return self::callSuccess ($contractInfo);
    }

    /**
     * @param $userId
     * @param $projectId
     * @param $investId
     * @param $type
     * @param $htmlRender
     * @return array
     * @desc  上传Oss文件并生成数据
     */
    protected function setContractFilePath( $userId,$projectId,$investId,$type ,$htmlRender )
    {
        $datePath       =    date("Ymd");

        $inputDir       =    self::CONTRACT_INPUT_PATH. "$datePath";

        $outPutDir      =    self::CONTRACT_OUTPUT_PATH. "$datePath";

        //合同编号
        $contractNum    =    $userId.'-'.$projectId.'-'.$investId;
        //合同名称
        $title          =    AgreementLogic::getTitleByType($type).$contractNum.'.pdf';
        //合同路径
        $pdfPath        =   $inputDir.'/'.$title;

        //将合同内容写入pdf文件
        $ossLogic       =   new OssLogic('oss_2');

        $ossLogic->writeFile($htmlRender,$pdfPath);

        $ossPdfPath     =   $ossLogic->getSignUrl($pdfPath);

        $outPutFile     =   $outPutDir.'/'.$title;

        return [
            'inputDir'  =>  $inputDir,
            'outPutDir' =>  $outPutDir,
            'contractNum'=> $contractNum,
            'title'     =>  $title,
            'pdfPath'   =>  $pdfPath,
            'ossPdfPath'=>  $ossPdfPath,
            'outPutFile'=>  $outPutFile,
            'baseSaveFile'=>self::CONTRACT_BASE_PATH,
        ];
    }

    /**
     * @param array $investInfo
     * @param array $contractPath
     * @param int $preservationId
     * @return array
     * @desc 下载文件并记入数据库
     */
    protected function doDownContractFile($investInfo=[],$contractPath=[],$preservationId='',$ebqType='EBQ')
    {
        if( empty( $preservationId ) ) {

            return self::callError('下载合同失败');
        }
        $contractDb =   new ContractDb();

        if($contractDb->getByInvestId ($investInfo['invest_id'])) {

            return self::callError('合同已经存在');
        }

        try{

            self::beginTransaction();

            $contractData = [
                'invest_id'         => $investInfo['invest_id'],
                'user_id'           => $investInfo['user_id'],
                'project_id'        => $investInfo['project_id'],
                'cash'              => $investInfo['cash'],
                'contract_num'      => 'JDY-'.$contractPath['contractNum'],
                'pdf_path'          => $contractPath['pdfPath'],
                'ebq_pdf_path'      => $contractPath['outPutFile'],
                'preservation_id'   => $preservationId,
                'ebq_type'          => $ebqType,
            ];

            if( $ebqType == 'JZQ' ) {

                $contractData['status'] = ContractDb::CONTRACT_STATUS_DOING ;
            }
            $contractModel  =  new ContractModel();

            $contractModel->doAddInfo( $contractData );

            self::commit();

        }catch (\Exception $e){

            self::rollback();

            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);

            return self::callError('下载合同失败');
        }

        return self::callSuccess(['down_load_url' => $contractPath['outPutFile'], 'show_load' => $contractPath['outPutFile'], 'file_name' => $contractPath['title']]);
    }
    /**
     * @param $data
     * @return array
     * @desc 生成保全合同
     * 1. 通过InvestId获取投资详细信息
     * 2. 生成pdf内容并保存
     * 3. 创建保全
     * 4. 存储保全信息
     * 5. 获取下载地址并返回
     */
    public function doCreateDownLoadJzq( $data , $isLogin=true ){

        $investId       =   $data['invest_id'];

        $contractInfo   =   $this->getContractInfo( $investId, $isLogin );

        if($contractInfo['status'] == false ) {

            return $contractInfo;
        }

        $investInfo     =   $contractInfo['data']['investInfo'];
        $content        =   $contractInfo['data']['content'];
        $userId         =   $investInfo['user_id'];
        $projectId      =   $investInfo['project_id'];
        $cash           =   $investInfo['cash'];

        //获取合同所需要的数据
        $type           =   $content['data']['projectWay'];

        //获取合同pdf的具体内容
        $htmlRender     =   AgreementLogic::getPdfAgreementByType($type, $content);

        $contractPath   =   $this->setContractFilePath( $userId,$projectId,$investId,$type ,$htmlRender );

        $title          =   $investInfo['user_id'] . $investInfo['project_id'] . $investId . '.pdf';

        //$baseSaveFile   =   isset($contractPath['baseSaveFile']) ? base_path() . $contractPath['baseSaveFile'] :  '/tmp/';

        $contractNum    =   $contractPath['contractNum'];

        //if (!is_dir($baseSaveFile )) @mkdir($baseSaveFile);

        //@file_put_contents( $baseSaveFile . $title, $htmlRender) ;

        $userInfo       = $content['data']['loginUser'];

        $jzqData        =   [
            'contract_num'    => 'JDY-'.$contractNum,
            'cash'            => $cash,
            'user_id'         => $userId,
            'identity'        => $userInfo['identity_card'],
            'real_name'       => $userInfo['real_name'],
            'phone'           => $userInfo['phone'],
            'file_path'       => $contractPath['ossPdfPath'] ,
            'file_name'       => $contractPath['title'],
            'type'            => $type,
            'file_number'     => $investInfo['user_id'] . $investInfo['project_id'] . $investId . '.pdf',
        ];

        $contractDb =   new ContractDb();

        if($contractDb->getByInvestId ($investId)) {

            return self::callError('合同已经存在');
        }

        $preservationId =  EbqLogic::doUpdateApplySignFile( $jzqData );

        $return =  $this->doDownContractFile($investInfo,$contractPath ,$preservationId,'JZQ');

        //@unlink($baseSaveFile .'/'.$title);

        if( $return['status'] == true ) {

            $checkParams    =   [
                'apply_no'      =>  $preservationId,
                'phone'         =>  $userInfo['phone'],
                'real_name'     =>  $userInfo['real_name'],
                'project_id'    =>  $projectId,
                'identity'      =>  $userInfo['identity_card'],
                'title'         =>  $contractPath['title'],
                'email'         =>  isset($data['email']) ? $data['email'] : '',
                'outPutFile'    =>  $contractPath['outPutFile'],
                'invest_id'     =>  $data['invest_id'] ,
                'limit_time'    =>  15 ,
            ];
            \Event::fire( new \App\Events\User\CheckContractEvent(['contract' => $checkParams ]) );

            return self::callSuccess ();
        }


        return self::callError('下载合同失败');
    }
    /**
     * 下载合同，使用短信提示
     */
    protected function getJzqDownUrl($data=[])
    {
        $preservationId =   $data['apply_no'] ;

        $realName       =   $data['real_name'] ;

        $identityCard   =   $data['identity'] ;

        $title          =   isset($data['title']) && !empty( $data['title']) ? $data['title'] : '九斗鱼投资合同' ;

        $jzqSignStatus =   EbqLogic::JzqService( $preservationId, EbqLogic::CF_GET_SIGN_STATUS,$realName , $identityCard);

        if($jzqSignStatus['success'] && $jzqSignStatus['success'] == true && $jzqSignStatus['signStatus'] == 3) {

             $jzqStatus =EbqLogic::JzqService( $preservationId, EbqLogic::CF_DOWN_LOAD_URL,$realName , $identityCard);

             $outPutDir  =   self::CONTRACT_OUTPUT_PATH. date('Ymd') ;

             if( isset( $jzqStatus['success'] ) && $jzqStatus['success'] == true ) {

                 if( ToolUrl::getFileAndSaveByUrl($jzqStatus['link'], $outPutDir, $title)== true ) {

                     $contractDb    =   new ContractDb();

                     $contractDb->doUpdate( ['invest_id' => $data['invest_id'],'status' => ContractDb::CONTRACT_STATUS_GOT] ) ;

                     return self::callSuccess('合同下载成功');
                 }
                 return self::callError() ;
             }
             return self::callSuccess('合同生成成功');
        }

        if( isset($data['limit_time']) && $data['limit_time'] > 0 ) {

            sleep(2);

            \Event::fire( new \App\Events\User\CheckContractEvent(['contract' => $data ]) );
        }
        return self::callError('合同下载失败') ;
    }

    /**
     * 下载合同，使用短信提示
     */
    protected function getEbqDownUrl($data=[])
    {
        $preservationId =   $data['apply_no'] ;

        $title          =   isset($data['title']) && !empty( $data['title']) ? $data['title'] : '九斗鱼投资合同' ;

        $ebqStatus      =   EbqLogic::ebqService( $preservationId, EbqLogic::CF_DOWN_LOAD_URL);

        $outPutDir      =   self::CONTRACT_OUTPUT_PATH. date('Ymd') ;

        if( isset( $ebqStatus['success'] ) && $ebqStatus['success'] == true ) {

            if( ToolUrl::getFileAndSaveByUrl($ebqStatus['downUrl'], $outPutDir, $title)==true ) {

                return self::callSuccess('合同下载成功');
            }
        }
        if( isset($data['limit_time']) && $data['limit_time'] > 0 ) {

            sleep(2);

            \Event::fire( new \App\Events\User\CheckContractEvent(['contract' => $data ]) );
        }
        return self::callError('合同下载失败') ;
    }
    /**
     * @param $data
     * @return array|bool
     * 合同下载
     */
    public function doDownLoad( $data, $isLogin=true )
    {

        try{

            $investId = $data['invest_id'];

            $db = new ContractDb();

            $contractInfo = $db->getByInvestId($investId);

            if( empty($contractInfo) ) {

                return $this->doCreatedContractFile($data);
            }

            $ossLogic = new OssLogic('oss_2');
            $ybqPath = $contractInfo['ebq_pdf_path'];
            $outPutDir = substr($ybqPath,0,strrpos($ybqPath,'/'));
            $title     = substr($ybqPath,strrpos($ybqPath,'/')+1);
            $ybqExit  = $ossLogic->checkPathExit($ybqPath);

            if($ybqExit){

                return self::callSuccess(['down_load_url' => $ybqPath, 'file_name' => $title]);
            }

            $wayData        =   [
                'way'       =>  isset($contractInfo['ebq_type']) && !empty($contractInfo['ebq_type']) ? $contractInfo['ebq_type'] : 'EBQ',
                'apply_no'  =>  $contractInfo['preservation_id'],
                'user_id'   =>  $contractInfo['user_id'],
                'outPutDir' =>  $outPutDir,
                'title'     =>  $title,
                'ybqPath'   =>  $ybqPath,
            ];

            return $this->checkDownContractByWay($wayData);

        }catch(\Exception $e){

            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);

            return self::callError($e->getMessage());
        }

    }

    /**
     * @param $data
     * @param bool $isLogin
     * @return array
     * @desc 君子签的下载地址
     */
    protected function doDownLoadJzq( $data, $isLogin=true )
    {
        try{

            $investId = $data['invest_id'];

            $db = new ContractDb();

            $contractInfo = $db->getByInvestId($investId);

            //本地信息不存在，新创建合同保全文件
            if( empty($contractInfo) ) {

                return $this->doCreatedContractFile($data);
            }
            //下载已经生成的保全合同文件
            $ossLogic   = new OssLogic('oss_2');
            $ybqPath    = $contractInfo['ebq_pdf_path'];
            $outPutDir  = substr($ybqPath,0,strrpos($ybqPath,'/'));
            $title      = substr($ybqPath,strrpos($ybqPath,'/')+1);
            $ybqExit    = $ossLogic->checkPathExit($ybqPath);

            if($ybqExit){

                return self::callSuccess(['down_load_url' => $ybqPath, 'file_name' => $title]);
            }
            $wayData        =   [
                'way'       =>  isset($contractInfo['ebq_type']) && !empty($contractInfo['ebq_type']) ? $contractInfo['ebq_type'] : 'EBQ',
                'apply_no'  =>  $contractInfo['preservation_id'],
                'user_id'   =>  $contractInfo['user_id'],
                'outPutDir' =>  $outPutDir,
                'title'     =>  $title,
                'ybqPath'   =>  $ybqPath,
            ];

            return $this->checkDownContractByWay($wayData);

        }catch(\Exception $e){

            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);

            return self::callError($e->getMessage());
        }
    }
    /**
     * @param $data
     * @param bool $isLogin
     * @return array
     * 合同预览
     */
    public function contractShow( $data, $isLogin=true )
    {

        try{

            $investId = $data['invest_id'];

            $db = new ContractDb();

            $contractInfo = $db->getByInvestId($investId);

            if(empty($contractInfo)){

                $pdfInfo = $this->doCreateDownLoad($data, $isLogin);

                $path   = $pdfInfo['data']['show_load'];

            }else{

                $path = $contractInfo->ebq_pdf_path;

            }

            $ossLogic = new OssLogic('oss_2');
            $fileUrl     = $ossLogic->getSignUrl($path);

            return $fileUrl;
            //return '/'.$path;
            //AgreementLogic::showPdfAgreement($path);

        }catch(\Exception $e){

            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);

            return self::callError($e->getMessage());

        }

    }

    /**
     * @param $investId
     * @param $email
     * @param bool $isLogin
     * @return array
     * @desc 发送邮件
     */
    public function doContractSendEmail( $investId, $email, $user=[], $isLogin=true )
    {

        try{

            $data['invest_id'] = $investId;

            ValidateModel::isEmail($email);

            $db = new ContractDb();

            $contractInfo = $db->getByInvestId($investId);

            if( empty($contractInfo) ) {

                $data['email']  =   $email;

                return  $this->doCreateDownLoad($data, $isLogin);
            }
            $ossLogic   = new OssLogic('oss_2');

            $path       = $contractInfo['ebq_pdf_path'];

            if( !$ossLogic->checkPathExit($path) ) {
                $outPutDir      = substr($path,0,strrpos($path,'/'));
                $title          = substr($path,strrpos($path,'/')+1);
                $wayData        =   [
                    'way'       =>  isset($contractInfo['ebq_type']) && !empty($contractInfo['ebq_type']) ? $contractInfo['ebq_type'] : 'EBQ',
                    'apply_no'  =>  $contractInfo['preservation_id'],
                    'user_id'   =>  $contractInfo['user_id'],
                    'outPutDir' =>  $outPutDir,
                    'title'     =>  $title,
                    'ybqPath'   =>  $path,
                ];

                $checkRes   =    $this->checkDownContractByWay($wayData);

                if( $checkRes['status'] == false ){

                    return self::callError('合同发送失败,请重试');
                }
            }

            $result = $this->doSendContractEmail($email,$user,$path);

            if($result['status']) {

                return self::callSuccess([], '合同发送成功');
            }

        }catch(\Exception $e){

            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);

            return self::callError($e->getMessage());

        }
        return self::callError('合同发送失败,请重试');
    }
    /**
     * @param $investId
     * @param $email
     * @param bool $isLogin
     * @return array
     * @desc 发送邮件
     */
    public function doContractSendEmailJzq( $investId, $email, $user=[], $isLogin=true )
    {

        try{

            $data['invest_id'] = $investId;

            ValidateModel::isEmail($email);

            $db             = new ContractDb();

            $contractInfo   = $db->getByInvestId($investId);

            if( empty($contractInfo) ) {

                $data['email']  =   $email;

                return $this->doCreateDownLoadJzq($data, $isLogin);

            }

            $ossLogic       = new OssLogic('oss_2');

            $path           = $contractInfo['ebq_pdf_path'];

            if( !$ossLogic->checkPathExit($path) ) {
                $outPutDir      = substr($path,0,strrpos($path,'/'));
                $title          = substr($path,strrpos($path,'/')+1);
                $wayData        =   [
                    'way'       =>  isset($contractInfo['ebq_type']) && !empty($contractInfo['ebq_type']) ? $contractInfo['ebq_type'] : 'EBQ',
                    'apply_no'  =>  $contractInfo['preservation_id'],
                    'user_id'   =>  $contractInfo['user_id'],
                    'outPutDir' =>  $outPutDir,
                    'title'     =>  $title,
                    'ybqPath'   =>  $path,
                ];

                $checkRes   =    $this->checkDownContractByWay($wayData);

                if( $checkRes['status'] == false ){

                    return self::callError('合同发送失败,请重试');
                }
            }


            $result = $this->doSendContractEmail($email,$user,$path);

            if($result['status']){

                return self::callSuccess([], '合同发送成功');
            }

        }catch(\Exception $e){

            \Log::info(__METHOD__, [$e->getMessage(), $e->getCode(), $e->getLine()]);

            return self::callError($e->getMessage());

        }

        return self::callError('合同发送失败,请重试');
    }

    /**
     * @param array $data
     * @return array
     * @desc 下载之前易保全的文件
     */
    protected function getDownEbqFile($data = [])
    {
        $preservationId = $data['apply_no'];

        $downLoadUrl    = EbqLogic::ebqService( $preservationId, EbqLogic::CF_DOWN_LOAD_URL);

        if(isset($downLoadUrl['success']) && $downLoadUrl['success'] == true){

            $downLoadUrls = $downLoadUrl['downUrl'];

            ToolUrl::getFileAndSaveByUrl($downLoadUrls, $data['outPutDir'], $data['title']);

            return self::callSuccess(['down_load_url' => $data['ybqPath'], 'file_name' => $data['title']]);
        }

        $errorMsg = empty($downLoadUrl['error']['message']) ? '下载合同失败' : $downLoadUrl['error']['message'];

        return self::callError($errorMsg);
    }

    /**
     * @param array $data
     * @return array
     * @desc 下载君子签的合同
     */
    protected function getDownJzqFile($data = [])
    {
        $userInfo       = UserModel::getCoreApiUserInfo ($data['user_id']);

        $downLoadUrl    = EbqLogic::JzqService( $data['apply_no'], EbqLogic::CF_DOWN_LOAD_URL,$userInfo['real_name'] , $userInfo['identity_card']);

        if(isset($downLoadUrl['success']) && $downLoadUrl['success'] == true){

            $downLoadUrls = $downLoadUrl['link'];

            ToolUrl::getFileAndSaveByUrl($downLoadUrls, $data['outPutDir'], $data['title']);

            return self::callSuccess(['down_load_url' => $data['ybqPath'], 'file_name' => $data['title']]);
        }

        return self::callError('下载合同失败');
    }

    /**
     * @param $email
     * @param $user
     * @param $path
     * @return null|void
     * @desc 执行邮件发送
     */
    public function doSendContractEmail($email,$user,$path)
    {
        $title      = '九斗鱼投资合同供您查阅！';

        $realName   = isset($user['real_name']) ? $user['real_name'] : '鱼客';

        $sexName    = isset($user['identity_card']) ? ToolIdCard::getSexNameByIdCard($user['identity_card']) : '';

        $content    = "尊敬的".$realName.$sexName.",您好! 您投资的项目合同详情已发送至邮箱附件，请查收！ \n\r  此邮件为系统自动发送，请勿回复！更多理财问题，请拨打全国客服电话：4006686568";

        $emailModel = new EmailModel();
        $ossLogic   = new OssLogic('oss_2');
        $readInfo   = $ossLogic->getObject($path);
        $fileName   = substr($path,strrpos($path,'/')+1);
        $file       = base_path().'/contract_file/PDF-'.$fileName;

        @file_put_contents($file,$readInfo);

        $return     = $emailModel->sendEmail([$email=>''], $title, $content, [$file]);

        @unlink($file);

        return $return;
    }


    /*********脚本路由*******/
    /**
     * @param $data
     * @param bool $isLogin
     * @return mixed
     * @desc 下载合同的路由
     */
    public function doDownLoadWay( $data, $isLogin=true )
    {
        $ebqConfig  =   SystemConfigLogic::getConfig ('CONTRACT_EBQ_CONFIG') ;

        $contractKey=   isset( $ebqConfig['USE_WAY'] ) && !empty( $ebqConfig['USE_WAY'] ) ? $ebqConfig['USE_WAY'] : 'EBQ' ;

        $methodArr  =   self::$contractMethod;

        $method     =   isset($methodArr[$contractKey]) ? $methodArr[$contractKey] : 'doDownLoad';

        return $this->$method($data, $isLogin);
    }

    /**
     * @return mixed|string
     * @desc 发送邮件的路由
     */
    public function doSendEmailContractMethod($investId, $email, $user=[], $isLogin=true)
    {
        $ebqConfig  =   SystemConfigLogic::getConfig ('CONTRACT_EBQ_CONFIG') ;

        $contractKey=   isset( $ebqConfig['USE_WAY'] ) && !empty( $ebqConfig['USE_WAY'] ) ? $ebqConfig['USE_WAY'] : 'EBQ' ;

        $methodArr  =   self::$contractSendEmailMethod;

        $method     =    isset($methodArr[$contractKey]) ? $methodArr[$contractKey] : 'doContractSendEmail';

        return $this->$method($investId, $email, $user, $isLogin);
    }

    /**
     * @params $investId int |投资id
     * @params $userId  int| 用户id
     * @return result Obj
     * @desc 自动生成保全合同的静态路由
     */
    public function doBuildContract( $data=[] )
    {

        $ebqConfig  =   SystemConfigLogic::getConfig ('CONTRACT_EBQ_CONFIG') ;

        $contractKey=   isset( $ebqConfig['USE_WAY'] ) && !empty( $ebqConfig['USE_WAY'] ) ? $ebqConfig['USE_WAY'] : 'EBQ' ;

        $methodArr  =   self::$buildContractMethod ;

        $method     =   isset($methodArr[$contractKey]) ? $methodArr[$contractKey] : 'doCreateDownLoad';

        return $this->$method( $data , false) ;
    }

    /**
     * @param array $data
     * @return array
     * @desc 入队列
     */
    public function doCreatedContractFile($data = [] )
    {
        $investId       =   (int) $data['invest_id'] ;

        if( $investId  == 0 || empty( $investId ) ) {

            return self::callError('参数错误') ;
        }

        $investModel   =   new InvestModel();

        $investInfo    =   $investModel->getInvestByInvestId($investId) ;

        if( empty($investInfo) ) {

            return self::callError('投资记录不存在') ;
        }
        $contractDb     =   new ContractDb();

        if( !empty($contractDb->getByInvestId($investId)) ) {

            return self::callError('合同生成中!') ;
        }
        //写入队列
        \Event::fire(new \App\Events\User\BuildContractFileEvent( ['contract' => $investInfo] ));

        return self::callSuccess();
    }

    /**
     * @param $contractKey
     * @param $data
     * @return mixed
     * @desc 兼容老的的合同接口
     */
    protected function checkDownContractByWay($checkData = [] )
    {
        $contractKey=   isset($checkData['way']) && !empty($checkData['way']) ? $checkData['way'] : 'EBQ' ;

        $methodArr  =   self::$checkDownContractMethod ;

        $checkMethod=   isset($methodArr[$contractKey]) ? $methodArr[$contractKey] : 'getDownEbqFile' ;

        return $this->$checkMethod( $checkData ) ;
    }
    /**
     * @param $data | array
     * @return call_back_fun method
     * @desc 获取保全合同生成的状态
     */
    public function getDownUrl( $data =[] )
    {
        if( empty( $data ) ) {

            return self::callError('参数错误');
        }

        $ebqConfig  =   SystemConfigLogic::getConfig ('CONTRACT_EBQ_CONFIG') ;

        $contractKey=   isset( $ebqConfig['USE_WAY'] ) && !empty( $ebqConfig['USE_WAY'] ) ? $ebqConfig['USE_WAY'] : 'EBQ' ;

        $methodArr  =   self::$getDownContractMethod ;

        $method     =   isset($methodArr[$contractKey]) ? $methodArr[$contractKey] : 'getEbqDownUrl' ;

        return $this->$method( $data ) ;
     }

    /**
     * @param $investId
     * @return array
     * @检测合同生成记录
     */
     public function doCheckContractStatus($investId)
     {
         $contractDb     =   new ContractDb();

         $contractStatus =  $contractDb->getByInvestId($investId);

         if( empty($contractStatus) ) {

             return self::callError('合同生成记录不存在') ;
         }

         if( $contractStatus['status'] != ContractDb::CONTRACT_STATUS_GOT ){

             return self::callError('合同生成中') ;
         }

         return self::callSuccess ();
     }
}
