<?php

/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/9/23
 * Time: 下午5:57
 */

namespace App\Http\Controllers\Admin\Activity;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Dbs\Activity\LotteryConfigDb;
use App\Http\Logics\Activity\LotteryConfigLogic;
use App\Http\Logics\Activity\LotteryRecordLogic;
use App\Lang\LangModel;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;

class LotteryController extends AdminController
{

    protected $homeName = '奖品配置';

    const PAGE_SIZE = 20; //设置列表每页条数

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLotteryConfigLimit( Request $request)
    {


        $page       = $request->input('page', 1);

        $size       = self::PAGE_SIZE;

        $logic      = new LotteryConfigLogic();

        $list       = $logic->getConfigList( $page, $size );

        $typeList   = $logic->getLotteryType();

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/lottery/configList');

        $paginate   = $toolPaginate->getPaginate();

        $viewDate   = [
            'list'   => $list['list'],
            'total'  => $list['total'],
            'type'   => $typeList,
            'paginate' => $paginate,
        ];

        return view('admin.activity.lottery.listsConfig',$viewDate);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 添加奖品配置
     */
    public function addLotteryConfig(Request $request)
    {
        $type       =   $request->input('type');
        
        $logic      =   new LotteryConfigLogic();
        
        $bonusInfo  =   $logic->getBonusList($type);
            
        $typeList   =   $logic->getLotteryType();

        $viewDate   =   [
            'typeList'  =>  $typeList,
            'bonusList' =>  $bonusInfo,
            'type'      =>  isset($type) ? $type : LotteryConfigDb::LOTTERY_TYPE_ENVELOPE,
        ];

        return view('admin.activity.lottery.addConfig' , $viewDate);
    }

    /**
     *
     */
    public function doAddConfig(Request $request)
    {
        $data       =   $request->all();

        $logic      =   new LotteryConfigLogic();

        $return     =   $logic->doAdd($data);

        if($return['status']){

            return redirect('/admin/lottery/addConfig?type='.$data['type'])->with('message', LangModel::getLang('ERROR_LOTTERY_CONFIG_ADD_SUCCESS'));

        }
        return redirect()->back()->withInput($request->input())->with('fail', LangModel::getLang('ERROR_LOTTERY_CONFIG_ADD_FAILED'));

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 编辑奖品
     */
    public function editConfig(Request $request)
    {
        $type       =   $request->input('type');

        $id         =   $request->input('id');

        $logic      =   new LotteryConfigLogic();

        $lotteryInfo=   $logic->getById($id);

        $bonusInfo  =   $logic->getBonusList($type);

        $typeList   =   $logic->getLotteryType();

        $viewDate   =   [
            'typeList'  =>  $typeList,
            'bonusList' =>  $bonusInfo,
            'lottery'   =>  $lotteryInfo,
            'type'      =>  isset($type) ? $type : LotteryConfigDb::LOTTERY_TYPE_ENVELOPE,
            'id'        =>  $id,
        ];

        return view("admin.activity.lottery.editConfig",$viewDate);
    }

    /**
     * @desc 执行编辑
     */
    public function doEditConfig( Request $request)
    {
        $data       =   $request->all();

        $logic      =   new LotteryConfigLogic();

        $return     =   $logic->doEdit($data['id'],$data);

        if($return['status']){

            return redirect('/admin/lottery/configList')->with('message', LangModel::getLang('ERROR_LOTTERY_CONFIG_EDIT_SUCCESS'));

        }

        return redirect()->back()->withInput($request->input())->with('fail', LangModel::getLang('ERROR_LOTTERY_CONFIG_EDIT_FAILED'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 中奖记录查询
     */
    public function getRecord( Request $request)
    {
        $page           =   $request->input('page', 1);

        $phone          =   $request->input('phone');

        $activityId     =   $request->input('activity_id');

        $export         =   $request->input('export');

        $size           =   self::PAGE_SIZE;

        $configLogic    =   new LotteryConfigLogic();

        $typeList       =   $configLogic->getLotteryType();

        $list           =   [ 'list'=>[] , 'total' =>0 ];

        $logic          =   new LotteryRecordLogic();

        $pageParam      =   '';

        if( !empty($activityId) || !empty($phone) ){

            $list       = $logic->getRecordList( $page, $size ,$phone ,$activityId);

            if($export ==1){

                LotteryRecordLogic::doAdminExport($list);
                die();
            }
            $pageParam  = '?phone='.$phone;

            $pageParam .= $activityId ? '&activity_id='.$activityId : '';
        }

        $toolPaginate = new ToolPaginate($list['total'], $page, $size, '/admin/lottery/record'.$pageParam);

        $paginate   = $toolPaginate->getPaginate();

        $viewDate   = [
            'list'          =>  $list['list'],
            'total'         =>  $list['total'],
            'typeList'      =>  $typeList,
            'paginate'      =>  $paginate,
            'activityNote'  =>  LotteryRecordLogic::getActivityNote(),
            'params'        =>  ['phone'=>$phone,'aid' => $activityId],
        ];
        
        return view('admin.activity.lottery.listsRecord',$viewDate);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 后台添加中奖者信息
     */
    public function addLotteryRecord()
    {
        $viewDate   = [
            'activityNote'  =>  LotteryRecordLogic::getLotteryActivityEventNote(),
        ];

        return view('admin.activity.lottery.addRecord',$viewDate);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 添加中奖者信息
     */
    public function doAddLotteryRecord(Request $request)
    {
        $data       =   $request->all();

        $groupStr   =   $request->input('activity_group');

        if( empty($groupStr) ){

            return redirect()->back()->withInput($request->input())->with('fail', LangModel::getLang('ERROR_LOTTERY_RECORD_EDIT_FAILED'));
        }

        $groupArr   =   explode('_',$groupStr);

        $activityId =   $groupArr[0];

        $data['activity_id']    =   $activityId;

        $logic      =   new LotteryRecordLogic();

        $return     =   $logic->doAdminAdd($data);
        
        if($return['status']){

            return redirect('/admin/lottery/addRecord')->with('message', LangModel::getLang('ERROR_LOTTERY_RECORD_ADD_SUCCESS'));
        }

        return redirect()->back()->withInput($request->input())->with('fail', LangModel::getLang('ERROR_LOTTERY_RECORD_ADD_FAILED'));

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @desc 编辑、审核中奖信息
     */
    public function editLotteryRecord(Request $request)
    {
        $recordId       =   $request->input('r_id');

        $activityNote   =   LotteryRecordLogic::getLotteryActivityEventNote();

        $recordLogic    =   new LotteryRecordLogic();

        $lotteryRecord  =   $recordLogic->getById($recordId);

        $groupId        =   $activityNote[$lotteryRecord['activity_id']]['group'];

        $lotteryLogic   =   new LotteryConfigLogic();
        
        $lotteryList    =   $lotteryLogic->getLotteryByGroup($groupId);

        $viewDate   =   [
            'activityNote'      =>  $activityNote,
            'lotteryRecord'     =>  $lotteryRecord,
            'lotteryList'       =>  $lotteryList,
        ];

        return view("admin.activity.lottery.editRecord",$viewDate);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @desc 执行编辑、审核中奖记录
     */
    public function doEditLotteryRecord( Request $request)
    {
        $data       =   $request->all();

        $groupStr   =   $request->input('activity_group');

        if( empty($groupStr) ){

            return redirect()->back()->withInput($request->input())->with('fail', LangModel::getLang('ERROR_LOTTERY_RECORD_EDIT_FAILED'));
        }

        $groupArr   =   explode('_',$groupStr);

        $activityId =   $groupArr[0];

        $data['activity_id']    =   $activityId;

        $logic      =   new LotteryRecordLogic();

        $return     =   $logic->doEdit($data);

        if($return['status']){

            return redirect('/admin/lottery/editRecord?r_id='.$data['id'])->with('message', LangModel::getLang('ERROR_LOTTERY_RECORD_EDIT_SUCCESS'));
        }

        return redirect()->back()->withInput($request->input())->with('fail', LangModel::getLang('ERROR_LOTTERY_RECORD_EDIT_FAILED'));
    }

    /**
     * @param Request $request
     * @return string
     * @desc  ajax 获取奖品信息
     */
    public function getLotteryByGroup(Request $request)
    {
        $groupStr    =   $request->input('group_id');

        if( empty($groupStr) ){

            $groupId =  0;
        }else{

            $groupArr=   explode('_',$groupStr);

            $groupId =   $groupArr[1];
        }

        $return      =   LotteryConfigLogic::getLotteryListByGroupId($groupId);

        return self::ajaxJson($return);
    }

    
}