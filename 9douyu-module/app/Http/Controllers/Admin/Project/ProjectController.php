<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/5/31
 * Time: 上午10:35
 * Desc: 项目控制器
 */

namespace App\Http\Controllers\Admin\Project;

use App\Http\Controllers\Admin\AdminController;

use App\Http\Dbs\Credit\CreditAllDb;
use App\Http\Dbs\Invest\InvestDb;
use App\Http\Dbs\Project\ProjectDb;
use App\Http\Logics\Credit\CreditAllLogic;
use App\Http\Logics\Credit\CreditLogic;
use App\Http\Logics\Credit\CreditUserLoanLogic;
use App\Http\Logics\Invest\TermLogic;
use App\Http\Logics\Project\ProjectLogic;
use App\Http\Models\Common\CoreApi\ProjectModel;
use App\Tools\ToolArray;
use App\Tools\ToolMoney;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Project\ProjectRequest;
use App\Tools\ToolPaginate;
use App\Http\Models\Project\ProjectExtendModel;


/**
 * Class ProjectController
 * @package App\Http\Controllers\Admin\Project
 */
class ProjectController extends AdminController{

    protected $homeName = '项目管理';

    /**
     * @param $productId 101 (九省心1月期)
     * @desc 创建项目匹配债权form表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create( $productId = ProjectDb::PRODUCT_LINE_ONE_MONTH){

        $productLine = ProjectLogic::getProductLine();

        //$creditList  = ProjectLogic::getCredit($productId, false);
        $creditList  = CreditAllLogic::getUseCreateProjectCreditList( $productId );

        $creditList = $this->formatCreditMoney($creditList);

        if($productId == ProjectDb::PRODUCT_LINE_FACTORING || $productId == ProjectDb::PRODUCT_LINE_ONE_MONTH)
        {
            $investNote = '天';
        }else{
            $investNote = '月';
        }

        $viewData = [

            'productId'     => $productId,
            'home'          => $this -> homeName,
            'title'         => '创建项目',
            'productLine'   => $productLine,
            'refundType'    => ProjectLogic::getRefundType(),
            'creditList'    => $creditList,
            'source'        => CreditLogic::getSource(),
            'type'          => CreditLogic::getType(),
            'creditFunName' => ProjectLogic::getCreditFunName(),
            'investNote'    => $investNote,
            'activity_sign' => ProjectExtendModel::setProjectActivitySign(),

        ];

        return view('admin.project.create.project', $viewData);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 项目列表
     */
    public function index( Request $request )
    {
        // 债权参数搜索开始
        //债权 - 参数
        $credit_name = trim($request->input('credit_name'));
        $originalName= trim($request->input('loan_username'));

        //搜索条件
        $condition  =  [];

        $projectIds = false;

        if(!empty($credit_name)){
            $condition[] = ['company_name', 'like','%' . $credit_name . '%'];
        }
        if(!empty($originalName)){
            $condition[] = ['loan_username', 'like','%' . $originalName . '%'];
        }

        if(!empty($condition)) {
            //列表
            $projectIds = [0];
            $creditLists = CreditAllLogic::getCreditLists($condition);
            $creditLists = CreditAllLogic::getProjectLinks($creditLists);
            if(!empty($creditLists)) {
                foreach ($creditLists as $creditDetailObj) {
                    if (!empty($creditDetailObj->projectLinks)) {
                        foreach ($creditDetailObj->projectLinks as $projectLinkRecord) {
                            $projectIds[] = $projectLinkRecord['project_id'];
                        }
                    }
                }
            }
        }
        // 债权参数搜索结束


        $page = $request->input('page', 1);

        $page = (int)$page;

        $size = 20;

        //默认九省心
        $projectLine = $request->input('product_line', '');

        $status = $request->input('status', '');

        $ids = $request->input('id', 0);

        $startTime = $request->input('start_time');

        $endTime = $request->input('end_time');

        $projectLogic = new ProjectLogic();

        $projectList = [];

        if( $ids ){  //按照id搜索

            $projectInfo = ProjectModel::getProjectListByIds([$ids]);

            $projectList['total'] = empty($projectInfo) ? 0 : count($projectInfo);

            $projectList['list'] = $projectInfo;

        }elseif( !empty($startTime) && !empty($endTime) ){  //按照项目满标的起始时间搜索

            $termLogic = new TermLogic();

            $investList = $termLogic->getLastInvestListByStartTimeEndTime($startTime, $endTime);

            $projectIds = ToolArray::arrayToIds($investList, 'project_id');

            $projectList['list'] = ProjectModel::getProjectListByIds($projectIds);

            $size = count($projectList['list']);

            $projectList['total'] = $size;

        }else{  //默认列表

            $projectList = $projectLogic->getListByProjectLine($projectLine, $page, $size, $status, true, $projectIds);

        }

        $projectLineArr = $projectLogic->getProductLineArr();

        $pageParam = '?product_line='.$projectLine;

        $pageParam .= $status ? '&status='.$status : '';

        $toolPaginate = new ToolPaginate($projectList['total'], $page, $size, '/admin/project/lists'.$pageParam);

        $paginate = $toolPaginate->getPaginate();

        if($projectLine == 'JSX'){
            $productLineNote = '九省心';
        }elseif($projectLine == 'JAX'){
            $productLineNote = '九安心';
        }else{
            $productLineNote = '闪电付息';
        }


        $viewData = [
            'home'              => $this->homeName,
            'title'             => '项目列表',
            'projectList'       => $projectList['list'],
            'total'             => $projectList['total'],
            'productLineNote'   => $productLineNote,
            'projectLineArr'    => $projectLineArr,
            'paginate'          => $paginate,
            'projectLine'       => $projectLine,
            'pageParam'         => ['credit_name'=> $credit_name, 'loan_username'=> $originalName],
        ];

        return view('admin.project.lists.index', $viewData);

    }

