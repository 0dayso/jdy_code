<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/12/4
 * Time: 下午10:07
 */

namespace App\Http\Logics\Contract;


use App\Http\Logics\Agreement\AgreementLogic;
use App\Http\Logics\Logic;
use App\Http\Models\Contract\ContractModel;
use App\Tools\ToolTcPdf;

class EbqLogic extends Logic
{

    const

        CF_DOWN_LOAD_URL        = 'cfDownloadUrl',            //获取合同保全文件下载地址
        CF_PRESERVATION         = 'preservationGet',          //根据保全编号查询保全
        CF_CERTIFICATE_LINK     = 'certificateLinkGet',       //保全证书的证书链接
        CF_VIEW_URL             = 'cfViewUrl',                //获取合同查看页URL
        CF_STATUS               = 'contractStatusGet',        //根据保全编号查询保全用户的确认状态
        CF_GET_SIGN_STATUS      = 'getSignStatus',            //君子签合同的签约状态

        END = true;

    protected $driver;

    public function __construct()
    {
        $this->driver = 'EBQ';
    }

    /**
     * @param $data
     * @return mixed
     * 创建保全
     */
    public static function cfPreservationCreate( $data ,$driver='EBQ')
    {

        $params = [
            'driver'        => $driver,
            'method'        => 'cfPreservationCreate',
            'contract_num'  => $data['contract_num'],
            'cash'          => $data['cash'],
            'user_id'       => $data['user_id'],
            'identity'      => $data['identity'],
            'real_name'     => $data['real_name'],
            'phone'         => $data['phone'],
            'file_path'     => $data['file_path'],
            'file_name'     => $data['file_name']
        ];

        $model = new ContractModel();
        $result = $model->contractService( $params );

        if(isset($result['success']) && $result['success'] == true){

            return $result['preservationId'];

        }

        return false;

    }

    public static function  ebqService( $preservationId, $methodName )
    {

        $params = [
            'driver'            => 'EBQ',
            'method'            => $methodName,
            'preservation_id'   => $preservationId,
        ];

        $model = new ContractModel();
        $result = $model->contractService( $params );
        return $result;

    }

    /**
     * @param $data
     * @return mixed
     * 易保全 - 君子签 创建保全
     */
    public static function doUpdateApplySignFile( $data )
    {
        //$page   =   ToolTcPdf::getPdfPage ($data['file_path']);

        $params = [
            'driver'        => 'JZQ',
            'method'        => 'doUpdateApplySignFile',
            'cash'          => $data['cash'],
            'identity_card' => $data['identity'],
            'real_name'     => $data['real_name'],
            'phone'         => $data['phone'],
            'file_path'     => $data['file_path'],
            'contract_name' => $data['file_name'],
            'file_number'   => $data['file_number'],
            'page'          => 0,
            'chaptes'       => AgreementLogic::setChapterOffset($data['type']),
        ];

        $model  = new ContractModel();

        $result = $model->contractService( $params );

        if( isset($result['success']) && $result['success'] == true){

            return $result['applyNo'];
        }

        return false;
    }

    /**
     * @param $applyNo
     * @param $methodName
     * @param string $realName
     * @param string $identityCard
     * @return mixed
     * @易保全-君子签服务接口
     */
    public static function  JzqService( $applyNo, $methodName ,$realName='' , $identityCard = '')
    {
        $params = [
            'driver'            => 'JZQ',
            'method'            => $methodName,
            'apply_no'          => $applyNo,
            'identity_card'     => $identityCard,
            'real_name'         => $realName,
        ];

        return ( new ContractModel() )->contractService( $params );
    }


}
