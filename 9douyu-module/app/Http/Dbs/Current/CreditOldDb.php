<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/9/6
 * Time: 14:08
 */

namespace App\Http\Dbs\Current;

use App\Http\Dbs\JdyDb;

class CreditOldDb extends JdyDb{
    
    protected $connection = 'mysql_old';

    protected $table = 'sf_current_credit';


    public function __construct(){

        parent::__construct();
        // 表前缀清空
        $this->getConnection()->setTablePrefix('');
    }


}