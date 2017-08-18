<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/8/22
 * Time: 下午5:41
 */

namespace App\Http\Controllers\App\User;


use App\Http\Controllers\App\UserController;
use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\Contract\ContractLogic;
use App\Http\Logics\Logic;
use App\Http\Logics\User\PasswordLogic;
use Illuminate\Http\Request;

class ContractController extends UserController
{

    /**
     * @SWG\Post(
     *   path="/invest_agreement",
     *   tags={"APP-Agreement"},
     *   summary="投资协议",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="类型(10:信贷;20:保理;30:九省心;40:债权转让;50:房贷;60:项目集;current:零钱计划;argument:投资协议;pre:闪电付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="1",
     *      enum={"10","20","30","40","50","60","current","argument","pre"}
     *   ),
     *     @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="成功,返回html字符串",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="失败",
     *   )
     * )
     */
    public function agreement( Request $request ){

        $type = $request->input('type');

        $projectId  = $request->input('project_id');

        $content    = ContractLogic::getContent($type, $projectId);
        if(!empty($content['data'])) {
            $htmlRender = AgreementLogic::getAgreementHtmlByType($type, $content);

            $content['data']['info'] = $htmlRender;
            $content['status'] = true;
        }else{
            $content = Logic::callError('获取合同数据失败');
        }
        return self::appReturnJson($content);
    }

    /**
     * @SWG\Post(
     *   path="/invest_contract",
     *   tags={"APP-Agreement"},
     *   summary="投资合同",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *  @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端来源",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="ios",
     *      enum={"ios","android"}
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="版本号",
     *      required=true,
     *      type="string",
     *      default="2.2.3",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="类型(10:信贷;20:保理;30:九省心;40:债权转让;50:房贷;60:项目集;current:零钱计划;argument:投资协议;pre:闪电付息)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="1",
     *      enum={"10","20","30","40","50","60","current","argument","pre"}
     *   ),
     *     @SWG\Parameter(
     *      name="project_id",
     *      in="formData",
     *      description="项目id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *    @SWG\Parameter(
     *      name="investId",
     *      in="formData",
     *      description="投资id",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *   @SWG\Parameter(
     *      name="contract",
     *      in="formData",
     *      description="查看合同,需要交易密码",
     *      required=true,
     *      type="integer",
     *      default="1",
     *   ),
     *     @SWG\Parameter(
     *      name="trading_password",
     *      in="formData",
     *      description="交易密码",
     *      required=true,
     *      type="integer",
     *      default="",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="成功,返回html字符串",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="失败",
     *   )
     * )
     */
    public function contract( Request $request ){

        $projectId = $request->input('project_id');

        $investId = $request->input('investId');

        $type = $request->input('type');

        $contract = $request->input('contract');

        $version = $request->input('version');

        //如果查看合同,则需要交易密码;
        //这就是个狗屎产品逻辑设计,为毛投资的时候已经输入了交易密码,看看合同也需要交易密码。
        //简直是扯淡,其实我想直接去掉验证,无奈。。。
        if( $contract && !($this->compareVersion($version, '3.2.0')) ){

            $tradingPassword = $request->input('trading_password');

            $passwordLogic = new PasswordLogic();

            $checkResult = $passwordLogic->checkTradingPassword($tradingPassword, $this->getUserId());

            if( !$checkResult['status'] ){

                self::appReturnJson($checkResult);

            }

        }

        $content = ContractLogic::getContent($type, $projectId, $investId);

        if(!empty($content['data'])) {
           if(!empty($content['data']['type'])){//第三方债权模版
                $htmlRender = AgreementLogic::getAgreementHtmlByType($content['data']['type'], $content);
            }else{
                $htmlRender = AgreementLogic::getAgreementHtmlByType($type, $content);
            }
            $content['data']['info'] = $htmlRender;
            //$content['data']['show_contract_url'] = env('APP_URL_WX').'contract_show?invest_id='.$investId;
            $content['status'] = true;
        }else{
            $content = Logic::callError('获取合同数据失败');
        }
        return self::appReturnJson($content);

    }

    /**
     * @param Request $request
     * 合同下载
     */
    public function contractDown(Request $request)
    {

        $data = $request->all();

        $this->checkUserIdIsLogin();

        $logic = new ContractLogic();

        $result = $logic->doDownLoad( $data );

        if(empty($result)){

            $result = Logic::callError('获取合同下载地址失败');

        }

        return self::appReturnJson($result);

    }

    /**
     * @param Request $request
     * @return array
     * @desc 合同预览接口
     */
    public function contractShowPdf(Request $request){

        $investId = $request->input('investId');

        $showPdfUrl = env('APP_URL_WX').'/contract_show?invest_id='.$investId;

        $result = Logic::callSuccess(['show_contract_url' => $showPdfUrl ]);

        return self::appReturnJson($result);

    }

    /**
     * @param Request $request
     * @return array
     * @desc 合同发送邮件
     */
    public function contractSendEmail(Request $request)
    {

        $investId = $request->input('investId');

        $email  = $request->input('email');

        $logic = new ContractLogic();

        $request = $logic->doContractSendEmail($investId, $email, $this->getUser());

        return self::appReturnJson($request);

    }


}
