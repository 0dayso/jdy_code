<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 16/7/22
 * Time: 15:13
 */
namespace App\Http\Controllers\Pc;

class UserController extends PcController{
    
    public function __construct()
    {
        parent::__construct();
        $this->checkLogin(true);
    }

    /**
     * 判断用户是否实名过
     */
    protected function checkIdentity(){
        
        $verifyStatus = $this->getVerifyStatus();

        if($verifyStatus === false){

            Header("Location: /user/setting/verify");

            exit();
        }
    }
    
}