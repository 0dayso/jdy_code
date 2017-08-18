<?php
/**
 * User: caelyn
 * Date: 16/6/27
 * Time: 下午3:10
 * Desc: 用户定期资产相关
 */
namespace App\Http\Controllers\App\User;

use App\Http\Controllers\App\UserController;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Models\Common\CoreApi\CreditAssignProjectModel;
use App\Lang\AppLang;
use Illuminate\Http\Request;

class TermController extends UserController
{
	/**
     * @SWG\Post(
     *   path="/user_invest_record",
     *   tags={"APP-User"},
     *   summary="用户定期投资记录列表 [User\TermController@investRecord]",
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
     *   @SWG\Parameter(
     *      name="p",
     *      in="formData",
     *      description="页号",
     *      required=true,
     *      type="integer",
     *      default="1"
     *   ),
     *   @SWG\Parameter(
     *      name="size",
     *      in="formData",
     *      description="条目数",
     *      required=true,
     *      type="integer",
     *      default="10"
     *   ),
     *   @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="类型",
     *      required=true,
     *      type="string",
     *      default="invested"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户定期投资记录列表成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户定期投资记录列表失败。",
     *   )
     * )
     */
	public function investRecord(Request $request) {

		$userId = $this->getUserId();

		$p 		= $request->input('p');
		$size 	= $request->input('size',10);
		$type 	= $request->input('type');

		$termLogic = new TermLogic();

		$creditAssignIds = CreditAssignProjectModel::getUserCreditAssignInvestIds($userId);

		if ($type=='invested') {
			//获取已完结项目列表
			$refunded   = $termLogic->getRefunded($userId,$p,$size);

			$record = isset($refunded['record']) ? $refunded['record'] : [];

			$recordList = $termLogic->formatTermDataForApp($this->client, $record, $creditAssignIds);
			//获取已完结项目总数
			$total	  = isset($refunded['total']) ? $refunded['total'] : 0;

			$done_num	  = 0;

		} else {
			//获取已完结项目总数
               $refunded   = $termLogic->getRefunded($userId,$p,$size);

               $done_num	  = isset($refunded['total']) ? $refunded['total'] : 0;
               //获取未完结项目列表
               $noFinish   = $termLogic->getNoFinish($userId,$p,$size);

				$record = isset($noFinish['record']) ? $noFinish['record'] : [];

				$recordList = $termLogic->formatTermDataForApp($this->client, $record, $creditAssignIds);
               //获取未完结项目总数
               $total      =  isset($noFinish['total']) ? $noFinish['total'] : 0;
		}

		$data = [
			'total'			=> $total,
			'invest_msg'	=> AppLang::APP_USER_TERM_MESSAGE,
			'done_num'		=> $done_num,
			'list'			=> empty($recordList) ? [[]] : $recordList,
		];

		return $this->appReturnJson(self::callSuccess($data));
		
	}

     /**
     * @SWG\Post(
     *   path="/user_invest_detail",
     *   tags={"APP-User"},
     *   summary="用户定期投资记录详情 [User\TermController@recordDetail]",
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
     *   @SWG\Parameter(
     *      name="id",
     *      in="formData",
     *      description="投资ID",
     *      required=true,
     *      type="string",
     *      default=""
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取用户定期投资记录详情成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取用户定期投资记录详情失败。",
     *   )
     * )
     */
     public function recordDetail(Request $request) {

          $investId = $request->input('id');

          $userId = $this->getUserId();

          $termLogic = new TermLogic();          

          $investInfo = $termLogic->getInvestDetailByIdForApp($userId,$investId);

          return $this->appReturnJson($investInfo);
     }
}