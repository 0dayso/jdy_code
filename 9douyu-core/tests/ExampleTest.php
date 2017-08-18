<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    public function testRequestBuilder() {

        $incomeModel = new \App\Http\Models\Common\IncomeModel();

        $result = $incomeModel->getPlanInterest(3466, 1000, 0);

        print_r($result);

    }
}
