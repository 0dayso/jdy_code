<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 红包、定期加息券、零钱计划加息券 后台
 */
namespace App\Http\Controllers\Admin\Bonus;

use App\Http\Controllers\Admin\AdminController;

use App\Http\Logics\Bonus\BonusLogic;

use App\Http\Requests\Admin\Bonus\BonusRequest;

use  App\Tools\ToolMoney;
/**
 * Class BonusController
 * @package App\Http\Controllers\Admin\Bonus
 */
class BonusController extends AdminController implements BonusInterfaceController{

    /**
     * 创建红包或加息券视图
     * @return mixed
     */
    public function getCreate(){
        $viewData = [

            'status'     => BonusLogic::getStatusData(),
            'useType'    => BonusLogic::getUseType(),
            'type'       => BonusLogic::getType(),
            'client'     => BonusLogic::getClientData(),
            'assignment' => BonusLogic::getAssignment(),
            'productLine'=> BonusLogic::getProductLine(),
            'effectType' => BonusLogic::getEffectType(),
        ];

        return view('admin.bonus.create', $viewData);
    }



    /**
     * @SWG\Post(
     *   path="/admin/bonus/doCreate",
     *   tags={"admin"},
     *   summary="红包/加息券 -> 创建 [admin\BonusController@PostCreate]",
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
    public function PostCreate(BonusRequest $request){
        $data        = $request->all();
        $data        = $this->formInput($data);

        $logicResult = BonusLogic::doCreate($data);
        if($logicResult['status']){
            return redirect('/admin/bonus/lists')->with('message', '创建成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', '数据库操作返回异常！');
        }
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
     * 列表
     * @return mixed
     */
    public function getLists(){
        $condition                  = []; //todo 查询条件

        $data = [
            'list'       => BonusLogic::getList($condition),
            'status'     => BonusLogic::getStatusData(),
            'useType'    => BonusLogic::getUseType(),
            'type'       => BonusLogic::getType(),
            'client'     => BonusLogic::getClientData(),
            'assignment' => BonusLogic::getAssignment(),
            'productLine'=> BonusLogic::getProductLine(),
        ];

        return view('admin.bonus.lists', $data);
    }

    /**
     * 编辑
     * @param int $id
     * @return mixed
     */
    public function getUpdate($id = 0, \Illuminate\Http\Request $request){
        $logicResult = BonusLogic::findById($id);

        if(!$logicResult['status'] && empty($logicResult['data']['obj'])){
            return redirect()->back()->withInput($request->input())->with('fail', '找不到该数据！');
        }

        $viewData = [
            'obj'        => $logicResult['data']['obj'],
            'status'     => BonusLogic::getStatusData(),
            'useType'    => BonusLogic::getUseType(),
            'type'       => BonusLogic::getType(),
            'client'     => BonusLogic::getClientData(),
            'assignment' => BonusLogic::getAssignment(),
            'productLine'=> BonusLogic::getProductLine(),
            'effectType' => BonusLogic::getEffectType(),
        ];

        return view('admin.bonus.update', $viewData);
    }


    /**
     * @SWG\Post(
     *   path="/admin/bonus/doUpdate",
     *   tags={"admin"},
     *   summary="红包/加息券 -> 编辑 [admin\BonusController@PostUpdate]",
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
    public function PostUpdate(BonusRequest $request){
        $data        = $request->all();
        $data        = $this->formInput($data);
        $logicResult = BonusLogic::doUpdate($data);
        if($logicResult['status']){
            return redirect('/admin/bonus/lists')->with('message', '编辑成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('fail', '数据库操作返回异常！');
        }
    }

    /**
     * @param $id
     * @return mixed
     * 发布红包或加息券
     */
    public function publishBonus($id){

        $logicResult = BonusLogic::publishBonus($id);

        if($logicResult['status']){

            return redirect('/admin/bonus/lists')->with('message', '发布成功！');

        }else {
            return redirect()->back()->with('fail', $logicResult['msg']);
        }
    }

}