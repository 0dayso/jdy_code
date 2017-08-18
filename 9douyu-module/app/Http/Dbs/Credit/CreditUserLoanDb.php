<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/18
 * Time: Pm 04:20
 * 用户借款债权DB类
 */

namespace App\Http\Dbs\Credit;

use App\Tools\ToolTime;

class CreditUserLoanDb extends CreditDb{

    const
        STATUS_UNUSED =  100,
        STATUS_ACTIVE =  200,
        END = true;
    protected $table = 'credit_loan_user';

    /**
     * @desc 创建债权
     * @param $attributes
     * @reurn mixed
     */
    public static function add($attributes = []){

        $model = new static($attributes, array_keys($attributes));
        $model->save();
        return $model->id;
    }

    public function del($id){

    }

    /**
     * @desc 获取借款人体系债权的列表
     * @author linguanghui
     * @param $size int
     * @param $condition array
     * @return mixed
     */
    public function getCreditListByStatus($status = self::STATUS_UNUSED, $size){

        return self::where('status_code', $status)
            ->orderBy('id',' desc')
            ->paginate($size);
    }

    /**
     * @desc 获取债权表的最大id值
     * @return object
     */
    public function getMaxCreditId( )
    {
        return self::max( 'id' );
    }

    /**
     * @desc 获取未使用的债权列表
     * @return array
     */
    public function getAbleCreditList( )
    {

        return self::select('id', 'credit_name', 'loan_username','loan_phone','loan_user_identity','loan_amounts', 'interest_rate', 'repayment_method', 'loan_deadline', 'loan_days', 'contract_no')
            ->where('status_code', self::STATUS_UNUSED)
            ->orderBy( 'id', 'desc' )
            ->get()
            ->toArray();
    }

    /**
     * @desc 更新债权的状态为已经使用
     * @return bool
     */
    public function doUpdateCreditStatus( $id , $status )
    {
        if( empty( $id ) )
        {
            return false;
        }

        return $this->where( 'id', $id )
            ->update( [ 'status_code' => $status ] );

    }

    /**
     * @desc 通过债权ID获取债权信息
     * @return string
     */
    public function getCreditInfoById( $creditId )
    {
        if( empty( $creditId ) )
        {
            return false;
        }

        $creditInfo = self::select('id', 'credit_name','loan_username','loan_phone','loan_user_identity','loan_amounts', 'interest_rate', 'repayment_method', 'loan_deadline', 'loan_days','contract_no')
            ->where( 'id', $creditId )
            ->get()
            ->toArray();

        return $creditInfo;
    }

    /**
     * @param array $where
     * @return mixed
     * @desc 根据条件查询
     */
    public function getDetailByWhere($where=[]){

        $param = [];

        if( isset($where['id']) && $where['id'] > 0 ){

            $param['id'] = $where['id'];

        }

        return $this->where($param)->first();
    }

}
