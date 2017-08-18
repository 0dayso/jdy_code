<?php
/**
 * User: zhangshuang
 * Date: 16/4/13
 * Time: 15:42
 * Desc: 用户提现卡相关控制器
 */


namespace App\Http\Controllers\BankCard;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Logics\BankCard\WithdrawLogic;

class WithdrawController extends Controller{


    private $logic = null;

    private $userId     = 0;    //用户ID
    private $bankId     = 0;    //银行ID
    private $cardNo     = '';   //银行卡号

    public function __construct(Request $request){

        $this->userId = $request->input('user_id',0);

        parent::__construct($request);

        $this->logic = new WithdrawLogic();
    }


    /**
     * @SWG\Post(
     *   path="/withdraw/card/get",
     *   tags={"BankCard"},
     *   summary="获取提现银行卡",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取提现银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取提现银行卡失败。",
     *   )
     * )
     */
    public function getWithdrawCardByUserId(){

        //获取用户提现银行卡
        $cardList   = $this->logic->getWithdrawCardByUserId($this->userId);

        return self::returnJson($cardList);
    }

    /**
     * @SWG\Post(
     *   path="/withdraw/card/create",
     *   tags={"BankCard"},
     *   summary="添加提现银行卡",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Parameter(
     *      name="bank_id",
     *      in="formData",
     *      description="银行ID",
     *      required=true,
     *      type="integer",
     *      default="6"
     *   ),
     *   @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="添加提现银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="添加提现银行卡失败。",
     *   )
     * )
     */
    public function bindCard(Request $request){

        $bankId = $request->input('bank_id',0);
        $cardNo = $request->input('card_no','');
        $result = $this->logic->bindCard($this->userId,$bankId,$cardNo);

        return self::returnJson($result);
    }

    /**
     * @SWG\Post(
     *   path="/withdraw/card/delete",
     *   tags={"BankCard"},
     *   summary="删除提现银行卡",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="user_id",
     *      in="formData",
     *      description="用户ID",
     *      required=true,
     *      type="integer",
     *      default="82692"
     *   ),
     *   @SWG\Parameter(
     *      name="card_no",
     *      in="formData",
     *      description="银行卡号",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="删除提现银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="删除提现银行卡失败。",
     *   )
     * )
     */
    public function deleteCard(Request $request){

        $cardNo = $request->input('card_no','');
        $result = $this->logic->deleteCard($this->userId,$cardNo);

        return self::returnJson($result);
    }


    /**
     * @SWG\Post(
     *   path="/withdraw/card/getById",
     *   tags={"BankCard"},
     *   summary="获取提现银行卡",
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="发送请求的模块名称",
     *      required=true,
     *      type="string",
     *      default="cli_test_user",
     *   ),
     *    @SWG\Parameter(
     *      name="sign",
     *      in="formData",
     *      description="数据校验",
     *      required=true,
     *      type="string",
     *   ),
     *   @SWG\Parameter(
     *      name="bank_card_id",
     *      in="formData",
     *      description="提现银行卡主键ID",
     *      required=true,
     *      type="integer",
     *      default="1"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="获取提现银行卡成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="获取提现银行卡失败。",
     *   )
     * )
     */
    public function getWithdrawCardById(Request $request){

        $bankCardId = $request->input('bank_card_id',0);
        $result = $this->logic->getWithdrawCardById($bankCardId);

        return self::returnJson($result);
    }


}