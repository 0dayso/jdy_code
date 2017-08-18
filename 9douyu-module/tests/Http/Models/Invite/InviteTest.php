<?php

/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/7/12
 * Time: 上午10:17
 */
namespace tests\Http\Models\Invite;

use App\Http\Dbs\User\InviteDb;
use App\Http\Models\User\InviteModel;

class InviteTest extends \TestCase
{

    public function testData(){

        return [
            [
                'invite' => [
                    'user_id' => 1,
                    'type'    => InviteDb::TYPE_PARTNER,
                ]
            ]
        ];

    }

    /**
     * @param $data
     * @dataProvider testData
     */
    public function testCreateInvite( $data ){
        //注册成功
        $data['other_user_id'] = 2;

        dump($data);

        try{
            $model = new InviteModel();

            $result = $model -> create( $data);

            dump($result);
        }catch(\Exception $e){
            dump($e->getMessage());
        }

    }

}