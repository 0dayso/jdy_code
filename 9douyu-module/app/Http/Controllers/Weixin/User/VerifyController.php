<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/27
 * Time: 10:48
 */
namespace App\Http\Controllers\Weixin\User;
use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\RequestSourceLogic;
use Illuminate\Http\Request;
use App\Http\Logics\User\UserLogic;


class VerifyController extends UserController{


    /**
     * @return mixed
     * 实名入口页
     */
    public function index(){

        return view('wap.user.verify.index');
    }


    /**
     * @param Request $request
     * @return mixed
     * 实名业务逻辑
     */
    public function doVerify( Request $request )
    {

        $name       = $request->input('name','');

        $userId     = $this->getUserId();

        $from       = RequestSourceLogic::getSource();

        $cardNo     = $request->input('card_no','');

        $idCard     = $request->input('id_card','');

        $logic      = new UserLogic();

        $result     = $logic->verify($userId,$name,$cardNo,$idCard,$from);

        if($result['status']){

            $roiProjectId = \Session::get("roiProjectId");
            if($roiProjectId){
                \Session::forget("roiProjectId");
                return redirect("/project/detail/".$roiProjectId);
            }
            //跳转至交易密码
            return redirect('/user/verifySuccess')->with('message', '实名认证成功！');

        }else {

            //返回
            return redirect()->back()->withInput($request->input())->with('errors', $result['msg']);

        }

    }


    public function verifySuccess(){

        return view('wap.user.verify.success');
    }
}
