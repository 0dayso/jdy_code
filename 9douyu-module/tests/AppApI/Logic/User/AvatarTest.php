<?php

namespace Tests\AppApi\Logic\User;

use App\Http\Logics\User\AvatarLogic;

class AvatarTest extends \TestCase
{

    public function sendData(){
        $data = file_get_contents(__DIR__."/148240326699518o5qA1.png");

        return [
            [
                'client'  => 'android',
                'version' => '4.0',
                'userId'  => '1426125',
                'data'    => $data,
                'status'  => true,
            ],
            [
                'client'  => 'ios',
                'version' => '4.0',
                'userId'  => '1426125',
                'data'    => '',
                'status'  => false,
            ],
        ];
    }

    /**
     * @param $client
     * @param $version
     * @param $userId
     * @param $data
     * @param $status
     * @dataProvider sendData
     */
    public function testUpAvatar($client,$version,$userId,$data,$status){
        $avatarLogic = new AvatarLogic();
        $result = $avatarLogic->upAvatar($userId,$client,$version,$data);

        $this->assertEquals($status,$result['status']);
        $this->assertArrayHasKey('data',$result);
    }
}