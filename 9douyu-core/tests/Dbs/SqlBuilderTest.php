<?php
/**
 * Created by PhpStorm.
 * User: zjmainstay
 * Date: 16/6/2
 * Time: 16:55
 */
class SqlBuilderTest extends TestCase
{
    public function testDB() {
        $projectDb = new \App\Http\Dbs\ProjectDb();

        $res = $projectDb->getProductLineParam('1001')
            ->getStatusParam('1002')
            ->getSqlBuilder()
            ->get()
            ->toArray();
        
        $this->assertContains('1001', $projectDb->getLastSql());
        $this->assertContains('1002', $projectDb->getLastSql());

        $res = $projectDb->getSqlBuilder(true)
            ->getProductLineParam('1003')
            ->getStatusParam('1004')
            ->getSqlBuilder()
            ->get()
            ->toArray();
        
        $this->assertContains('1003', $projectDb->getLastSql());
        $this->assertContains('1004', $projectDb->getLastSql());
    }

    public function testDB2() {
        $projectDb = new \App\Http\Dbs\ProjectDb();

        $res = $projectDb->getProductLineParam('1005')
            ->getStatusParam('1006')
            ->getSqlBuilder()
            ->get()
            ->toArray();

        $this->assertContains('1005', $projectDb->getLastSql());
        $this->assertContains('1006', $projectDb->getLastSql());
        
        
        $res = $projectDb->getSqlBuilder(true)->get();
        $this->assertNotContains('1006', $projectDb->getLastSql());
    }
}
