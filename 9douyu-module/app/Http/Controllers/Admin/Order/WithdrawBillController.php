<?php
/**
 * Created by PhpStorm.
 * User: caelyn
 * Date: 16/6/3
 * Time: 上午10:35
 * Desc: 提现自动对账控制器
 */

namespace App\Http\Controllers\Admin\Order;

use App\Http\Logics\Order\WithdrawBillLogic;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Oss\OssLogic;
use App\Tools\ToolTime;
use Illuminate\Http\Request;
use Redirect;

/**
 * Class WithdrawBillController
 * @package App\Http\Controllers\Admin\Withdraw
 */
class WithdrawBillController extends AdminController{

    /**rout
     * 自动对账form表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkBill(){

        $data['uploadData'] = $this->getCache();

        return view('admin.order.withdrawCheckBill', $data);
    }

    /**
     * 自动对账提交
     * @param Request $request
     */
    public function uploadBill(Request $request){
        $file = $_FILES['billFile'];
        $payChannel = $request->input('payChannel','jd');
        //获取excel内容
        $withdrawBillLogic = new WithdrawBillLogic();

        if($payChannel == "suma" || $payChannel == "ucf"){

            $ossLogic = new OssLogic();
            //上传到oss public目录下
            $doUpload = $ossLogic->putFile($file,'public');

            if(!$doUpload['status']){
                return Redirect::to('admin/withdraw/checkBill')->with('message',$doUpload['msg']);
            }

            $reader     = \Excel::load($file['tmp_name']);
            $data       = $reader->getSheet(0)->toArray();
            if($payChannel == "suma"){
                $result     = $withdrawBillLogic->loadExcelSuma($data);
            }else{
                $result     = $withdrawBillLogic->loadExcelUcf($data);
            }
        }else{
            $result = $withdrawBillLogic->loadExcel($file['tmp_name']);
        }

        //入库
        if(!empty($result)){

            if(isset($result['status']) && $result['status']==false){
                return Redirect::to('admin/withdraw/checkBill')->with('message',$result['msg']);
            }
            $res    = $withdrawBillLogic->addBillInfo($result);

            if($res['status']){
                $this->setCache($file['name']);
                return Redirect::to('admin/withdraw/checkBill')->with('message',$res['msg']);
            }
            return Redirect::back()->with('message',$res['msg']);

        }
        return Redirect::to('admin/withdraw/checkBill');

    }

    /**
     * @return mixed
     * @desc 获取缓存
     */
    private function getCache(){

        return \Cache::get(ToolTime::dbDate().'_U_P_B', []);

    }

    /**
     * @param $fileName
     * @return mixed
     * @desc 设置缓存
     */
    private function setCache($fileName){

        $data = $this->getCache();

        $data[] = $fileName;

        return \Cache::put(ToolTime::dbDate().'_U_P_B', $data, 1440);

    }

}
