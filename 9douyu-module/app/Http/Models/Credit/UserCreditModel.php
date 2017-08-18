<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */
namespace App\Http\Models\Credit;

use App\Http\Dbs\Credit\UserCreditDb;
use App\Http\Models\Model;
use App\Lang\LangModel;

use App\Http\Models\Common\ExceptionCodeModel;

use App\Http\Dbs\Credit\CreditThirdDb;

use App\Tools\ToolTime;
use Log;
/**
 * 第三方债权模型
 * Class CreditProjectGroupModel
 * @package App\Http\Models\Credit
 */
class UserCreditModel extends Model
{


    public static $codeArr            = [
        'doAdd' => 1,
        'findById' => 2,
        'doUpdate' => 3
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_CREDIT_THIRD;


    /**
     * 创建保理债权
     * @param $data
     * @return static
     * @throws \Exception
     */
    public function doAdd($data){

        $db = new UserCreditDb();

        $result = $db->add($data);

        if(!$result){

            throw new \Exception('添加债权出错', self::getFinalCode('doAdd'));

        }

        //日志
        \App\Tools\AdminUser::userLog('credit-'.ToolTime::dbDate(), ['data' => $data]);

        return $result;
    }


    /**
     * @param $userId
     * @param int $size
     * @return mixed
     * @desc 获取债权列表
     */
    public function getListByUserId($userId, $size=100){

        $db = new UserCreditDb();

        return $db->getCreditList($userId, $size);

    }

    /**
     * @desc 获取昨日债权匹配的用户数据
     * @author linguanghui
     * @return array
     */
    public static function getYesterdayMatchAccount( )
    {

        $db = new UserCreditDb();

        $yesterdayMatchData  =  $db->getYesterdayMatchAccount();

        if( empty( $yesterdayMatchData ) )
        {
            return [];
        }
        return $yesterdayMatchData;
    }

}
