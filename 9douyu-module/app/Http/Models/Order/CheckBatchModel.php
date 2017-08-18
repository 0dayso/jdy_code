<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/10/8
 * Time: 上午10:31
 */

namespace App\Http\Models\Order;


use App\Http\Dbs\Order\CheckBatchDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class CheckBatchModel extends Model
{

    public static $codeArr            = [
        'doAddBatch'    =>  1,
        'doEditBatch'   =>  2,
        'doDeleteBatch' =>  3,
        'doValidation'  =>  4,
        'doUploadFile'  =>  5,

    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_ORDER_BATCH;

    /**
     * @param $data
     * @throws \Exception
     * @desc  添加记录
     */
    public function doAdd( $data )
    {
        $db     =   new CheckBatchDb();
        
        $return =   $db->doAdd( $data );
        
        if( empty($return) ){

            throw new \Exception(LangModel::getLang('ERROR_CHECK_ORDER_BATCH_ADD_FAILED') , self::getFinalCode('doAddBatch'));
        }

        return $return;
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 更新记录
     */
    public function doEdit( $id , $data )
    {
        $db     =   new CheckBatchDb();

        $return =   $db->doEdit( $id , $data);

        if( empty($return) ){

            throw new \Exception(LangModel::getLang('ERROR_CHECK_ORDER_BATCH_EDIT_FAILED'), self::getFinalCode('doEditBatch'));

        }

        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @desc 删除记录
     */
    public function doDelete( $id )
    {
        $db     =   new CheckBatchDb();

        $return =   $db->doDelete($id);

        if( empty($return) ){

            throw  new \Exception(LangModel::getLang('ERROR_CHECK_ORDER_BATCH_DELETE_FAILED'), self::getFinalCode('doDeleteBatch'));
        }

        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @desc 验证删除数据的有效性
     */
    public function doValidation( $id )
    {
        if( empty( $id ) ){

            throw new \Exception(LangModel::getLang('ERROR_FAMILY_PARAM_LESS'),self::getFinalCode('doDeleteBatch'));
            
        }
        
        $db     =   new CheckBatchDb();
        
        $result =   $db->getById( $id );
        
        if( empty($result) ){
            
            throw new \Exception(LangModel::getLang('ERROR_CHECK_ORDER_BATCH_DELETE_EMPTY'),self::getFinalCode('doDeleteBatch'));
        }

        if( $result['status'] != CheckBatchDb::STATUS_PENDING){

            throw new \Exception( LangModel::getLang('ERROR_CHECK_ORDER_BATCH_DELETE_STATUS_FAILED'),self::getFinalCode('doDeleteBatch') );
        }

        return true;
    }


    /**
     * @param $file
     * @return bool
     * @throws \Exception
     * @desc 检测上传文件类型
     */
    public function checkBillsFile( $file ){

        $allowedExtensions  = self::setUploadFilesType();

        $extension          = substr($file['name'],strrpos($file['name'],'.')+1);

        if ( $extension && !in_array($extension, $allowedExtensions)) {

            throw new \Exception( "请上传".implode(",",$allowedExtensions)."的文件",self::getFinalCode('doUploadFile') );

        }

        return true;

    }

    /**
     * @return array
     * @desc 对账上传的文件格式
     */
    protected static function setUploadFilesType()
    {
        return [
            'xlsx','xls',
        ];
    }

}