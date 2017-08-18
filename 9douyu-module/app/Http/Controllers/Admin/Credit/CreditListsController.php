<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/17
 * Time: 下午5:26
 * Desc: 债权列表汇总
 */
namespace App\Http\Controllers\Admin\Credit;

use App\Http\Controllers\Admin\AdminController;

use App\Http\Logics\Credit\CreditAllLogic;
use App\Http\Logics\Credit\CreditLogic;

use Illuminate\Http\Request;

use App\Tools\ToolMoney;
/**
 * 债权列表集合
 *
 * Class CreditListsController
 * @package App\Http\Controllers\Admin\Credit
 */
class CreditListsController extends AdminController{

    /**
     * 债权列表合集
     *
     * @return mixed
     */
    public function lists(Request $request){
        //参数
        $credit_name = trim($request->input('credit_name'));
        $source      = trim($request->input('credit_source'));
        $credit_tag  = trim($request->input('credit_tag'));
        $originalName= trim($request->input('loan_username'));

        //搜索条件
        $condition   = [];

        if(!empty($credit_name)){
            $condition[] = ['credit_name', 'like','%' . $credit_name . '%'];
        }
        if(!empty($source)){
            $condition['source']      = $source;
        }
        if(!empty($credit_tag)){
            $condition['credit_tag']  = $credit_tag;
        }
        if(!empty($originalName)){
            $name        = addslashes(json_encode($originalName));
            $condition[] = ['loan_username', 'like','%' . $name . '%'];
        }

        //列表
        $list      = CreditLogic::geAllLists($condition);

        $data                       = [];
        $data['list']               = CreditLogic::getProjectLinks($list);
        $data['list']               = self::formatOutput($data['list']);
        $data['source']             = CreditLogic::getSource();
        $data['type']               = CreditLogic::getType();
        $data['pageParam']          = ['credit_name'=> $credit_name, 'credit_source'=> $source, 'credit_tag'=> $credit_tag, 'loan_username'=> $originalName];
        $data['productLine']        = CreditLogic::getProductLine();
        $data['repaymentMethod']    = CreditLogic::getRefundType();
        $data['dayOrMonth']         = CreditLogic::getLoanDeadlineDayOrMonth();

        return view('admin.credit.lists.credit-all-lists', $data);
    }

    /**
     * 格式列表输出金额
     * @param array $listData
     * @return array
     */
    protected static function formatOutput($listData = []){
        if($listData){

            foreach($listData as $list){
                $list->loan_amounts     = ToolMoney::formatDbCashDeleteTenThousand($list->loan_amounts);
                $list->can_use_amounts  = ToolMoney::formatDbCashDeleteTenThousand($list->can_use_amounts);

                //债权项目关联表
                if(!empty($list->projectLinks)){
                    $projectLinks_array = [];
                    foreach($list->projectLinks as $projectLinkKey => $projectLink){
                        $credit_info           = $projectLink['credit_info'];
                        $credit_cash = [];
                        if(!empty($credit_info)) {
                            $credit_info_array = json_decode($credit_info, true);
                            foreach ($credit_info_array as $credit_key => $credit) {
                                if ($credit['credit_id'] == $list->credit_id) {
                                    $credit_cash[] = ToolMoney::formatDbCashDeleteTenThousand($credit['credit_cash']);
                                }
                            }
                        }
                        $projectLinks_array[] = ['project_id'=>$projectLink['project_id'], 'product_line'=>$projectLink['product_line'], 'cash'=> array_sum($credit_cash)];

                    }
                    $list->projectLinks_array = $projectLinks_array;
                }
            }
        }
        return $listData;
    }

}