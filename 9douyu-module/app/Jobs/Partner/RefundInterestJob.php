<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/23
 * Time: 下午12:47
 */

namespace App\Jobs\Partner;

use App\Http\Logics\Partner\PartnerLogic;
use App\Jobs\Job;

class RefundInterestJob extends Job{

    protected $data = '';



    public function __construct($data)
    {

        $this->data = $data;

    }

    /**
     * 统一入口
     */
    public function handle()
    {

        $partnerLogic = new PartnerLogic();

        $partnerLogic->doBackInterest($this->data);

    }

}