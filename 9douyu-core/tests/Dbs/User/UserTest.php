<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/4/14
 * Time: 下午6:27
 */


class UserTest extends TestCase
{

    /**
     * 创建10000个用户
     */
    public function testAddUser()
    {

echo 2;
        /*for( $i=0; $i<10000; $i++ ){

            $data = [
                'phone' => 'test'.$i,
                'password'  => md5($i)
            ];

            \App\Http\Dbs\UserRegisterDb::create($data);

        }*/


    }

    public function testAddBalance()
    {

        /*$db = new \App\Http\Dbs\UserDb();

        for( $i=0; $i<1000000; $i++ ){

            $list = $db->getList($i, 2);

            if( $list ){

                foreach( $list as $val ){

                    $balance = rand(10000000,1000000000);

                    $db->updateBalance($val['id'],$balance);

                }

            }else{

                exit('完成');

            }

        }*/


    }




}
