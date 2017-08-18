<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/19
 * Time: 上午11:57
 */

namespace Tests\Http\Logics\Project;

use App\Http\Logics\Project\ProjectDetailLogic;

class ProjectDetailLogicTest extends \TestCase{

    /**
     * @return mixed
     */
    public function testGet(){
        $obj  = new  ProjectDetailLogic;
        $data = $obj->get(1);
        if($data) {
            $this->assertTrue(is_array($data));
            $this->assertArrayHasKey('status', $data);
            $this->assertArrayHasKey('data', $data);
            if ($data['status'] === true) {
                $this->assertNotEmpty($data['data']['project']);
                $this->assertNotEmpty($data['data']['credit']);
                $this->assertNotEmpty($data['data']['linkCredit']);
            }
        }
    }
}