<?php
/**
 * Created by PhpStorm.
 * User: liuqiuhui
 * Date: 16/6/7
 * Time: 下午5:14
 */

namespace Test\Http\Models\Bonus;


use App\Http\Dbs\Bonus\UserBonusDb;
use App\Http\Models\Bonus\BonusModel;
use App\Http\Models\Bonus\UserBonusModel;

class UserBonusModelTest extends \TestCase
{


    public function testList(){

        $obj = new UserBonusModel();

        $data = $obj -> getAbleUserBonusListByProject(1, 100, 1, 2);

        echo __METHOD__.count($data)."\n";
        $this->assertTrue(is_array($data));
        return $data;

    }


    public function testUsedTime(){

        $obj  = new UserBonusModel();

        $res = $obj -> checkIsUsed(UserBonusDb::USED_TIME);

        $this->assertTrue($res);

    }

    public function testDataBonusId(){

        return [
            [1]
        ];

    }

    /**
     * @param $bonusId
     * @return mixed
     * @dataProvider testDataBonusId
     */
    public function test($bonusId){
        $obj  = new BonusModel();
        //验证红包是否可以发放
        $data = $obj->checkBonus($bonusId);
        $this->assertTrue(is_array($data));
        return $data;
    }

    public function testDataUserId(){

        return [
            [1]
        ];

    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户优惠券已使用列表
     * @dataProvider testDataBonusId
     */
    public function testUsedList($userId){
        $obj  = new UserBonusModel();
        //获取用户优惠券已使用列表
        $data = $obj -> getUsedListByUserId($userId);
        echo __METHOD__.count($data)."\n";
        $this->assertTrue(is_array($data));
        return $data;
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取用户优惠券可使用列表
     * @dataProvider testDataBonusId
     */
    public function testUseList($userId){
        $obj  = new UserBonusModel();
        //获取用户优惠券可使用列表
        $data = $obj -> getAbleUseListByUserId($userId);
        echo __METHOD__.count($data)."\n";
        $this->assertTrue(is_array($data));
        return $data;
    }

    /**
     * @param $userId
     * @return mixed
     * @desc 获取已过期优惠券可使用列表
     * @dataProvider testDataBonusId
     */
    public function testExpireUseList($userId){
        $obj  = new UserBonusModel();
        //验证红包是否可以发放
        $data = $obj -> getExpireListByUserId($userId);
        echo __METHOD__.count($data)."\n";
        $this->assertTrue(is_array($data));
        return $data;

    }

    public function testIsCanUse(){

        $obj  = BonusModel::findById(1);
        $data = $obj->getAttributes();
        $data = BonusModel::formatShow($data);
        $this->assertTrue(is_array($data));
        return $data;
    }


    public function testLock(){

        $model = new UserBonusDb();

        $this->assertTrue($model -> addLock(1));
        $this->assertTrue($model -> delLock(1));
        $this->assertTrue($model -> doRegularUsedBonus(1));

    }

}