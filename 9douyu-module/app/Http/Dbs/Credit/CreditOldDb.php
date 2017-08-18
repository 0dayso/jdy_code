<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/23
 * Time: 上午11:25
 */
namespace App\Http\Dbs\Credit;

use App\Http\Dbs\JdyDb;

/**
 * 老数据库-债权
 *
 * Class CreditOldDb
 * @package App\Http\Dbs\Credit
 */
class CreditOldDb extends JdyDb{

    protected $connection = 'mysql_old';

    public $tableIndex    = 10;

    const
        //债权来源
        SOURCE_FACTORING                = 10,    // 耀盛保理
        SOURCE_CREDIT_LOAN              = 20,    // 耀盛信贷
        SOURCE_HOUSING_MORTGAGE         = 30,    // 房产抵押
        SOURCE_THIRD_CREDIT             = 40,    // 第三方
        //债权类型
        TYPE_PROJECT_GROUP              = 60,    // 项目集
        TYPE_NINE_CREDIT                = 70,    // 九省心



        END=true;


    /**
     * @param int $tableIndex 【新系统 code】
     */
    public function __construct($tableIndex = 10){
        parent::__construct();
        // 用于 获取 table
        $this->tableIndex = $tableIndex;
        // 表前缀清空
        $this->getConnection()->setTablePrefix('');
    }

    /**
     * 获取table
     * @return mixed
     */
    public function getTable(){
        $return = [
            10      => 'sf_factor',           // 耀盛保理
            20      => 'sf_finance_company',  // 耀盛信贷
            30      => 'sf_home_loan',        // 房贷
            60      => 'sf_project_group',    // 项目集
            70      => 'sf_free_credit',      //（新）九省心

            90      => 'sf_project',      //（老）九省心
            91      => 'sf_free_project', // 老九省心债权

            100     => 'sf_project'          // 项目数据表
        ];
        return $return[$this->tableIndex];
    }
}