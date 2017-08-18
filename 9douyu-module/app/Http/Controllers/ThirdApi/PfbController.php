<?php

namespace App\Http\Controllers\ThirdApi;

use App\Http\Controllers\Controller;

use App\Http\Logics\RequestSourceLogic;
use App\Http\Logics\ThirdApi\PfbLogic;
use Cache, Log;
/**
 * 普付宝接口【逻辑源自老的九斗鱼】增加 ip 白名单
 *
 * Class PfbController
 * @package App\Http\Controllers\ThirdApi
 */
class PfbController extends Controller{

	private $client		    = null;

	public function beforeConstruct(){
		$this->client = RequestSourceLogic::SOURCE_PUFUBAO;
		RequestSourceLogic::setSource($this->client);
	}

	/**
	 * 普付宝 请求入口
	 *
	 * @return mixed|string
	 */
	function index($fixed=null) {

		$phpInput        = file_get_contents('php://input');

		$phpInputArray   = json_decode($phpInput, true);

		$phpInputArray   = empty($phpInputArray) ? $_REQUEST : $phpInputArray;

		Log::info('普付宝接口请求：',$phpInputArray);

		try {

			return call_user_func_array(
				[
					$this,
					$phpInputArray['request']
				],
				$phpInputArray
			);

		}catch (\Exception $e){
			Log::error('pfb: ' . $e->getCode() . $e->getMessage());
			return '404';
		}
	}

	/**
	 * 授权登陆
	 */
	public function authLogin()
	{
		$request 		 = app('request');

		$phone 			 = $request->input("phone");
		$userName 		 = $request->input("userName");
		$identityCard 	 = $request->input("identityCard");
		$sign 			 = $request->input("sign");

		Log::info('普付宝授权登录请求：',[$phone, $userName, $identityCard, $sign]);

		// 请求token
		$logicResult = PfbLogic::requestToken([
			'client'		=> $this->client,   // 普付宝
			'phone' 		=> $phone, 			// 手机号
			'name'  		=> $userName,		// 身份证姓名
			'identityCard'  => $identityCard,    // 身份证号
			'sign'			=> $sign
		]);

		$this->jsonReturn($logicResult);
	}


	/**
	 * 刷新token
	 */
	public function refreshToken(){

		$request 		 = app('request');

		$phone 			 = $request->input("phone");
		$sign 			 = $request->input("sign");

		Log::info('普付宝刷新token请求：',[$phone, $sign]);

		// 请求token
		$logicResult = PfbLogic::refreshToken([
			'client'		=> $this->client,   // 普付宝
			'phone' 		=> $phone, 			// 手机号
			'sign'			=> $sign
		]);

		$this->jsonReturn($logicResult);
	}

	/**
	 * 发送短信验证码
	 */
	public function sendSms(){

		$request 			= app('request');

		$data['phone']   	= $request->input('phone');
		$data['type']    	= $request->input('smsType');
		$data['token']   	= $request->input('token');
		$data['sign']       = $request->input('sign');
		$data['userId']		= $this->getUserId() ? $this->getUserId() : 0;

		Log::info('普付宝发送短信验证码请求：',$data);

		$logic 				= new PfbLogic();
		$result			    = $logic->doSms($data);

		$this->jsonReturn($result);

	}

	/**
	 * 检测验证码
	 */
	public function checkSms(){

		$request 			= app('request');

		$data['phone']   	= $request->input('phone');
		$data['type']    	= $request->input('smsType');
		$data['code']		= $request->input('code');
		$data['token']   	= $request->input('token');
		$data['sign']       = $request->input('sign');
		$data['userId']		= $this->getUserId() ? $this->getUserId() : 0;

		Log::info('普付宝检测短信验证码请求：',$data);

		$logic 				= new PfbLogic();
		$result 			= $logic->chkSms($data);

		$this->jsonReturn($result);
	}

	/**
	 * 订单处理
	 */
	public function freezeOrder(){

		$request 		 = app('request');

		$data['orderId'] = $request->input('orderId');
		$data['type']    = $request->input('freezeType');   //freeze（冻结），unfreeze(解冻)
		$data['token']   = $request->input('token');
		$data['sign']    = $request->input('sign');
		$data['userId']  = $this->getUserId() ? $this->getUserId() : 0;

		Log::info('普付宝处理订单请求：',$data);

		$logic  		 = new PfbLogic();
		$result 		 = $logic->dealOrder($data);

		$this->jsonReturn($result);

	}

	/**
	 * 获取用户可质押订单
	 */
	public function getOrder(){

		$request    = app('request');

		$phone      = $request->input('phone');
		$sign       = $request->input('sign');
		$token      = $request->input('token');
		$userId 	= $this->getUserId() ? $this->getUserId() : 0;

		$logic 		= new PfbLogic();
		$result 	= $logic->getOrder($phone,$sign,$token,$userId);

		$this->jsonReturn($result);

	}

	/**
	 * 首页验证token
	 */

	public function getProject(){

		$request 	= app('request');

		$token 		= $request->input('token');
		$type  		= $request->input('projectType');  //项目类型:up,down,all
		$userId 	= $this->getUserId() ? $this->getUserId() : 0;

		$logic 		= new PfbLogic();
		$result 	= $logic->getProject($type,$token,$userId);

		$this->jsonReturn($result);

	}

	/**
	 * 获取首页项目利率
	 */
	public function getProjectRate(){

		$logic  = new PfbLogic();

		$result = $logic->getProjectRate();

		$this->jsonReturn($result);

	}

	/**
	 * 获取用户银行卡信息
	 */
	public function getBank(){

		$request 	= app('request');

		$phone 		= $request->input('phone');
		$token 		= $request->input('token');
		$sign  		= $request->input('sign');
		$userId 	= $this->getUserId() ? $this->getUserId() : 0;

		$logic 		= new PfbLogic();
		$result 	= $logic->getBank($phone,$token,$sign,$userId);

		$this->jsonReturn($result);
	}

	/**
	 * 获取用户已质押订单
	 */
	public function getFreezeOrder(){

		$request    = app('request');

		$phone      = $request->input('phone');
		$sign       = $request->input('sign');
		$token      = $request->input('token');
		$userId 	= $this->getUserId() ? $this->getUserId() : 0;

		$logic 		= new PfbLogic();
		$result 	= $logic->getFreezeOrder($phone,$token,$sign,$userId);

		$this->jsonReturn($result);

	}

	//获取用户质押订单情况余额
	public function getBalance(){

		$request    = app('request');

		$phone      = $request->input('phone');
		$sign       = $request->input('sign');
		$token      = $request->input('token');
		$userId 	= $this->getUserId() ? $this->getUserId() : 0;

		$logic 		= new PfbLogic();
		$result 	= $logic->getBalance($phone,$token,$sign,$userId);

		$this->jsonReturn($result);

	}

	/**
	 * 普付宝 json数据返回【兼容 老版本】
	 * @param array $data
	 */
	private function jsonReturn($data = []){
		$dataDefault           = [];
		$dataDefault["status"] = PfbLogic::SUCCESS_STATUS;
		$dataDefault["msg"]    = "请求成功";
		$dataDefault["item"]   = [];

		$data = array_merge($dataDefault, $data);

		if(empty($data['item'])){
			$data['item'] = array("__EMPTY" => "__EMPTY");
		}

		return self::returnJson($data);
	}
}

