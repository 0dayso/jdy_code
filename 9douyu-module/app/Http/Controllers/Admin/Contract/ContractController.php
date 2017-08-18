<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/4
 * Time: 上午12:20
 */

namespace App\Http\Controllers\Admin\Contract;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Contract\ContractLogic;
use App\Http\Logics\Oss\OssLogic;
use Illuminate\Http\Request;
use Redirect;

class ContractController extends AdminController
{

    protected $homeName = '合同管理';

    public function createDownLoad()
    {

        $viewData = [

            'home'          => $this -> homeName,
            'title'         => '创建并下载保全合同',

        ];

        return view('admin.contract.createDownLoad', $viewData);

    }

    public function doCreateDownLoad(Request $request)
    {

        $logic = new ContractLogic();

        $data = $request->all();

        $result = $logic->doDownLoad( $data , false );

        if(!empty($result['status']) && $result['status'] == true){
            $ossLogic = new OssLogic('oss_2');
            $contents = $ossLogic->getObject($result['data']['down_load_url']);
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment;filename='.$result['data']['file_name']);
            echo $contents;
            exit;
        }

        return redirect()->back()->withInput($request->input())->with('message', $result['msg']);

    }

}