<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/5/16
 * Time: Pm 04:20
 * 九斗鱼所有债权合并DB类
 */

namespace App\Http\Dbs\Credit;

use App\Tools\ToolArray;
use App\Tools\ToolTime;

class CreditAllDb extends CreditDb{

    const
        STATUS_UNUSED =  100, //债权未使用
        STATUS_USED   =  200, //债权已经使用

        COMMON_CREDIT = 50, //常规债权
        ARRAY_CREDIT  = 60, //项目集
        NINE_CREDIT   = 70, //九省心

        END = true;

    protected $table = 'credit';
    //public $timestamps = false;

    /**
     * @desc 创建新债权
     * @param $attributes
     * @reurn mixed
     */
    public static function add($attributes = []){

        $model = new static($attributes, array_keys($attributes));
        $model->save();
        return $model->id;
    }

    /**
     * @param $creditTag
     * @param int $statusCode
     * @param string $expirationDate
     * @return mixed
     * @desc 创建项目通过TAG调取可匹配债券列表
     */
    public static function getUseCreateProjectCreditList($creditTag, $statusCode=self::STATUS_UNUSED, $expirationDate='')
    {

        $expirationDate = empty($expirationDate) ? ToolTime::dbDate() : $expirationDate;

        $result = self::where('credit_tag', $creditTag)
            ->where('status_code', $statusCode)
            ->where('expiration_date', '>', $expirationDate)
            ->get()
            ->toArray();

        return $result;
    }

    /**
     * @param int $statusCode
     * @param string $expirationDate
     * @return mixed
     * 项目可用债权列表
     */
    public static function getAbleUseCreditList( $statusCode=self::STATUS_UNUSED, $expirationDate='' ){

        $expirationDate = empty($expirationDate) ? ToolTime::dbDate() : $expirationDate;

        $result = self::where('status_code', $statusCode)
            ->where('credit_tag', '>', 0)
            ->where('expiration_date', '>', $expirationDate)
            ->get()
            ->toArray();

        return $result;

    }

    /**
     * @param $creditIds
     * @return mixed
     * 通过债权Id获取债权列表
     */
    public static function getCreditListByCreditIds( $creditIds ){

        $result = self::whereIn('id', $creditIds)
                    ->orderBy('expiration_date')
                    ->get()
                    ->toArray();

        return $result;

    }

    /**
     * @desc 更新债权的状态
     * @param $creditId
     * @param $statusCode int
     * @return mixed
     */
    public static function updateCreditStatus( $creditId , $statusCode = self::STATUS_USED )
    {
        return self::whereIn( 'id', $creditId )
            ->update( ['status_code' => $statusCode] );
    }

    /**
     * @desc 获取债权表的最大id值
     * @return object
     */
    public static function getMaxCreditId( )
    {
        return self::max( 'id' );
    }

    /**
     * @param $creditId | int 债券的Id
     * @return array $result
     * @desc 通过债券Id获取债券的主信息
     */
    public function getCreditByCreditId( $creditId = 0)
    {
        return $this->dbToArray(
            $this->where('id',$creditId)
                 ->first()
        );
    }

    /**
     * @param int $creditId
     * @return mixed    通过债券Id获取债权来源和类型
     */
    public function getCreditTypeById( $creditId = 0 )
    {
        return $this->dbToArray(
            $this->select('type','source')
            ->where('id',$creditId)
            ->first()
        );
    }

    /**
     * @desc    获取还款中保理、房抵、信贷项目个数
     **/
    public function getCreditInvestProjectNum( $projectIds ){
        if(!$projectIds){
            return 0;
        }
        $result   = \DB::table("credit")
            ->leftJoin("project_link_credit_new AS pc",'credit.id', '=', 'pc.credit_id')
            ->whereIn('credit.source',  [self::SOURCE_FACTORING,self::SOURCE_CREDIT_LOAN,self::SOURCE_HOUSING_MORTGAGE])
            ->whereIn("pc.project_id",  $projectIds)
            ->count();
        return $result ? $result :0;
    }

    /**
     * @desc    获取还款中保理
     **/
    public function getCreditInvestFactorNum( $projectIds ){
        if(!$projectIds){
            return 0;
        }
        $result   = \DB::table("credit")
            ->leftJoin("project_link_credit_new AS pc",'credit.id', '=', 'pc.credit_id')
            ->where('credit.source',  self::SOURCE_FACTORING)
            ->whereIn("pc.project_id",  $projectIds)
            ->count();
        return $result ? $result :0;
    }

    /**
     * @desc    获取还款中保理、信贷项目ID
     **/
    public function getCreditInvestProject( $projectIds ){
        if(!$projectIds){
            return [];
        }
        $result   = \DB::table("credit")
            ->select('pc.project_id')
            ->leftJoin("project_link_credit_new AS pc",'credit.id', '=', 'pc.credit_id')
            ->whereIn('credit.source',  [self::SOURCE_FACTORING,self::SOURCE_CREDIT_LOAN])
            ->whereIn("pc.project_id",  $projectIds)
            ->get();
        return $result ? ToolArray::objectToArray($result) :[];
    }

}
