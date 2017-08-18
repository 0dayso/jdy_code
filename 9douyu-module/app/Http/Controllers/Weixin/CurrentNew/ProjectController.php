<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 17/3/22
 * Time: 上午11:55
 */

namespace App\Http\Controllers\Weixin\CurrentNew;


use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\CurrentNew\ProjectLogic;
use App\Http\Logics\CurrentNew\UserLogic;
use App\Http\Models\Credit\UserCreditModel;
use App\Http\Models\CurrentNew\UserModel;
use Illuminate\Http\Request;
use Redirect;

class ProjectController extends UserController
{

    public function __construct()
    {

        parent::__construct();

        $this->checkIsShow();

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 投资页面
     */
    public function index(){

        $user = $this->getUser();

        $logic = new ProjectLogic();

        $info  = $logic->getDetail();

        //项目查询

        $data = [
            'user_balance' => $user['balance'], //用户账户余额
            'detail'       => $info['data'],    //项目详情
            'user_current_new_amount' => UserLogic::getUserAmount($user['id']),    //新版活期账户
            'max_invest'    => UserModel::getMaxInvestCash(),

        ];

        return view('wap.current_new.index', $data);

    }

    public function creditList(){

        $userId = $this->getUserId();

        $size = 10;

        $model = new UserCreditModel();

        $data['data'] = $model->getListByUserId($userId, $size);

        return view('wap.current_new.credit', $data);

    }

    /**
     * @param Request $request
     * 投资
     */
    public function invest(Request $request){

        $type       = $request->input('type', 0);
        $cash       = (float)$request->input('cash', 0);
        $userId     = $this->getUserId();

        if(empty($type) || $cash<=0){
            return Redirect::to("/current/new")->with('errors','参数错误')->withInput();
        }

        $logic = new ProjectLogic();

        if($type == 1){

            $result = $logic->investOut($userId, $cash);

            $message = (!$result['status'])?$result['msg']:'申请转出成功,次日到账户余额';

        }elseif($type == 2){

            $result = $logic->invest($userId, $cash);

            $message = (!$result['status'])?$result['msg']:'投资成功';

        }

        return Redirect::to("/current/new")->with('errors', $message)->withInput();

    }

    public function checkIsShow(){

        $logic = new ProjectLogic();

        $userId = $this->getUserId();

        $result = $logic->checkIsShowByUserId($userId);

        if(!$result){

            Header("Location: /user");
            exit();

        }

    }

}