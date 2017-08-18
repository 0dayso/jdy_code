<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/27
 * Time: 下午2:58
 * Desc: 充值银行卡
 */
namespace App\Http\Controllers\AppApi\V4_0\BankCard;

use App\Http\Controllers\AppApi\AppController;
use App\Http\Logics\AppLogic;
use App\Http\Logics\Pay\WithdrawLogic;
use Illuminate\Http\Request;

class WithdrawController extends AppController
{

	/**
     * @SWG\Post(
     *   path="/bank_withdrawCards",
     *   tags={"APP-BankCard"},
     *   summary="用户提现银行卡 [BankCard\WithdrawController@withdrawCards]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户提现银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户提现银行卡失败。",
     *   )
     * )
     */
	public function withdrawCards() {

		$userId = $this->getUserId();

		$withdrawLogic = new WithdrawLogic();

		$result = $withdrawLogic->getWithdrawCardForApp($userId);

        if(!$result['status'])
            $result['code'] = AppLogic::CODE_ERROR;

		return $this->returnJsonData($result);
	}

     /**
     * @SWG\Post(
     *   path="/bank_withdrawBanks",
     *   tags={"APP-BankCard"},
     *   summary="可提现银行卡列表 [BankCard\WithdrawController@withdrawBanks]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0.0",
     *   ),
     *   @SWG\Parameter(
     *      name="token",
     *      in="formData",
     *      description="token",
     *      required=true,
     *      type="string",
     *      default="653030e9f8e4f6559669386dfe4f56d4",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取可提现银行卡列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取可提现银行卡列表失败。",
     *   )
     * )
     */
	public function withdrawBanks() {

		$rechargeLogic = new WithdrawLogic();

		$result = $rechargeLogic->getWithdrawBanksForApp();

		if(!$result['status'])
            $result['code'] = AppLogic::CODE_ERROR;

		return $this->returnJsonData($result);
	}


	/**
     * @SWG\Post(
     *   path="/bank_addwithDrawCard",
     *   tags={"APP-BankCard"},
     *   summary="绑定提现银行卡 [实名+绑卡+交易密码接口代替次接口] [BankCard\WithdrawController@bindWithdrawCard]",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *   @SWG\Parameter(
     *      name="client",
     *      in="formData",
     *      description="客户端",
     *      required=true,
     *      type="string",
     *      default="ios",
     *   ),
     *   @SWG\Parameter(
     *      name="version",
     *      in="formData",
     *      description="客户端版本号",
     *      required=true,
     *      type="string",
     *      default="4.0.0",
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
     *      name="bankType",
     *      in="formData",
     *      description="银行ID【仅用非生产环境 ip限制 值参考：module_bank】",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Parameter(
     *      name="card_number",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="integer",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="绑定提现银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="绑定提现银行卡失败。",
     *   )
     * )
     */
	private function bindWithdrawCard(Request $request){

		$userId = $this->getUserId();

		$bankId	= $request->input("bankType", null); //银行id

        $cardNo = $request->input("card_number"); //银行卡号

		$rechargeLogic = new WithdrawLogic();

		$result = $rechargeLogic->bindWithdrawCard($userId,$bankId,$cardNo);

        if(!$result['status'])
            $result['code'] = AppLogic::CODE_ERROR;

		return $this->returnJsonData($result);
	}
}