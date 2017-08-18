<?php
/**
 * Created by Vim.
 * User: linguanghui
 * Date: 17/4/20
 * Time: 16:54
 * Desc: 借款人体系债权Model
 */



namespace App\Http\Models\Common\LoanUserApi;

use App\Http\Logics\Logic;
use Config;

use Log;
use App\Http\Models\Model;

use App\Http\Models\Common\HttpQuery;

class LoanUserCreditApiModel extends Model
{

    /**
     * @param $creditInfo
     * @return array|mixed
     * @throws \Exception
     * @desc 发送借款人及债权数据至借款人系统
     */
    public static function sendLoanUserCreditData( $creditInfo )
    {

        return Logic::callSuccess();

        $api = Config::get( 'loanUserApi.LoanUserCreditApi.sendLoanUserCreditData');

        $param = $creditInfo;

        Log::info(__METHOD__.'传入借款人体系账户的数据', $param );

        return HttpQuery::loanUserPost( $api, $param );

    }

    /**
     * @param $creditId
     * @param $projectId
     * @param $projectPublishRate
     * @return array|mixed
     * @throws \Exception
     * @desc 请求借款人系统发布债权
     */
    public static function doPublishCredit( $creditId, $projectId, $projectPublishRate ){

        return Logic::callSuccess();

        $api = Config::get('loanUserApi.LoanUserCreditApi.doPublishCredit');

        $params = [
            'credit_id'             => $creditId,
            'project_id'            => $projectId,
            'project_publish_rate'  => $projectPublishRate,
        ];

        Log::info(__METHOD__, $params );

        return HttpQuery::loanUserPost( $api, $params );

    }

    /**
     * @param $params
     * @return array|mixed
     * @throws \Exception
     * @desc 发送债权还款通知到债权借款人系统
     */
    public static function sendRefundNotice( $params ){

        return Logic::callSuccess();

        $api = Config::get('loanUserApi.LoanUserCreditApi.doRefundNotice');

        Log::info(__METHOD__, $params );

        return HttpQuery::loanUserPost( $api, $params );

    }

    /**
     * @param $params
     * @return array|mixed
     * @desc 发布项目满标通知
     */
    public static function sendMakeLoansNotice( $params ){

        return Logic::callSuccess();

        $api = Config::get('loanUserApi.LoanUserCreditApi.makeLoans');

        Log::info(__METHOD__, $params );

        return HttpQuery::loanUserPost( $api, $params );

    }

}
