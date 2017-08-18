<?php
/**
 * Created by Vim_anywhere.
 * User: linguanghui
 * Date: 17/3/21
 * Time: 4:14PM
 */

namespace App\Http\Models\Credit;

use App\Lang\LangModel;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Requests\Admin\Credit\CreditLoanUserRequest as LoanUserRequest;
use Illuminate\Http\Request;
use Validator;

use App\Http\Dbs\Credit\CreditUserLoanDb;
use Log;
use Cache;
use App\Tools\ToolArray;
use App\Tools\ToolTime;
/**
 * 新合规的分散匹配债权模型
 * Class CreditProjectGroupModel
 * @package App\Http\Models\Credit
 */

class CreditUserLoanModel extends CreditModel{


    const
        ABLE_MATCH    = 200, //可匹配的
        UNABLED_MATCH = 300, //匹配的

        LOAN_TYPE_PERSON = 1,  //借款类型-个人
        LOAN_TYPE_COMPANY = 2, //借款类型-企业

        CREDIT_ABLE_AMOUNT_KEY = 'CREDIT_ABLE_AMOUNTS', //可匹配债权金额总和的缓存的KEY
    END =true;

    public static $codeArr            = [
        'doCreate' => 1,
        'findById' => 2,
        'doUpdate' => 3,
        'getAbleCreditDisperseList' =>4,
        'initCreditData'  => 5
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CREDIT_DISPERSE;

    /**
     * @desc 录入新的债权
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function doCreate($data){

    //    unset( $data['loan_phone'] );
        unset( $data['bank_name'] );
        unset( $data['bank_card'] );

        $return = CreditUserLoanDb::add($data);

        if(!$return)
            throw new \Exception( '添加债权失败', self::getFinalCode('doCreate') );

        //日志
        \App\Tools\AdminUser::userLog('credit_loan_user',[$data, $return]);

        return $return;

    }

    /**
     * @desc 批量录入新的债权
     * @param $data
     * @return static
     * @throws \Exception
     */
    public static function doBatchCreate( $data )
    {

        $return = CreditUserLoanDb::insert( $data );

        if(!$return)
            throw new \Exception( '批量录入新定期债权信息失败', self::getFinalCode('doBatchCreate') );

        //日志
        \App\Tools\AdminUser::userLog('batch_credit_loan_user',[$data, $return]);

        return $return;

    }

    /**
     * @desc 分散债权列表
     * @param $condition array
     * @return $throws\Exception| array
     */
    public function getCreditUserLoanListByStatus($status, $size=100){

        $creditUserLoanDb  = new CreditUserLoanDb();

        $creditList = $creditUserLoanDb->getCreditListByStatus($status, $size);

        return $creditList;
    }

    /**
     * @desc 获取可用的债权列表[用于发布项目选择]
     * @author linguanghui
     * @return array
     */
    public function getAbleCreditList( )
    {

        $creditUserLoanDb  = new CreditUserLoanDb();

        $creditList = $creditUserLoanDb->getAbleCreditList();

        if( !$creditList )
            return [];

        return $creditList;

    }

    /**
     * @desc 更新债权为已经使用的状态[发布项目后]
     * @param $creditId int
     * @param $status int
     * @author linguanghui
     * @return bool
     */
    public function doUpdateCreditStatus( $creditId, $status )
    {
        if( empty( $creditId ) || empty( $status ) )

            throw new \Exception( '债权ID或状态不能为空', self::getFinalCode('doUpdateCreditStatus') );

        $creditUserLoanDb  =   new CreditUserLoanDb();

        return $creditUserLoanDb->doUpdateCreditStatus( $creditId, $status );
    }

    /**
     * @desc 获取债权的详情by creditId
     * @author linguanghui
     * @param $creditId int
     * @return array
     */
    public function getCreditInfoById( $creditId )
    {

        if( empty( $creditId ) )
            return [];

        $creditUserLoanDb  =   new CreditUserLoanDb();

        return $creditUserLoanDb->getCreditInfoById( $creditId );
    }


    /**
     * @desc 逻辑层检测添加债权的数据是否为空
     * @return array
     */
    public static function checkAddCreditData( $creditInfo )
    {

        $loanUserRequest = new LoanUserRequest();

        $validator = Validator::make( $creditInfo, $loanUserRequest->rules( 2, $creditInfo['loan_type'] ), [], $loanUserRequest->attributes() );


        if($validator->fails()){
            $titleInfo  = '身份证号为:'.$creditInfo['loan_user_identity'].'借款人  ';
            throw new \Exception( $titleInfo.$validator->errors()->all()[0] , self::getFinalCode('checkAddCreditData') );

            \Log::error( '检测债权信息失败', $creditInfo );
        }

        return true;
    }

    /**
     * @desc 设置借款人类型
     * @return array
     */
    public static function setLoanType( )
    {

        return [
            self::LOAN_TYPE_PERSON  => '个人',
            self::LOAN_TYPE_COMPANY => '企业',
            ];
    }

}
