<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/27
 * Time: 下午2:58
 * Desc: 充值银行卡
 */
namespace App\Http\Controllers\App\BankCard;

use App\Http\Controllers\App\AppController;
use App\Http\Logics\Pay\RechargeLogic;

class RechargeController extends AppController
{

	/**
     * @SWG\Post(
     *   path="/bank_rechargeCards",
     *   tags={"APP-BankCard"},
     *   summary="用户充值银行卡 [BankCard\RechargeController@rechargeCards]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户充值银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户充值银行卡失败。",
     *   )
     * )
     */
	public function rechargeCards()
	{

		$userId = $this->getUserId();

		$rechargeLogic = new RechargeLogic();

		$version = $this->version;
		$client = $this->client;
		$result = $rechargeLogic->getRechargeCardsForApp($userId,$version,$client);

		return $this->appReturnJson($result);		
	}

	/**
     * @SWG\Post(
     *   path="/bank_rechargeBanks",
     *   tags={"APP-BankCard"},
     *   summary="可充值银行卡列表 [BankCard\RechargeController@rechargeBanks]",
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
     *   @SWG\Response(
     *     response=200,
     *     description="获取可充值银行卡列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取可充值银行卡列表失败。",
     *   )
     * )
     */
	public function rechargeBanks(){

		$rechargeLogic = new RechargeLogic();

		$client = $this->client;
		$version = $this->version;
		$result = $rechargeLogic->getRechargeBanksForApp($version,$client);

		return $this->appReturnJson($result);
	}


}