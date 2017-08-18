<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/9/18
 * Time: 下午5:16
 */

namespace App\Http\Controllers\Admin\Invite;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Logics\Invite\InviteLogic;
use App\Http\Models\Common\CoreApi\UserModel;
use App\Tools\ToolPaginate;
use Illuminate\Http\Request;
use Lang;

class InviteController extends AdminController{

    const PAGE_SIZE = 20;
    /**
     * @param Request $request
     * @desc 邀请
     */
    public function index(Request $request){

        $phone = $request->input('phone', '');

        $page  = $request->input('page', 1);

        if(!empty($phone)){
            $userInfo  = UserModel::getBaseUserInfo($phone);
        }

        if(!empty($userInfo)) {
            $userId = $userInfo['id'];
            $param['userId'] = $userId;
        }

        if(!empty($userId)){
            $inviteLogic = new InviteLogic();

            $inviteData  = $inviteLogic->getListByUserId($userId, $page, self::PAGE_SIZE);

            if(!empty($inviteData['list'])){
                foreach($inviteData['list'] as $key=>$item){
                    $inviteData['list'][$key]['type_str'] = Lang::get('invite.TYPE_'.$item['type']);
                    $inviteData['list'][$key]['user_type_str'] = Lang::get('invite.USER_TYPE_'.$item['user_type']);
                }
                $toolPaginates = new ToolPaginate($inviteData['total'], $page, self::PAGE_SIZE, '/admin/invite/index', 'phone='.$phone.'&page=');
                $pageInfo   = $toolPaginates->getPaginate();
            }
        }


        $viewData = [
            'phone'      => $phone,
            'inviteData' => empty($inviteData['list'])?'':$inviteData['list'],
            'pageInfo'   => empty($pageInfo)?'':$pageInfo,
        ];

        return view('admin.invite.index', $viewData);

    }

}
