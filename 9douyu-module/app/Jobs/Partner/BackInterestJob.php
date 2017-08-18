<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/11/10
 * Time: 17:20
 */
namespace App\Jobs\Partner;

use App\Http\Logics\Partner\PartnerLogic;
use App\Jobs\Job;

class BackInterestJob extends Job{

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

        $partnerLogic->doCalInterest($this->data);

    }

}