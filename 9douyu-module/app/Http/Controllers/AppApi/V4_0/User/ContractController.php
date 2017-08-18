<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/28
 * Time: 下午5:32
 */

namespace App\Http\Controllers\AppApi\V4_0\User;


use App\Http\Controllers\AppApi\AppController;
use App\Http\Dbs\Contract\ContractDb;
use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\Contract\ContractLogic;
use App\Http\Logics\Logic;
use Illuminate\Http\Request;

class ContractController extends AppController
{

    /**
     * @SWG\Post(
     *   path="/contract",
     *   tags={"APP-Project"},
     *   summary="投资合同 [V4_0\User\ContractController@contract]",
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
     *      name="is_credit_assign",
     *      in="formData",
     *      description="类型(1:债权转让;0:正常合同)",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="0",
     *      enum={"0","1"}
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
     *      name="invest_id",
     *      in="formData",
     *      description="投资id",
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
    /**
     * @param Request $request
     * @return array
     */
    public function contract(Request $request){

        $isCreditAssign = $request->input('is_credit_assign', 0);

        $projectId = $request->input('project_id');

        $investId = $request->input('invest_id');

        $type = 0;

        if($isCreditAssign == ContractDb::PROJECT_CREDIT_ASSIGN){

            $type = $isCreditAssign;

        }

        $content = ContractLogic::getContent($type, $projectId, $investId);

        if(!empty($content['data'])) {
            if(!empty($content['data']['type'])){//第三方债权模版
                $htmlRender = AgreementLogic::getAgreementHtmlByType($content['data']['type'], $content);
            }else{
                $type = (!$type ? $content['data']['projectWay'] : $type);
                $htmlRender = AgreementLogic::getAgreementHtmlByType($type, $content);
            }
            unset($content['data']);
            $content['data']['info'] = $htmlRender;
            $content['status'] = true;
        }else{
            $content = Logic::callError('获取合同数据失败');
        }
        return  $this->returnJsonData($content);

    }

    /**
     * @SWG\Post(
     *   path="/invest_contract_send",
     *   tags={"APP-Project"},
     *   summary="投资合同发送至邮件 [V4_0\User\ContractController@contractSendEmail]",
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
     *      name="email",
     *      in="formData",
     *      description="邮箱",
     *      required=true,
     *      type="string",
     *      default="liu.qiuhui@9douyu.com",
     *   ),
     *    @SWG\Parameter(
     *      name="invest_id",
     *      in="formData",
     *      description="投资id",
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
    /**
     * @param Request $request
     * @return array
     * @desc 合同发送邮件
     */
    public function contractSendEmail(Request $request)
    {

        $investId = $request->input('invest_id');

        $email  = $request->input('email');

        $logic = new ContractLogic();

        //$result = $logic->doContractSendEmail($investId, $email, $this->getUser());
        $result = $logic->doSendEmailContractMethod($investId, $email, $this->getUser());

        return self::returnJsonData($result);

    }

}