    /**
     * @desc 执行项目创建
     * @param ProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doCreate(ProjectRequest $request){

        $logic = new ProjectLogic();

        $data = $request->all();

        if($data['invest_days'] > 20){
            return redirect()->back()->withInput($request->input())->with('message', '融资时间不能大于20天');
        }

        $result = $logic->doCreate($data);

        if($result['status']){
            return redirect('/admin/project/lists')->with('message', '项目创建成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }

    }


    /**
     * @param $id
     * @param Request $request
     * @desc 更新项目
     * @return mixed
     */
    public function update($id, \Illuminate\Http\Request  $request){

        $logicResult = ProjectLogic::getById($id);

        if( !$logicResult['status'] && empty($logicResult['data']) ){
            return redirect()->back()->withInput($request->input())->with('fail', '该项目不存在');
        }

        $productId = $logicResult['data']['product_line']+$logicResult['data']['type'];

        $productLine = ProjectLogic::getProductLine();

        $creditList  = CreditAllLogic::getUseCreateProjectCreditList( $productId, true, $logicResult['data']['credit_id'] );

        $creditList = $this->formatCreditMoney($creditList);

        if($productId == ProjectDb::PRODUCT_LINE_FACTORING || $productId == ProjectDb::PRODUCT_LINE_ONE_MONTH)
        {
            $investNote = '天';
        }else{
            $investNote = '月';
        }

        $viewData = [
            'projectInfo'   => $logicResult['data'],
            'productId'     => $productId,
            'home'          => $this -> homeName,
            'title'         => '编辑项目',
            'productLine'   => $productLine,
            'refundType'    => ProjectLogic::getRefundType(),
            'creditList'    => $creditList,
            'source'        => CreditLogic::getSource(),
            'type'          => CreditLogic::getType(),
            'creditFunName' => ProjectLogic::getCreditFunName(),
            'investNote'    => $investNote,

        ];

        return view('admin.project.edit.project', $viewData);

    }

    /**
     * @param ProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doUpdate(ProjectRequest $request){

        $logic = new ProjectLogic();

        $data = $request->all();

        if($data['invest_days'] > 20){
            return redirect()->back()->withInput($request->input())->with('message', '融资时间不能大于20天');
        }

        $projectId = $data['id'];

        $result = $logic->doUpdate($projectId, $data);

        if($result['status']){
            return redirect('/admin/project/lists')->with('message', '项目更新成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }

    }

    /**
     * @param $data
     * @return mixed
     */
    public function formatCreditMoney($data){
        if(empty($data)) return [];

        foreach($data as $key => $value){
            $data[$key]['loan_amounts']    = ToolMoney::formatDbCashDelete($value['loan_amounts']);
        }

        return $data;
    }

    /**
     * @param $data
     * @param $creditId array
     * @return array
     * @desc 格式化债权
     */
    public function formatProjectCreditMoney($data, $creditId){

        if(empty($data) || empty($creditId)) return [];

        $list = [];

        foreach($creditId as $id){
            if(empty($data[$id])){
                continue;
            }
            $data[$id]['cash'] = ToolMoney::formatDbCashAdd($data[$id]['cash']);
            $list[$id] = $data[$id];
        }

        return $list;
    }

    /**
     * @param Request $request
     * @return string
     * @desc 审核通过
     */
    public function doPass(Request $request)
    {

        $id = $request->input('id', 0);

        $logic = new ProjectLogic();

        $result = $logic->doPass( $id );

        return $this->ajaxJson($result);

    }

    /**
     * @param Request $request
     * @return string
     * @desc 审核不通过
     */
    public function doNoPass(Request $request)
    {

        $id = $request->input('id', 0);

        $logic = new ProjectLogic();

        $result = $logic->doNoPass( $id );

        return $this->ajaxJson($result);

    }

    /**
     * @param Request $request
     * @return string
     * @desc 审核通过
     */
    public function doPublish(Request $request)
    {

        $id = $request->input('id', 0);

        $logic = new ProjectLogic();

        $result = $logic->doPublish( $id );

        return $this->ajaxJson($result);

    }

