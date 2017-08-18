<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/18
 * Time: Pm 06:35
 * Desc: 债权合并后的逻辑层处理
 */

namespace App\Http\Logics\Credit;

use App\Http\Dbs\Credit\CreditDb;
use App\Http\Dbs\Credit\CreditAllDb;
use App\Http\Models\Credit\CreditAllModel;
use App\Http\Logics\Credit\CreditExtendLogic;
use App\Http\Models\Credit\CreditExtendModel;
use App\Http\Models\Project\ProjectLinkCreditNewModel;
use App\Http\Models\User\UserModel;
use Log;
use App\Tools\ToolArray;

class CreditAllLogic extends CreditLogic
{

    /**
     * @desc 创建合并后的债权数据
     * @param $data
     * @return array
     */
    public function doCreate( $data )
    {

        $attributes = [
            'id'                => isset( $data['id'] ) ? $data['id'] : null,
            'company_name'      => $data['company_name'],
            'loan_username'     => $data['loan_username'],
            'loan_user_identity' => $data['loan_user_identity'],
            'loan_amounts'      => $data['loan_amounts'],
            'interest_rate'     => $data['interest_rate'],
            'repayment_method'  => $data['repayment_method'],
            'expiration_date'   => $data['expiration_date'],
            'loan_deadline'     => $data['loan_deadline'],
            'contract_no'        => $data['contract_no'],
            'type'              => $data['type'],
            'source'            => $data['source'],
            'credit_tag'        => $data['credit_tag'],
            ];

        self::beginTransaction();
        try{

            if( $data['source'] == CreditDb::SOURCE_TAO_SHOP ){
                UserModel::checkRegisterByPhone( $data['loan_phone'] );

                $extra = [
                    'loan_use' => $data['loan_use'],
                    'age'      => $data['age'],
                    'loan_hometown'=> $data['loan_hometown'],
                    'sex'      => $data['sex'],
                    'loan_phone'      => $data['loan_phone'],
                    ];
                $creditInfo['extra'] = json_encode( $extra );

            }

            //执行债权的创建
            $result = CreditAllModel::doCreate( $attributes );

            //扩展表数据添加
            $creditInfo['credit_id'] = $result;

            CreditExtendModel::doCreate( $creditInfo );
            self::commit();

        }catch( \Exception $e ){
            self::rollback();
            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError( $e->getMessage() );
        }

        return self::callSuccess( );
    }

    /**
     * @param $productId
     * @param bool $isEdit
     * @param $creditIds
     * @return array|mixed
     * 创建项目调取可匹配债券列表
     */
    public static function getUseCreateProjectCreditList( $productId='', $isEdit = false, $creditIds=[] ){

        if($productId){

            $list = CreditAllDb::getUseCreateProjectCreditList( $productId );

        }else{

            $list = CreditAllDb::getAbleUseCreditList();

        }


        if( $isEdit ){

            $creditIdsList = CreditAllDb::getCreditListByCreditIds( $creditIds );

            $list = array_merge($creditIdsList, $list);

        }

        if( !empty($list) && is_array($list) ){

            $list = CreditAllModel::formatCreateProjectCreditList( $list );

        }

        return $list;
    }

    /**
     * @desc 编辑合并后的债权信息
     * @author linguanghui
     * @param $data
     * @return array
     */
    public function doUpdate( $data = [] )
    {

        $attributes = [
            'company_name'      => $data['company_name'],
            'loan_username'     => $data['loan_username'],
            'loan_user_identity' => $data['loan_user_identity'],
            'loan_amounts'      => $data['loan_amounts'],
            'interest_rate'     => $data['interest_rate'],
            'repayment_method'  => $data['repayment_method'],
            'expiration_date'   => $data['expiration_date'],
            'loan_deadline'     => $data['loan_deadline'],
            'contract_no'        => $data['contract_no'],
            'type'              => $data['type'],
            'source'            => $data['source'],
            'credit_tag'        => $data['credit_tag'],
            ];

        try{
            $id =  $data['id'];

            $result  =  CreditAllModel::doUpdate( $id, $attributes );

            if( $data['source'] == CreditDb::SOURCE_TAO_SHOP ){

                $extra = [
                    'loan_use' => $data['loan_use'],
                    'age'      => $data['age'],
                    'loan_hometown'=> $data['loan_hometown'],
                    'sex'      => $data['sex'],
                    'loan_phone'      => $data['loan_phone'],
                    ];
                $creditInfo['extra'] = json_encode( $extra );

                CreditExtendModel::doUpdateExtra( $id, $creditInfo['extra'] );

            }

        }catch(\Exception $e ){

            $attributes['data']           = $attributes;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError( $e->getMessage() );
        }
        return self::callSuccess();
    }

