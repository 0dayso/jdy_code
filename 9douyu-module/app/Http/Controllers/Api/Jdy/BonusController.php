<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 红包、定期加息券、零钱计划加息券 后台
 */
namespace App\Http\Controllers\Api\Jdy;

use App\Http\Controllers\Controller;

use App\Http\Controllers\LaravelController;

use App\Http\Logics\Bonus\BonusLogic;

use App\Http\Requests\Admin\Bonus\BonusRequest;

use  App\Tools\ToolMoney;

use Illuminate\Http\Request;

/**
 * 后台红包创建 todo 九斗鱼 数据对接 对接后 直接移除该文件
 * Class BonusController
 * @package App\Http\Controllers\Api\Jdy
 */
class BonusController extends Controller{


    /**
     * @SWG\Post(
     *   path="/api/jdy/admin/bonus/doCreate",
     *   tags={"JDY-Api"},
     *   summary="红包/加息券 -> 创建 [Api\Jdy\BonusController@PostCreate]",
     *   @SWG\Parameter(
     *      name="module_name",
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
     *      type="string",
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
     *      name="name",
     *      in="formData",
     *      description="名称",
     *      required=true,
     *      type="string",
     *      default="红包/加息券 名称",
     *   ),
     *  @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="类型[100：定期加息券，200：零钱计划加息券，300：红包]",
     *      required=true,
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100", "200", "300"}
     *   ),
     *   @SWG\Parameter(
     *      name="use_type",
     *      in="formData",
     *      description="使用类型【100 投资使用】",
     *      required=true,
     *      type="string",
     *      default="100",
     *      enum={"100"}
     *   ),
     *  @SWG\Parameter(
     *      name="project_type[]",
     *      in="formData",
     *      description=" 可用项目类型【产品线】",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="101",
     *      enum={"101", "103", "106", "112", "200", "302"}
     *   ),
     *   @SWG\Parameter(
     *      name="client_type[]",
     *      in="formData",
     *      description="投资端类型【9全部 1app 、2wap、3web】",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      default="9",
     *      enum={"9", "1", "2", "3"}
     *   ),
     *     @SWG\Parameter(
     *      name="using_desc",
     *      in="formData",
     *      description="使用范围",
     *      required=true,
     *      type="string",
     *      default="使用范围文字描述",
     *   ),
     *
     *    @SWG\Parameter(
     *      name="rate",
     *      in="formData",
     *      description="利率【%】",
     *      required=true,
     *      type="string",
     *      default="2",
     *   ),
     *    @SWG\Parameter(
     *      name="min_money",
     *      in="formData",
     *      description="最低金额",
     *      required=true,
     *      type="string",
     *      default="100",
     *   ),
     *    @SWG\Parameter(
     *      name="send_start_date",
     *      in="formData",
     *      description="发送开始时间",
     *      required=true,
     *      type="string",
     *      default="2016-06-06",
     *   ),
     *    @SWG\Parameter(
     *      name="send_end_date",
     *      in="formData",
     *      description="发送结束时间",
     *      required=true,
     *      type="string",
     *      default="2016-10-10",
     *   ),
     *    @SWG\Parameter(
     *      name="expires",
     *      in="formData",
     *      description="期限【天】",
     *      required=true,
     *      type="string",
     *      default="30",
     *   ),

     *    @SWG\Parameter(
     *      name="note",
     *      in="formData",
     *      description="备注",
     *      required=true,
     *      type="string",
     *      default="备注啦",
     *   ),

     *  @SWG\Parameter(
     *      name="give_type",
     *      in="formData",
     *      description="是否允许转让【200 允许转让】",
     *      required=true,
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="200",
     *      enum={"200","300"}
     *   ),
     *    @SWG\Parameter(
     *      name="current_day",
     *      in="formData",
     *      description="零钱计划计息天数【天】",
     *      required=true,
     *      type="string",
     *      default="5",
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="红吧/加息券 -> 创建成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description=" -> 红吧/加息券 -> 创建失败。",
     *   )
     * )
     */
    public function PostCreate(Request $request){
        $data        = $request->all();
        $data        = $this->formInput($data);

        $logicResult = BonusLogic::doCreate($data);

        return self::returnJson($logicResult);
    }

    /**
     * 格式化金额
     *
     * @param $data
     * @return mixed
     */
    protected function formInput($data){
        $data['money']    = isset($data['money']) ? ToolMoney::formatDbCashAdd($data['money']) : 0;
        $data['min_money']= isset($data['min_money']) ? ToolMoney::formatDbCashAdd($data['min_money']) : 0;
        return $data;
    }




