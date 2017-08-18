<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/5/23
 * Time: 上午11:25
 * 债权信贷DB类
 */
namespace App\Http\Dbs\Credit;

class CreditLoanDb extends CreditDb{

    protected $table = 'credit_loan';

    const

        END = true;
}