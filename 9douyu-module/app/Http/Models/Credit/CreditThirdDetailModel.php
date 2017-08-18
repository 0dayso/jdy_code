<?php
/**
 * Created by PhpStorm.
 * User: lgh-dev
 * Date: 16/11/15
 * Time: 11:01
 * Desc: 第三方债权人详情Model
 */

namespace App\Http\Models\Credit;


use App\Http\Dbs\Credit\CreditThirdDetailDb;
use App\Lang\LangModel;

class CreditThirdDetailModel extends CreditModel
{

    /**
     * @desc 创建第三方债权人详情
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public static function doCreateDetail($data){

        $return = CreditThirdDetailDb::insert($data);

        if(!$return)
            throw new \Exception(LangModel::getLang('ERROR_CREDIT_CREATE_THIRD_DETAIL'), self::getFinalCode('doCreateDetail'));

        //记录日志
        \App\Tools\AdminUser::userLog('credit_third_detail',[$data,$return]);

        return $return;
    }

    /**
     * @desc 获取债权人详情列表
     * @param $creditId
     * @return mixed
     */
    public static function getAbleCreditDetailList($creditId){

        $creditThirdDb = new CreditThirdDetailDb();

        $creditDetail  = $creditThirdDb->getAbleCreditDetailList($creditId);

        return $creditDetail;
    }

    /**
     * @desc 检测是否已经导入第三方债权详情
     * @param $creditId
     * @return bool
     */
    public function checkCreditThirdIsImport($creditId){

        $creditThirdDb = new CreditThirdDetailDb();

        $creditDetails = $creditThirdDb->getCreditListByThirdId($creditId);
        //未导入过债权详情
        if(empty($creditDetails)){
            return false;
        }
        return true;
    }

    /**
     * @desc 编辑第三方债权上传文件时清除之前关联的数据
     * @param $creditId int
     * @return mixed | Exception
     */
    public static function delDetailByCreditId( $creditId )
    {
        if( empty( $creditId ) )
            throw new \Exception('债权ID不能为空', self::getFinalCode('doCreateDetail'));

        $return = CreditThirdDetailDb::delDetailByCreditId( $creditId );

        if(!$return)
            throw new \Exception('债权详情删除失败', self::getFinalCode('doCreateDetail'));
    }
}