    /**
     * 通过条件获取所有列表
     *
     * @param array $condition
     * @return mixed
     */
    public static function getCreditLists($condition = []){
        $creditModel = new CreditAllModel;
        return $creditModel->getCreditLists($condition);
    }

    /**
     * 获取债权使用记录
     *
     * @param $items
     * @return mixed
     */
    public static function getProjectLinks($items){
        if($items){
            foreach($items as $k => $creditObj){
                $creditObj->project_link_code = CreditLogic::getProjectLinksCode($creditObj);
                $projectLink                  = ProjectLinkCreditNewModel::getListsByCredit($creditObj->id);
                $creditObj->projectLinks      = $projectLink;
            }
        }
        return $items;
    }

    /**
     * @desc 批量录入债权信息
     * @param $creditInfo
     * @return array
     */
    public function doBatchImport( $creditInfo )
    {
        if( empty( $creditInfo ) )
            return self::callError('债权数据不能为空');

        self::beginTransaction();
        try{

            $formatCreditInfo = $this->formatBatchCredit( $creditInfo );

            $result = CreditAllModel::doBatchCreate( $formatCreditInfo );

            $formatCreditExtend = $this->formatCreditExtend( $creditInfo );

            CreditExtendModel::doAddBatchExtra( $formatCreditExtend );

            self::commit();
        }catch( \Exception $e ){
            self::rollback();

            $attributes['data']           = $creditInfo;
            $attributes['msg']            = $e->getMessage();
            $attributes['code']           = $e->getCode();

            Log::error(__METHOD__.'Error', $attributes);

            return self::callError( $e->getMessage() );
        }
        return self::callSuccess();
    }

    /**
     * @desc 格式化批量插入的债权数据
     * @param $creditInfo array
     * @return array
     */
    public function formatBatchCredit( $creditInfo )
    {
        if( empty( $creditInfo ) )
            return [];

        $formatCreditInfo = [];

        foreach( $creditInfo as $key=>$value )
        {

            $formatCreditInfo[$key] = [
                    'id'      => isset( $value['credit_id'] ) ? $value['credit_id'] : null,
                    'company_name'      => $value['company_name'],
                    'loan_username'     => $value['loan_username'],
                    'loan_user_identity' => $value['loan_user_identity'],
                    'loan_amounts'      => $value['loan_amounts'],
                    'interest_rate'     => $value['interest_rate'],
                    'repayment_method'  => $value['repayment_method'],
                    'expiration_date'   => $value['expiration_date'],
                    'loan_deadline'     => $value['loan_deadline'],
                    'contract_no'        => $value['contract_no'],
                    'type'              => $value['type'],
                    'source'            => $value['source'],
                    'credit_tag'        => $value['credit_tag'],
               ];
        }
        return $formatCreditInfo;
    }

    /**
     * @desc 格式化批量添加债权扩展信息
     * @param $credInfo array
     * @return array
     */
    public function formatCreditExtend( $creditInfo )
    {
        if( empty( $creditInfo ) )
            return [];

        $formatCreditExtend = [];

        foreach( $creditInfo as $key =>$value )
        {
            $formatCreditExtend[$key]['credit_id'] = $value['credit_id'];
        }

        return $formatCreditExtend;
    }

    /**
     * @desc 获取债权最大值
     * return int
     */
    public function getMaxCreditId()
    {
        $maxCreditId = CreditAllDb::getMaxCreditId();

        return $maxCreditId ? $maxCreditId : 0;
    }
    /**
     * @param $creditId | init 债券的Id
     * @return $result  | array
     * @desc 获取债券的主信息
     */
    public static function getCreditByCreditId( $creditId = 0 )
    {
        if( empty( $creditId ) ) {

            return [];
        }

        $creditDb   =   new CreditAllDb() ;

        return $creditDb->getCreditByCreditId( $creditId ) ;
    }

}