    /**
     * @SWG\Post(
     *   path="/api/jdy/admin/bonus/doUpdate",
     *   tags={"JDY-Api"},
     *   summary="红包/加息券 -> 编辑 [Api\Jdy\BonusController@PostUpdate]",
     *   @SWG\Parameter(
     *      name="module_name",
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
     *      type="string",
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
     *      name="id",
     *      in="formData",
     *      description="ID",
     *      required=true,
     *      type="string",
     *      default="10",
     *   ),
     *   @SWG\Parameter(
     *      name="name",
     *      in="formData",
     *      description="名称",
     *      required=true,
     *      type="string",
     *      default="红包/加息券 名称",
     *   ),
     *  @SWG\Parameter(
     *      name="type",
     *      in="formData",
     *      description="类型[100：定期加息券，200：零钱计划加息券，300：红包]",
     *      required=true,
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100", "200", "300"}
     *   ),
     *   @SWG\Parameter(
     *      name="use_type",
     *      in="formData",
     *      description="使用类型【100 投资使用】",
     *      required=true,
     *      type="string",
     *      default="100",
     *      enum={"100"}
     *   ),
     *  @SWG\Parameter(
     *      name="project_type[]",
     *      in="formData",
     *      description=" 可用项目类型【产品线】",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="101",
     *      enum={"101", "103", "106", "112", "200", "302"}
     *   ),
     *   @SWG\Parameter(
     *      name="client_type[]",
     *      in="formData",
     *      description="投资端类型【9全部 1app 、2wap、3web】",
     *      required=true,
     *      type="array",
     *      @SWG\Items(type="string"),
     *      default="9",
     *      enum={"9", "1", "2", "3"}
     *   ),
     *     @SWG\Parameter(
     *      name="using_desc",
     *      in="formData",
     *      description="使用范围",
     *      required=true,
     *      type="string",
     *      default="使用范围文字描述",
     *   ),
     *
     *    @SWG\Parameter(
     *      name="rate",
     *      in="formData",
     *      description="利率【%】",
     *      required=true,
     *      type="string",
     *      default="2",
     *   ),
     *    @SWG\Parameter(
     *      name="min_money",
     *      in="formData",
     *      description="最低金额",
     *      required=true,
     *      type="string",
     *      default="100",
     *   ),
     *    @SWG\Parameter(
     *      name="send_start_date",
     *      in="formData",
     *      description="发送开始时间",
     *      required=true,
     *      type="string",
     *      default="2016-06-06",
     *   ),
     *    @SWG\Parameter(
     *      name="send_end_date",
     *      in="formData",
     *      description="发送结束时间",
     *      required=true,
     *      type="string",
     *      default="2016-10-10",
     *   ),
     *    @SWG\Parameter(
     *      name="expires",
     *      in="formData",
     *      description="期限【天】",
     *      required=true,
     *      type="string",
     *      default="30",
     *   ),

     *    @SWG\Parameter(
     *      name="note",
     *      in="formData",
     *      description="备注",
     *      required=true,
     *      type="string",
     *      default="备注啦",
     *   ),

     *  @SWG\Parameter(
     *      name="give_type",
     *      in="formData",
     *      description="是否允许转让【200 允许转让】",
     *      required=true,
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="200",
     *      enum={"200","300"}
     *   ),
     *    @SWG\Parameter(
     *      name="current_day",
     *      in="formData",
     *      description="零钱计划计息天数【天】",
     *      required=true,
     *      type="string",
     *      default="5",
     *   ),
     *
     *  @SWG\Parameter(
     *      name="status",
     *      in="formData",
     *      description="状态【100 未发布 200 已发布】",
     *      required=true,
     *      type="string",
     *      @SWG\Items(type="string"),
     *      collectionFormat="multi",
     *      default="100",
     *      enum={"100","200"}
     *   ),
     *
     *   @SWG\Response(
     *     response=200,
     *     description="红吧/加息券 -> 编辑成功。",
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description=" -> 红吧/加息券 -> 编辑失败。",
     *   )
     * )
     */
    public function PostUpdate(Request $request){
        $data        = $request->all();
        $data        = $this->formInput($data);
        $logicResult = BonusLogic::doUpdate($data);
        return self::returnJson($logicResult);
    }



}