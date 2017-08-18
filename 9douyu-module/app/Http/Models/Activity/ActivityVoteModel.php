<?php
/**
 * Created by PhpStorm.
 * User: scofie <wu.changming@9douyu.com>
 * Date: 16/11/8
 * Time: 下午2:54
 */

namespace App\Http\Models\Activity;


use App\Http\Dbs\Activity\ActivityVoteDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Http\Models\SystemConfig\SystemConfigModel;
use App\Lang\LangModel;

class ActivityVoteModel extends Model
{   
    
    const
        VOTE_MAX_TIME       =   1;  //默认投票次数
    public static $codeArr = [
        'doAddVote'           => 1,
    ];

    public static $expNameSpace = ExceptionCodeModel::EXP_MODEL_ACTIVITY_VOTE;


    /**
     * @param $data
     * @return bool
     * @throws \Exception
     * @desc 增加投票记录
     */
    public static function doAddVote( $data )
    {
        $db     =   new ActivityVoteDb();
        
        $return =   $db->doAdd($data);
        
        if( empty($return) ){
            
            throw new \Exception(LangModel::getLang('ERROR_ACTIVITY_VOTE_ADD_RECORD'),self::getFinalCode('doAddVote'));
        }

        return $return;
    }

    public static function isCanVote()
    {
        
    }
   
}