<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/23
 * Time: 上午11:25
 * 债权公共类
 */
namespace App\Http\Dbs\Credit;

use App\Http\Dbs\JdyDb;

class CreditDb extends JdyDb{
    const
        //还款方式
        REFUND_TYPE_BASE_INTEREST       = 10,   // 到期还本息
        REFUND_TYPE_ONLY_INTEREST       = 20,   // 按月付息，到期还本
        REFUND_TYPE_FIRST_INTEREST      = 30,   // 投资当日付息，到期还本

        REFUND_TYPE_WITH_BASE           = 40,       //等额本息 老系统导入新系统暂时不用
        REFUND_TYPE_BASE_PRINCIPAL      = 50,       //等额本金 老系统导入新系统暂时不用
        REFUND_TYPE_CYCLE_INVEST        = 60,       //循环投资 老系统导入新系统暂时不用

        //债权来源
        SOURCE_FACTORING                = 10,    // 耀盛保理
        SOURCE_CREDIT_LOAN              = 20,    // 耀盛信贷
        SOURCE_HOUSING_MORTGAGE         = 30,    // 房产抵押
        SOURCE_THIRD_CREDIT             = 40,    // 第三方
        SOURCE_TAO_SHOP                 = 41,   //淘当铺
        //债权类型
        TYPE_BASE                       = 50,    // 常规
        TYPE_PROJECT_GROUP              = 60,    // 项目集
        TYPE_NINE_CREDIT                = 70,    // 九省心
        TYPE_CAR_LOAN                   = 71,    //车贷
        TYPE_HOUSE_LOAN                 = 72,    //房贷
        TYPE_CREDIT_LOAN                = 73,    //信用贷

        TYPE_CREDIT_LOAN_USER           = 100,    // 借款人体系新债权


        STATUS_CODE_UNUSED              = 100,   // 未发布
        STATUS_CODE_ACTIVE              = 200,   // 已发布
        STATUS_CODE_EXPIRE              = 300,   //过期

        END=true;

    /**
     * 创建记录
     * @param array $attributes
     * @return static
     */
    public static function addRecord($attributes = []){
        $model = new static($attributes, array_keys($attributes));
        $model->save();
        return $model->id;
    }

    /**
     * 获取指定ID所有字段的记录
     * @param $id
     * @return mixed
     */
    public static function findById($id){
        return static::find($id);
    }


    /**
     * 更新指定ID债权
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public static function doUpdate($id = 0, $data = []){
        return static::where('id', $id)->Update($data);
    }

    /**
     * @desc    根据用户身份证号，获取借款信息
     **/
    public static function getCreditWithUser($idNo){

        $dbPrefix = env('DB_PREFIX');//表前缀

       // $sql = "SELECT id, source, type,loan_amounts,loan_username,loan_user_identity ,created_at FROM (
       //             SELECT id, source,type,loan_amounts,loan_username,loan_user_identity, created_at FROM ".$dbPrefix."credit_building_mortgage  where  loan_user_identity like '%$idNo%'
       //             union all

       //             SELECT id, source, type, loan_amounts,loan_username,loan_user_identity, created_at FROM ".$dbPrefix."credit_factoring  where  loan_user_identity like '%$idNo%'
       //             union all

       //             SELECT id,source , type, loan_amounts,loan_username,loan_user_identity, created_at FROM ".$dbPrefix."credit_group  where  loan_user_identity like '%$idNo%'

       //             union all

       //             SELECT id, source , type, loan_amounts,loan_username,loan_user_identity, created_at FROM ".$dbPrefix."credit_loan  where  loan_user_identity like '%$idNo%'
       //     ) as t1 order by created_at DESC
       // ";

        $sql = "select id, source, type,loan_amounts,loan_username,loan_user_identity ,created_at from ".$dbPrefix."credit where source in ( ".self::SOURCE_FACTORING.", ".self::SOURCE_CREDIT_LOAN.", ".self::SOURCE_HOUSING_MORTGAGE.") and loan_user_identity like '%$idNo%' " ;

        $result = app('db')->select($sql);

        return $result;
    }

    /**
     * @desc    获取借款人数
     */
    public static function getCreditUser(){

        $dbPrefix = env('DB_PREFIX');//表前缀

       //    $sql = "SELECT loan_user_identity FROM (
       //                SELECT loan_user_identity FROM ".$dbPrefix."credit_building_mortgages

       //                union

       //                SELECT loan_user_identity FROM ".$dbPrefix."credit_factoring

       //                union

       //                SELECT loan_user_identity FROM ".$dbPrefix."credit_group

       //                union

       //                SELECT loan_user_identity FROM ".$dbPrefix."credit_loan

       //        ) as t1
       //    ";

        $sql = "select loan_user_identity from ".$dbPrefix."credit where source in ( ".self::SOURCE_FACTORING.", ".self::SOURCE_CREDIT_LOAN.", ".self::SOURCE_HOUSING_MORTGAGE.")";

        $result = app('db')->select($sql);

        return $result;
    }

}
