<?php
/**
 * 普付宝订单记录
 * User: bihua
 * Date: 16/8/19
 * Time: 15:55
 */
namespace App\Http\Logics\ThirdApi;

use App\Http\Dbs\Pfb\InvestPfbDb;
use App\Http\Logics\Logic;
use App\Http\Models\Invest\InvestModel;
use App\Http\Models\Pfb\InvestPfbModel;
use App\Http\Models\Project\ProjectLinkCreditModel;
use App\Http\Models\Common\CoreApi\ProjectModel as CoreApiProjectModel;

class InvestPfbLogic extends Logic
{

    /**
     * 处理普付宝订单
     * @param $orderId
     * @param $type
     * @param $userId
     * @return bool|mixed
     */
    public function dealOrder($orderId,$type,$userId){

        $returnArr = array(
            'status'    => false,
            'msg'       => '操作失败，无此订单或订单号错误',
            'ids'       => ''
        );

        $orderArr = explode(',',$orderId);

        $str = '';
        try {
            self::beginTransaction();

            if(count($orderArr) > 1){

                for($i = 0; $i<count($orderArr); $i++){

                    $res = $this->setOrderById($orderArr[$i],$type,$userId);

                    if($res){

                        $str .= $orderArr[$i].',';
                    }
                }

                $str = rtrim($str,',');
            }else{

                $res = $this->setOrderById($orderId,$type,$userId);

                if($res){

                    $str = $orderId;
                }
            }

            self::commit();

            $returnArr['status'] = true;
            $returnArr['msg']    = '操作成功';
            $returnArr['ids']    = $str;

        }catch (\Exception $e){

            self::rollback();

            $returnArr['msg'] = $e->getMessage();
        }

        return self::callSuccess($returnArr);
    }

    /**
     * 设置普付宝订单信息
     * @param $orderId
     * @param $type
     * @param $userId
     * @return bool|mixed
     */
    private function setOrderById($orderId,$type,$userId){

        $db        = new InvestPfbDb();

        $pfbModel  = new InvestPfbModel();

        $orderData = InvestModel::getInvestByInvestId($orderId);

        if (empty($orderData) || $orderData['user_id'] != $userId) {

            return false;
        }

        $info   = $db->getOrderByInvestId($orderId);

        $status = $db->getStatus($type);

        if (empty($info)) {

            //添加数据

            $id = $pfbModel->addInfo($orderId,$orderData['cash'],$orderData['user_id']);

            return $id;

        } else {

            $res = $pfbModel->editStatus($orderId,$status);

            return $res;
        }

    }

    /**
     * 根据订单ID获取项目信息，多个订单以英文的逗号（,）隔开
     * @param $ids
     * @return array|mixed
     */
    public function getMortgageByInvestIds($ids){

        if(empty($ids)){

            return array();
        }

        $model   = new InvestModel();

        $idArr   = explode(',',$ids);

        $list    = $model->getInvestByIdArr($idArr);

        $data    = [];

        if(!empty($list)){

            $projectModel = new ProjectLinkCreditModel();

            foreach ($list as $k=>$v) {

                $data[$k]['id']         = $v['invest_id'];

                $data[$k]['cash']       = $v['cash'];

                $data[$k]['project_id'] = $v['project_id'];

                $project                =  $projectModel->getCoreProjectDetail($v['project_id']);

                $data[$k]['end_at']     = $project['end_at'];

                $data[$k]['name']       = $project['name'];
            }

        }

        return self::callSuccess($data);
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取普付宝用户资金情况
     */
    public function getUserPledgeBalance($userId){

        $model        = new InvestPfbModel();

        //冻结订单总额
        $freezeCash   = $model->getFreezeCash($userId);

        //总额
        $totalCash    = CoreApiProjectModel::getPfbInvestTotal($userId);

        //未冻结订单总额(投资可质押项目的订单)
        $unFreezeCash = $totalCash - $freezeCash;

        $data         = ['total'=>$totalCash,'freeze'=>$freezeCash,'unfreeze'=>$unFreezeCash];

        return self::callSuccess($data);
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户已质押订单列表
     */
    public function getUserPledgeInvest($userId){

        $model = new InvestPfbModel();

        $idArr = $model->getFreezeInvestIds($userId);

        $list  = CoreApiProjectModel::getPfbInvestList($userId,1,1000);

        $data  = [];

        if(!empty($idArr)) {

            $arr   = array_column($idArr,'invest_id');

            foreach ($list as $v) {

                if (in_array($v['id'], $arr)) {

                    $data[] = $v;
                }

            }
        }

        return self::callSuccess($data);
    }


    /**
     * 不允许债转的投资id集合
     * @param $userId 用户Id
     * @return array
     */
    public function getFreezeInvestIds($userId){
        $model       = new InvestPfbModel();
        $idArr       = $model->getFreezeInvestIds($userId);
        $investIds   = array_column($idArr,'invest_id');
        return $investIds;
    }

    /**
     * @param $userId
     * @return array
     * @desc 获取用户可质押订单列表
     */
    public function getUserUnPledgeInvest($userId){

        $model = new InvestPfbModel();

        $idArr = $model->getFreezeInvestIds($userId);

        $list  = CoreApiProjectModel::getPfbInvestList($userId,1,1000);

        $data  = [];

        if(!empty($idArr)) {

            $arr   = array_column($idArr,'invest_id');

            foreach ($list as $v) {

                if (!in_array($v['id'], $arr)) {

                    $data[] = $v;
                }

            }
        }else{

            $data = $list;
        }

        return self::callSuccess($data);
    }
}