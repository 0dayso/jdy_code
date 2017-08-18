<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/15
 * Time: 下午2:38
 */

namespace App\Http\Controllers\Weixin\User;


use App\Http\Controllers\Weixin\UserController;
use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\Contract\ContractLogic;
use App\Http\Logics\Invest\CurrentLogic;
use Illuminate\Http\Request;

class ContractController extends UserController
{

    /**
     * 合同展示
     */
    public function contractShowOld(Request $request)
    {

        $investId   = $request->input('invest_id');

        $data['invest_id'] = $investId;

        $this->getUserId();

        $logic      = new ContractLogic();

        $data['pdfUrl'] = $logic->contractShow( $data );

        header("Content-type: application/pdf");
        //header("Content-type: application/pdf");

        header('filename='.$data['pdfUrl']);

//        readfile($data['pdfUrl']);
        echo file_get_contents($data['pdfUrl']);

        die;
        //return view('wap.user.contract.show',$data);

    }


    public function contractShow(Request $request){

        $isCreditAssign = $request->input('is_credit_assign', 0);

        $projectId = $request->input('project_id');

        $investId = $request->input('investId');

        $type = 0;

        if($isCreditAssign){

            $type = 40;

        }

        $content = ContractLogic::getContent($type, $projectId, $investId);

        if(!empty($content['data'])) {

            $bladeName = AgreementLogic::getBladeByType($content['data']['projectWay'], $content);

            return view("common.agreement.{$bladeName}", $content);

        }else{

            echo '获取合同数据失败';

        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 协议
     */
    public function agreementShow(Request $request){

        $userId = $this->getUserId();
        $agreementType = $request->input('type', '');
        switch($agreementType){
            case 'current':     //活期投资协议
                $currentLogic = new CurrentLogic();
                $viewData = $currentLogic->getAgreementInfo($userId);
                return view('app.agreement.current', $viewData['data']);
            break;
            default:    //投资咨询与管理服务协议
                $projectId  = $request->input('project_id');
                $content    = ContractLogic::getContent($agreementType, $projectId);
                $bladeName  = AgreementLogic::getBladeByType($agreementType, $content);
                return view("common.agreement.{$bladeName}", $content);
            break;
        }

    }


}