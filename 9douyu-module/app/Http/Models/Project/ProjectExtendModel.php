<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/8/29
 * Time: 下午8:18
 * Desc: 项目扩展表
 */

namespace App\Http\Models\Project;

use App\Http\Dbs\Project\ProjectExtendDb;
use App\Http\Models\Common\ExceptionCodeModel;
use App\Http\Models\Model;
use App\Lang\LangModel;

class ProjectExtendModel extends Model{

    public static $codeArr            = [
        'doAdd'         => 1,
    ];

    public static $expNameSpace       = ExceptionCodeModel::EXP_MODEL_PROJECT;


    /**
     * @param array $data
     * @throws \Exception
     * @desc 执行添加
     */
    public function doAdd($data=[])
    {

        if( empty($data) || !isset($data['project_id']) || !isset($data['type']) ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_EXTEND_DATA'), self::getFinalCode('doAdd'));

        }

        $db = new ProjectExtendDb();

        $res = $db->doAdd($data);

        if( !$res ){

            throw new \Exception(LangModel::getLang('ERROR_PROJECT_EXTEND_ADD'), self::getFinalCode('doAdd'));

        }

    }


    /**
     * @desc 设置发布项目选择的活动标示
     * @author linguanghui
     * return array
     */
    public static function setProjectActivitySign( )
    {

        return [
              ProjectExtendDb::TYPE_NEW_COMER  =>  '新手专享',
              ProjectExtendDb::TYPE_RECOMMEND_CHOICE  =>  '优选推荐',
              ProjectExtendDb::TYPE_INVEST_PK    =>  '投资PK',

            ];
    }

    public function getActivitySign()
    {

        $db = new ProjectExtendDb();

        $list = $db->getActivitySignList();

        return $list;

    }

}