    /**
     * @param Request $request
     * @return string
     * @desc 删除
     */
    public function doDelete(Request $request){

        $id = $request->input('id', 0);

        $logic = new ProjectLogic();

        $result = $logic->doDelete( $id );

        return $this->ajaxJson($result);

    }

    /**
     * @param Request $request
     * @return string
     */
    public function doBeforeRefundRecord(Request $request){

        $id = $request->input('project_id', '');

        $logic = new ProjectLogic();

        $result = $logic->doBeforeRefundRecord( $id );

        return $this->ajaxJson($result);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 后台导出功能
     */
    public function orderExport( Request $request ){

        $email  = $request->input('email');

        $name   = $request->input('name');

        $exportType = $request->input('export_type','1');

        $startTime  = $request->input('start_time');

        $endTime    = $request->input('end_time');

        $isBefore   =   $request->input('is_before');

        if( empty($email) || empty($name) || empty($startTime) || empty($endTime) ){

            return redirect()->back()->withInput($request->input())->with('message', '信息不完整!');

        }

        $data = [
            'start_time'        => $startTime,
            'end_time'          => $endTime,
            'email'             => $email,
            'name'              => $name,
            'export_type'       => $exportType,
            'is_before'         => $isBefore,
        ];

        $logic = new ProjectLogic();

        $result = $logic->adminExport($data);

    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 创建新定期
     */
    public function createNew(){

        $productLine = ProjectLogic::getProductLine();

        $creditList  = CreditAllLogic::getUseCreateProjectCreditList( );

        $viewData = [

            'home'          => $this -> homeName,
            'title'         => '创建新定期项目',
            'productLine'   => $productLine,
            'creditList'    => $creditList,
            'source'        => CreditLogic::getSource(),
            'type'          => CreditLogic::getType(),
            'refundType'    => ProjectLogic::getRefundType(),
            'activity_sign' => ProjectExtendModel::setProjectActivitySign(),

        ];

        return view('admin.project.create.projectNew', $viewData);

    }

    /**
     * @param $id
     * @param Request $request
     * @desc 更新项目
     * @return mixed
     */
    public function updateNew($id, Request $request){

        $logicResult = ProjectLogic::getById($id);

        if( !$logicResult['status'] && empty($logicResult['data']) ){
            return redirect()->back()->withInput($request->input())->with('fail', '该项目不存在');
        }

        //$logicResult['data']['credit_id'] = $logicResult['data']['credit_id'][0];

        $productId = $logicResult['data']['product_line']+$logicResult['data']['type'];

        $productLine = ProjectLogic::getProductLine();

        $creditList  = CreditAllLogic::getUseCreateProjectCreditList( '', true, $logicResult['data']['credit_id'] );

        if($productId == ProjectDb::PRODUCT_LINE_FACTORING || $productId == ProjectDb::PRODUCT_LINE_ONE_MONTH)
        {
            $investNote = '天';
        }else {
            $investNote = '月';
        }

            $viewData = [
            'projectInfo'   => $logicResult['data'],
            'productId'     => $productId,
            'home'          => $this -> homeName,
            'title'         => '编辑项目',
            'productLine'   => $productLine,
            'source'        => CreditLogic::getSource(),
            'type'          => CreditLogic::getType(),
            'refundType'    => ProjectLogic::getRefundType(),
            'creditList'    => $creditList,
            'investNote'    => $investNote,

        ];

        return view('admin.project.edit.projectNew', $viewData);

    }


    /**
     * @desc 执行项目创建
     * @param ProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doCreateNew(ProjectRequest $request){

        $logic = new ProjectLogic();

        $data = $request->all();

        if($data['invest_days'] > 20){
            return redirect()->back()->withInput($request->input())->with('message', '融资时间不能大于20天');
        }

        $creditId = $data['credit_id'];

        if(!is_array($data['credit_id'])){
            $data['credit_id'] = [$creditId];
        }

        $data['credit'] = $this -> formatProjectCreditMoney($data['credit'], $data['credit_id']);

        $data['credit'][$creditId]['product_line'] = $data['product_line'];

        $result = $logic->doCreate($data);

        if($result['status']){
            return redirect('/admin/project/lists')->with('message', '项目创建成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }

    }

    /**
     * @param ProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doUpdateNew(ProjectRequest $request){

        $logic = new ProjectLogic();

        $data = $request->all();

        if($data['invest_days'] > 20){
            return redirect()->back()->withInput($request->input())->with('message', '融资时间不能大于20天');
        }

        $creditId = $data['credit_id'];

        if(!is_array($data['credit_id'])){
            $data['credit_id'] = [$creditId];
        }

        $data['credit'] = $this -> formatProjectCreditMoney($data['credit'], $data['credit_id']);

        $data['credit'][$creditId]['product_line'] = $data['product_line'];

        $projectId = $data['id'];

        $result = $logic->doUpdate($projectId, $data);

        if($result['status']){
            return redirect('/admin/project/lists')->with('message', '项目更新成功！');
        }else {
            return redirect()->back()->withInput($request->input())->with('message', $result['msg']);
        }

    }


}
